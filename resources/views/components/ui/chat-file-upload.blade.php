@php
    $isCustomPlaceholder = isset($placeholder);
@endphp

@props([
    'multiple' => false,
    'required' => false,
    'disabled' => false,
    'placeholder' => __('Drag & Drop your files or <span class="filepond--label-action"> Browse </span>'),
])

@php
    if (! $wireModelAttribute = $attributes->whereStartsWith('wire:model')->first()) {
        throw new Exception("You must wire:model to the filepond input.");
    }

    $pondProperties = $attributes->except([
        'class',
        'placeholder',
        'required',
        'disabled',
        'multiple',
        'wire:model',
    ]);

    // convert keys from kebab-case to camelCase
    $pondProperties = collect($pondProperties)
        ->mapWithKeys(fn ($value, $key) => [Illuminate\Support\Str::camel($key) => $value])
        ->toArray();

    $pondLocalizations = __('livewire-filepond::filepond');
@endphp
<div
    class="{{ $attributes->get('class') }}"
    x-show="isDropping"
    :class="{ '!block': isDropping }"
    wire:ignore
    x-cloak
    x-data="{
        model: @entangle($wireModelAttribute),
        isMultiple: @js($multiple),
        current: undefined,
        files: [],
        async loadModel() {
            if (! this.model) {
              return;
            }

            if (this.isMultiple) {
              await Promise.all(Object.values(this.model).map(async (picture) => this.files.push(await URLtoFile(picture))))
              return;
            }

            this.files.push(await URLtoFile(this.model))
        }
    }"
    x-init="async () => {
      await loadModel();

      const pond = LivewireFilePond.create($refs.input);

      pond.setOptions({
          allowMultiple: isMultiple,
          server: {
              process: async (fieldName, file, metadata, load, error, progress) => {
                  $dispatch('filepond-upload-started', '{{ $wireModelAttribute }}');
                  await @this.upload('{{ $wireModelAttribute }}', file, async (response) => {
                    let validationResult  = await @this.call('validateUploadedFile', response);
                        // Check server validation result
                        if (validationResult === true) {
                            // File is valid, dispatch the upload-finished event
                            load(response);
                            $dispatch('filepond-upload-finished', { '{{ $wireModelAttribute }}': response });
                        } else {
                            // Throw error after validating server side
                            error('Filepond Api Ignores This Message');
                            $dispatch('filepond-upload-reset', '{{ $wireModelAttribute }}');
                        }
                  }, error, (event) => {
                        progress(event.detail.progress, event.detail.progress, 100);
                });
              },
              revert: async (filename, load) => {
                  await @this.revert('{{ $wireModelAttribute }}', filename, load);
                  $dispatch('filepond-upload-reverted', {'attribute' : '{{ $wireModelAttribute }}'});
              },
              remove: async (file, load) => {
                  await @this.remove('{{ $wireModelAttribute }}', file.name);
                  load();
                  $dispatch('filepond-upload-file-removed', {'attribute' : '{{ $wireModelAttribute }}'});
              },
          },
          required: @js($required),
          disabled: @js($disabled),
      });
      pond.setOptions(@js($pondLocalizations));

      pond.setOptions(@js($pondProperties));

      pond.setOptions({
        maxFileSize: '5MB', // твой лимит
        labelMaxFileSizeExceeded: 'Файл слишком большой',
        labelMaxFileSize: 'Макс. размер: {filesize}',
    });

      @if($isCustomPlaceholder)
        pond.setOptions({ labelIdle: @js($placeholder) });
      @endif

        if (files && files.length > 0) {
            pond.addFiles(files.filter(f => f instanceof File));
        }

        pond.on('addfile', (error, file) => {
            if (error) console.log(error);
            isDropping = false
        });

        // All files have been processed and uploaded, dispatch the upload-completed event
        pond.on('processfiles', () => {
            $dispatch('filepond-upload-completed', {'attribute' : '{{ $wireModelAttribute }}'});
      });



      $wire.on('filepond-reset-{{ $wireModelAttribute }}', () => {
          pond.removeFiles();
      });


      pond.on('processfile', (error, file) => {
            $dispatch('filepond-upload-done', {
                attribute: '{{ $wireModelAttribute }}',
                error: !!error,
                file: file.filename,
            });
        });

      pond.on('processfileabort', () => {
           $dispatch('filepond-upload-aborted');
      });
      // 👉 прячем/показываем root в зависимости от наличия файлов
        pond.on('addfile', () => {
            $el.classList.add('!block');
        });

        pond.on('updatefiles', (files) => {
            if (files.length === 0) {
                $el.classList.remove('!block');
            }
        });
    }"
>
    <input type="file" x-ref="input">
</div>
