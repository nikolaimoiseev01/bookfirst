<?php

namespace Database\Seeders;

use App\Models\OwnBook\OwnBook;
use App\Services\CopyTableService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OwnBookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function make_own_books($test = false)
    {
        $oldOwnBooks = DB::connection('old_mysql')->table('own_books')->get();
        $limit = $test ? 270 : 0;
        foreach ($oldOwnBooks as $oldOwnBook) {

            if ($oldOwnBook->id > $limit) {


                if ($oldOwnBook->amazon_link ?? null) {
                    $external_links = json_encode(['amazon' => $oldOwnBook->amazon_link]);
                } else {
                    $external_links = null;
                }

                $own_book = OwnBook::create([
                    'id' => $oldOwnBook->id,
                    'user_id' => $oldOwnBook->user_id,
                    'author' => $oldOwnBook->author,
                    'title' => $oldOwnBook->title,
                    'own_book_status_id' => $oldOwnBook->own_book_status_id,
                    'own_book_cover_status_id' => $oldOwnBook->own_book_cover_status_id,
                    'own_book_inside_status_id' => $oldOwnBook->own_book_inside_status_id,
                    'deadline_inside' => $oldOwnBook->inside_deadline,
                    'deadline_cover' => $oldOwnBook->cover_deadline,
                    'pages' => $oldOwnBook->pages,
                    'inside_type' => $oldOwnBook->inside_type,
                    'comment' => $oldOwnBook->comment,
                    'comment_author_cover' => $oldOwnBook->cover_comment,
                    'internal_promo_type' => $oldOwnBook->promo_type,
                    'price_text_design' => $oldOwnBook->text_design_price,
                    'price_text_check' => $oldOwnBook->text_check_price,
                    'price_cover' => $oldOwnBook->cover_price,
                    'price_print' => $oldOwnBook->print_price,
                    'price_promo' => $oldOwnBook->promo_price,
                    'price_total' => $oldOwnBook->total_price,
                    'paid_at_without_print' => $oldOwnBook->paid_at_without_print,
                    'paid_at_print_only' => $oldOwnBook->paid_at_print_only,
                    'old_author_email' => $oldOwnBook->old_author_email,
                    'annotation' => $oldOwnBook->own_book_desc,
                    'external_links' => $external_links
                ]);
                $author_inside_file = DB::connection('old_mysql')
                    ->table('own_book_files')
                    ->where('own_book_id', $oldOwnBook->id)
                    ->get();
                if (count($author_inside_file) > 0 && $author_inside_file) {
                    foreach ($author_inside_file as $file) {
                        if ($file->file_type == 'inside') {
                            $own_book->addMediaFromUrl('https://pervajakniga.ru/' . $file->file)->toMediaCollection('from_author_inside');
                        } elseif ($file->file_type == 'cover') {
                            $own_book->addMediaFromUrl('https://pervajakniga.ru/' . $file->file)->toMediaCollection('from_author_cover');
                        }
                    }
                }
                try {
                    if ($oldOwnBook->cover_2d) {
                        $own_book->addMediaFromUrl('https://pervajakniga.ru/' . $oldOwnBook->cover_2d)->toMediaCollection('cover_front');
                    }
                    if ($oldOwnBook->inside_file) {
                        $own_book->addMediaFromUrl('https://pervajakniga.ru/' . $oldOwnBook->inside_file)->toMediaCollection('inside_file');
                    }
                    if ($oldOwnBook->inside_file_cut) {
                        $own_book->addMediaFromUrl('https://pervajakniga.ru/' . $oldOwnBook->inside_file_cut)->toMediaCollection('inside_file_cut');
                    }
                } catch (\Throwable $th) {
                    continue;
                }
            }
        }

    }

    public function run($test = false): void
    {
        (new CopyTableService())->copy(
            sourceTable: 'own_book_statuses'
            , targetTable: 'own_book_statuses'
            , columnsToRename: ['status_title' => 'name']
        );
        (new CopyTableService())->copy(
            sourceTable: 'own_book_cover_statuses'
            , targetTable: 'own_book_cover_statuses'
            , columnsToRename: ['status_title' => 'name']
        );
        (new CopyTableService())->copy(
            sourceTable: 'own_book_inside_statuses'
            , targetTable: 'own_book_inside_statuses'
            , columnsToRename: ['status_title' => 'name']
        );
        (new CopyTableService())->copy(
            sourceTable: 'own_books_works'
            , targetTable: 'own_book_works'
        );
        (new CopyTableService())->copy(
            sourceTable: 'own_book_reviews'
            , targetTable: 'own_book_reviews'
        );
        $this->make_own_books($test);
    }
}
