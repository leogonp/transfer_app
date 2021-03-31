<?php

namespace App\Model;

use Exception;
use Illuminate\Auth\Authenticatable;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Support\Facades\Queue;
use App\Jobs\NotificationJob;
use App\Utils\Messages;


    /**
     * @OA\Schema(@OA\Xml(name="Transaction"), @OA\Parameter(ref="#/components/schemas/Transaction"))
     */
class Transaction extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;
    private const AUTHORIZER_URL = "https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6";
    private const URL_NOTIFICATION = "https://run.mocky.io/v3/b19f7b9f-9cbf-4fc6-ad22-dc30601aec04";

    protected $fillable = [
        'payer_id','payee_id','value'
    ];

    /**
     * Payer id
     * @var integer
     *
     * @OA\Property(
     *   property="payer",
     *   type="integer",
     *   default= 1,
     *   description="O id da pessoa que vai realizar o pagamento"
     * )
     */

    /**
     * Payee id
     * @var integer
     *
     * @OA\Property(
     *   property="payee",
     *   type="integer",
     *   default= 2,
     *   description="O id da pessoa que vai receber o pagamento"
     * )
     */

    /**
     * Value
     * @var integer
     *
     * @OA\Property(
     *   property="value",
     *   type="number",
     *   default= 100.50,
     *   description="O valor a ser pago"
     * )
     */

    public function run(): void
    {
        $payer = Person::getPerson($this->payer_id);
        $payee = Person::getPerson($this->payee_id);
        if ($payer instanceof ShopkeeperPerson) {
        	abort(Response::HTTP_BAD_REQUEST,Messages::PAYER_MUST_NOT_BE_VENDOR);
        }
        $this->transfer($payer,$payee);
        Queue::push((new NotificationJob(Transaction::URL_NOTIFICATION)));

    }

    private function transfer(CommonPerson $payer, Person $payee): void
    {
    	if ($payer->wallet < $this->value) {
            abort(Response::HTTP_BAD_REQUEST, Messages::INSUFFICIENT_BALANCE);
        }

    	$response = Http::get(Transaction::AUTHORIZER_URL);

    	if ($response->failed()) {
            $this->save();
            abort($response->status(), $response->body());
        }

        $this->processPayment($payer, $payee);
    }

    private function processPayment(CommonPerson $payer, Person $payee): void
    {
        $payer->pay($payee, $this->value);
        $this->authorized = true;
        $payer->update();
        $payee->update();
        $this->save();
    }

}


