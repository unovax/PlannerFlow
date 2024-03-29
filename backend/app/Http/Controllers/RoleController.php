<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $roles = Role::where('tenant_id', Auth::user()->tenant_id);
            if($request->has('search')){
                $roles = Role::
                    with('permissions')
                    ->where('code', 'like', '%'.$request->search.'%')
                    ->orWhere('name', 'like', '%'.$request->search.'%')
                    ->orWhere('description', 'like', '%'.$request->search.'%')
                    ->paginate($request->size_page);
                return response()->json([
                    'data' => $roles,
                    'message' => 'Roles retrieved',
                    'result' => 'success'
                ]);
            }
            $roles = $roles->paginate($request->size_page);
            return response()->json([
                'data' => $roles,
                'message' => 'Roles retrieved',
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
            $role = Role::create($request->all());
            foreach($request->permissions as $permission){
                $validPermissions[] = $permission['id'];
            }
            $role->asyncPermissions($validPermissions);
            $response = [
                'data' => $role->load('permissions'),
                'message' => 'Role created',
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
            $role = Role::find($id);
            $response = [
                'data' => $role,
                'message' => 'Role retrieved',
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
            $role = Role::find($id);
            $role->update($request->all());

            foreach($request->permissions as $permission){
                $validPermissions[] = $permission['id'];
            }
            $role->permissions()->detach();
            $role->asyncPermissions($validPermissions);

            $response = [
                'data' => $role->load('permissions'),
                'message' => 'Role updated',
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
            $role = Role::find($id);
            $role->permissions()->detach();
            $role->delete();
            $response = [
                'data' => $role,
                'message' => 'Role deleted',
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
