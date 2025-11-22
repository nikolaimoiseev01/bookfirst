<div>
    @section('title')
        Избранные авторы
    @endsection
    <div class="flex gap-4">
        @forelse($favAuthors as $author)
            <x-ui.cards.author-card :author="$author"/>
        @empty
            <p class="italic">Вы еще не подписывались на авторов, но все еще впереди 🙂</p>
        @endforelse
    </div>
</div>
