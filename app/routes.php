<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', array(
			'as' => 'home',
			'uses' => 'HomeController@home'
));

/*
	authenticated group
*/	
Route::group(array( 'before' => 'auth'), function(){

				/*
					Singing OUT account (GET)
				*/	
			Route::get('/account/logout', array(
					'as' => 'account-logout',
					'uses' => 'AccountController@getLogout'
			));

});

/*
	Unauthenticated group
*/	
Route::group(array( 'before' => 'guest'), function(){

		/*
			CSRF Protection group
		*/	
	Route::group(array( 'before' => 'csrf'), function(){


				/*
					create account (POST)
				*/	
			Route::post('/account/create', array(
					'as' => 'account-create-post',
					'uses' => 'AccountController@postCreate'
			));

				/*
					signin account (POST)
				*/	
			Route::post('/account/signin', array(
					'as' => 'account-signin-post',
					'uses' => 'AccountController@postSignin'
			));


				/*
					Forgot password account (POST)
				*/	
			Route::post('/account/forgotpass', array(
					'as' => 'account-forgot-post',
					'uses' => 'AccountController@postForgot'
			));


				/*
					Password Changing Fotaccount (POST)
				*/	
			Route::post('/account/resetpassword', array(
					'as' => 'account-password-reset-post',
					'uses' => 'AccountController@postResetPassword'
			));
	});


		/*
			create account (GET)
		*/	
	Route::get('/account/create', array(
			'as' => 'account-create',
			'uses' => 'AccountController@getCreate'
	));
		/*
			account  activate(GET)
		*/	
	Route::get('/account/activate/{code}', array(
			'as' => 'account-activate',
			'uses' => 'AccountController@getActivate'
	));
		/*
			account  Password Reset(GET)
		*/	
	Route::get('/account/resetpassword/{email}/{code}', array(
			'as' => 'account-password-reset',
			'uses' => 'AccountController@getResetPassword'
	));

		/*
			Signing to  account (GET)
		*/	
	Route::get('/account/signin', array(
			'as' => 'account-signin',
			'uses' => 'AccountController@getSignin'
	));

		/*
			Forget Password account (GET)
		*/	
	Route::get('/account/forgotpass', array(
			'as' => 'account-forgot',
			'uses' => 'AccountController@getForgot'
	));

});