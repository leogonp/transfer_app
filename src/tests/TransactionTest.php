<?php

use App\Model\Person;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TransactionTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */


    public function testTransaction()
    {
        $arrayDataPayer = [
                    'name' => Str::random(10),
                    'email' => Str::random(10).'@gmail.com',
                    'password' => Hash::make('password'),
                    'cpf_cnpj' => sprintf('%05d', rand(0, 99999)).sprintf('%06d', rand(0, 999999)),
                    'shopkeeper' => 0,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
                    'wallet' => 100
                ];

        $payer = new Person($arrayDataPayer);
        $payer->save();
        $arrayDataPayee = [
                    'name' => Str::random(10),
                    'email' => Str::random(10).'@gmail.com',
                    'password' => Hash::make('password'),
                    'cpf_cnpj' => sprintf('%05d', rand(0, 99999)).sprintf('%06d', rand(0, 999999)),
                    'shopkeeper' => 0,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
                    'wallet' => 100
                ];
        $payee = new Person($arrayDataPayee);
        $payee->save();
        $arrayData = [
                    'value' => 80,
                    'payer' => $payer->id,
                    'payee' => $payee->id
                ];
        $this->post('/transaction', $arrayData,['api-token' => env('API_TOKEN')]);
        $this->assertResponseOk();
        $content = (array) json_decode($this->response->content());
        $this->assertArrayHasKey('transactionId',$content );

        $this->seeInDatabase("transactions",[
             'payer_id' => $payer["id"],
             'payee_id' => $payee["id"]
        ]);

        $payerDB = Person::find($payer->id);
        $payeeDB = Person::find($payee->id);
        $this->assertEquals($payer->wallet - $arrayData["value"], $payerDB->wallet);
        $this->assertEquals($payee->wallet + $arrayData["value"], $payeeDB->wallet);

        $this->delete("/transaction/".$content["transactionId"],['api-token' => env('API_TOKEN')]);
        $this->assertResponseOk();
        $payerDB->delete();
        $payeeDB->delete();

    }

    public function testTransactionPayerShopkeeper()
    {
        $arrayDataPayer = [
                    'name' => Str::random(10),
                    'email' => Str::random(10).'@gmail.com',
                    'password' => Hash::make('password'),
                    'cpf_cnpj' => sprintf('%05d', rand(0, 99999)).sprintf('%06d', rand(0, 999999)),
                    'shopkeeper' => 1,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
                    'wallet' => 100
                ];

        $payer = new Person($arrayDataPayer);
        $payer->save();
        $arrayDataPayee = [
                    'name' => Str::random(10),
                    'email' => Str::random(10).'@gmail.com',
                    'password' => Hash::make('password'),
                    'cpf_cnpj' => sprintf('%05d', rand(0, 99999)).sprintf('%06d', rand(0, 999999)),
                    'shopkeeper' => 0,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
                    'wallet' => 100
                ];
        $payee = new Person($arrayDataPayee);
        $payee->save();
        $arrayData = [
                    'value' => 80,
                    'payer' => $payer->id,
                    'payee' => $payee->id
                ];
        $this->post('/transaction', $arrayData,['api-token' => env('API_TOKEN')]);
        $this->response->assertStatus(400);
        $payer->delete();
        $payee->delete();

    }


    public function testTransactionInsufficientWallet()
    {
        $arrayDataPayer = [
                    'name' => Str::random(10),
                    'email' => Str::random(10).'@gmail.com',
                    'password' => Hash::make('password'),
                    'cpf_cnpj' => sprintf('%05d', rand(0, 99999)).sprintf('%06d', rand(0, 999999)),
                    'shopkeeper' => 0,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
                    'wallet' => 20
                ];

        $payer = new Person($arrayDataPayer);
        $payer->save();
        $arrayDataPayee = [
                    'name' => Str::random(10),
                    'email' => Str::random(10).'@gmail.com',
                    'password' => Hash::make('password'),
                    'cpf_cnpj' => sprintf('%05d', rand(0, 99999)).sprintf('%06d', rand(0, 999999)),
                    'shopkeeper' => 0,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
                    'wallet' => 100
                ];
        $payee = new Person($arrayDataPayee);
        $payee->save();
        $arrayData = [
                    'value' => 80,
                    'payer' => $payer->id,
                    'payee' => $payee->id
                ];
        $this->post('/transaction', $arrayData,['api-token' => env('API_TOKEN')]);
        $this->response->assertStatus(400);
        $payer->delete();
        $payee->delete();

    }

    public function testTransactionNonExistingPayer()
    {
        $arrayDataPayee = [
                    'name' => Str::random(10),
                    'email' => Str::random(10).'@gmail.com',
                    'password' => Hash::make('password'),
                    'cpf_cnpj' => sprintf('%05d', rand(0, 99999)).sprintf('%06d', rand(0, 999999)),
                    'shopkeeper' => 0,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
                    'wallet' => 100
                ];
        $payee = new Person($arrayDataPayee);
        $payee->save();
        $arrayData = [
                    'value' => 80,
                    'payer' => -1,
                    'payee' => $payee->id
                ];
        $this->post('/transaction', $arrayData,['api-token' => env('API_TOKEN')]);
        $this->response->assertStatus(404);
        $payee->delete();

    }
    public function testTransactionNonExistingPayee()
    {
        $arrayDataPayer = [
                    'name' => Str::random(10),
                    'email' => Str::random(10).'@gmail.com',
                    'password' => Hash::make('password'),
                    'cpf_cnpj' => sprintf('%05d', rand(0, 99999)).sprintf('%06d', rand(0, 999999)),
                    'shopkeeper' => 0,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
                    'wallet' => 100
                ];
        $payer = new Person($arrayDataPayer);
        $payer->save();
        $arrayData = [
                    'value' => 80,
                    'payee' => -1,
                    'payer' => $payer->id
                ];
        $this->post('/transaction', $arrayData,['api-token' => env('API_TOKEN')]);
        $this->response->assertStatus(404);
        $payer->delete();
    }



    public function testTransactionNonNumericValue()
    {
        $arrayDataPayer = [
                    'name' => Str::random(10),
                    'email' => Str::random(10).'@gmail.com',
                    'password' => Hash::make('password'),
                    'cpf_cnpj' => sprintf('%05d', rand(0, 99999)).sprintf('%06d', rand(0, 999999)),
                    'shopkeeper' => 0,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
                    'wallet' => 100
                ];

        $payer = new Person($arrayDataPayer);
        $payer->save();
        $arrayDataPayee = [
                    'name' => Str::random(10),
                    'email' => Str::random(10).'@gmail.com',
                    'password' => Hash::make('password'),
                    'cpf_cnpj' => sprintf('%05d', rand(0, 99999)).sprintf('%06d', rand(0, 999999)),
                    'shopkeeper' => 0,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
                    'wallet' => 100
                ];
        $payee = new Person($arrayDataPayee);
        $payee->save();
        $arrayData = [
                    'value' => "80a",
                    'payer' => $payer->id,
                    'payee' => $payee->id
                ];
        $this->post('/transaction', $arrayData,['api-token' => env('API_TOKEN')]);
        $this->response->assertStatus(422);
        $payer->delete();
        $payee->delete();

    }
}
