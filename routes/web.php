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

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['auth','admin']],function(){

    // route::get('/dachboard',function(){
    // return view('homeAdmin.dachboard');
    // });


    Route::get('/dachboard', 'admin\ChartDataController@index');
      //route::get('/dachboard','admin\ChartDataController@getMonthlyPostData');


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


});