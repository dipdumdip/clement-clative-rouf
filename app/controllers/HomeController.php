<?php

class HomeController extends BaseController {


	public function home()
	{
		// echo $user = \Authors\Author::find(1)->authorname;
		// Mail::send('emails.auth.reminder', array('name' => 'alex'), function($message){
		// 	$message->to('jose.pariyani@gmail.com', 'alex gerret', 'test');
		// });
		// $test = Authors\Author::update_friends(2, 10, 0, 1);
		$test = Tool\DashboardingUpdates::update_friends_total(2, 1);

		return View::make('home', array('name' => 'ddasdada'));
	}

}
