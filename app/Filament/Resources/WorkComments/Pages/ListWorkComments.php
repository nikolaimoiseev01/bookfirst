<?php

namespace App\Filament\Resources\WorkComments\Pages;

use App\Filament\Resources\WorkComments\WorkCommentResource;
use Filament\Resources\Pages\ListRecords;

class ListWorkComments extends ListRecords
{
    protected static string $resource = WorkCommentResource::class;
}
