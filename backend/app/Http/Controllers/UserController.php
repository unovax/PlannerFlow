<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $users = User::where('tenant_id', Auth::user()->tenant_id);
            if($request->has('search')){
                $users = User::
                    with('roles')
                    ->where('name', 'like', '%'.$request->search.'%')
                    ->paginate($request->size_page);
                return response()->json([
                    'data' => $users,
                    'message' => 'Users retrieved',
                    'result' => 'success'
                ]);
            }
            $users = $users::paginate($request->size_page);
            return response()->json([
                'data' => $users,
                'message' => 'Users retrieved',
                'result' => 'success'
            ]);
        } catch (\Throwable $th) {
            $response = [
                'data' => null,
                'message' => $th->getMessage(),
                'result' => 'error'
            ];
            return response()->json($response, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $request['password'] = bcrypt($request->password);

            $user = User::create(['tenant_id' => Auth::user()->tenant_id] + $request->all());
            foreach($request->roles as $role){
                $validRoles[] = $role['id'];
            }
            $user->asyncRoles($validRoles);
            $response = [
                'data' => $user->load('roles'),
                'message' => 'User created',
                'result' => 'success'
            ];
            return response()->json($response, 201);
        } catch (\Throwable $th) {
            $response = [
                'data' => null,
                'message' => $th->getMessage(),
                'result' => 'error'
            ];
            return response()->json($response, 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $user = User::find($id);
            $response = [
                'data' => $user,
                'message' => 'User retrieved',
                'result' => 'success'
            ];
            return response()->json($response);
        } catch (\Throwable $th) {
            $response = [
                'data' => null,
                'message' => $th->getMessage(),
                'result' => 'error'
            ];
            return response()->json($response, 500);
        }
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
        try {
            $user = User::find($id);
            $user->update($request->all());

            foreach($request->roles as $role){
                $validRoles[] = $role['id'];
            }
            $user->roles()->detach();
            $user->asyncRoles($validRoles);

            $response = [
                'data' => $user->load('roles'),
                'message' => 'User updated',
                'result' => 'success'
            ];
            return response()->json($response);
        } catch (\Throwable $th) {
            $response = [
                'data' => null,
                'message' => $th->getMessage(),
                'result' => 'error'
            ];
            return response()->json($response, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $user = User::find($id);
            $user->roles()->detach();
            $user->delete();
            $response = [
                'data' => $user,
                'message' => 'User deleted',
                'result' => 'success'
            ];
            return response()->json($response);
        } catch (\Throwable $th) {
            $response = [
                'data' => null,
                'message' => $th->getMessage(),
                'result' => 'error'
            ];
            return response()->json($response, 500);
        }
    }
}
