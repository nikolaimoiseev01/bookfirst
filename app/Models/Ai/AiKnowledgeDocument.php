<?php

namespace App\Models\Ai;

use Illuminate\Database\Eloquent\Model;

class AiKnowledgeDocument extends Model
{
    protected $fillable = ['title', 'category', 'content', 'route_name', 'route_parameters', 'is_active', 'is_generated'];

    protected function casts(): array
    {
        return ['route_parameters' => 'array', 'is_active' => 'boolean', 'is_generated' => 'boolean'];
    }
}
