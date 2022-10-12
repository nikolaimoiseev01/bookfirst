<div class="container user_header_block">
    <div>
        <img style="width:80px;" class="user_avatar"
             src="{{($last_work->user['avatar'] ?? '/img/avatars/default_avatar.png')}}" alt="user_avatar">
    </div>

    <div>

        <div style="margin-bottom: 10px; display: flex; align-items: center;">
            <div>
            <h2>
                {{($user['nickname']) ? $user['nickname'] : $user['name'] . ' ' . $user['surname']}}
            </h2>
            </div>

            <span style="border: 1px #969393 solid; color: #969393;" class="user_now"> Не в сети</span>

        </div>

        <div class="user_header_buttons">
            @livewire('subscribe-button', ['user_to_subscribe' => $user->id])
            <a href="">
                <span class="tooltip" title="Сообщение">
                    <i class="fa-regular fa-envelope"></i>
                </span>

            </a>
            <a href="">
                <span class="tooltip" title="Спонсировать">
                    <i class="fa-solid fa-ruble-sign"></i>
                </span>
            </a>
        </div>
    </div>

    <div class="user_header_stats_block">
        <div>
            <h1>40</h1>
            <p>читателей</p>
        </div>

        <div>
            <h1>3</h1>
            <p>читает</p>
        </div>

        <div>
            <h1>20</h1>
            <p>работ</p>
        </div>

        <div>
            <h1>5</h1>
            <p>наград</p>
        </div>

        <div>
            <h1>20</h1>
            <p>дней вместе</p>
        </div>

    </div>


</div>
