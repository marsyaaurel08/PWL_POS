<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilModel extends Model
{
    use HasFactory;

    protected $table = 'profils'; 
    protected $primaryKey = 'id'; 
    public $timestamps = true; 

    protected $fillable = ['user_id', 'foto'];

    // Relasi ke UserModel
    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
    }
}
