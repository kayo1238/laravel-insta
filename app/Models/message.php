<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class message extends Model
{
    // create() 　や update()で一括代入するcolumn
    protected $fillable = [
        'send_id',
        'receiver_id',
        'body',
        'read_at',
    ];

    // read_atをcarbon(日付object)として扱う
    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    //  sender
    public function sender(): BelongsTo
    {
        return $this->belingsTo(User::class, 'sender_id');
    }

    // receiver
    public function receiver(): BelongsTo
    {
        return $this->belongsTo('User::class', 'receiver_id');
    }
}
