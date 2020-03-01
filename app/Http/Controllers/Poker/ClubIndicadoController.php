<?php

namespace App\Http\Controllers\Poker;

use App\Http\Controllers\Controller;
use App\Models\Poker\Club;
use App\Models\Poker\ClubIndicado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ClubIndicadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $lista = ClubIndicado::wherestatus(0)->get();
        return view('poker.indicados.list',compact('lista'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        //
        $ind = ClubIndicado::find($id);

        $cad = new Club();
        $cad->id = 0;
        $cad->name = $ind->name;
        $cad->phone = $ind->phone;
        $cad->responsible = $ind->responsible;

        return view('poker.clubs.edit',compact('cad','ind'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Club  $club
     * @return \Illuminate\Http\Response
     */
    public function show(Club $club)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Club  $club
     * @return \Illuminate\Http\Response
     */
    public function edit(Club $club)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Club  $club
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Club $club)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Club  $club
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClubIndicado $cad){
        Auditoria('DELETE','CLUB_INDICADO',$cad->id,
            'Nome: '.$cad->name
            .chr(13).'phone: '.$cad->phone
            .chr(13).'Club_id: '.$cad->club_id
            .chr(13).'userapp_id: '.$cad->userapp_id
            );

        $cad->delete();

        Session::put('Sok', 'Cadastro Excluido!');
        return redirect()->route('poker.indic');
    }
}
