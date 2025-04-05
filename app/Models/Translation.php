<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    use HasFactory;
    protected $fillable = ['group', 'key', 'text', 'tags'];

    protected $casts = [
        'text' => 'json',
        'tags' => 'json',
    ];

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('key', 'like', "%{$search}%")
                ->orWhere('group', 'like', "%{$search}%")
                ->orWhereJsonContains('tags', $search)
                ->orWhere('text', 'like', "%{$search}%");
        });
    }

    public function scopeByGroup($query, $group)
    {
        return $query->where('group', $group);
    }

    public function scopeByTag($query, $tag)
    {
        return $query->whereJsonContains('tags', $tag);
    }

    public function scopeByLocale($query, $locale)
    {
        return $query->whereNotNull("text->{$locale}");
    }
}
