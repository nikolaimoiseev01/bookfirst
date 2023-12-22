<div id="reviews" x-data="{ open: false }" class="reviews_block_wrap">


    <form wire:submit.prevent="create_comment(Object.fromEntries(new FormData($event.target)))" class="review_form">
        <div x-show="$wire.show_input">
            <div class="header">
                <x-stars-rating model="stars" inputrating="0"/>
                <a @click="$wire.set('show_input', false)" class="link">Скрыть</a>
            </div>

            <x-chat-textarea x-show="open" model="review_text"
                             placeholder="Введите текст"
                             attachable="false" sendable="true"></x-chat-textarea>
        </div>
    </form>

    @if($reviews and count($reviews) > 0)
        <a @click="$wire.set('show_input', true)" x-show="!$wire.show_input" class="link">Написать отзыв</a>
        <div class="reviews_wrap">
            @foreach($reviews as $review)
                <div class="review_wrap">
                    <div class="header">
                        <x-user-avatar-small :user="$review->user"/>
                        <a href="{{route('social.user_page', $review['user_id'])}}" class="link">{{prefer_name($review->user['name'],$review->user['surname'],$review->user['nickname'])}}</a>
                        <x-stars-rating model="stars" :inputrating="$review['stars']"/>
                        <p class="time">{{ Date::parse($review['created_at'])->addHours(3)->format('j F H:i') }}</p>
                    </div>
                    <p>{{$review['text']}}</p>
                </div>
            @endforeach
            {{ $reviews->links() }}
        </div>
    @else
        <p x-show="!$wire.show_input">Пока у книги нет отзывов. <a @click="$wire.set('show_input', true)" class="link">Оставьте первый!</a></p>
    @endif


</div>
