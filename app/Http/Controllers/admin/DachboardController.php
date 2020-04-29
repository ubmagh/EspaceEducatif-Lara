<?php

namespace App\Http\Controllers\Admin;

use App\professeur;
use App\Etudiant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DachboardController extends Controller
{

    public function listeEtudiant()
    { 
        //first strep:: njibo data model
        $etudiants = Etudiant::all();
        $emails = DB::select(" SELECT email FROM users WHERE UserType='etud' AND email not in ( SELECT email from etudiants ) ");
        return view('homeAdmin.listeEtudiant')->with(['etudiants'=>$etudiants,'emails'=>$emails]);
    }

    public function EtudiantInsert(Request $request){
        $emails=[];
        $emails__ = DB::select(" SELECT email FROM users WHERE UserType='etud' AND email not in ( SELECT email from etudiants ) ");
        foreach($emails__ as $email){
            array_push($emails,$email->email);
        }

        $this->validate($request,
        [
            'Lname'   =>  'required|min:2|max:25|regex:/^[a-z A-Z éèà]+(([\',. -][a-zA-Z ])?[a-zA-Z]*)*$/',
            'Fname'    =>  'required|min:2|max:35|regex:/^[a-z A-Z éèà]+(([\',. -][a-zA-Z ])?[a-zA-Z]*)*$/',
            'email' =>  ['required','email',Rule::in($emails)],
            'cin'   =>  'required|min:7|max:12|regex:/^[a-z A-Z 0-9]+(([0-9a-zA-Z ])?[a-zA-Z0-9]*)*$/',
            'naissance' =>  'required|date',
            'annee' =>  Rule::in(['1','2']),
            'sex' =>  Rule::in(['M','F']),
            'filiere' =>  Rule::in(['GE','GI'])
        ],
        [
            'Lname.required'  =>  "Saisissez le nom !",
            'Lname.min'  =>  "Nom invalide !",
            'Lname.max'  =>  "Nom invalide Max 25 Caractères !",
            'Lname.regex'  =>  "Nom invalide !",
            'Fname.required'  =>  "Saisissez le prenom !",
            'Fname.min'  =>  "Prenom invalide !",
            'Fname.max'  =>  "Prenom invalide Max 35 Caractères!",
            'Fname.regex'  =>  "Prenom invalide !",
            'cin.required'  =>  "Saisissez le CIN  !",
            'cin.regex'  =>  "CIN invalide !",
            'cin.min'  =>  "CIN invalide !",
            'cin.max'  =>  "CIN invalide !",
            'naissance.required'  =>  "Saisissez la date de naissance !",
            'naissance.date'  =>  " date de naissance Invalide !",
            'annee.in'  =>  " Choix invalide !",
            'sex.in'  =>  " Choix invalide !",
            'filiere.in'  =>  " Choix invalide !",
            'email.in'  =>  " Choix invalide !",
            'email.required'  =>  " Choix invalide !",
            'email.email'  =>  " Choix invalide !",
        ]);

        $etudiant = new Etudiant();
        $etudiant->Fname = $request->input('Fname');
        $etudiant->Lname = $request->input('Lname');
        $etudiant->Sex = $request->input('sex');
        $etudiant->email = $request->input('email');
        $etudiant->CIN = $request->input('cin');
        $etudiant->dateNaissance = $request->input('naissance');
        $etudiant->Annee = $request->input('annee');
        $etudiant->Filiere = $request->input('filiere');
        $etudiant->AvatarPath = ($request->input('sex') == "M")? "DefM.png":"DefF.png";
        $etudiant->save();
        return redirect(url('/liste-etudiant'))->with('status','Etudiant Bien Créé');
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
        
        if(empty($users))
            return redirect(url('liste-etudiant'))->with('notfound','x');

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
        return redirect('/liste-etudiant')->with('status','Supprission Faite');
    }
    

    // hna partie professeurs      !!!!!!!!!!!
     

    public function listeProfesseur()
    { 
        //first strep:: njibo data model
        $emails = DB::select(" SELECT email FROM users WHERE UserType='prof' AND email not in ( SELECT email from professeurs ) ");
        $professeur = professeur::all();
        return view('homeAdmin.listeProfesseur')->with(['professeur'=>$professeur,'emails'=>$emails]);
    }


    public function prof_insert(Request $request){

        $emails=[];
        $emails__ =DB::select(" SELECT email FROM users WHERE UserType='prof' AND email not in ( SELECT email from professeurs ) ");
        foreach($emails__ as $email){
            array_push($emails,$email->email);
        }

        $this->validate($request,
        [
            'Lname'   =>  'required|min:2|max:25|regex:/^[a-z A-Z éèà]+(([\',. -][a-zA-Z ])?[a-zA-Z]*)*$/',
            'Fname'    =>  'required|min:2|max:35|regex:/^[a-z A-Z éèà]+(([\',. -][a-zA-Z ])?[a-zA-Z]*)*$/',
            'email' =>  ['required','email',Rule::in($emails)],
            'matiere'   =>  'required|max:30|regex:/^[a-z A-Zàéè 0-9]+(([0-9a-zA-Zéçàè ])?[a-zA-Z0-9]*)*$/',
            'sex' =>  Rule::in(['M','F']),
            'filiere' =>  Rule::in(['GE','GI'])
        ],
        [
            'Lname.required'  =>  "Saisissez le nom !",
            'Lname.min'  =>  "Nom invalide !",
            'Lname.max'  =>  "Nom invalide Max 25 Caractères !",
            'Lname.regex'  =>  "Nom invalide !",
            'Fname.required'  =>  "Saisissez le prenom !",
            'Fname.min'  =>  "Prenom invalide !",
            'Fname.max'  =>  "Prenom invalide Max 35 Caractères!",
            'Fname.regex'  =>  "Prenom invalide !",
            'matiere.required'  =>  "Saisissez le nom de la Matière !",
            'matiere.regex'  =>  "nom de Matière invalide !",
            'matiere.max'  =>  "nom de Matière est de 30 caractères au Max !",
            'sex.in'  =>  " Choix invalide !",
            'filiere.in'  =>  " Choix invalide !",
            'email.in'  =>  " Choix invalide !",
            'email.required'  =>  " Choix invalide !",
            'email.email'  =>  " Choix invalide !",
        ]);

        $professeur = new professeur();
        $professeur->Fname = $request->input('Fname');
        $professeur->Lname = $request->input('Lname');
        $professeur->Sex = $request->input('sex');
        $professeur->email = $request->input('email');
        $professeur->Matiere = $request->input('matiere');
        $professeur->Filiere = $request->input('filiere');
        $professeur->AvatarPath = ($request->input('sex') == "M")? "DefTM.png":"DefTF.png";
        $professeur->save();
        return redirect(url('/liste-professeur'))->with('status','professeur Bien Créé');
        

    }

    public function prof_delete(Request $request, $id){
        $prof =professeur::findOrFail($id);
        $prof->delete();
        return redirect('/liste-professeur')->with('status','supprission Faite');
    }

    public function listeProfesseur_edit (Request $request,$id){

        $professeur = professeur::find($id);
        if(empty($professeur))
            return view('errors.404');
        
        return view('homeAdmin.listeProfesseur_edit')->with('professeur',$professeur);
    }

    public function ProfesseurModify(Request $request,$id){

        $this->validate($request,
        [
            'nom'   =>  'required|min:2|max:25|regex:/^[a-z A-Z éèà]+(([\',. -][a-zA-Z ])?[a-zA-Z]*)*$/',
            'prenom'    =>  'required|min:2|max:35|regex:/^[a-z A-Z éèà]+(([\',. -][a-zA-Z ])?[a-zA-Z]*)*$/',
            'Matiere'   =>  'required|min:3|max:30|regex:/^[a-z A-Z éèà0-9]+(([\',. -][a-zA-Z0-9 ])?[a-zA-Z0-9]*)*$/',
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
            'Matiere.required'  =>  "Saisissez la Matière  !",
            'Matiere.regex'  =>  "Nom de la Matiere invalide !",
            'Matiere.min'  =>  "Nom de la Matiere invalide !",
            'Matiere.max'  =>  " Le nom de la Matiere est de 30 caractères Max",
            'sex.in'  =>  " Choix invalide !",
            'filiere.in'  =>  " Choix invalide !",
        ]);

        $prof = professeur::findOrfail($id);

        $prof->Fname = $request->input('prenom');
        $prof->Lname = $request->input('nom');
        $prof->Matiere = $request->input('Matiere');
        $prof->Sex = $request->input('sex');
        $prof->Filiere = $request->input('filiere');

        $prof->update();
        return redirect('/liste-professeur')->with('status','Modification Faite');

    }



}