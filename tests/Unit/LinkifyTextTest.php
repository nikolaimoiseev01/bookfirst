<?php

test('turns web addresses into safe links', function () {
    $result = linkifyText('Откройте https://example.com/path?a=1&b=2 или www.example.org.');

    expect($result)
        ->toContain('href="https://example.com/path?a=1&amp;b=2"')
        ->toContain('href="https://www.example.org"')
        ->toContain('>www.example.org</a>.')
        ->toContain('target="_blank"')
        ->toContain('rel="noopener noreferrer"');
});

test('does not turn email addresses or html into executable markup', function () {
    $result = linkifyText('Почта user@example.com <script>alert(1)</script> https://example.com');

    expect($result)
        ->toContain('user@example.com')
        ->toContain('&lt;script&gt;alert(1)&lt;/script&gt;')
        ->not->toContain('<script>')
        ->toContain('<a href="https://example.com"');
});
