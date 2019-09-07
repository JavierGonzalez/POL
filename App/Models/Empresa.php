<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Empresa extends Eloquent
{
    protected $primaryKey = 'ID';
    public $timestamps = false;

    public function categoria()
    {
        return $this->belongsTo(Category::class, 'cat_ID')->where('pais', PAIS);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_ID');
    }

}