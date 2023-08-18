<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\award;
use App\Models\Chat;
use App\Models\Collection;
use App\Models\Col_status;
use App\Models\collection_winner;
use App\Models\EmailSent;
use App\Models\Message;
use App\Models\Participation;
use App\Models\Participation_work;
use App\Models\preview_comment;
use App\Models\Printorder;
use App\Models\User;
use App\Models\vote;
use App\Models\Work;
use App\Notifications\AllParticipantsEmail;
use App\Notifications\EmailNotification;
use App\Notifications\UserNotification;
use Carbon\Carbon;
use Illuminate\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Jenssegers\Date\Date;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        $col_statuses = Col_status::orderBY('id')->get();
        $collections = DB::table('collections')
            ->leftJoin('participations', function ($join) {
                $join->on('collections.id', '=', 'participations.collection_id');
            })
            ->Join('col_statuses', 'collections.col_status_id', '=', 'col_statuses.id')
            ->select('collections.*', 'col_statuses.col_status',
                DB::raw('sum((CASE WHEN participations.pat_status_id = 1 THEN 1 ELSE 0 END)) AS new_participants'),
                DB::raw('count(participations.id) AS total_participants')
            )
            ->where('col_status_id', '<>', 9)
            ->groupBy('collections.id')
            ->orderBy('collections.created_at', 'desc')
            ->paginate(5);


        return view('admin.collection.index', [
            'collections' => $collections,
            'col_statuses' => $col_statuses
        ]);
    }

    public function closed_collections()
    {
        $col_statuses = Col_status::orderBY('id')->get();
        $collections = Collection::where('col_status_id', 9)->orderBy('id', 'desc')->paginate(10);


        return view('admin.collection.closed_collections', [
            'collections' => $collections,
            'col_statuses' => $col_statuses
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        $col_statuses = Col_status::orderBY('id')->get();

        return
            view('admin.collection.create', [
                'col_statuses' => $col_statuses
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        File::makeDirectory('admin_files/Collections/' . $request->title);
        $new_col = new Collection();
        $new_col->title = $request->title;
        $new_col->col_desc = $request->col_desc;
        $new_col->cover_2d = 'admin_files/Collections/' . $request->title . '/' . $request->cover_2d->getClientOriginalName();
        $new_col->cover_3d = 'admin_files/Collections/' . $request->title . '/' . $request->cover_3d->getClientOriginalName();
        $new_col->col_status_id = 1;
        $new_col->col_date1 = $request->col_date1;
        $new_col->col_date2 = $request->col_date2;
        $new_col->col_date3 = $request->col_date3;
        $new_col->col_date4 = $request->col_date4;
        $new_col->save();
        $request->cover_2d->move(public_path('admin_files/Collections/' . $request->title . '/'), $request->cover_2d->getClientOriginalName());
        $request->cover_3d->move(public_path('admin_files/Collections/' . $request->title . '/'), $request->cover_3d->getClientOriginalName());
        return redirect()->back()->withSuccess('Книга была добавлена!');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Collection $collection
     * @return \Illuminate\Http\Response
     */
    public function show(Collection $collection)
    {
        //
    }

    public function download_all_prints(Request $request)
    {
        $authors = Participation::where('collection_id', $request->col_id)->where('pat_status_id', 3)->where('printorder_id', '>', 0)->get();
        $prints = Printorder::where('collection_id', $request->col_id)->where('paid_at', '<>', null)->get();


        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'ФИО');
        $sheet->setCellValue('B1', 'Адрес');
        $sheet->setCellValue('C1', 'Кол-во');
        $sheet->setCellValue('D1', 'Трек-номер');

        $spreadsheet->getActiveSheet()->getStyle("A1:D1")->getFont()->setBold(true);

        foreach ($authors as $key => $author) {
            $print = Printorder::where('id', $author['printorder_id'])->first();
            $sheet->setCellValue("A" . ($key + 2), $print['send_to_name']);
            $sheet->setCellValue("B" . ($key + 2), $print['send_to_address'] . '; Тел.: ' . $print['send_to_tel']);
            $sheet->setCellValue("C" . ($key + 2), $print['books_needed']);
        }

        foreach (range('A', 'D') as $columnID) {
            $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                ->setAutoSize(true);
        }


        $writer = new Xlsx($spreadsheet);
        $col_title = 'Печать ' . Collection::where('id', $request->col_id)->value('title');
        $writer->save($col_title . '.xlsx');
        return response()->download($col_title . '.xlsx')->deleteFileAfterSend(true);

    }


    public function create_col_file(Request $request)
    {
        $authors = Participation::where('collection_id', $request->col_id)->orderBy('paid_at', 'asc')->where('pat_status_id', 3)->get();

        // Creating the new document...
        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        // Делаем стили для разных сборников
        if (str_contains($authors[1]->collection['title'], 'Дух')) {
            $page_size = "A5";
            $author_name_style = array('name' => 'a_BentTitulNr', 'size' => 16, 'color' => 'F79646', 'bold' => true);
            $author_name_footer_style = array('name' => 'Bad Script', 'size' => 14, 'color' => '000000', 'bold' => true);
            $work_title_style = array('name' => 'Bad Script', 'size' => 16, 'color' => 'FF0000', 'bold' => true);
            $work_title_align = array('align' => 'left');
            $work_text_style = array('name' => 'Ayuthaya', 'size' => 10, 'color' => '000000', 'bold' => false);
            $phpWord->getSettings()->setMirrorMargins(true);

        } else {
            $page_size = "A4";
            $author_name_style = array('name' => 'Days', 'size' => 16, 'color' => 'F79646', 'bold' => true);
            $author_name_footer_style = array('name' => 'Accuratist', 'size' => 14, 'color' => '000000', 'bold' => false);
            $work_title_style = array('name' => 'Ayuthaya', 'size' => 14, 'color' => 'FF0000', 'bold' => false, 'italic' => true);
            $work_title_align = array('align' => 'center');
            $work_text_style = array('name' => 'Calibri Light', 'size' => 14, 'color' => '000000', 'bold' => false);
        }

        $PidPageSettings = array(
            'marginTop' => 1000,
            'footerHeight' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(.35),
            'marginBottom' => 1100,
            "paperSize" => $page_size,
            'headerHeight' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(.28)
        );


        foreach ($authors as $author) {


            // Создаем новый раздел для автора
            $section = $phpWord->addSection($PidPageSettings);

            $phpWord->setDefaultParagraphStyle(
                array(
                    'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(0),
                    'spacing' => 120,
                    'lineHeight' => 1,
                )
            );

            if ($author['nickname']) {
                $author_name = $author['nickname'];
            } else {
                $author_name = $author['name'] . ' ' . $author['surname'];
            }

            // Пишем имя автора
            $section->addText(
                $author_name,
                $author_name_style,
                ['align' => 'center']
            );

            // Делаем отступ от автора
            $section->addText(' ',
                array('name' => 'Calibri', 'size' => 5, 'color' => '000000', 'bold' => false)
            );

            // Пишем имя автора в колонтитул
            $footer = $section->addFooter();
            $footer->addText(
                $author_name,
                $author_name_footer_style
            );

            // Делаем изображение в хедер
            if (str_contains($author->collection['title'], 'Дух')) {
                $header = $section->addHeader();
                $header->firstPage();
                $header->addText("");

                $header_sub = $section->addHeader();
                $header_sub->addImage('img/duh_header_img.png',
                    array('width' => 200,
                        'height' => 27.27,
                        'alignment' => 'center'
                    )
                );
            }


            $author_works = Participation_work::where('participation_id', $author['id'])->get();

            foreach ($author_works as $author_work) {

                $work = Work::where('id', $author_work['work_id'])->first();
                // Пишем название
                $section->addText($work['title'],
                    $work_title_style,
                    $work_title_align
                );

                $work_text = str_replace("\n", '<w:br/>', htmlspecialchars($work['text']));

                \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(false);


                // Пишем текст работы
                $section->addText(
//                        xmlEntities(htmlentities($work_text)),
                    $work_text,
                    $work_text_style
                );
            }

        }


        // Создаем контактную информацию авторов

        $section = $phpWord->addSection($PidPageSettings);
        $table = $section->addTable();

        foreach ($authors as $author) {
            if ($author['nickname']) {
                $author_name = $author['nickname'];
            } else {
                $author_name = $author['name'] . ' ' . $author['surname'];
            }
            $table->addRow();
            $table->addCell(1750)->addText($author_name);
            $table->addCell(1750)->addText($author->user['email']);
        }

        \PhpOffice\PhpWord\Settings::setCompatibility(false);
        \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(false);
        // Saving the document as HTML file...
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $col_title = Collection::where('id', $request->col_id)->value('title');
        $objWriter->save($col_title . '.docx');
        return response()->download($col_title . '.docx')->deleteFileAfterSend(true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Collection $collection
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit(Collection $collection)
    {
        $col_statuses = Col_status::orderBY('id')->get();
        $participations = Participation::orderBy('pat_status_id', 'asc')->orderBy('paid_at', 'asc')->where('collection_id', $collection->id)->get();
        $collection_title = DB::table('collections')->where('id', $collection->id)->value('title');
        $printorders = PrintOrder::orderBy('id', 'desc')->where('collection_id', $collection->id)->get();
        $pre_comments = preview_comment::where('collection_id', $collection->id)->with('participation')->get();
//        $votes = vote::where('collection_id', $collection->id)->with('Collection')->with('Participation')->get();
        $votes = DB::table('votes')
            ->Join('participations as p1', function ($join) {
                $join->on('p1.user_id', '=', 'votes.user_id_from');
                $join->on('p1.collection_id', '=', 'votes.collection_id');
            })
            ->Join('participations as p2', function ($join) {
                $join->on('p2.user_id', '=', 'votes.user_id_to');
                $join->on('p2.collection_id', '=', 'votes.collection_id');
            })
            ->select('votes.*'
                , 'p1.id as participation_id_from'
                , 'p2.id as participation_id_to'
                , 'p1.name as user_from_name'
                , 'p1.surname as user_from_surname'
                , 'p1.nickname as user_from_nickname'
                , 'p2.name as user_to_name'
                , 'p2.surname as user_to_surname'
                , 'p2.nickname as user_to_nickname'
            )
            ->where('votes.collection_id', $collection->id)
            ->get();

        $winners_candidates = DB::table('votes')
            ->Join('participations as p2', function ($join) {
                $join->on('p2.user_id', '=', 'votes.user_id_to');
                $join->on('p2.collection_id', '=', 'votes.collection_id');
            })
            ->select('p2.id as participation_id', 'p2.name', 'p2.surname', 'p2.nickname'
                , DB::raw('count(votes.user_id_from) AS votes_got')
            )
            ->where('votes.collection_id', $collection->id)
            ->groupBy('votes.user_id_to')
            ->orderBy('votes_got', 'desc')
            ->get();
        $emails_sent = EmailSent::where('collection_id', $collection->id)->get();
        $winners = collection_winner::where('collection_id', $collection->id)->orderBy('place', 'asc')->get();
        return view('admin.collection.collection-page', [
            'collection' => $collection,
            'col_statuses' => $col_statuses,
            'participations' => $participations,
            'collection_title' => $collection_title,
            'printorders' => $printorders,
            'pre_comments' => $pre_comments,
            'votes' => $votes,
            'winners_candidates' => $winners_candidates,
            'emails_sent' => $emails_sent,
            'winners' => $winners,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Collection $collection
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Collection $collection)
    {

        App::setLocale('ru');


        if (($request->col_status_id === "2") && $collection->pre_var === null && !$request->file('pre_var')) {
            session()->flash('success', 'change_printorder');
            session()->flash('alert_type', 'error');
            session()->flash('alert_title', 'Что-то пошло не так :(');
            session()->flash('alert_text', 'Сначала нужно загрузить файл предварительного варианта!');
        } else {

            // Сохраняем файлы из формы
            if (!is_null($request->file('cover_2d'))) {
                $cover_2d = 'admin_files/Collections/' . $request->title . '/' . $request->file('cover_2d')->getClientOriginalName();
                $collection->cover_2d = $cover_2d;
                File::delete($collection->cover_2d);
                $request->file('cover_2d')->move(public_path('admin_files/Collections/' . $request->title . '/'), $request->file('cover_2d')->getClientOriginalName());
            }
            if (!is_null($request->file('cover_3d'))) {
                $cover_3d = 'admin_files/Collections/' . $request->title . '/' . $request->file('cover_3d')->getClientOriginalName();
                $collection->cover_3d = $cover_3d;
                File::delete($collection->cover_3d);
                $request->file('cover_3d')->move(public_path('admin_files/Collections/' . $request->title . '/'), $request->file('cover_3d')->getClientOriginalName());
            }

            if (!is_null($request->file('pre_var'))) {
                $pre_var = 'admin_files/Collections/' . $request->title . '/' . $request->file('pre_var')->getClientOriginalName();
                File::delete($collection->pre_var);
                $collection->pre_var = $pre_var;
                $request->file('pre_var')->move(public_path('admin_files/Collections/' . $request->title . '/'), $request->file('pre_var')->getClientOriginalName());
            }


            // Создаем тексты уведомлений

            if ($collection['col_status_id'] === $request->col_status_id
                && $collection['amazon_link'] === null
                && $request->amazon_link <> null) { // Если статус не менялся, а просто появилась ссылка

                $subject = "Сборник '" . $request->title . "' успешно появился на Amazon.com! ";
                $text = "Ссылка на покупку доступна на странице наших сборников.";

            } elseif ($request->col_status_id == 2) {
                $subject = 'Процесс издания сборника';
                $text = "Спешим вам сообщить, что произошла смена этапа издания сборника: '" . $request->title .
                    "'! Сборник сменил свой статус на \"предварительная проверка\". Теперь его можно скачать на странице участия и внести правки. " .
                    "Срок внесения изменений: до " . Date::parse($collection->col_date3)->format('j F') . " (19:59 МСК). " .
                    "Вся подробная информация об издании сборника и вашем процессе указана на странице участия.";

            } elseif ($request->col_status_id == 3) {
                $subject = 'Процесс издания сборника';
                $text = "Спешим вам сообщить, что произошла смена этапа издания сборника: '" . $request->title .
                    "'! В сборнике были учтены все исправления, и сейчас начинается печать экземпляров. " .
                    "Обычно это занимает 14 рабочих дней. Как только экземпляры будут напечатаны, Вы получите оповещние об этом по Email. "
                    . "Далее в личном кабинете на странице участия Вы сможете отследить свою посылку. " .
                    "Вся подробная информация об издании сборника и вашем процессе указана на странице участия.";


            } elseif ($request->col_status_id == 9) {

                $subject = 'Процесс издания сборника';
                $text = "Произошла смена этапа издания сборника: '" . $request->title .
                    "'! Спешим сообщить, что все печатные экземпляры были успешно отправлены в указанные пункты назначения. На странице участия Вы всегда можете отследить нахождение лично Вашей посылки.";

            }


            // Посылаем уведомление всем пользователям
            if (!ENV('APP_DEBUG')) {
                $users_from_participation = Participation::where('collection_id', $collection->id)->get('user_id')->toArray();
                $users = User::whereIn('id', $users_from_participation)->get();
                foreach ($users as $user) {
                    $button_link = route('homePortal') . "/myaccount/collections/" . $collection->id . "/participation/" . Participation::where([['user_id', $user->id], ['collection_id', $collection->id]])->value('id');
                    $user->notify(new EmailNotification(
                            $subject,
                            $user['name'],
                            $text,
                            'Страница участия',
                            $button_link)
                    );
                    \Illuminate\Support\Facades\Notification::send($user, new UserNotification(
                        $subject,
                        $button_link
                    ));

                }
            }

            // Обновляем значения сборника
            $collection->title = $request->title;
            $collection->col_desc = $request->col_desc;
            $collection->col_status_id = $request->col_status_id;
            $collection->col_date1 = $request->col_date1;
            $collection->col_date2 = $request->col_date2;
            $collection->col_date3 = $request->col_date3;
            $collection->col_date4 = $request->col_date4;
            $collection->amazon_link = $request->amazon_link;

            $collection->save();
            session()->flash('success', 'change_printorder');
            session()->flash('alert_type', 'success');
            session()->flash('alert_title', 'Сборник успешно обновлен!');

        }
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Collection $collection
     * @return \Illuminate\Http\Response
     */
    public function destroy(Collection $collection)
    {
        //
    }

    public function send_email_all_participants(Request $request)
    {

        if ($request->subject === "" || $request->email_text === null || $request->email_text === "" || $request->email_text === null) {
            session()->flash('success', 'change_printorder');
            session()->flash('alert_type', 'error');
            session()->flash('alert_title', 'Что-то пошло не так :(');
            session()->flash('alert_text', 'Не все поля заполнены :(');
            return redirect()->back();
        } else {


            $users_from_participation = Participation::where('collection_id', $request->col_id)->get('user_id')->toArray();
            $users = User::whereIn('id', $users_from_participation)->get();
            $sent_to_users = "";

            foreach ($users as $user) {
                $sent_to_users = $sent_to_users . $user['id'] . ";";
                $user->notify(new AllParticipantsEmail(
                        $request->subject,
                        $user['name'],
                        $request->email_text,
                        route('homePortal') . "/myaccount/collections/" . $request->col_id . "/participation/" . Participation::where([['user_id', $user->id], ['collection_id', $request->col_id]])->value('id'))
                );
            }


            // ---- Сохраняем письмо! ---- //
            $new_EmailSent = new EmailSent();
            $new_EmailSent->collection_id = $request->col_id;
            $new_EmailSent->subject = $request->subject;
            $new_EmailSent->email_text = $request->email_text;
            $new_EmailSent->sent_to_user = substr($sent_to_users, 0, -1);
            $new_EmailSent->save();
            // ---- //// Сохраняем письмо! ---- //


            session()->flash('success', 'change_printorder');
            session()->flash('alert_type', 'success');
            session()->flash('alert_title', 'Успешно!');
            session()->flash('alert_text', 'Мы послали всем участникам емейлы :)');

            return redirect()->back();
        }
    }

    public function change_all_preview_collection_comment_status(Request $request)
    {
        $prev_comments = preview_comment::where('collection_id', $request->collection_id)
            ->update(['status_done' => 1]);

        return redirect(url()->previous());
    }


    public function add_winner($collection_id, Request $request)
    {
        $user = User::where('id', Participation::where('id', $request->winner_participation_id)->value('user_id'))->first();
        $collection = Collection::where('id', $collection_id)->first();
        $chat = Chat::where('user_created', $user['id'])->where('collection_id', $collection_id)->first();
        $message_text_email = "Поздравляем! Вы заняли " . $request->place . " место в конкурсе авторов сборника '" . $collection['title'] . "'! " .
            "Сейчас необходимо прислать небольшой блок информации о себе для добавления в сборник. Пожалуйста, отправьте его в чате на странице участия.";

        $message_text_chat = "Здравствуйте, " . $user['name'] . "!" . "\n\n" .
            "Спешим Вам сообщить, что вы заняли " . $request->place . " место в конкурсе авторов сборника '" . $collection['title'] . "'!\n" .
            "За вас проголосовало большое количество участников! Пожалуйста, пришлите информацию о себе, которую вы бы хотели видеть в сборнике (она будет вставлена в блок призеров конкурса)." . "\n\n" .
            "О том, как получить приз, мы сообщим позднее." . "\n\n" .
            "Поздравляем!";

        // Создаем награду юзеру
        award::create([
            'user_id' => $user['id'],
            'award_type_id' => $request->place,
            'collection_id' => $collection['id']
        ]);

        // ---- Сохраняем победителя! ---- //
        $new_winner = new collection_winner();
        $new_winner->collection_id = $collection['id'];
        $new_winner->participation_id = $request->winner_participation_id;
        $new_winner->place = $request->place;
        $new_winner->user_id = $user['id'];
        $new_winner->save();
        // ---- //// Сохраняем победителя! ---- //

        // ---- //// Пишем по почте! ---- //
        $user->notify(new EmailNotification(
                'Вы были выбраны призёром конкурса!',
                $user['name'],
                $message_text_email,
                "На страницу участия",
                route('participation_index', ['participation_id' => $request->winner_participation_id, 'collection_id' => $collection['id']]))
        );

        // ---- //// Пишем в личном кабинете (нотификация) ---- //
        \Illuminate\Support\Facades\Notification::send($user, new UserNotification(
            'Вы были выбраны призёром конкурса!',
            route('participation_index', ['participation_id' => $request->winner_participation_id, 'collection_id' => $collection['id']])
        ));

        // ---- //// Пишем в чат! ---- //
        $new_message = new Message();
        $new_message->chat_id = $chat['id'];
        $new_message->user_from = 2;
        $new_message->user_to = $user['id'];
        $new_message->text = $message_text_chat;
        $new_message->save();

        session()->flash('success', 'change_printorder');
        session()->flash('alert_type', 'success');
        session()->flash('alert_title', 'Успешно!');
        session()->flash('alert_text', 'Выбрали победителя и даже послали письмо :)');

        return redirect()->back();


    }


}
