<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilModel extends Model
{
    use HasFactory;

    protected $table = 'profils'; // nama tabel baru
    protected $primaryKey = 'id'; // kalau kamu pakai id default
    public $timestamps = true; // aktifin kalau pakai created_at, updated_at

    protected $fillable = ['user_id', 'foto'];

    // Relasi ke UserModel
    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
    }
}
