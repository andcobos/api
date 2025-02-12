<?php

namespace App\Http\Controllers;
use App\Http\Resources\UserResource; /* Importo la clase */

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Validation\Rule;


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

        //la funcion make mapea una isntaica, objeto, y cuando son varias usamos collection
        return UserResource::collection($users);
        
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

  
    //public function show($user):
    //{
    //    $user = User::where('id', $user)->firstOrFail();
    //    return $user;
    //}


    //Endpoint necesita una ruta, funcion show, al ponerle que es User $user nos devuelve lo de la BD
    //http://api.test/api/v1/users/1 usa un identficador para buscar un usuario. Le mando el id y aca al id lo trata como $user
    public function show(User $user)
    {
        return response()->json(UserResource::make ($user));
    }


    //Endpoint para acualizar un usuario
    public function update(StoreUserRequest $request, User $user)
    {
        $data = $request->validated();
        $user->update($data);

        return response()->json(UserResource::make($user));
    }


    //Endpoint para hacer actualizacion parcial
    public function partialUpdate(Request $request, User $user)
    {
        //Validaciones
        $validatedData = $request->validate([
            'username' => ['sometimes', 'string', Rule::unique('users')->ignore($user->id)],
            'email' => ['sometimes', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => ['sometimes', 'string', 'min:6'],
            'name' => ['sometimes', 'string', 'max:255'],
            'lastname' => ['sometimes', 'string', 'max:255'],
        ]);
    
        if ($request->has('password')) {
            $validatedData['password'] = bcrypt($validatedData['password']);
        }
    
        $user->update($validatedData);
    
        return response()->json(UserResource::make($user), 200);
    }



}

//PUT actualiza todo
//PATCH actualiza solo lo que le mandamos
