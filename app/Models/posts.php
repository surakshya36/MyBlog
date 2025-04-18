<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class posts extends Model
{
    use HasFactory;
    protected $table = 'posts';
    protected $fillable = [
        'name',
        'description',
        'created_by',
        'status',
    ];
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
