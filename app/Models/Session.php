<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $table = 'sessions';

    protected $primaryKey = 'id';

    // Tipe primary key
    public $incrementing = false; // karena `id` pada tabel sessions biasanya string

    // Tipe data primary key
    protected $keyType = 'string';

    // Tentukan atribut mana yang boleh diisi secara massal
    protected $fillable = [
        'id', 'user_id', 'ip_address', 'user_agent', 'payload', 'last_activity'
    ];

    // Matikan pengaturan timestamps (created_at dan updated_at)
    public $timestamps = false;

    // Relasi ke model User (opsional)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}