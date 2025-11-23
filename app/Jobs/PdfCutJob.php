<?php

namespace App\Jobs;

use App\Services\PdfService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class PdfCutJob implements ShouldQueue
{
    use Queueable;

    public $model;
    public string $pdfPath;
    public int $pages;
    public string $collection;

    /**
     * Create a new job instance.
     */
    public function __construct($model, $pdfPath, $pages, $collection)
    {
        $this->model = $model;
        $this->pdfPath = $pdfPath;
        $this->pages = $pages;
        $this->collection = $collection;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        app(PdfService::class)
            ->cutAndAttach($this->model, $this->pdfPath, $this->pages, $this->collection);
    }
}
