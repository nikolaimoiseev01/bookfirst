<?php

namespace App\Models\Cdek;

use Illuminate\Database\Eloquent\Model;

class CdekOffice extends Model
{
    protected function casts(): array
    {
        return [
            'full_data' => 'array'
        ];
    }
}
