<?php

// app/Models/Task.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    // Izinkan kolom ini untuk diisi secara massal
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status',
        'due_date',
    ];

    // Relasi bahwa setiap tugas dimiliki oleh satu user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}