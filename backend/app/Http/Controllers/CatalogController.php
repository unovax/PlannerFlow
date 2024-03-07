<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CatalogController extends Controller
{

    public function index(Request $request, $catalog)
    {
        try {
            $data = DB::table($catalog)->select($request->value, $request->name)->get();
            return response()->json($data);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function show(Request $request, $catalog, $id)
    {
        try {
            $data = DB::table($catalog)->where('id', $id)->first();
            return response()->json($data);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

}
