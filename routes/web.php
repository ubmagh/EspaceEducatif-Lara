<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if( Auth::check() )
      return redirect('/home');
    return view('loginAdmin.login');
});

Auth::routes();


Route::middleware('guest')->get('forgot',function(){
  return view('loginAdmin.forgot');
});
Route::middleware('guest')->post('forgot','admin\AdminController@SendPwdReset');
Route::middleware('guest')->get('/Reset/{token?}','admin\AdminController@getForm');
Route::middleware('guest')->post('/Reset','admin\AdminController@Reset');



Route::get('/home', function(){
  return redirect(url('/dachboard'));
})->name('home');

Route::group(['middleware' => ['auth','admin']],function(){

    


    Route::get('/dachboard', 'admin\ChartDataController@index');
    Route::get('/Settings', 'admin\AdminController@SettingsView');
    Route::post('/Settings', 'admin\AdminController@SettingsSubmit');


    route::get('/liste-etudiant','admin\DachboardController@listeEtudiant');
    route::get('/listeEtudiant-edit/{id}','admin\DachboardController@listeEtudiant_edit');
    route::put('/listeEtudiant-modifier/{id}','admin\DachboardController@listeEtudiant_modifier');
    route::delete('/listeEtudiant-delete/{id}','admin\DachboardController@listeEtudiant_supprimer');

    route::get('/liste-utilisateur','UserController@listeUser');
    route::get('/listeUser-edit/{id}','UserController@listeUser_edit');
    route::put('/listeUser-modifier/{id}','UserController@listeUser_modifier');
    route::post('/user-insert','UserController@user_professeur_insert')->name('user-insert');


    route::get('/liste-professeur','admin\DachboardController@listeProfesseur');
    route::get('/listeProfesseur-edit/{id}','admin\DachboardController@listeProfesseur_edit');


     route::get('/test','admin\ChartDataController@getMonthlyPostData');


     // Messages Routes

     Route::get('/Messages','ContactController@View');
     Route::get('/Messages/{id}','ContactController@ViewMessage');
     Route::get('/Messages/del/{id}','ContactController@delete');

});