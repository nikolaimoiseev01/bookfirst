<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Spatie\Browsershot\Browsershot;

class CollectionCoverPngService
{
    public function make($collection): string
    {
        $html = view('filament.components.filament-book-3d', [
            'cover' => $collection->getFirstMediaUrl('cover_front'),
        ])->render();

        $directory = storage_path('app/tmp/collections');

        File::ensureDirectoryExists($directory);

        $path = $directory . '/collection-' . $collection->id . '-cover-3d.png';

        Browsershot::html($html)
            ->select('#book')
            ->deviceScaleFactor(2)
            ->save($path);

        return $path;
    }
}
