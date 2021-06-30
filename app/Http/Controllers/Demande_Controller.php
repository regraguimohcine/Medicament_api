<?php

namespace App\Http\Controllers;

use App\Demande;
use App\Client;
use App\Livreur;
use App\Pharmacie;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class Demande_Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Array=[];
        $Demande=Demande::all();
        foreach ($Demande as $d) {
            $Livreur=Livreur::find($d->id_livreur);
            $Client=Client::find($d->id_client);
            $Pharmacie=Pharmacie::find($d->id_pharmacie);
            array_push($Array,array_merge($d->toArray(),$Livreur->toArray(),$Client->toArray(),$Pharmacie->toArray()));
        }
        return response()->json($Array,200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $Demande=new Demande();
        $Demande->description=$request->input('description');
        if($request->file('demande_image')!=null){
            $Demande->demande_image=$request->file('demande_image')->getClientOriginalName();
            $request->file('demande_image')->storeas('public',$request->file('demande_image')->getClientOriginalName());
        }
        $Demande->status=$request->input('status');
        $Demande->message_refuse=$request->input('message_refuse');
        $Demande->id_client=$request->input('id_client');
        $Demande->id_livreur=$request->input('id_livreur');
        $Demande->id_pharmacie=$request->input('id_pharmacie');
        $Demande->save();
        return response()->json($Demande,200);
    }

    
    public function show($id)
    {
        $Demande=Demande::find($id);
        $Livreur=Livreur::find($Demande->id_livreur);
        $Client=Client::find($Demande->id_client);
        $Pharmacie=Pharmacie::find($Demande->id_pharmacie);
        return response()->json(array_merge($Demande->toArray(),$Livreur->toArray(),$Client->toArray(),$Pharmacie->toArray()),200);
    }

    public function get_demande_by_client($id)
    {
        $Array=[];
        $Demande=Demande::where('id_client',$id)->get();
        foreach ($Demande as $d) {
            $Livreur=Livreur::find($d->id_livreur);
            $Pharmacie=Pharmacie::find($d->id_pharmacie);
            array_push($Array,array_merge($d->toArray(),$Livreur->toArray(),$Pharmacie->toArray()));
        }
        return response()->json($Array,200);
    }

    public function get_demande_by_livreur($id)
    {
        $Array=[];
        $Demande=Demande::where('id_livreur',$id)->get();
        foreach ($Demande as $d) {
            $Client=Client::find($d->id_client);
            $Pharmacie=Pharmacie::find($d->id_pharmacie);
            array_push($Array,array_merge($d->toArray(),$Client->toArray(),$Pharmacie->toArray()));
        }
        return response()->json($Array,200);
    }

    public function get_demande_by_pharmacie($id)
    {
        $Array=[];
        $Demande=Demande::where('id_pharmacie',$id)->get();
        foreach ($Demande as $d) {
            $Client=Client::find($d->id_client);
            $Livreur=Livreur::find($d->id_livreur);
            array_push($Array,array_merge($d->toArray(),$Livreur->toArray(),$Client->toArray()));
        }
        return response()->json($Array,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Demande  $demande
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $Demande=Demande::find($id);
        if($request->input('description')!==null){
            $Demande->description=$request->input('description');
        }
        if($request->input('status')!==null){
            $Demande->status=$request->input('status');
        }
        if($request->input('message_refuse')!==null){
            $Demande->message_refuse=$request->input('message_refuse');
        }
        if($request->input('id_livreur')!==null){
            $Demande->id_livreur=$request->input('id_livreur');
        }
        $Demande->save();
        return response()->json($Demande,200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Demande  $demande
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $Demande=Demande::find($id);
        $Demande->delete();
        return response()->json($Demande,200);
    }
}
