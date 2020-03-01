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

Route::get('/phpinfo', function () {
    return phpinfo();
});


Auth::routes();

Route::get('/register', function () {
    return redirect('/config/user/new');
});

Route::get('/', function () {
    return redirect()->route('app.home');
});

Route::get('/off', function () {
    return view('auth.login');
});

Route::get('/msg', function () {
    return view('msg');
})->name('msg');

Route::get('/load', function () {
    return view('load');
})->name('load');

Route::get('/logout', function () {
    \Illuminate\Support\Facades\Auth::logout();
    return redirect('/');
});


/***************** SICOOB *********************/
Route::get('/boleto/{hash}', 'Adm\BillReceivableController@PrintBoleto')->name('boleto.url');


/***para o sistema deve estar logado *****/
Route::group(['middleware' => 'check.user'], function () {
    Route::get('/home', 'HomeController@index')->name('app.home');

    Route::group(['prefix'=>'config'], function () {
        Route::group(['prefix'=>'user'], function () {
            Route::get('/', 'Adm\UserController@index')->name('config.user');
            Route::get('/home', 'Adm\UserController@index')->name('config.user.home');
            Route::post('/', 'Adm\UserController@search')->name('config.user.search');
            Route::get('/new', 'Adm\UserController@create')->name('config.user.new');
            Route::get('/edit/{cad}', 'Adm\UserController@edit')->name('config.user.edit');
            Route::post('/save', 'Adm\UserController@save')->name('config.user.save');
            Route::post('/update', 'Adm\UserController@update')->name('config.user.update');
            Route::get('/del/{cad}', 'Adm\UserController@destroy')->name('config.user.del');
            Route::get('/active/{cad}', 'Adm\UserController@active')->name('config.user.active');
            Route::get('/show/{cad}', 'Adm\UserController@show')->name('config.user.show');
            Route::get('/roles/{cad}', 'Adm\UserController@roles')->name('config.user.roles');
            Route::post('/role', 'Adm\UserController@role_save')->name('config.user.role.save');
        });
    });

    Route::group(['prefix'=>'poker'], function () {

        Route::group(['prefix'=>'clubs'], function () {
            Route::get('/', 'Poker\ClubController@index')->name('poker.clubs');
            Route::get('/home', 'Poker\ClubController@index')->name('poker.club.home');
            //lista
            Route::post('/search', 'Poker\ClubController@search')->name('poker.club.search');
            Route::get('/find/{id}', 'Poker\ClubController@find')->name('poker.club.find');
            Route::get('/state/{uf}', 'Poker\ClubController@state')->name('poker.club.state');
            Route::get('/all', 'Poker\ClubController@all')->name('poker.club.all');
            //Edits
            Route::get('/edit/{cad}', 'Poker\ClubController@edit')->name('poker.club.edit');
            Route::get('/new', 'Poker\ClubController@create')->name('poker.club.new');
            Route::post('/save', 'Poker\ClubController@save')->name('poker.club.save');
            //
            Route::get('/show/{cad}', 'Poker\ClubController@show')->name('poker.club.show');
            Route::get('/del/{cad}', 'Poker\ClubController@destroy')->name('poker.club.del');
            Route::get('/active/{cad}', 'Poker\ClubController@active')->name('poker.club.active');

            Route::group(['prefix'=>'user'], function () {
                Route::post('/save', 'Poker\ClubUserController@store')->name('poker.club.user.save');
                Route::post('/del', 'Poker\ClubUserController@destroy')->name('poker.club.user.del');
                Route::post('/reset', 'Poker\ClubUserController@reset')->name('poker.club.user.reset');
            });

            Route::get('/licenses/{club_id}', 'Poker\LicenseController@LicensesClub')->name('poker.club.licenses');
            Route::get('/crms/{club_id}', 'Poker\CrmController@CrmsClub')->name('poker.club.crms');

            Route::get('/list/premium', 'Poker\LicenseController@list_premium')->name('poker.club.list.premium');
            Route::get('/list/anual', 'Poker\LicenseController@list_anual')->name('poker.club.list.anual');
            Route::get('/list/trinta', 'Poker\LicenseController@list_trinta')->name('poker.club.list.trinta');
        });

        Route::group(['prefix'=>'indic'], function () {
            Route::get('/', 'Poker\ClubIndicadoController@index')->name('poker.indic');
            Route::post('/search', 'Poker\ClubIndicadoController@search')->name('poker.indic.search');
//            Route::post('/new', 'Poker\ClubController@new')->name('poker.indic.new');
            Route::get('/create/{id}', 'Poker\ClubIndicadoController@create')->name('poker.indic.create');
            Route::get('/edit', 'Poker\ClubIndicadoController@edit')->name('poker.indic.edit');
            Route::get('/del/{cad}', 'Poker\ClubIndicadoController@destroy')->name('poker.indic.del');
        });

        Route::group(['prefix'=>'audit'], function () {
            Route::get('/{cad}', 'Poker\AuditController@index')->name('poker.audit');
        });

        Route::group(['prefix'=>'crm'], function () {
            Route::get('/{cad}', 'Poker\CrmController@index')->name('poker.crm');
            Route::post('/save', 'Poker\CrmController@save')->name('poker.crm.save');
        });

        Route::group(['prefix'=>'lic'], function () {
            Route::post('/new', 'Poker\LicenseController@store')->name('poker.lic.new');
            Route::get('/del/{lic}', 'Poker\LicenseController@destroy')->name('poker.lic.del');
            Route::get('/club', 'Poker\LicenseController@club')->name('poker.lic.club');
            Route::get('/pay/{cad}', 'Poker\LicenseController@licpay')->name('poker.lic.pay');
        });

    });
});