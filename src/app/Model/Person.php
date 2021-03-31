<?php

namespace App\Model;

use App\Utils\Messages;
use Illuminate\Auth\Authenticatable;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
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

    static public function getPerson(int $id): Person
    {
        try{
            $shopkeeper = DB::table('people')->find($id)->shopkeeper;
        }catch(\Exception $e){
            abort(Response::HTTP_NOT_FOUND,Messages::USER_NOT_FOUND);
        }
        return $shopkeeper ? ShopkeeperPerson::findOrFail($id) :  CommonPerson::findOrFail($id);

    }

}
