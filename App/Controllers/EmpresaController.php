<?php

namespace App\Controllers;

use App\Models\Category;
use Jenssegers\Blade\Blade;
use App\Models\Empresa;
use App\Models\User;

const TIPO_CATEGORY = 'empresas';

class EmpresaController
{

    private $blade;
    private $pol;
    private $urlA;
    private $urlB;

    public function __construct($pol)
    {
        $this->pol = $pol;
        $this->blade = new Blade([ RAIZ . 'App/Views'], RAIZ.'cache');
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

    private function baseEmpresa()
    {
        return Empresa::where([
            'pais' => PAIS,
            'url'  => $this->urlB
        ]);
    }

    public function getCategory()
    {
        return $this->baseQuery()
                    ->where('url', $this->urlA)
                    ->first();
    }

    public function getEmpresa()
    {
        return $this->baseEmpresa()->first();
    }


    public function indexCategorias()
    {
        $categories = $this->baseQuery()
                        ->orderBy('orden')
                        ->get();

        return $this->blade->make('empresas.index', [ 
            'categories' => $categories,
            'pols_empresa' => $this->pol['config']['pols_empresa']
            ]);
    }


    public function verCategoria()
    {
        $categoria = $this->getCategory();
        return $this->blade->make('empresas.categorias', ['categoria' => $categoria]);
    }


    public function verEmpresa()
    {
        $empresa = $this->getEmpresa();

        //Actualiza visita
        $empresa->increment('pv');

        return $this->blade->make('empresas.empresa', [
            'empresa' => $empresa, 
            'pol' => $this->pol
        ]);
    }

    public function crearEmpresa()
    {
        $categories = $this->baseQuery()
                    ->orderBy('orden')
                    ->get();

        return $this->blade->make('empresas.crear', [
            'categories' => $categories,
            'pols_empresa' => $this->pol['config']['pols_empresa']
        ]);
    }


}