<div style="width:90%;">
    <style>
        .chat-create-wrap input,
        .chat-create-wrap textarea {
            margin-bottom: 15px;
        }

        .add-work-form textarea, .chat-create-wrap textarea {
            height: 300px;
        }

        .chat-create-wrap {
            display: flex;
            flex-direction: column;
            max-width: 700px;
        }
    </style>
{{--    {{session('show_modal')}}--}}
    <form
        id="chat"
        wire:submit.prevent="storeChat(Object.fromEntries(new FormData($event.target)))"
        enctype="multipart/form-data">
        <div class="chat-create-wrap">
            @csrf
            <p class="mb-0">Тема: </p>
            <input wire:model="chat_title" value="123" class="form-control" type="text">
            <textarea wire:model="text" id="text" type="text" placeholder="Опишите Ваш вопрос" name="text" class="form-control" id="text"></textarea>
            <button id="chat_form" style="position: relative;"  class="@if (Auth::user()->hasRole('admin'))create_chat @endif preloader_button_wo_submit button btn btn-block bg-gradient-primary" >
                <span class="button__text">Создать вопрос</span>
            </button>
        </div>
    </form>


</div>
