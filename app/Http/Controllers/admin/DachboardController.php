<?php

namespace App\Http\Controllers\Admin;

use App\professeur;
use App\Etudiant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


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
        return view ('homeAdmin.profileEtudiant')->with('users',$users);
    }


    public function listeEtudiant_modifier(Request $request, $id)
    { 
        //editing
        $users =Etudiant::find($id);
        $users->Fname = $request->input('nom');
        $users->Lname = $request->input('prenom');
        $users->email = $request->input('email');
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
