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
        $collections = Collection::query()->where('id', '<', 78)->with('media')->orderBy('id', 'desc')->get();
        $counter = 0;
        foreach ($collections as $collection) {
            $path = public_path("fixed/temp/collection-{$collection->id}.png");
            if (file_exists($path)) {
                $path = asset("fixed/temp/collection-{$collection->id}.png");
                $collection->clearMediaCollection('cover_front');
                $collection->addMediaFromUrl($path)->toMediaCollection('cover_front');
                $counter++;
            }
        }
        dd('EDITED: ' . $counter);
    }
}
