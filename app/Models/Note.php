<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder; // Opravený import pre Eloquent Builder

class Note extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'notes';
    protected $fillable = ['user_id', 'title', 'body', 'status', 'is_pinned'];
    protected $casts = ['is_pinned' => 'boolean'];

    // --- Vlastné metódy pre logiku (Slajd 32) ---

    public function publish(): bool {
        $this->status = 'published';
        return $this->save();
    }

    public function archive(): bool {
        $this->status = 'archived';
        return $this->save();
    }

    public function pin(): bool {
        $this->is_pinned = true;
        return $this->save();
    }

    public function unpin(): bool {
        $this->is_pinned = false;
        return $this->save();
    }

    public function togglePin(): bool {
        $this->is_pinned = !$this->is_pinned;
        return $this->save();
    }

    // --- Vyhľadávanie (Slajd 25) ---

    public static function searchPublished(string $q, int $limit = 20) {
        $q = trim($q);

        return static::query()
            ->where('status', 'published')
            ->where(function (Builder $query) use ($q) {
                $query->where('title', 'like', "%{$q}%")
                    ->orWhere('body', 'like', "%{$q}%");
            })
            ->orderByDesc('updated_at')
            ->limit($limit)
            ->get();
    }
}
