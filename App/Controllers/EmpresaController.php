<?php

namespace App\Controllers;

use App\Models\Category;
use Jenssegers\Blade\Blade;

const TIPO_CATEGORY = 'empresas';

class EmpresaController
{

    private $pol;

    public function __construct($pol)
    {
        $this->pol = $pol;   
    }


    public function index()
    {
        $categories = Category::where([
            'tipo' => TIPO_CATEGORY,
            'pais' => PAIS
            ])->orderBy('orden')->get();        

        $blade = new Blade([ RAIZ . 'App/Views'], RAIZ.'cache');
        return $blade->make('empresas.index', [ 
            'categories' => $categories,
            'pols_empresa' => $this->pol['config']['pols_empresa']
            ]);
    }


}