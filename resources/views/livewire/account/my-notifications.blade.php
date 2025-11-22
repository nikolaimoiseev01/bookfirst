<div>
    <div class="account-header">
        <h1>Мои оповещения</h1>
        @if (count($notifications) > 0)
            <a class="link" wire:click.prevent="MarkAllAsRead">Отметить все прочитанными</a>
        @endif
    </div>

    @if (count($notifications) === 0)
        <div style="max-width: 2000px;" class="no-books-yet">
            <h1>На данный момент непрочитанные оповещения отсутствуют</h1>
        </div>
    @endif
    <div class="element-wrap">
        @foreach($notifications as $notification)
            <a wire:click.prevent="MarkAsRead('{{$notification['id']}}', '{{$notification['data']['link']}}')"
               href="/myaccount/chats/1">
                <div class="container container-hover">
                    <div style="color: #36356d;" class="el-desc">
                        <span>{{$notification['data']['text']}}</span>
                        Подробнее
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>
