<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class User extends Eloquent
{

    protected $table = 'users';
    protected $primaryKey = 'ID';

    public $timestamps = false;

    public function empresas()
    {
        return $this->hasMany(Empresa::class, 'user_ID', 'ID')->where('pais', PAIS);
    }

}