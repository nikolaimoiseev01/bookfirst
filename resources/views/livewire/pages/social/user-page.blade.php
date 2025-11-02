<main class="flex-1 mb-16 content">
    @section('title')
        {{getUserName($user)}}
    @endsection

    <livewire:components.social.user-header :user="$user"/>

    <section x-data="{tab: 'works'}" class="flex justify-between">
        <div class="flex flex-col max-w-4xl">
            <div class="flex gap-4 mb-4">
                <h3 @click="tab = 'works'" :class="tab == 'works' ? 'text-blue-500' : ''"
                    class="text-4xl cursor-pointer transition hover:text-blue-500">Произведения</h3>
                <h3 @click="tab = 'own_books'" :class="tab == 'own_books' ? 'text-blue-500' : ''"
                    class="text-4xl cursor-pointer transition hover:text-blue-500">Собственные книги</h3>
            </div>
            <div x-show="tab == 'works'">
                <livewire:components.social.work-feed :user-id="$user['id']"/>
            </div>
            <div x-show="tab == 'own_books'">
            </div>
        </div>
    </section>
</main>
