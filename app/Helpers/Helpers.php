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

/**
 * Escape message text and turn web addresses into safe links.
 *
 * URLs are transformed only while rendering, so existing messages benefit
 * from the same behavior and the database continues to contain plain text.
 */
function linkifyText(?string $text): string
{
    if ($text === null || $text === '') {
        return '';
    }

    $pattern = '~(?<![\p{L}\p{N}_@])((?:(?:https?://|www\.)[^\s<]+|(?:[\p{L}\p{N}](?:[\p{L}\p{N}-]{0,61}[\p{L}\p{N}])?\.)+[\p{L}]{2,}(?::\d+)?(?:/[^\s<]*)?))~iu';
    preg_match_all($pattern, $text, $matches, PREG_OFFSET_CAPTURE);

    $result = '';
    $offset = 0;

    foreach ($matches[0] ?? [] as [$matchedUrl, $start]) {
        $result .= e(substr($text, $offset, $start - $offset));

        [$url, $trailing] = splitTrailingUrlPunctuation($matchedUrl);
        $href = preg_match('~^www\.~i', $url) || !preg_match('~^https?://~i', $url)
            ? "https://{$url}"
            : $url;

        $result .= '<a href="' . e($href) . '" target="_blank" rel="noopener noreferrer" class="underline break-all">'
            . e($url)
            . '</a>'
            . e($trailing);

        $offset = $start + strlen($matchedUrl);
    }

    return $result . e(substr($text, $offset));
}

function splitTrailingUrlPunctuation(string $url): array
{
    $trailing = '';

    while ($url !== '') {
        $lastCharacter = substr($url, -1);

        if (!str_contains('.,!?;:)]}>"\'', $lastCharacter)) {
            break;
        }

        if ($lastCharacter === ')' && substr_count($url, '(') >= substr_count($url, ')')) {
            break;
        }

        $trailing = $lastCharacter . $trailing;
        $url = substr($url, 0, -1);
    }

    return [$url, $trailing];
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
