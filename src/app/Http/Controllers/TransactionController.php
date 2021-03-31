<?php

namespace App\Http\Controllers;

use App\Transaction;
use App\CommonPerson;
use App\ShopkeeperPerson;
use App\Person;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class TransactionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        //
    }
    public function view($id){
        $result= Transaction::find($id);
        return $result ? $result : new Response('Não existe o registro.',404);
    }
    public function delete($id){
        return Transaction::destroy($id)
                ? new Response('Removido com sucesso',200)
                : new Response('Falha ao remover',400);
    }
    /**
 * @OA\Swagger(
 *     schemes={"http"},
 *     host=API_HOST,
 *     basePath="/",
 *     @OA\Info(
 *         version="1.0.0",
 *         title="Documenteção Api",
 *         description="Interface de documentação e teste da api LunaProject",
 *         termsOfService="",
 *         @OA\Contact(
 *             email="admin@lunaproject.com"
 *         ),
 *     ),
 * )
 */

    /**
     * @OA\Post(
     *     path="/LunaProject/public/transaction",
     *     tags={"transaction"},
     *     summary="Realiza uma transferência monetária entre duas pessoas",
     *     operationId="transaction",
     *     @OA\Response(
     *         response="200",
     *         description="Sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="O pagador e/ou recebedor não existe no banco de dados."
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="O pagador não tem saldo suficiente ou o pagador passado é um lojista. Informação detalhada no body do response."
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Transação não autorizada ou falha na autenticação da api."
     *     ),
      *     @OA\Response(
     *         response=500,
     *         description="Erro interno da api. Entre em contato com o responsável."
     *     ),
     *     @OA\Parameter(
     *         name="api-token",
     *         in="header",
     *         description="Token de autenticação da api",
     *         required=true
     *     ),
     *      @OA\RequestBody(
     *         description="Json para envio",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Transaction")
     *     )
     *
     * )
     */
    public function transaction(Request $request){
        $this->validate($request, [
             'payer' => 'required|integer',
             'payee' => 'required|integer',
             'value' => 'required|numeric'
        ]);
        $transaction =  new Transaction(["payer_id" => $request->payer, "payee_id" => $request->payee,"value" => $request->value]);
        try{
            $transaction->run();
        }
        catch(ModelNotFoundException $e){
            return response('{"message":"'.$e->getMessage().'"}' ,404)->header('Content-Type', 'application/json');
        }
        catch(\Exception $e){
            if (method_exists($e, 'getStatusCode'))
                return response('{"message":"'.$e->getMessage().'"}' ,$e->getStatusCode() )->header('Content-Type', 'application/json');
            return response('{"message":"'.$e->getMessage().'"}' ,500 )->header('Content-Type', 'application/json');
        }
        return response('{"message":"Transação realizada com sucesso.","transactionId":'.$transaction->id.'}',200)->header('Content-Type', 'application/json');
    }


    //
}
