<?php

namespace App\Model;

use App\Utils\Messages;
use Illuminate\Auth\Authenticatable;
use Illuminate\Http\Response;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Person extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;
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

    static public function getPerson($id){
        try{
            $person = Person::query()->findOrFail($id);
        }catch(\Exception $e){
            abort(Response::HTTP_NOT_FOUND,Messages::USER_NOT_FOUND);
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
