<?php

// Function to filter array by ID
use Carbon\Carbon;

function makeMoney($int, $decimals = 0, $rub_sign = false)
{
    $money = number_format(intval($int), $decimals, ',', ' ') . ($rub_sign ? ' ₽' : '');
    return $money;
}

function getUserAvatar($user)
{
    $avatar = $user->getFirstMediaUrl('avatar', 'thumb');
    if ($avatar == null || $avatar == '') {
        return ENV('APP_URL') . '/fixed/default_avatar.svg';
    } else {
        return $avatar;
    }
}

function getUserName($user)
{
    return $user['nickname'] ?? $user['name'] . ' ' . $user['surname'];
}

function getWorkCover($work)
{
    if ($work->getFirstMediaUrl('cover') ?? null) {
        $cover = $work->getFirstMediaUrl('cover');
    } else {
        $rnd = Rand(1, 4);
        $cover = "/fixed/default_work_pic_{$rnd}.svg";
    }
    return $cover;
}

function formatDate($date, $format='j F', $addDays=0):string {
    return Carbon::parse($date)->addDays($addDays)->translatedFormat($format);
}

function getTelegramChatId($chat = null) {
    if (config('app.env') == 'local') {
        $chatId = config('services.telegram-chats.test');
    } else {
        if ($chat == 'extPromotion') {
            $chatId =  config('services.telegram-chats.ext_promotion');
        } else {
            $chatId =  config('services.telegram-chats.main');
        }
    }
    return $chatId;
}


