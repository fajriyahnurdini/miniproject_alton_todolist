<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todolist extends Model
{
    use HasFactory;

    // Nama tabel di database MySQL
   protected $table = 'todolists';

    // Kolom yang diizinkan untuk diisi data (Mass Assignment)
  protected $fillable = [
    'user_id',
    'title',
    'description',
    'is_completed',
    'due_date',
];
    // Mengubah due_date menjadi objek Carbon agar mudah diformat
    protected $casts = [
        'due_date' => 'datetime',
        'is_completed' => 'boolean',
    ];
}