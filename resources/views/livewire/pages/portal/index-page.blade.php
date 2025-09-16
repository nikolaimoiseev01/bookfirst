<main>
    @section('title')
        Главная
    @endsection
    <section class="h-screen w-full flex items-center">
        <svg class="absolute left-0 top-0 h-full" id="Слой_1" data-name="Слой 1" xmlns="http://www.w3.org/2000/svg"
             viewBox="0 0 531.37 733.2">
            <path
                d="M0,0V733.2c160.82-13.53,151.4-116.91,245-168.7C561,453.3,564.65,307.64,494.5,213,454.77,159.4,298.39,0,270.8,0Z"
                class="fill-[#fffbef] dark:fill-[#292929]"/>
        </svg>
        <svg class="absolute right-0 max-h-[70vh] top-1/2 -translate-y-1/2" id="Слой_1" data-name="Слой 1"
             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 681 673">
            <path
                d="M681,11.62c-129.23-71.85-326.93,211.67-559.88,246C-93.35,289.19,19.74,596.9,133.83,632c52.17,16,138.49,40.92,270.78,41C561.66,673.16,681,542.33,681,542.33Z"
                class="fill-[#fffbef] dark:fill-[#292929]"/>
        </svg>
        <div class="max-w-(--breakpoint-3xl) mx-auto w-[90%] flex justify-between items-center relative">
            <div class="flex flex-col gap-4 max-w-[600px]">
                <h2 class="text-7xl font-medium text-black-500 dark:text-white">Ваш шаг в мир<br>литературы</h2>
                <x-portal.welcome-running-line/>
                <div class="flex gap-8">
                    <a href="#"
                       class="text-3xl px-10 py-1 text-green-500
                              border border-green-500 no-underline
                              relative overflow-hidden rounded-[10px]
                              transition-all duration-300
                              hover:shadow-[5px_5px_2px_#499b897a] hover:scale-[1.03]">
                        Опубликовать
                    </a>
                </div>
            </div>
            <img src="/fixed/woman_welcome.svg" class="max-w-3xl" alt="">
        </div>
    </section>

    <x-portal.collection-examples/>

    <section class="flex w-full">
        <img src="/fixed/woman_sitting.svg" class="w-96" alt="">
        <div class="flex flex-col mb-4 w-full ml-32">
            <h2 class="mb-8">За <span class="  text-green-500">{{ date('Y') - 2015 }} лет</span> работы у нас:</h2>
            <x-portal.history-numbers/>
        </div>
    </section>

    <section ic="actual-collections" class="content mb-32">
        <div class="relative w-fit mx-auto mb-32">
            <svg class="absolute -top-[100px]" xmlns="http://www.w3.org/2000/svg" width="401" height="278"
                 viewBox="0 0 401 278" fill="none">
                <script xmlns=""/>
                <path
                    d="M56.385 83.5782C162.464 106.477 137.685 49.9122 241.152 77.093C344.619 104.274 386.775 169.962 371.842 207.864C356.909 245.766 274.186 248.669 187.075 214.349C99.9639 180.028 -49.6943 60.6796 56.385 83.5782Z"
                    stroke="#73A096" stroke-width="2" stroke-linecap="round" stroke-dasharray="10 15"/>
                <script xmlns=""/>
            </svg>
            <h2>Идет прием заявок</h2>
        </div>
        <div class="flex flex-col gap-16">
            @foreach($collections_actual as $collection)
                <x-portal.card-collection-wide :collection="$collection"/>
            @endforeach
        </div>
    </section>

    <section id="reviews" class="content mb-32">
        <div class="flex w-full justify-between mb-8">
            <h2>Отзывы</h2>
            <a href="">Больше отзывов</a>
        </div>
        <div class="flex justify-between gap-8">
            @foreach($reviews as $review)
                <div class="flex flex-col max-w-96">
                    <div class="relative mb-8 p-4 container">
                        <svg class="absolute w-[50px] h-[50px] -bottom-[35px]" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 26.4 16.08">
                            <defs>
                                <style>.cls-1 {
                                        isolation: isolate;
                                    }

                                    .cls-2 {
                                        opacity: 0.06;
                                        mix-blend-mode: color-burn;
                                    }

                                    .cls-3 {
                                        fill: #fff;
                                    }</style>
                            </defs>
                            <g class="cls-1">
                                <g id="Слой_1" data-name="Слой 1">
                                    <image class="cls-2" width="110" height="67" transform="scale(0.24)" xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAG4AAABDCAYAAAB9aTATAAAACXBIWXMAAC4jAAAuIwF4pT92AAAGCElEQVR4Xu3ca1PjOBCF4dcBMrC7//9nzsBwDTfvB/ug444cOyEXy6SrVDaJSRE96ZZk7FR1XXOO8mIxdMA5phmXQweMiaqqqqFjzpGi3kOZq7Z9jQGkM+BwZDt8W8zRcAGsCtvOoZnHztFE7Ow6bJsfRqAMwhlYbtv33Dn6w7H69gfxNsIFtL624Aw4JiKSt8/MYxvxeuF60BY9zQFzJfUnRx/YZ09zyF68oVllRLuw7QXN7/vjDglnOMiDffS0qt0u2uPqqqqqHF4WLjMRiVhqV7av5x3vJwPGTFNGfQDv1t7aJjQdqz7bOuNyJVJYV8AyNCHm8H4qXC7LhPXatvgh1+9U9KDBcKmE9KKecb/adt02/SxUldCfipdDU4a9AivghaY/HUxZucDwcuVyCC5m3AUJ5xr4B7hptxHQS+dPKZu58hiz7AV4Jn24oVtGNdZ5fw2PcT1nRhxQeEsS3n8kRAF66dTv6rX8dUsP71Tt+yxRWSa0J1Kf6HmNc7mZeTbGZpz2PfMuSXg3wL80gDdt09jnZXNueJvQPNNUGq9IGSTQ3LxA0TvOrcHVdV1b1inlPWL2RUDPvjnjjc00oamv9biGk9FZ5jGUcYo60/R4LKG/SOPeDf1lc25wWjTHTLukeX/vdCdu/p5jv/rj2RiCi1hxWqv9r5U+CXFJQsxlXmVNUQJgBPO+iWMaNP2zIlWa2I/qv3j2xJNjLTbBKZv0Ig4W1yKv7c96rg9xaMJSEpyXx5hpkM6AOFTss9hvWbTRZ05snIufJv1xqt0rmqntdWir9pgcYlznlTLmxUyD9TFNgDomLgO0FHhu99VP6qtcBcvGUKmE7ifmne6kROsRnTnJnVFZ2uOKBWWVzTHlUaHM0qTkGXhs24PtP5EAlXWOthtcm3VfP7KecRfWLm071C5If5zKZR/e1CKi+X4Ee6IBum/b37bdkwCfSVn3xjpcvet/ByD9YdBd1cfOdoRtG5nXmlrksi6OXc80YA80QHdtu223wntoj1O5FJpPVHbLOCC3pvOy4BOKfTbFlPAiWm689wz7S4N1C/yx/bv2eWXbC90JSgds0z9SBzMulEzPPM2ecoB9EY/pg4uvcQrE2Gkxy4QWy+IdDdYf4LftO5qyzSclnaXA0KULg3DQi7dtjO38C1Knjf2dQ4aPa3Fx7aUxZllEU4l8pFsi1yYlQ2gwEg72hrdN+NinicyxorZtzLIhtN8cGA22gIMfgzd5NNgSDmaPVwQa7AAHs8UrBg12hIPZ4RWFBt+Ag9ngFYcG34SD4vGKRIM9wEGxeMWiwZ7goDi8otFgj3BQDF7xaLBnOJg83izQ4ABwMFm82aDBgeBgcnizQoMDwsEk8DxmgwYHhoOT4ylmhQZHgIOT4FXWamuzQIMjwcHR8fx6GM+2WaDBEeHgKHhCuiSVTOiixYt6ikODI8PBwfF89qis82xbka4RKRYNTgAHB8GrQ9PV0oJTtjnaPYWiwYngYG94dWb7SXOfQoTTuCa0TVdjTRoNTggHe8OD7gTkg6aTr2jK5SdpQqLLwHWRapFocGI42AteDu2G7t2fuv7xkQbGs604NJgAHHwLz8e1j7YJaUnKOM0kNSFxuFsKQ4OJwMFOeI7mZ0RWpIzzUukzSZVKofmNGJNHgwnBwVZ4AtO+4JRtEU4Zp1IpPO0/sH4TxmTRYGJwsBWe4pPuOk03Wi5pxjhlnMa4+9D8Wv4i0GCCcDAKL5dtfipLty1rcuJruMfQ/AaMItBgonAwCg/y49sT6+s4P9Wl013aX5EyTROcSaPBhOFgI16cmPhazb+SSnAa/4T0Qsoyvxu0CDSYOBwMZp4vBZRVuhfdz1X6v3M2feNBEWhQABz04nnWKVveaN6TvrFHoeffrcUsKwYNCoGDLF5ugpL7xr6I61id0kghaMDwt6BPLapGz9vCtt6knBsPPcO+MrgUNCgQDtj07eyeaXo+ZmadaZSEBoXCwRqetvExjy+kuC0NDQqGUxggdMF839/k136JYIri4RQBsDdKxvKYDVwuqir/nf1ziFnDzTl8kXqOguJ/A3IRUXpsQGYAAAAASUVORK5CYII="></image>
                                    <path class="cls-3" d="M0,10H20l-9.56,9.55a.62.62,0,0,1-.5.23.63.63,0,0,1-.43-.21Z" transform="translate(3.24 -7.8)"></path>
                                </g>
                            </g>
                        </svg>
                        <p class="text-xl">{{$review['text']}}</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <img src="{{$review['avatar']}}" class="w-16 h-16 rounded-full" alt="">
                        <p class="font-normal text-dark-400">{{$review['name']}}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</main>
