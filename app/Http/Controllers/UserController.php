<?php

namespace App\Http\Controllers;
use App\Http\Resources\UserResource; /* Importo la clase */

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     
    /* Funcion para que devuelva la lista de los usuarios al hacer gewt en postman*/
    public function index()
    {
        
        //eloquent
        $users = User::when(request()->has('username'), function($query){
            $query->where('username', 'like', '%'.request()->input('username') .'%')->get();
            }
        )-> when(
            request()->has('email'), function($query){
                $query->where('email', 'like', '%'.request()->input('email') .'%')->get();
        })
        ->paginate(request()->per_page);

        //query builder
       // $users = DB::table("users")->get();

       //eloquent, para hacer busquedas especificas
       //$users = User::where('username', '=', 'jose') -> get();


        return UserResource::collection($users);
        //la funcion mak,e mapea una isntaica, objeto, y cuando son varias usamos collection
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Str::random(8); // Le colocamos una contraseña por defecto

        $user = User::create($data);
        
        return response()->json(UserResource::make($user), 201);
    }

    public function show(User $user)
    {
        return response()->json(UserResource::make($user),0);
    }
}
