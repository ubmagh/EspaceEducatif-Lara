<?php

use Illuminate\Http\Request;
use Illuminate\Routing\RouteGroup;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/

/// pour enregistrer les messages envoye=és depuis la page aide .
Route::post('/NewContact', 'ContactController@New');


//// contactes d'aide des membres 
Route::get('Help', 'UserController@Help');


///// capturer les données envoyées depuis la page de registre d'un nouveau etudiant
Route::post('register', 'UserController@register');


//// Login méthode qui va etre utilisée pour tous les membres admins+profs+etuds
Route::post('login', 'UserController@Login');

///// retourne les infos primaires et vérifi le token d'un utilisateur

Route::get('TokenVerification', 'UserController@getAuthenticatedUser');


/////// retoourne les infos à afficher sur le profile
Route::get('Profile', 'UserController@MyProfileData');

//// Obtenir la Dérniére connexion
Route::get('LastLogin', 'UserController@LastLogin');


///// first step if accessing guest to profile 
Route::get('CheckProfile', 'UserController@Check_Profile');


/////Classe Routes
Route::prefix('classes')->group(function () {
    Route::get('GetInitialClasses', 'UserController@GetInitialClasses'); /// initial classe list just 5 classes randomly
    Route::get('GetClassesList', 'UserController@GetClassesList'); /// Get all classes list
});

///// End Classe Routes


//// Postes Routes
Route::prefix('Postes')->group(function () {
    Route::post('newIntoClasse', 'UserController@NewClassPoste'); /// initial classe list just 5 classes randomly
});
//// End Postes Routes


/////parametres Routes
Route::prefix('Settings')->group(function () {
    Route::post('ChangeEmail', 'UserController@ChangeEmail'); ///changer l'email
    Route::post('ChangePwD', 'UserController@ChangePwD'); ///changer le mot de passe
    Route::post('ChangeAva', 'UserController@ChangeAva'); ///changer l'image de profile
    Route::post('DefAvatar', 'UserController@DefAvatar'); /// image de profle par defaut
});
///// End parametres Routes




Route::get('DashPosts','UserController@DashPosts');
Route::get('MorePosts','UserController@DashPosts_MorePosts');




//// Classes Routes
Route::prefix('Classes')->group(function () {
    Route::get('ClassInfo', 'UserController@GetClasseInfos'); ///changer l'email
    Route::get('Posts','UserController@Posts'); //// retournera les premiers postes a afficher 8 max aprés getMore va charger 4 par 4
    Route::get('MorePosts','UserController@Posts_MorePosts'); //// retournera les premiers postes a afficher 8 max aprés getMore va charger 4 par 4
    Route::get('Comment','UserController@NewComment'); //// Commenter une publication
    Route::get('Like','UserController@Like');
    Route::get('GetClassesProf','UserController@GetClassProf');
    Route::get('CheckDownload','UserController@CheckMedia');
    Route::get('Download','UserController@GetMedia');
    Route::get('ClasseMates','UserController@ClasseMates');
    Route::post('ClasseCover','UserController@ClasseCover');
    Route::get('affichages','UserController@Affichages');
    Route::post('NewAffichage','UserController@NewAffichage');
    Route::get('Affichage_Media','UserController@AffichageMedia');
    Route::get('DelAffichage','UserController@AffichageDel');
    Route::get('QuickAffichages','UserController@QuickAffichages');
    Route::get('InnaPosts','UserController@InnaprouvedPosts');
    Route::get('PostApprouve','UserController@ApprouvePost');
}); 


/////// End of Classes Routes












///// the next one i don't why it is there !
Route::middleware('api')->get('user', function (Request $request) {
    return $request->user();
});