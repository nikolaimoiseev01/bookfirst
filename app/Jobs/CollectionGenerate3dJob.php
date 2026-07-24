<?php

namespace App\Jobs;

use App\Models\Collection\Collection;
use App\Services\CollectionCoverPngService;
use App\Services\PdfService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class CollectionGenerate3dJob implements ShouldQueue
{
    use Queueable;

    public $collectionId;

    /**
     * Create a new job instance.
     */
    public function __construct($collectionId)
    {
        $this->collectionId = $collectionId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $collection = Collection::where('id', $this->collectionId)->with('media')->first();
        $path = app(CollectionCoverPngService::class)->make($collection);

        $collection
            ->addMedia($path)
            ->toMediaCollection('cover_3d');
    }
}
