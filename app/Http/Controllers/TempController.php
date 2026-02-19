<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Schema;

class TempController extends Controller
{
    public function getNegaraColumns()
    {
        $columns = Schema::getColumnListing('negara');
        return response()->json($columns);
    }
}
