<?php

namespace App\Controllers;

use App\Models\Category;
use Jenssegers\Blade\Blade;

const TIPO_CATEGORY = 'empresas';

class EmpresaController
{

    private $pol;
    private $urlA;
    private $urlB;

    public function __construct($pol)
    {
        $this->pol = $pol;   
    }

    public function setUrls($urlA, $urlB)
    {
        $this->urlA = $urlA;
        $this->urlB = $urlB;
    }

    private function baseQuery()
    {
        return Category::where([
            'tipo' => TIPO_CATEGORY,
            'pais' => PAIS
        ]);
    }

    public function getCategory()
    {
        return $this->baseQuery()
                    ->where('url', $this->urlA)
                    ->first();
    }


    public function indexCategorias()
    {
        $categories = $this->baseQuery()
                        ->orderBy('orden')
                        ->get();

        $blade = new Blade([ RAIZ . 'App/Views'], RAIZ.'cache');
        return $blade->make('empresas.index', [ 
            'categories' => $categories,
            'pols_empresa' => $this->pol['config']['pols_empresa']
            ]);
    }


    public function verCategoria()
    {
        $categoria = $this->getCategory();

        $blade = new Blade([ RAIZ . 'App/Views'], RAIZ.'cache');
        return $blade->make('empresas.categorias', ['categoria' => $categoria]);
    }


}