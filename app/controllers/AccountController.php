<?php

class AccountController extends BaseController {


	public function getCreate()
	 { 
			return View::make('account.login', array('active' => 'register'));
	}

	public function getForgot()
	 {
			return View::make('account.login', array('active' => 'forger_pass'));
	}

	public function postForgot()
	 {
		$validator = Validator::make(Input::all(),

				array(
						'email' => 		'required|max:50|email',
					)
				);
		if($validator->fails()){

			return Redirect::route('account-forgot')
					->withErrors($validator)
					->withInput();
		}else{

				$email= Input::get('email');
					//<- account existance checking
			$author =Authors\Author::where('email', '=', $email);
		if($author->count()){
			$author = $author->first();
				$rand_code= str_random(43);
				$author->confirm= $rand_code;
			if($author->save()){
			Mail::send('emails.auth.forgotpassword', array(
					'link' => URL::route('account-password-reset',array( $email, $rand_code)),
					 'username' => $author->authorname ),
					  function($message) use ($author){
							$message->to($author->email, $author->authorname)
					->subject('Account Activate');
			});
			 return Redirect::route('home')
			 	->with('global', 'Your Password Request was Success Please See Your Email.');
			}else{

				 return Redirect::route('home')
				 	->with('global', 'Cannot Proceed Your Password Requesting.');
			}
		}else{
				return Redirect::route('account-forgot')
				 	->with('global', 'Please Enter a Valid Account Details.');
		}
	  }
	}

	public function getResetPassword($email, $code)
	 {
			$author =Authors\Author::where('confirm', '=', $code)->where('email', '=', $email);

			if($author->count()){
		
				return View::make('account.password_reset', array('email' => $email, 'code' => $code));

			}else{
				return Redirect::route('home')
			 	->with('global', 'Wrong Activation Code.. Try again later.');
			}
	}

	public function postResetPassword()
	 {
			$validator = Validator::make(Input::all(),
				array(
						'email_hidden' => 		'required|max:50|email',
						'password' =>  'required',
						'password_confirm' => 'required|same:password'
					)
					);
			$email= Input::get('email_hidden');
			$code= Input::get('code_hidden');
		if($validator->fails()){

			return Redirect::route('account-password-reset', array($email, $code))
					->withErrors($validator);
		}else{
				$email= Input::get('email_hidden');
				$password= Input::get('password');

				$author =Authors\Author::where('confirm', '=', $code)->where('email', '=', $email);
			if($author->count()){
					$author = $author->first();
					$author->active=1;
					$author->confirm='';
					$author->password =Hash::make($password);
				if($author->save()){
						$auth = Auth::attempt( array(
							'email' => $email,
							'password' => $password,
							'active' => 1
						));

					if($auth){
							// password Changed and Logged in
							return Redirect::route('home')
						 		->with('global', 'Your password is now changed.');

					}else{
							return Redirect::route('home')
							 	->with('global', 'There was problem while changing your password.. Try again later.');
					}
				}
			}
				return Redirect::route('home')
				 		->with('global', 'There was problem while changing your password.. Try again later.');
		}
	}

	public function postCreate()
	 {
		$validator = Validator::make(Input::all(),

				array(
						'email' => 		'required|max:50|email|unique:authors',
						'first_name' => 'required|max:20|min:6',
						'last_name' =>  'required|min:6',
						'password' =>  'required',
						'password_confirm' => 'required|same:password'
					)
				);
		if($validator->fails()){

			return Redirect::route('account-create')
					->withErrors($validator)
					->withInput();
		}else{

				$email= Input::get('email');
				$first_name= Input::get('first_name');
				$last_name= Input::get('last_name');
				$password= Input::get('password');
					//<- activation code
				$rand_code= str_random(43);
				// insert new recode to the databse
			$author = \Authors\Author::create( array(
					'email' => $email,
					'authorname' => $first_name."".$last_name,
					'password' => Hash::make($password),
					'confirm' => $rand_code,
					'active' => 0
					));
		if($author){

			Mail::send('emails.auth.activate', array(
					'link' => URL::route('account-activate', $rand_code),
					 'username' => $author->authorname ),
					  function($message) use ($author){
							$message->to($author->email, $author->authorname)
					->subject('Account Activate');
			});
			 return Redirect::route('home')
			 	->with('global', 'Your accound is now created please confirm your email Now.');

		}else{

		}
	  }
	}

	public function getActivate($code)
	 {
			$author =Authors\Author::where('confirm', '=', $code)->where('active', '=', 0);

			if($author->count()){
					$author = $author->first();
					$author->active=1;
					$author->confirm='';
					if($author->save()){
						return Redirect::route('home')
					 	->with('global', 'Your accound is Successfully Acitvated.');
					}
			}else{
				return Redirect::route('home')
			 	->with('global', 'There was problem on activation.. Try again later.');
			}
	}

	public function getSignin()
	 { 
		return View::make('account.login', array('active' => 'login'));
	} 

	public function postSignin()
	 {
		$validator = Validator::make(Input::all(),

				array(
						'email' => 		'required|email',
						'password' =>  'required'
					)
				);
		if($validator->fails()){
					// error  user signin
			return Redirect::route('account-signin')
					->withErrors($validator)
					->withInput();
		}else{
			// attempt user signin
			$email= Input::get('email');
			$password= Input::get('password');
			$remember= (Input::has('remember')) ? true : false;

			$auth = Auth::attempt( array(
					'email' => $email,
					'password' => $password,
					'active' => 1
				), $remember);

		if($auth){
				// Redirected to intended page
			return Redirect::intended('/');


		}else{
				$author =Authors\Author::where('email', '=', $email);
			if($author->count()){
				return Redirect::route('account-signin', array('message'=>'You Have entered a Wrong Password'))
					->withInput();
			}else{

			 return Redirect::route('account-signin')
			 	->with('global', 'Email/Password is not Correct or Account is not Activated.');
			 }
		}
	  }
		 return Redirect::route('account-signin')
 				->with('global', 'Your have a problem in Login in');
	}

	public function getLogout()
	 {
		Auth::logout();
			return Redirect::route('home');
	}


}