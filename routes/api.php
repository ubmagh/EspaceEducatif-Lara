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









//// Classes Routes
Route::prefix('Classes')->group(function () {
    Route::get('ClassInfo', 'UserController@GetClasseInfos'); ///changer l'email

});


/////// End of Classes Routes













///// the next one i don't why it is there !
Route::middleware('api')->get('user', function (Request $request) {
    return $request->user();
});
