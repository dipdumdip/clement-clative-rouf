<?php

class HomeController extends BaseController {


	public function home()
	{
		// echo $user = \Authors\Author::find(1)->authorname;
		// Mail::send('emails.auth.reminder', array('name' => 'alex'), function($message){
		// 	$message->to('jose.pariyani@gmail.com', 'alex gerret', 'test');
		// });
		return View::make('home', array('name' => 'ddasdada'));
	}

}
