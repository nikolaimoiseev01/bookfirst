
    <div class="search_bar_wrap">
        <input required placeholder="Поиск..."
               wire:model.defer="{{$model}}"
               class="input"
               type="text">
        <span wire:click.prevent="search()" class="search_icon material-icons-outlined">search</span>
        @if($search_input)
            <span wire:click.prevent="clear_search()"
                  class="close_icon material-icons-outlined">close</span>
        @endif
    </div>


