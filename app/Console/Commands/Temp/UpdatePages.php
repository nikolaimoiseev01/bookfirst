<?php

namespace App\Console\Commands\Temp;

use App\Enums\CollectionStatusEnums;
use App\Enums\OwnBookStatusEnums;
use App\Models\Collection\Collection;
use App\Models\OwnBook\OwnBook;
use App\Services\PdfService;
use Illuminate\Console\Command;

class UpdatePages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-pages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pdfService = new PdfService();

        $success = 0;
        $errors = 0;

        $collections = Collection::query()
            ->where('status', CollectionStatusEnums::DONE)
            ->get();

        foreach ($collections as $collection) {

            $insideFilePath = $collection->getFirstMediaPath('inside_file');

            if (!$insideFilePath) {
                continue;
            }

            try {
                $collection->update([
                    'pages' => $pdfService->getPageCount($insideFilePath),
                ]);

                $success++;

            } catch (\Throwable $e) {

                $errors++;
                $this->error("Collection {$collection->id}: {$e->getMessage()}");
            }
        }

        $ownBooks = OwnBook::query()
            ->where('status_general', OwnBookStatusEnums::DONE)
            ->get();

        foreach ($ownBooks as $ownBook) {

            $insideFilePath = $ownBook->getFirstMediaPath('inside_file');

            if (!$insideFilePath) {
                continue;
            }

            try {
                $ownBook->update([
                    'pages' => $pdfService->getPageCount($insideFilePath),
                ]);

                $success++;

            } catch (\Throwable $e) {

                $errors++;
                $this->error("OwnBook {$ownBook->id}: {$e->getMessage()}");
            }
        }

        $this->info("Done.");
        $this->info("Successful: {$success}");
        $this->info("Errors: {$errors}");
    }
}
