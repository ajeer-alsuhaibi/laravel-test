<?php

namespace App\Models\Sync;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyncRun extends Model
{
    use HasFactory;

    protected $fillable = [
        'source',
        'started_at',
        'finished_at',
        'status',
    ];

    public function syncItems()
    {
        return $this->hasMany(SyncRunItem::class);
    }
}
