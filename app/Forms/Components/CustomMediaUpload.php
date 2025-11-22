<?php
namespace App\Forms\Components;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload as BaseUpload;

class CustomMediaUpload extends BaseUpload
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->getUploadedFileUsing(static function (self $component, string $file): ?array {
            $media = $component->getRecord()?->getRelationValue('media')->firstWhere('uuid', $file);
            $url = $media?->getUrl();

            return [
                'name' => $media?->getAttributeValue('file_name'),
                'size' => $media?->getAttributeValue('size'),
                'type' => $media?->getAttributeValue('mime_type'),
                'url' => $url,
            ];
        });
    }
}
