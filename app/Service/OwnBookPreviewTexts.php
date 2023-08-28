<?php

namespace App\Service;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Date;

class OwnBookPreviewTexts
{
    public function get_text($own_book, $chosen_type, $status_id)
    {


        if ($chosen_type == 'inside') {
            $deadline = \Jenssegers\Date\Date::parse($own_book['inside_deadline'])->format('j F Y');
            $type = 'внутренний блок';
            $type_2 = 'обложка';
        } else {
            $deadline = \Jenssegers\Date\Date::parse($own_book['cover_deadline'])->format('j F Y');
            $type = 'обложка';
            $type_2 = 'обложку';
        }

        $text_dev = '
            <p class="no-access">
                На данный момент идет работа над материалами. Предварительные варианты появятся здесь
                до ' . $deadline . '.
            </p>
        ';

        $text_inside_check = ($chosen_type == 'inside') ? 'Это означает, что все регистрационные номера присвоены, и блок сверстан.' : '';
        $text_check = '
            <p>
                На данный момент ' . $type . ' находится на этапе предварительной проверки.
                ' . $text_inside_check . '
            Сейчас необходимо скачать файл и проверить его.
                <br><b>Если исправления не требуются, пожалуйста, утвердите макет нажатием на кнопку
                    "Утвердить макет"</b>
                <br>Если требуются, пожалуйста, укажите описание исправления в форме ниже.
                <br> Когда все исправления указаны, необходимо отправить макет на дальнейшее редактирование.
                <b> Только тогда мы начнем работу над исправлениями. Для этого нажмите "Отправить на
                    исправление".</b>
            </p>
        ';


        $text_fixing = '
            <p>
                На данный момент мы вносим указанные изменения.
                Срок: до ' . $deadline . '. Как только они будут учтены,
                Вы получите оповещение об этом на почте и внутри нашей системы.
                Далее материалы можно будет еще раз проверить, а затем запросить
                дополнительные изменения или утвердить.
            </p>
        ';

        $text_ready = '
            <p>
                Поздравляем! Вы успешно утвердили ' . $type_2 . '
                Как только будут утверждены обложка и внутренний блок, можно будет переходить к
                следующим этапам издания.
            </p>
        ';

        $text_ready_from_author = '
            <p class="no-access">
                В заявке указано, что ' . $type . ' полностью подходит для издания и печати.
                Сейчас мы это проверяем.
                Если все в порядке, мы сменим статус на "готово к изданию".
                Если нет, то укажем комментарий по исправлению в чате на этой странице.
                Вы получите об этом оповещение на почте в том числе.
            </p>
        ';

        $text_wait_for_author = '
            <p>
                Чтобы продолжить работу, пожалуйста, ответьте на вопрос в чате выше.
            </p>
        ';

        if ($status_id === 1) {
            $text = $text_dev;
        } elseif ($status_id === 2) {
            $text = $text_check;
        } elseif ($status_id === 3) {
            $text = $text_fixing;
        } elseif ($status_id === 4) {
            $text = $text_ready;
        } elseif ($status_id === 9) {
            $text = $text_ready_from_author;
        } elseif ($status_id === 99) {
            $text = $text_wait_for_author;
        }

        return $text;

    }
}
