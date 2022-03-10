<?php

namespace wish\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $fillable = [
        'email',
        'password'
    ];


    public function removeAccount(){

        $this->delete();

    }

}
