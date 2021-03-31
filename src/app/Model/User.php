<?php

namespace App\Model;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
/**
     * @OA\Schema(@OA\Xml(name="User"), @OA\Parameter(ref="#/components/schemas/User"))
     */
class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    /**
     * Login
     * @var string
     *
     * @OA\Property(
     *   property="login",
     *   type="string",
     *   default= "login",
     *   description="O endereço de email do usuário no cadastrado no banco"
     * )
     */

    /**
     * Senha
     * @var string
     *
     * @OA\Property(
     *   property="password",
     *   type="string",
     *   default= "senha",
     *   description="A senha do usuário."
     * )
     */
    protected $fillable = [
        'name', 'email',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];
}
