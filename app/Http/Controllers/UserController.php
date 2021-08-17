<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::all();
        return response()->json(['data' => $users],200);

    }
    public function register(Request $request) {
        $data = $request->all();
        $apiToken = Str::random(80);
        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' =>$data['password'],
            'api_token' => $apiToken
        ]);
        return response()->json(['status' => true,'message' => 'User registered successfully!'],201);
    }
    public function login(Request $request) {
        $data = $request->all();
        $apiToken = Str::random(80);
        if($data) {
           $user = User::where('email',$data['email'])->first();
           if($user) {
               if($user->password == $data['password']) {
                   User::where('email',$data['email'])->update(['api_token' => $apiToken]);
                   return response()->json(['status' => true,'message' => 'Login successfully!','api_token' => $apiToken],200);
               }
               return response()->json(['status' => false,'message' => 'Wrong password!'],422);
           }
            return response()->json(['status' => false,'message' => 'Wrong email!'],422);

        }
    }
    public function logout(Request $request) {
        $apiToken = $request->header('Authorization');
        if (empty($apiToken)) {
            return response()->json(['status' => false,'message' => 'Missing Authorization header!'],422);
        }
        $apiToken = str_replace('Bearer ', '', $apiToken);
        $user = User::where('api_token', $apiToken)->update(['api_token' => NULL]);
        if ($user) {
            return response()->json(['status' => true,'message' => 'Logout successful!']);
        }
        return response()->json(['status' => false,'message' => 'Missing Authorization header!'],422);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' =>'required',
            'email' =>'required',
            'password' =>'required',
        ]);
        $user = User::create($request->all());
        return response()->json(['message'=>'them thanh cong'],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new UserResource(User::findOrFail($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->all());
        return new UserResource(User::findOrFail($id));
//        return response()->json(['message'=>'sua thanh cong'],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return new UserResource(User::all());
    }
}
