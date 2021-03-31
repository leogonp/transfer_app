<?php

namespace App\Model;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Person extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;


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
    /**
     * @var mixed
     */
    public $wallet;


    static public function getPerson($id){
        try{
            $person = Person::findOrFail($id);
        }catch(Exception $e){
            abort(404,"Usuário não encontrado");
        }
        if ( isset( $person->shopkeeper ) && $person->shopkeeper ){
            $person = ShopkeeperPerson::findOrFail($id);

        }
        else{
            $person = CommonPerson::findOrFail($id);
        }
        return $person;

    }

}
