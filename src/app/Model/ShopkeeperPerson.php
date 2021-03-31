<?php

namespace App\Model;

class ShopkeeperPerson extends Person
{
    protected $table = 'people';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','cpf_cnpj','shopkeeper', 'email','wallet','password'
    ];
    protected $hidden = [
        'password'
    ];


}
