<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Col_status;
use App\Models\Participation;
use App\Models\preview_comment;
use App\Models\Printorder;
use App\Models\User;
use App\Models\vote;
use App\Notifications\EmailNotification;
use App\Notifications\UserNotification;
use Illuminate\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Jenssegers\Date\Date;

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
            ->orderBy('collections.col_status_id')
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Collection $collection
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit(Collection $collection)
    {
        $col_statuses = Col_status::orderBY('id')->get();
        $participations = Participation::orderBy('pat_status_id', 'asc')->where('collection_id', $collection->id)->get();
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
                , 'p1.name as user_from_name'
                , 'p1.surname as user_from_surname'
                , 'p1.nickname as user_from_nickname'
                , 'p2.name as user_to_name'
                , 'p2.surname as user_to_surname'
                , 'p2.nickname as user_to_nickname'
            )
            ->where('votes.collection_id', $collection->id)
            ->get();

        $winners = DB::table('votes')
            ->Join('participations as p2', function ($join) {
                $join->on('p2.user_id', '=', 'votes.user_id_to');
                $join->on('p2.collection_id', '=', 'votes.collection_id');
            })
            ->select('p2.name', 'p2.surname', 'p2.nickname'
                , DB::raw('count(votes.user_id_from) AS votes_got')
            )
            ->where('votes.collection_id', $collection->id)
            ->groupBy('votes.user_id_to')
            ->orderBy('votes_got', 'desc')
            ->get();

        return view('admin.collection.collection-page', [
            'collection' => $collection,
            'col_statuses' => $col_statuses,
            'participations' => $participations,
            'collection_title' => $collection_title,
            'printorders' => $printorders,
            'pre_comments' => $pre_comments,
            'votes' => $votes,
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

            $collection->title = $request->title;
            $collection->col_desc = $request->col_desc;
            $collection->col_status_id = $request->col_status_id;
            $collection->col_date1 = $request->col_date1;
            $collection->col_date2 = $request->col_date2;
            $collection->col_date3 = $request->col_date3;
            $collection->col_date4 = $request->col_date4;
            $collection->amazon_link = $request->amazon_link;


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

            if (Collection::where('id', $collection->id)->value('amazon_link') === null and $request->amazon_link <> null) {
                $users_from_participation = Participation::where('collection_id', $collection->id)->get('user_id')->toArray();
                $users = User::whereIn('id', $users_from_participation)->get();


                foreach ($users as $user) {

                    $user->notify(new EmailNotification(
                        'Встречайте книгу на Amazon.com!',
                        $user['name'],
                        "Сборник '" . $request->title . "' успешно появился на Amazon.com! " .
                        "Ссылка на покупку доступна на странице наших сборников:",
                        "Наши сборники",
                        route('homePortal') . "/old_collections",
                    ));

                    \Illuminate\Support\Facades\Notification::send($user, new UserNotification(
                        'Сборник был размещен на Amazon.com!',
                        route('old_collections'),
                    ));
                }

            }

            if ($request->col_status_id == 2) {
                $users_from_participation = Participation::where('collection_id', $collection->id)->get('user_id')->toArray();
                $users = User::whereIn('id', $users_from_participation)->get();

                foreach ($users as $user) {
                    $user->notify(new EmailNotification(
                            'Процесс издания сборника',
                            $user['name'],
                            "Спешим вам сообщить, что произошла смена этапа издания сборника: " . $request->title .
                            "'! Сборник сменил свой статус на \"предварительная проверка\", и теперь вы можете проверить предварительный экземпляр и внести правки. " .
                            "Срок внесения изменений: до " . Date::parse($collection->col_date3)->format('j F') . " (19:59 МСК). " .
                            "Вся подробная информация об издании сборника и вашем процессе указана на странице участия:",
                            "Ваша страница участия",
                            route('homePortal') . "/myaccount/collections/" . $collection->id . "/participation/" . Participation::where([['user_id', $user->id], ['collection_id', $collection->id]])->value('id'))
                    );

                    \Illuminate\Support\Facades\Notification::send($user, new UserNotification(
                        'Статус сборника был изменен!',
                        route('homePortal') . "/myaccount/collections/" . $collection->id . "/participation/" . Participation::where([['user_id', $user->id], ['collection_id', $collection->id]])->value('id')
                    ));
                }
            }

            if ($request->col_status_id == 3) {
                $users_from_participation = Participation::where('collection_id', $collection->id)->get('user_id')->toArray();
                $users = User::whereIn('id', $users_from_participation)->get();

                foreach ($users as $user) {
                    $user->notify(new EmailNotification(
                            'Процесс издания сборника',
                            $user['name'],
                            "Спешим вам сообщить, что произошла смена этапа издания сборника: " . $request->title .
                            "'! В сборнике были учтены все исправления, и сейчас начинается печать экземпляров. " .
                            "Обычно это занимает 14 рабочих дней. Как только экземпляры будут напечатаны, Вы получите оповещние об этом по Email. "
                            . "Далее в личном кабинете на странице участия Вы сможете отследить свою посылку. " .
                            "Вся подробная информация об издании сборника и вашем процессе указана на странице участия:",
                            "Ваша страница участия",
                            route('homePortal') . "/myaccount/collections/" . $collection->id . "/participation/" . Participation::where([['user_id', $user->id], ['collection_id', $collection->id]])->value('id'))
                    );

                    \Illuminate\Support\Facades\Notification::send($user, new UserNotification(
                        'Статус сборника был изменен!',
                        route('homePortal') . "/myaccount/collections/" . $collection->id . "/participation/" . Participation::where([['user_id', $user->id], ['collection_id', $collection->id]])->value('id')
                    ));
                }
            }

            if ($request->col_status_id == 9 && $request->amazon_link === null) {
                $users_from_participation = Participation::where('collection_id', $collection->id)->get('user_id')->toArray();
                $users = User::whereIn('id', $users_from_participation)->get();

                foreach ($users as $user) {
                    $user->notify(new EmailNotification(
                            'Печатные экземпляры успешно отправлены!',
                            $user['name'],
                            "Произошла смена этапа издания сборника: " . $request->title .
                            "'! Спешим сообщить, что все печатные экземпляры были успешно отправлены в указанные пункты назначения. На странице участия Вы всегда можете отследить нахождение лично Вашей посылки.",
                            "На страницу участия",
                            route('homePortal') . "/myaccount/collections/" . $collection->id . "/participation/" . Participation::where([['user_id', $user->id], ['collection_id', $collection->id]])->value('id'))
                    );

                    \Illuminate\Support\Facades\Notification::send($user, new UserNotification(
                        'Статус сборника был изменен!',
                        route('homePortal') . "/myaccount/collections/" . $collection->id . "/participation/" . Participation::where([['user_id', $user->id], ['collection_id', $collection->id]])->value('id')
                    ));
                }
            }

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

    public function change_all_preview_collection_comment_status(Request $request)
    {
        $prev_comments = preview_comment::where('collection_id', $request->collection_id)
            ->update(['status_done' => 1]);

        return redirect(url()->previous());
    }
}
