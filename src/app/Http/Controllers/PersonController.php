<?php

namespace App\Http\Controllers;

use App\Model\Person;
use App\Utils\Messages;
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

    public function store(Request $request)
    {
        $person = new Person($request->all());
        return $person->save() ? $person : new Response(Messages::INSERT_ERROR,Response::HTTP_BAD_REQUEST);
    }

    public function view(int $id)
    {
        $result= Person::find($id);
        return $result ? $result : new Response(Messages::NOT_FOUND_REGISTER,Response::HTTP_NOT_FOUND);
    }

    public function list(){
        return Person::all();
    }

    public function update(Request $request, int $id)
    {
        $person = Person::find($id);
        if($person){
            $person->name       = $request->input("name");
            $person->email      = $request->input("email");
            return $person->update() ? $person : new Response(Messages::UPDATE_ERROR,Response::HTTP_BAD_REQUEST);

        }
        return new Response(Messages::NOT_FOUND_REGISTER,Response::HTTP_NOT_FOUND);

    }
    public function delete(int $id)
    {
        return Person::destroy($id)
            ? new Response(Messages::DELETE_SUCCESSFUL,Response::HTTP_OK)
            : new Response(Messages::DELETE_FAILURE,Response::HTTP_BAD_REQUEST);
    }

    //
}
