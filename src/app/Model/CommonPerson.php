<?php

namespace App\Model;

class CommonPerson extends Person
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

    public function pay(Person $payee, $value){
        $this->wallet -=  $value;
        $payee->wallet +=  $value;
    }



}
