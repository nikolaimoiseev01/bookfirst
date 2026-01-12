@component('mail::message')
# Здравствуйте, {{ $user->name }}!

{{ $text }}

@component('mail::button', ['url' => $continueUrl])
    Продолжить заполнение
@endcomponent

С уважением,<br>
{{config('app.name')}}

<p style="margin-top: 20px; font-size: 14px;">
    <a href="{{ $unsubscribeUrl }}" style="color: #999;">
        Отписаться от этого уведомления
    </a>
</p>
@endcomponent
