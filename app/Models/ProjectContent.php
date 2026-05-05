<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectContent extends Model
{
    protected $fillable = ['page_number', 'section_title', 'type', 'content', 'source', 'year_range'];

    protected $casts = [
        'content' => 'array',
    ];
}
