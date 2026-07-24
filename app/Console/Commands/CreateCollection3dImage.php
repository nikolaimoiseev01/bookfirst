<?php

namespace App\Console\Commands;

use App\Models\Collection\Collection;
use App\Services\CollectionCoverPngService;
use Illuminate\Console\Command;

class CreateCollection3dImage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-collection3d-image {collection_id}';

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
        $collectionId = $this->argument('collection_id');
        $collection = Collection::where('id', $collectionId)->with('media')->first();

        if ($collection->hasMedia('cover_3d')) {
            $this->warn("Collection {$collectionId} already has a 3D cover image.");
            return 0;
        }

        $path = app(CollectionCoverPngService::class)->make($collection);

        $collection
            ->addMedia($path)
            ->toMediaCollection('cover_3d');

        $this->info("3D cover image generated successfully for collection {$collectionId}");

        return 0;
    }
}
