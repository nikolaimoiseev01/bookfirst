<?php

namespace Database\Seeders;

use App\Enums\OwnBookCoverStatusEnums;
use App\Enums\OwnBookInsideStatusEnums;
use App\Enums\OwnBookStatusEnums;
use App\Models\OwnBook\OwnBook;
use App\Models\OwnBook\OwnBookWork;
use App\Models\Work\Work;
use App\Services\CopyTableService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OwnBookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function FC($str) {
        return mb_convert_case(mb_substr($str, 0, 1), MB_CASE_UPPER, "UTF-8").mb_convert_case(mb_substr($str, 1, mb_strlen($str) -1 ), MB_CASE_LOWER, "UTF-8");
    }

    public function make_own_books($test = false)
    {
        $oldOwnBooks = DB::connection('old_mysql')
            ->table('own_books')
            ->where('id', '>', $test ? 230 : 0)
            ->get();
        foreach ($oldOwnBooks as $oldOwnBook) {


            if ($oldOwnBook->amazon_link ?? null) {
                $selling_links = [
                    'platform' => 'amazon',
                    'link' => $oldOwnBook->amazon_link
                ];
            } else {
                $selling_links = null;
            }

            $status_general = optional(
                DB::connection('old_mysql')
                    ->table('own_book_statuses')
                    ->where('id', $oldOwnBook->own_book_status_id)
                    ->first()
            )->status_title ?? OwnBookStatusEnums::DONE->value;

            $status_cover = optional(DB::connection('old_mysql')
                ->table('own_book_cover_statuses')
                ->where('id', $oldOwnBook->own_book_cover_status_id)
                ->first())->status_title ?? OwnBookCoverStatusEnums::READY_FOR_PUBLICATION->value;
            $status_inside = optional(DB::connection('old_mysql')
                ->table('own_book_inside_statuses')
                ->where('id', $oldOwnBook->own_book_inside_status_id)
                ->first())->status_title ?? OwnBookInsideStatusEnums::READY_FOR_PUBLICATION->value;

            $own_book = OwnBook::create([
                'id' => $oldOwnBook->id,
                'user_id' => $oldOwnBook->user_id,
                'author' => $oldOwnBook->author,
                'title' => $oldOwnBook->title,
                'slug' => Str::slug($oldOwnBook->title),
                'status_general' => $this->FC($status_general),
                'status_cover' => $this->FC($status_cover),
                'status_inside' => $this->FC($status_inside),
                'deadline_inside' => $oldOwnBook->inside_deadline,
                'deadline_cover' => $oldOwnBook->cover_deadline,
                'deadline_print' => Carbon::parse($oldOwnBook->paid_at_print_only)->addDays(14),
                'pages' => $oldOwnBook->pages,
                'inside_type' => $oldOwnBook->inside_type,
                'comment' => $oldOwnBook->comment,
                'comment_author_cover' => $oldOwnBook->cover_comment,
                'internal_promo_type' => $oldOwnBook->promo_type,
                'price_text_design' => $oldOwnBook->text_design_price,
                'price_text_check' => $oldOwnBook->text_check_price,
                'price_inside' => $oldOwnBook->inside_price,
                'price_cover' => $oldOwnBook->cover_price,
                'price_promo' => $oldOwnBook->promo_price,
                'price_total' => $oldOwnBook->total_price - $oldOwnBook->print_price,
                'paid_at_without_print' => $oldOwnBook->paid_at_without_print,
                'paid_at_print_only' => $oldOwnBook->paid_at_print_only,
                'old_author_email' => $oldOwnBook->old_author_email,
                'annotation' => $oldOwnBook->own_book_desc,
                'selling_links' => $selling_links
            ]);
            $author_inside_files = DB::connection('old_mysql')
                ->table('own_book_files')
                ->where('own_book_id', $oldOwnBook->id)
                ->get();
            if (count($author_inside_files) > 0 && $author_inside_files && $oldOwnBook->id > 230) {
                foreach ($author_inside_files as $file) {
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
                    $own_book->addMediaFromUrl('https://pervajakniga.ru/' . $oldOwnBook->inside_file_cut)->toMediaCollection('inside_file_preview');
                }
            } catch (\Throwable $th) {
                continue;
            }

        }
    }


    public function make_own_book_works($test)
    {
        $works = Work::pluck('id')->unique()->values()->toArray();
        $own_books = OwnBook::pluck('id')->unique()->values()->toArray();
        $oldOwnBookWorks = DB::connection('old_mysql')
            ->table('own_books_works')
            ->whereIn('work_id', $works)
            ->whereIn('own_book_id', $own_books)
            ->get();
        foreach ($oldOwnBookWorks as $oldOwnBookWork) {
            OwnBookWork::create([
                'work_id' => $oldOwnBookWork->work_id,
                'own_book_id' => $oldOwnBookWork->own_book_id
            ]);
        }
    }

    public function run($test = false): void
    {
        $now_time = Carbon::now()->format('H:i:s');
        echo "Own Books START ($now_time)\n";
        $this->make_own_book_works($test);
        $this->make_own_books($test);
        (new CopyTableService())->copy(
            sourceTable: 'own_book_reviews'
            , targetTable: 'own_book_reviews'
        );
        $now_time = Carbon::now()->format('H:i:s');
        echo "Own Books END ($now_time)\n";
    }
}
