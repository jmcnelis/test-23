<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ResultsController extends Controller
{
    
    public function webSearch(Request $request){

        $request->validate([
            'name' => 'required|max:100',
            'gender' => 'required',
            'status' => 'required'
        ]);

        $name_filter = '?name='.$request->name;
        $status_filter = '&status='.$request->status;
        $gender_filter = '&gender='.$request->gender;

        $url  = config('app.rick_morty_api_url').'/character/'.$name_filter.$status_filter.$gender_filter;
        $data   = Http ::get($url);
        $results = json_decode($data, true);


        if (array_key_exists('error', $results)) {
            return view('welcome')->with('results', $results);
        } else {
            $results_col = collect($results['results']);
            $paginated_results = $this->paginate($results_col, 2, null, ['path' => 'search/']);
        }

        return view('welcome')->with('results', $paginated_results);

    }


    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

}
