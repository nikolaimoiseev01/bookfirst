<?php

namespace Database\Seeders;

use App\Models\Collection\Participation;
use App\Models\Collection\ParticipationStatus;
use App\Models\Promocode;
use App\Services\CopyTableService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParticipationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function make_participations()
    {
        $oldParticipations = DB::connection('old_mysql')->table('participations')->get();
        foreach ($oldParticipations as $participation) {

            if ($participation->nickname) {
                $author_name = $participation->nickname;
            } else {
                $author_name = $participation->name . ' ' . $participation->surname;
            }
            $promocode_id = Promocode::where('name', $participation->promocode)->first()['id'] ?? null;

            Participation::create([
                'collection_id' => $participation->collection_id,
                'user_id' => $participation->user_id,
                'author_name' => $author_name,
                'works_number' => $participation->works_number,
                'rows' => $participation->rows,
                'pages' => $participation->pages,
                'participation_status_id' => $participation->pat_status_id,
                'promocode_id' => $promocode_id,
                'price_part' => $participation->part_price,
                'price_print' => $participation->print_price,
                'price_check' => $participation->check_price,
                'price_send' => $participation->send_price,
                'price_total' => $participation->total_price
            ]);
        }
    }

    public function run($test = false): void
    {
        (new CopyTableService())->copy(sourceTable:'participation_works', targetTable: 'participation_works');
        (new CopyTableService())->copy( sourceTable: 'promocodes', targetTable: 'promocodes', columnsToRename: ['promocode' => 'name']);
        (new CopyTableService())->copy( sourceTable: 'pat_statuses', targetTable: 'participation_statuses', columnsToRename: ['pat_status_title' => 'name']);
        $this->make_participations();
    }
}
