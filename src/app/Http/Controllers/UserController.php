<?php

namespace App\Http\Controllers;

use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        //
    }

    /**
     * @OA\Post(
     *     path="/transfer_app/src/public/login",
     *     tags={"user"},
     *     summary="Efetua o login e retorna o token para uso da api",
     *     operationId="user",
     *     @OA\Response(
     *         response="200",
     *         description="Sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Login/Senha errados"
     *     ),
     *      @OA\RequestBody(
     *         description="Json para envio",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     )
     *
     * )
     */
    public function login(Request $request)
    {
        $this->validate($request, [
             'login'    => 'required',
             'password' => 'required'
        ]);
        return User::where('email', $request->input("login"))
                    ->where('password', md5($request->input("password")))
                    ->firstOrFail()->api_token;
    }
    //
}
