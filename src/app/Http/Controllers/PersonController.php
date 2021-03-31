<?php

namespace App\Http\Controllers;

use App\Model\Person;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PersonController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        //
    }

    public function store(Request $request){
        $person = new Person($request->all());
        return $person->save() ? $person : new Response('Erro ao inserir.',400);
    }

    public function view($id){
        $result= Person::find($id);
        return $result ? $result : new Response('Não existe o registro.',404);
    }

    public function list(){
        return Person::all();
    }

    public function update(Request $request,$id){
        $person = Person::find($id);
        if($person){
            $person->name       = $request->input("name");
            $person->email      = $request->input("email");

            if($person->update()){
                return $person;
            }
            return new Response('Erro ao atualizar.',400);
        }
        return new Response('Não existe o registro.',404);

    }
    public function delete($id){
        return Person::destroy($id)
            ? new Response('Removido com sucesso',200)
            : new Response('Falha ao remover',400);
    }

    //
}
