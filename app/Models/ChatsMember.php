<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatsMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'member_type',
        'member_id',
    ];

    /**
     * @return BelongsTo
     */
    public function member(): BelongsTo
    {
        return $this->morphTo();
    }
}
