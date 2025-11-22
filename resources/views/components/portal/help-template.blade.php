@props([
    'data'
])
<div x-data="{ current: window.location.pathname }"
     class="container flex flex-col p-4 w-full">
    <div class="flex border-b-2 border-dark-100 gap-8 pb-2 mb-4">
        @foreach($links as $link)
            <a wire:navigate href="{{ $link['route'] }}"
               :class="current === '{{ parse_url($link['route'], PHP_URL_PATH) }}'
                        ? 'text-green-500 after:bg-green-500'
                        : 'text-dark-100 after:bg-transparent'"
               class="text-3xl relative transition hover:text-green-500
                      after:content-[''] after:absolute after:-bottom-[10px] after:h-[2px] after:w-full after:left-0"
            >
                {{ $link['name'] }}
            </a>
        @endforeach
    </div>
    <h3 class="text-4xl mb-2">Содержание</h3>

    <div class="flex flex-col gap-2 z-[99]">
        @foreach($data as $key=>$item)
            <x-ui.link-simple :isLivewire="false" href="#{{$item['id']}}">{{$key + 1}}
                . {{$item['title']}}</x-ui.link-simple>
            @if(isset($item['subcontent']))
                @foreach($item['subcontent'] as $subcontent)
                    <x-ui.link-simple :isLivewire="false" class="text-xl pl-4"
                                      href="#{{$subcontent['id']}}">{{$subcontent['title']}}</x-ui.link-simple>
                @endforeach
            @endif
        @endforeach
    </div>

    <div class="flex flex-col gap-8">
        @foreach($data as $key=>$item)
            <div id="{{$item['id']}}" style="z-index: {{count($data) - $key}}"
                 class="pt-28 -mt-28 relative flex flex-col after:content-[''] after:border after:mt-8 after:border-dark-50">
                <h3 class="text-4xl mx-auto">{{$key + 1}}. {{$item['title']}}</h3>
                <div class="mb-2">
                    {!! $item['content'] !!}
                </div>
                @if(isset($item['subcontent']))
                    @foreach($item['subcontent'] as $subcontent)
                        <p id="{{$subcontent['id']}}"
                           class="font-medium pt-28 -mt-28">{!! $subcontent['title'] !!}</p>
                        <p class="font-medium">{!! $subcontent['text'] !!}</p>
                    @endforeach
                @endif
                @if(isset($item['media']))
                    <div class="flex flex-col gap-8">
                        @foreach($item['media'] as $media)
                            @if($media['type'] == 'gif')
                                <div class="flex flex-col gap-2" x-data="{show: false}">
                                    <a x-text="show ? 'Скрыть' : 'Смотреть видеопример'"
                                       @click="show = !show"
                                       class="link mx-auto text-2xl font-light text-center"></a>
                                    <img alt="{{$media['src']}}" src="{{$media['src']}}"
                                         x-show="show"
                                         class="mx-auto rounded-xl border border-green-500"/>
                                </div>
                            @endif
                            @if(($media['type'] == 'png' || $media['type'] == 'jpg'))
                                <img alt="{{$media['src']}}" src="{{$media['src']}}"
                                     class="rounded-xl border border-green-500 mx-auto"/>
                            @endif
                            @if($media['type'] == 'video')
                                <div class="flex flex-col gap-2" x-data="{show: false}">
                                    <a x-text="show ? 'Скрыть' : 'Смотреть видеопример'"
                                       @click="show = !show"
                                       class="link mx-auto text-2xl font-light text-center"></a>
                                    <video x-show="show" x-init="$el.volume = 0.3" id="modalVideo"
                                           class="w-full aspect-video" controls>
                                        <source src="{{$media['src']}}" type="video/mp4">
                                        <source src="movie.ogg" type="video/ogg">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>

</div>
