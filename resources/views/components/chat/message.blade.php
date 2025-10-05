<div class="flex flex-col w-fit">
    <div class="flex justify-between items-center">
        <span class="text-dark-600 dark:text-white">
            @if($role['name'] == 'user')
                {{$message->user['name'] . ' ' . $message->user['surname']}}
            @else
                {{$role['public_name']}}
            @endif
        </span>
        @hasanyrole('admin|secondary_admin')
        <x-lucide-edit class="w-5 h-5 text-green-400"/>
        @endhasanyrole
    </div>

    <div class="rounded-xl
    @if($role['name'] == 'user') bg-brown-300 @else bg-green-500 @endif
    px-4 py-2 w-fit max-w-fit flex flex-col">
        <p class="text-lg text-white">{{$message['text']}}</p>
        @if(count($message->getMedia('files')) > 0)
            <div class="h-px bg-dark-100 w-full my-2"></div>
            <p class="font-normal text-white text-xl">Прикрепленные файлы:</p>
            <div class="flex flex-col gap-2">
                @foreach($message->getMedia('files') as $file)
                    <a href="{{$file->getUrl()}}" download="true" class="flex gap-2 items-center">
                        <x-bi-download class="text-white h-4 w-4"/>
                        <span class="flex gap-2 text-white">{{Str::limit($file->getAttribute('file_name'), 30, '...')}}</span>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
    <span class="text-dark-600 text-sm dark:text-white">
        {{$message['created_at']->translatedFormat('j F H:i')}}
    </span>
</div>
