<?php

namespace App\Models;

use App\Models\Soporte\Categoria;
use App\Models\Soporte\UsuariosSoporte;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laratrust\Traits\LaratrustUserTrait;
use PhpOffice\PhpSpreadsheet\Calculation\Category;
use PhpParser\Node\Stmt\Return_;
use App\Models\Soporte\Ticket;

class User extends Authenticatable
{
    use LaratrustUserTrait;
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'lastname',
        'image',
        'email',
        'password',
        'status',
        'last_login'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            $user->employee()->create();
        });

        /*  static::created(function ($user) {
            $user->contact()->create();
        });

        static::created(function ($user) {
            $user->vacation()->create();
        });*/
    }

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function contact()
    {
        return $this->hasOne(Contact::class);
    }

    public function vacation()
    {
        return $this->hasOne(Vacations::class, 'users_id');
    }

    public function vacationsAvailables()
    {
        return $this->hasMany(Vacations::class, 'users_id')->where('period', '<>', 3);
    }

    public function vacationsComplete()
    {
        return $this->hasMany(Vacations::class, 'users_id');
    }

    public function directory()
    {
        return $this->hasMany(Directory::class, 'user_id');
    }
    public function publications()
    {
        return $this->hasMany(Publications::class, 'user_id');
    }

    public function meGusta()
    {
        return $this->belongsToMany(Publications::class, 'likes', 'user_id', 'publication_id');
    }
    public function messages()
    {
        return $this->hasMany(Message::class, 'transmitter_id');
    }

    // Dias seleccionados que no estan ligados a un request
    public function daysSelected()
    {
        return $this->hasMany(RequestCalendar::class, 'users_id')->where('requests_id', null);
    }

    //Relacionar el usuario para traer los roles
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    public function asignacionCategoria()
    {
        return $this->belongsToMany(Categoria::class, 'soporte_usuarios_soporte', "users_id", 'categorias_id');
    }

    //para traer los tickets relacionados con el usuario
    public function tickets()
    {
        return $this->hasMany(Ticket::class,'user_id');
    }



    public function userDownMotive()
    {
        return $this->hasOne(UserDownMotive::class, 'user_id');

    }

    public function userDetails()
    {
        return $this->hasOne(UserDetails::class, 'user_id');

    }

}
