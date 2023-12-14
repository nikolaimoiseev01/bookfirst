<div id="reviews" x-data="{ open: false }" class="reviews_block_wrap">


    <form wire:submit.prevent="create_comment(Object.fromEntries(new FormData($event.target)))" class="review_form">
        <a @click="open = true" x-show="!open" class="button">Написать отзыв</a>
        <div x-show="open">
            <div class="header">
                <x-stars-rating model="stars" inputrating="0"/>
                <a @click="open = false" class="link">Скрыть</a>
            </div>

            <x-chat-textarea x-show="open" model="review_text"
                             placeholder="Введите текст"
                             attachable="false" sendable="true"></x-chat-textarea>
        </div>

    </form>

    <div class="reviews_wrap">
        @foreach($reviews as $review)
            <div class="review_wrap">
                <div class="header">
                    <x-user-avatar-small :user="$review->user"/>
                    <a class="link">{{prefer_name($review->user['name'],$review->user['surname'],$review->user['nickname'])}}</a>
                    <x-stars-rating model="stars" :inputrating="$review['stars']"/>
                </div>
                <p>{{$review['text']}}</p>
            </div>
        @endforeach
{{--            {{ $reviews->links() }}--}}
    </div>


</div>
