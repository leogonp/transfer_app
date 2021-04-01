<?php

namespace App\Http\Controllers;

use App\Model\Transaction;
use App\Utils\Messages;
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
    public function __construct()
    {
        //
    }

    public function view(int $id): Response
    {
        $result = Transaction::find($id);
        return $result
            ? new Response($result, Response::HTTP_OK)
            : new Response(Messages::NOT_FOUND_REGISTER, Response::HTTP_NOT_FOUND);
    }

    public function delete(int $id): Response
    {
        return Transaction::destroy($id)
            ? new Response(Messages::DELETE_SUCCESSFUL, Response::HTTP_OK)
            : new Response(Messages::DELETE_FAILURE, Response::HTTP_BAD_REQUEST);
    }
    /**
     * @OA\Swagger(
     *     schemes={"http"},
     *     host=localhost:30001,
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
     *     path="/transaction",
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
     * @param Request $request
     * @return Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function transaction(Request $request)
    {
        $this->getValidation($request);
        $transaction = new Transaction(["payer_id" => $request->input('payer'), "payee_id" => $request->input('payee'), "value" => $request->input('value')]);
        try {
            $transaction->run();
        } catch (ModelNotFoundException $e) {
            return response('{"message":"' . $e->getMessage() . '"}', Response::HTTP_NOT_FOUND)->header('Content-Type', 'application/json');
        } catch (\Exception $e) {
            if (method_exists($e, 'getStatusCode'))
                return response('{"message":"' . $e->getMessage() . '"}', $e->getStatusCode())->header('Content-Type', 'application/json');
            return response('{"message":"' . $e->getMessage() . '"}', Response::HTTP_BAD_REQUEST)->header('Content-Type', 'application/json');
        }
        return response('{"message":"' . Messages::SUCCESSFUL_TRANSACTION . '","transactionId":' . $transaction->id . '}', 200)->header('Content-Type', 'application/json');
    }

    private function getValidation(Request $request): void
    {
        $this->validate($request, [
            'payer' => 'required|integer',
            'payee' => 'required|integer',
            'value' => 'required|numeric'
        ]);
    }
}
