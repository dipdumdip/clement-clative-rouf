<?php
namespace Authors;


use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Eloquent;

class Author extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	//list the essential database fields into an array for CRUD
		protected $fillable = array( 'authorname', 'password', 'email', 'salt', 'follower_count', 'role',  
													'last_login', 'part', 'confirm', 'active');
	

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'authors';
    protected $primaryKey = 'auid';
    public $timestamps = false;

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

}
