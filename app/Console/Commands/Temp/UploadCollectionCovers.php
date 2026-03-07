<?php

namespace App\Console\Commands\Temp;

use App\Models\Collection\Collection;
use Illuminate\Console\Command;

class UploadCollectionCovers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:upload-collection-covers';

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
        $collections = Collection::query()->with('media')->orderBy('id', 'desc')->get();

        foreach ($collections as $collection) {
            if (!$collection->getMedia('cover_front')->isNotEmpty()) {
                $path = public_path("fixed/temp/collection-{$collection->id}.png");
                if (file_exists($path)) {
                    $collection->addMedia($path)->preservingOriginal()->toMediaCollection('cover_front');
                }
            }
        }
    }
}
