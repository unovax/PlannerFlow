<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            if($request->has('search')){
                $warehouses = Warehouse::
                    where('code', 'like', '%'.$request->search.'%')
                    ->orWhere('name', 'like', '%'.$request->search.'%')
                    ->orWhere('address', 'like', '%'.$request->search.'%')
                    ->orWhere('city', 'like', '%'.$request->search.'%')
                    ->orWhere('state', 'like', '%'.$request->search.'%')
                    ->orWhere('country', 'like', '%'.$request->search.'%')
                    ->paginate($request->size_page);
                return response()->json([
                    'data' => $warehouses,
                    'message' => 'Warehouses retrieved',
                    'result' => 'success'
                ]);
            }
            $warehouses = Warehouse::paginate($request->size_page);
            return response()->json([
                'data' => $warehouses,
                'message' => 'Warehouses retrieved',
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
            $warehouse = Warehouse::create($request->all());
            $response = [
                'data' => $warehouse,
                'message' => 'Warehouse created',
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
            $warehouse = Warehouse::find($id);
            $response = [
                'data' => $warehouse,
                'message' => 'Warehouse retrieved',
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
            $warehouse = Warehouse::find($id);
            $warehouse->update($request->all());
            $response = [
                'data' => $warehouse,
                'message' => 'Warehouse updated',
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
            $warehouse = Warehouse::find($id);
            $warehouse->delete();
            $response = [
                'data' => $warehouse,
                'message' => 'Warehouse deleted',
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
