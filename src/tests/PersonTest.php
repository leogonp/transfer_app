<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PersonTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreatePerson()
    {
        $arrayData = [
            'name' => Str::random(10),
            'email' => Str::random(10).'@gmail.com',
            'password' => Hash::make('password'),
            'cpf_cnpj' => sprintf('%05d', rand(0, 99999)).sprintf('%06d', rand(0, 999999)),
            'shopkeeper' => 0,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
            'wallet' => 100
        ];

        $this->post('/person', $arrayData,['api-token' => env('API_TOKEN')]);
        $this->assertResponseOk();

        $content = (array) json_decode($this->response->content());

        $this->assertArrayHasKey('name',$content );
        $this->assertArrayHasKey('email',$content );
        $this->assertArrayHasKey('cpf_cnpj',$content );
        $this->assertArrayHasKey('shopkeeper',$content );
        $this->assertArrayHasKey('wallet',$content );
        $this->assertArrayHasKey('id',$content );

         $this->seeInDatabase("people",[
            'name'       => $arrayData['name'],
            'email'      => $arrayData['email'],
            'cpf_cnpj'   => $arrayData['cpf_cnpj'],
            'shopkeeper' => $arrayData['shopkeeper'],
            'wallet'     => $arrayData['wallet']
        ]);
    }

    public function testViewPerson()
    {
        $person=DB::table('people')->orderBy('id')->first();
        $this->get("/person/".$person->id,['api-token' => env('API_TOKEN')]);
        $this->assertResponseOk();
        $content = (array) json_decode($this->response->content());
        $this->assertArrayHasKey('name',$content );
        $this->assertArrayHasKey('email',$content );
        $this->assertArrayHasKey('cpf_cnpj',$content );
        $this->assertArrayHasKey('shopkeeper',$content );
        $this->assertArrayHasKey('wallet',$content );
        $this->assertArrayHasKey('id',$content );
    }

     public function testAllPeople()
     {
        $this->get("/people",['api-token' => env('API_TOKEN')]);
        $this->assertResponseOk();
        $this->seeJsonStructure([
            '*' => [
                'id',
                'name',
                'email',
                'cpf_cnpj',
                'shopkeeper',
                'wallet',
            ]

        ]);
    }

    public function testUpdatePerson()
    {
        $data = [
            'name' => Str::random(10),
            'email' => Str::random(10).'TESTEUPDATE@gmail.com'
        ];
        $person=DB::table('people')->orderBy('id','DESC')->first();
        $this->put("/person/".$person->id,$data,['api-token' => env('API_TOKEN')]);
        $this->assertResponseOk();
        $content = (array) json_decode($this->response->content());
        $this->assertArrayHasKey('id',$content );
        $this->assertArrayHasKey('name',$content );
        $this->assertArrayHasKey('email',$content );

       $this->seeInDatabase("people",[
            'name' => $data['name'],
            'email' => $data['email']
        ]);

    }
}
