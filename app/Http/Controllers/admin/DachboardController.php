<?php

namespace App\Http\Controllers\Admin;

use App\professeur;
use App\Etudiant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Permission;
use Illuminate\Validation\Rule;

class DachboardController extends Controller
{

    public function listeEtudiant()
    { 
        //first strep:: njibo data model
        $etudiants = Etudiant::all();
        return view('homeAdmin.listeEtudiant')->with('etudiants',$etudiants);
    }

    public function listeEtudiant_edit(Request $request, $id)
    { 
        //editing
        $users =Etudiant::findOrFail($id);
        $permissions = Permission::find($users->id);
        return view ('homeAdmin.profileEtudiant')->with(['users'=>$users,'permissions'=>$permissions]);
    }


    public function listeEtudiant_modifier(Request $request, $id)
    { 
        $this->validate($request,
        [
            'nom'   =>  'required|min:2|max:25|regex:/^[a-z A-Z éèà]+(([\',. -][a-zA-Z ])?[a-zA-Z]*)*$/',
            'prenom'    =>  'required|min:2|max:35|regex:/^[a-z A-Z éèà]+(([\',. -][a-zA-Z ])?[a-zA-Z]*)*$/',
            'cin'   =>  'required|min:7|max:12|regex:/^[a-z A-Z 0-9]+(([0-9a-zA-Z ])?[a-zA-Z0-9]*)*$/',
            'naissance' =>  'required|date',
            'annee' =>  Rule::in(['1','2']),
            'sex' =>  Rule::in(['M','F']),
            'filiere' =>  Rule::in(['GE','GI'])
        ],
        [
            'nom.required'  =>  "Saisissez le nom !",
            'nom.min'  =>  "Nom invalide !",
            'nom.max'  =>  "Nom invalide Max 25 Caractères !",
            'nom.regex'  =>  "Nom invalide !",
            'prenom.required'  =>  "Saisissez le prenom !",
            'prenom.min'  =>  "Prenom invalide !",
            'prenom.max'  =>  "Prenom invalide Max 35 Caractères!",
            'prenom.regex'  =>  "Prenom invalide !",
            'cin.required'  =>  "Saisissez le CIN  !",
            'cin.regex'  =>  "CIN invalide !",
            'cin.min'  =>  "CIN invalide !",
            'cin.max'  =>  "CIN invalide !",
            'naissance.required'  =>  "Saisissez la date de naissance !",
            'naissance.date'  =>  " date de naissance Invalide !",
            'annee.in'  =>  " Choix invalide !",
            'sex.in'  =>  " Choix invalide !",
            'filiere.in'  =>  " Choix invalide !",
        ]);
        //editing
        $users =Etudiant::find($id);
        
        $permission = Permission::where('EtudiantID',$users->id)->first();
        $permission->posting = (  $request->input('publier')  && $request->input('publier')=='on' ) ? '1': '0';
        $permission->commenting = (  $request->input('commenter')  && $request->input('commenter')=='on' ) ? '1': '0';
        $permission->save();

        if( count( Etudiant::where('CIN',$request->input('cin'))->where('id','!=',$id)->get() )>0 )
            return redirect()->back()->with('cin','taken');

        $users->Fname = $request->input('nom');
        $users->Lname = $request->input('prenom');
        $users->Filiere = $request->input('filiere');
        $users->Sex = $request->input('sex');
        $users->Annee = $request->input('annee');
        $users->CIN = $request->input('cin');
        $users->update();
        return redirect('/liste-etudiant')->with('status','Modification Faite');
    }


    public function listeEtudiant_supprimer(Request $request, $id)
    {
        $users =Etudiant::findOrFail($id);
        $users->delete();
        return redirect('/liste-etudiant')->with('status','Supprimation Faite');
    }
    

    // hna partie professeurs      !!!!!!!!!!!
     

    public function listeProfesseur()
    { 
        //first strep:: njibo data model
        $professeur = professeur::all();
        return view('homeAdmin.listeProfesseur')->with('professeur',$professeur);
    }




}