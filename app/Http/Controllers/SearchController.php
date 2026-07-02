<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Administrasi\Penawaran;
use App\Models\Administrasi\Deal;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $keyword = $request->query('query');

        $penawaran = Penawaran::where('nama_proyek', 'like', "%$keyword%")->get();
        $deal = Deal::where('nama_proyek', 'like', "%$keyword%")->get();

        return view('dashboard', compact('penawaran', 'deal', 'keyword'));
    }
}
