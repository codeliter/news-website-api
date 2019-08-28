<?php

namespace App\Models;

class Users extends BaseModel
{
    protected $fillable = ['name', 'username', 'password'];

    protected $hidden = ['password'];
}