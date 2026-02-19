<?php

namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuarios extends Authenticatable
{
    protected $table = 'usuario';
    // protected $primaryKey = 'idalumno';
    public $timestamps=false;
}