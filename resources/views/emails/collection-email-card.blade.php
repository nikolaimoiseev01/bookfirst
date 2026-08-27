<table class="box" style="border: 1px solid #e0e0e0; width:80%;min-width:800px;max-width:800px;border-radius:15px;background-color:#ffffff;margin-top:50px;margin-bottom:50px;margin-right:0;margin-left:0;box-shadow: 0 0 10px 1px rgb(0 0 0 / 11%);">
    <tbody>
    <tr>
        <td style="text-align:center; padding-top: 16px; padding-bottom: 16px;">
            <img
                alt="{{ $collection->title }}"
                src="{{ $collection->getFirstMediaUrl('cover_3d') }}"
                style="width:100%;min-width:190px;max-width:190px;image-rendering:-webkit-optimize-contrast;image-rendering:-moz-crisp-edges;"
            >
        </td>

        <td style="vertical-align:top;width:65%;">
            <table>
                <tbody>
                <tr>
                    <td
                        class="book-title"
                        style="color:{{ $collection->title_color ?? '#e39807' }};font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;font-weight:400;font-size:26px;"
                    >
                        {{ $collection->title }}
                    </td>
                </tr>
                <tr>
                    <td
                        class="book-desc"
                        style="font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;font-weight:400;color:#1c1c16;font-size:18px;line-height:25px;letter-spacing:0.2px;"
                    >
                        <p style="color:#1c1c16">
                            {{ $collection->description }}
                        </p>

                        <p style="font-style:italic;">
                            <span style="color:#47AF98; font-weight: 400;">Конец приема заявок:</span>
                            <span style="color:#1c1c16">
                                        {{ $collection->date_apps_end->translatedFormat('j F') }}
                                    </span>
                            <br>

                            <span style="color:#47AF98; font-weight: 400;">Формат произведений:</span>
                            <span style="color:#1c1c16">
                                        {{ $collection->workType->name }} любого жанра

                                    </span>
                        </p>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>

    <tr>
        <td
            align="center"
            class="more-info"
            colspan="2"
            style="border-top:1px solid #E0E0E0;font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;font-size:18px;line-height:1.5;letter-spacing:0.2px;font-weight:600;color:#3b5fb9;padding:10px;"
        >
            <a
                class="button"
                href="{{ route('portal.collection', [
                    'slug' => $collection->slug,
                    'utm_source' => 'email',
                    'utm_content' => $collection->slug,
                    'utm_campaign' => $utmCampaign ?? '',
                ]) }}"
                style="width:50%;display:block;font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;font-weight:400;background:#ffffff;color:#47AF98;border-radius:7px;border:1px #47AF98 solid;padding:3px 20px;text-decoration:none;"
            >
                Подробнее
            </a>
        </td>
    </tr>
    </tbody>
</table>
