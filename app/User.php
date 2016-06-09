<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'upline',
        'patrocinador',
        'asignado',
        'side',
        'fecha_ingreso',
        'nombre', 
        'apellido_paterno', 
        'apellido_materno',
        'fecha_nac',
        'ife',
        'tel_cel',
        'email',
        'user',
        'password',
        'cp',
        'direccion',
        'colonia',
        'delegacion',
        'estado',
        'beneficiario',
        'parentesco',
        'beneficiario_fecha_nac',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
