<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ResultsController extends Controller
{
    
    public function webSearch(Request $request){

        $validated = $request->validate([
            'name' => 'required|alpha_num|max:100',
            'gender' => 'required',
            'status' => 'required'
        ]);

        $name_filter = '?name='.$request->name;
        $status_filter = '&status='.$request->status;
        $gender_filter = '&gender='.$request->gender;

        $url  = config('app.rick_morty_api_url').'/character/'.$name_filter.$status_filter.$gender_filter;
        $data   = Http ::get($url);
        $results = json_decode($data, true);
        return view('welcome')->with('results', $results);

    }

}
