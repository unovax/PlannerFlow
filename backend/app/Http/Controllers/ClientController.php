<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
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
                $clients = Client::with('currency')
                    ->where('code', 'like', '%'.$request->search.'%')
                    ->orWhere('name', 'like', '%'.$request->search.'%')
                    ->orWhere('rfc', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%')
                    ->orWhere('phone', 'like', '%'.$request->search.'%')
                    ->orWhere('country', 'like', '%'.$request->search.'%')
                    ->orWhere('state', 'like', '%'.$request->search.'%')
                    ->orWhere('city', 'like', '%'.$request->search.'%')
                    ->orWhere('address', 'like', '%'.$request->search.'%')
                    ->orWhereHas('currency', function($query) use ($request){
                        $query->where('name', 'like', '%'.$request->search.'%');
                    })
                    ->paginate($request->size_page);
                return response()->json([
                    'data' => $clients,
                    'message' => 'Clients retrieved',
                    'result' => 'success'
                ]);
            }
            $clients = Client::paginate($request->size_page);
            return response()->json([
                'data' => $clients,
                'message' => 'Clients retrieved',
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
            $client = Client::create($request->all());
            $response = [
                'data' => $client->load('currency'),
                'message' => 'Client created',
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
            $client = Client::with('currency')->find($id);
            $response = [
                'data' => $client,
                'message' => 'Client retrieved',
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
            $client = Client::find($id);
            $client->update($request->all());
            $response = [
                'data' => $client->load('currency'),
                'message' => 'Client updated',
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
            $client = Client::find($id);
            $client->delete();
            $response = [
                'data' => $client,
                'message' => 'Client deleted',
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
