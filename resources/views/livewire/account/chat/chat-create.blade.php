<div class="create_chat_page_wrap">

   <div class="form_wrap">
        <input wire:model="chat_title" type="text">
        <textarea wire:model="text" type="text" placeholder="Опишите Ваш вопрос" name="text"></textarea>
        <a wire:click.prevent="storeChat" class="button show_preloader_on_click">Создать обсуждение</a>
    </div>


</div>
