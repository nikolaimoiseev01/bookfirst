<main class="flex-1 mb-16 content">
    @section('title')
        {{getUserName($user)}}
    @endsection

    <livewire:components.social.user-header :user="$user"/>

    <section x-data="{tab: 'works'}" class="flex justify-between gap-16">
        <div class="flex flex-col max-w-5xl md:max-w-full md:w-full">
            <div class="flex gap-4 mb-8 md:flex-col">
                <h3 @click="tab = 'works'" :class="tab == 'works' ? 'text-blue-500' : ''"
                    class="text-4xl cursor-pointer transition hover:text-blue-500">Произведения</h3>
                <h3 @click="tab = 'own_books'" :class="tab == 'own_books' ? 'text-blue-500' : ''"
                    class="text-4xl cursor-pointer transition hover:text-blue-500">Собственные
                    книги</h3>
            </div>
            <div x-show="tab == 'works'">
                @if($user['works_cnt'] > 0)
                    <livewire:components.social.work-feed :user-id="$user['id']"/>
                @else
                    <p class="italic text-dark-350">Автор еще не публиковал у нас произведения, но все еще впереди 🙂</p>
                @endif
            </div>
            <div x-show="tab == 'own_books'" class="flex gap-8 flex-wrap">
                @forelse($user->ownBooks as $ownBook)
                    <x-ui.cards.card-own-book class="!min-w-48 !max-w-48" :ownbook="$ownBook"/>
                @empty
                    <p class="italic text-dark-350">Автор еще не издавал у нас книги, но все еще впереди 🙂</p>
                @endforelse
            </div>
        </div>
        <div class="flex flex-col md:hidden">
            <x-ui.link-simple class="text-4xl font-medium !text-dark-500 mb-10">Случайные работы
            </x-ui.link-simple>
            <div class="flex flex-col items-center gap-8">
                @foreach($randomWorks as $work)
                    <x-ui.cards.card-social-work-mini :work="$work"/>
                @endforeach
            </div>
        </div>
    </section>
</main>
