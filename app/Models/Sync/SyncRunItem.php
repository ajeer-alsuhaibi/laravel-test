<?php

namespace App\Models\Sync;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyncRunItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sync_run_id',
        'sku',
        'status',
        'error_message',
    ];

    public function syncRun()
    {
        return $this->belongsTo(SyncRun::class);
    }
}
