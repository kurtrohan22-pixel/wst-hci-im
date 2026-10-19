<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Portfolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'email',
        'phone',
        'address',
        'about',
        'profile_image',
        'education',
        'skills',
        'projects',
        'experience',
        'social_links',
        'template',
    ];

    protected function casts(): array
    {
        return [
            'education' => 'array',
            'skills' => 'array',
            'projects' => 'array',
            'experience' => 'array',
            'social_links' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Portfolio $portfolio) {
            $portfolio->uuid ??= (string) Str::uuid();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
