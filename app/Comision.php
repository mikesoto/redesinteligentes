<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Comision extends Authenticatable
{
    protected $table = 'comisiones';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'created_at',//will use the fecha_ingreso of the new user
        'user_id',//the user that this comission is assigned to
        'new_user_id',//the new user that was registered
        'upline_id',
        'patroc_id',
        'asignado_id',
        'type',
        'amount',
        'date_payed',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
}
