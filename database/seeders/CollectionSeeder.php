<?php

namespace Database\Seeders;

use App\Models\Collection\Collection;
use App\Models\Collection\CollectionNewsLetter;
use App\Models\Collection\CollectionStatus;
use App\Models\EmailSent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function make_collections($test = false)
    {
        $oldCollections = DB::connection('old_mysql')
            ->table('collections')
            ->where('id', '>', $test ? 100 : 0)
            ->get();
        foreach ($oldCollections as $collection) {

            $wordsToRemove = ['Современный ', ' Поэзии', 'Сокровенные ', 'Выпуск '];
            $short_nm = str_replace($wordsToRemove, '', $collection->title);
            $slug = Str::slug($short_nm);

            $winners = DB::connection('old_mysql')
                ->table('collection_winners')
                ->where('collection_id', $collection->id)
                ->orderBy('place', 'asc')
                ->pluck('user_id')
                ->toArray();

            $new_collection = Collection::firstOrCreate(
                ['name' => $collection->title],
                [
                    'id' => $collection->id,
                    'name_short' => $short_nm,
                    'slug' => $slug,
                    'collection_status_id' => $collection->col_status_id,
                    'description' => $collection->col_desc,
                    'date_apps_end' => $collection->col_date1,
                    'date_preview' => $collection->col_date2,
                    'date_voting_end' => $collection->col_date3,
                    'date_print_start' => $collection->col_date3,
                    'date_print_end' => $collection->col_date4,
                    'created_at' => $collection->created_at,
                    'updated_at' => $collection->updated_at,
                    'winners' => json_encode($winners),
                ]);
            if ($new_collection->wasRecentlyCreated) {
                $inside_url = 'https://pervajakniga.ru/' . $collection->pre_var;
                $cover_2d_url = 'https://pervajakniga.ru/' . $collection->cover_2d;
                $cover_3d_url = 'https://pervajakniga.ru/' . $collection->cover_3d;
                try {
                    $new_collection->addMediaFromUrl($inside_url)
                        ->usingFileName("$slug.pdf")      // имя файла на диске
                        ->usingName($slug)
                        ->preservingOriginal()
                        ->toMediaCollection('inside_file');
                } catch (\Throwable $th) {
                    // 1. Декодируем URL
                    $decoded = urldecode($inside_url);
                    // 2. Заменяем "й" (один символ) на "и" + "◌̆" (диакритика)
                    $replaced = str_replace('й', "и\u{0306}", $decoded);
                    // 3. Снова кодируем в URL
                    $encoded = rawurlencode($replaced);
                    // 4. Восстанавливаем остальные части URL (чтобы не кодировались двоеточия, слэши и т.п.)
                    $finalUrl = str_replace(
                        ['%3A', '%2F'],
                        [':', '/'],
                        $encoded
                    );
                    try {
                        $new_collection->addMediaFromUrl($finalUrl)
                            ->usingFileName("$slug.pdf")      // имя файла на диске
                            ->usingName($slug)
                            ->preservingOriginal()
                            ->toMediaCollection('inside_file');
                    } catch (\Throwable $th) {

                    }
                }
                try {
                    $new_collection->addMediaFromUrl($cover_2d_url)
                        ->preservingOriginal()
                        ->toMediaCollection('cover_2d');
                } catch (\Throwable $th) {
                    try {
                        $new_collection->addMediaFromUrl($cover_3d_url)
                            ->preservingOriginal()
                            ->toMediaCollection('cover_3d');
                    } catch (\Throwable $th) {
                    }
                }
            }
        }
    }

    public function make_statuses()
    {
        $statuses = [
            [
                'id' => 1,
                'name' => 'Идет прием заявок',
            ],
            [
                'id' => 2,
                'name' => 'Предварительная проверка',
            ],
            [
                'id' => 3,
                'name' => 'Подготовка к печати',
            ],
            [
                'id' => 4,
                'name' => 'Идет печать',
            ],
            [
                'id' => 9,
                'name' => 'Сборник издан',
            ],
        ];
        foreach ($statuses as $status) {
            CollectionStatus::create($status);
        }
    }

    public function make_news_letters()
    {
        $oldEmailSents = DB::connection('old_mysql')->table('email_sents')->get();
        foreach ($oldEmailSents as $emailSent) {
            $users = array_map('intval', explode(';', $emailSent->sent_to_user));
            if ($emailSent->subject != 'Требуется действие') {
                CollectionNewsLetter::create([
                    'collection_id' => $emailSent->collection_id,
                    'subject' => $emailSent->subject,
                    'text' => $emailSent->email_text,
                    'users' => json_encode($users)
                ]);
            }
            if (count($users) > 0) {
                foreach ($users as $user) {
                    EmailSent::create([
                        'user_id' => $user,
                        'subject' => $emailSent->subject,
                        'text' => $emailSent->email_text
                    ]);
                }
            }
        }
    }

    public function run($test = false): void
    {
        $this->make_collections($test);
        $this->make_statuses();
        $this->make_news_letters();
    }
}
