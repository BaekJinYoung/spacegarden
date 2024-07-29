<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function uploadFile (Request $request) {
        $request->validate(['image' => 'required']);

        $fileName = $request->file('image')->getClientOriginalName();
        $path = $request->file('image')->storeAs('public/images', $fileName);
        $url = asset(Storage::url($path));

        return response()->json(['url' => $url]);
    }
}
