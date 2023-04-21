<?php

namespace App\Models\Soporte;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    public $table = "soporte_tickets";
    protected $fillable  = [
        'name',
        'category_id',
        'create',
        'data',
        'user_id',
        'status_id'
    ];

     public function category()
     {
         return $this->belongsTo(Categoria::class,'category_id');
     }
    
    public function status()
    {
        return $this->belongsTo(Status::class,'status_id');
    }
    // public function historial()
    // {
    //     return $this->hasMany(Historial::class);
    // }
     public function mensajes()
     {
         return $this->hasMany(Mensaje::class,'ticket_id');
     }

    public function solution()
    {
        return $this->hasMany(Solucion::class,'ticket_id');
    }

   
}
