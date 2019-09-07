<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Category extends Eloquent
{

    protected $table = 'cat';

    public function empresas()
    {
        return $this->hasMany(Empresa::class, 'cat_ID', 'ID')->where('pais', PAIS);
    }

    public function count_visitas()
    {
        $countVisitas = 0;
        foreach ($this->empresas as $empresa) {
            $countVisitas += $empresa->pv;
        }
        return $countVisitas;
    }

}