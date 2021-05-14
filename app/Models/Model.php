<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model as EloquentModel;

class Model extends EloquentModel
{

    //Show as less data as possible
    protected $visible = ['id', 'created_at'];

}
