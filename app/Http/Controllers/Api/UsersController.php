<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return UserResource::collection($users);
    }

    public function getUserById($id)
    {
        $user = User::findOrFail($id);
        return  new UserResource($user);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = User::create($request->all());

        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return UserResource::collection($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $user->update($request->all());

        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(null, 204);
    }



    public function getjson()
    {
        $path = storage_path() . "/app/public/json.json";
        $json = json_decode(file_get_contents($path), true);

        return $json;
    }



    public  function userAuth(Request $request)
    {

        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);
        $credentials = $request->only('email', 'password');

        if($user = Auth::attempt($credentials)) {

            return response([
                'message'=>'you are logged in',
                "userData" => auth()->user()
            ], 200);
        }
        return response([
            'message'=>'Invalid Credentials'
        ], 401);
    }


    public function userRegister(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required'
        ]);


        $user = new User();
        $user->name = $request->post('name');
        $user->email = $request->post('email');
        $user->password = bcrypt($request->post('password'));
        if(   $user->save()){

            return response([
                'message' => $user
            ], 200);
        }
        return response([
            'message' => 'Registration failed'
        ], 401);


    }

}
