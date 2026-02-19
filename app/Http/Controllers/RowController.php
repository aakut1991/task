<?php

namespace App\Http\Controllers;

use App\Models\Row;
use App\Imports\RowsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class RowController extends Controller {
    public function upload(Request $request) {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:10240'
        ]);

        $id = uniqid();
        Excel::import(new RowsImport($id), $request->file('file'));

        return response()->json([
            'import_id' => $id, 
            'status' => 'Processing'
        ]);
    }

    public function index() {
        return Row::all()->groupBy('date');
    }
}