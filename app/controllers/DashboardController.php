<?php
use \Authors\Author as Author;
use \Authors\Friends as Friends;
use \Authors\Personal as Personal;
use \Tool\DashboardingUpdates as DashboardingUpdates;
use \MyPaginator as Pagination;
use \Update\HidePost as Hide_Post;
use \Update\Commentz as Commentz;
use \Update\Commentz_2 as Commentz_2;

class DashboardController extends BaseController {


	public function home()
	{

		return View::make('dashboard.home', array('name' => 'ddasdada'));
	}

	public function updates()	//<-- main Dashboard updates
	{
			$dashboarding= new DashboardingUpdates();
			$personal= new Personal();
			$friends= new Friends();
			$Commentz= new Commentz();

			$lastid= !empty($lastid) ? $lastid : 0;
			$msg_id_holder='';

			$author_id=!empty($author_id) ? $author_id : 2;
			$author_cur=$author_id;
			//pagination variables
			$page= empty($page) ? 1 : $page;
			$total= $dashboarding->update_friends_total($author_id, ''); 
			$per_page= 12;

			//pagination class declaration
			$pagination = new Pagination($page, $per_page, $total);
			
			//main search function calling
			$updatesarray= isset($pagination) ? $dashboarding->update_friends($author_id, $per_page, $pagination->offset(),'') : false;

			$commet_personal =  Personal::find_profile_photo_by_author_id($author_id);
			$Comm_photo_name =empty($commet_personal) ? 'default.jpg' : $commet_personal;
			$session_face=return_image_forUser_profile($Comm_photo_name);  //<------calling the common function for image loading....-->//
		
			$author_ault =Friends::friends_count_by_author($author_id) >0 ? ' uid="'.$author_id.'"' : '';

			
							$data['Comm_face']= $session_face;
							$data['total']= $total;
							$data['updatesarray']= $updatesarray;
							// $data['total']= $total;
							// $data['session_face']= $session_face;
							$data['author_ault']= $author_ault;
							$data['author_id']=  Auth::user()->auid;
							$data['profile_uid']=  Auth::user()->auid;
							
							// $data['author_ault']= $author_ault;
							$data['page']= $page;
							$data['pagination']= $pagination;
							// $data['profile_uid']= $profile_uid;
							$data['session']= 	(object) array( 	'authorname' => Auth::user()->authorname,
																	'auid' => Auth::user()->auid,
																	'email' => Auth::user()->email
																	);
							// 	//<--necessary Classes for view
							$data['Author']= new Author();
							$data['Personal']= new Personal();
							$data['Hide_Post']= new Hide_Post();

							// print_r($commentsarray);
						return View::make('dashboard.updates', $data);
	}

	public function comments($post_id_real='', $x_value=0, $level='')	//<-- main updates comments
	{
			$dashboarding= new DashboardingUpdates();
			$personal= new Personal();
			$friends= new Friends();
			$Commentz= new Commentz();
			$data='';$page=1;$pagination=false;
			$x_value    = isset($x_value) && !empty($x_value) ? $x_value : 0;
					$ID_array= isset($post_id_real) && !empty($post_id_real) ?  explode('-', $post_id_real) : 0;
					$ID= $ID_array ?  $ID_array[0] : 0;
					$level_array = isset($level) && !empty($levellevel) ? $level : explode('-', $post_id_real);
					$level = is_array($level_array) ? $level_array[1] : $level_array;
					 
					 $total_count=$Commentz->Total_replys_no($level, $ID);

				$x1=$x_value;
					if($x1){
						$second_count= $total_count-2;
							if($total_count>2){
							$commentsarray=$Commentz->load_comments_by_updt_id_count($level, $ID, $second_count);

							}else{
								$commentsarray=$Commentz->load_comments_by_updt_id_count($level, $ID, 0);
							}
					}

					if(!$x1){
						$page= empty($page) ? 1 : $page;
						$total_count= (isset($total_count) && !empty($total_count) )? $total_count : $Commentz->Total_replys_no($level, $ID); 
						$per_page= 5;
						$page= ($page=='last') ? $total_count : $page;

						//pagination class declaration
						$pagination = new Pagination($page, $per_page, $total_count);

						$commentsarray= isset($pagination) ? $Commentz->load_comments_by_updt_id_pagination($level, $ID, $per_page, $pagination->offset()) : false;
						//main search function calling
					
						if(empty($commentsarray) && $total_count>0){
							$page=$pagination->total_pages();
							$pagination = new Pagination($page, $per_page, $total_count);
							$commentsarray= isset($pagination) ? $Commentz->load_comments_by_updt_id_pagination($level, $ID, $per_page, $pagination->offset()) : false;
						}
					}

							$data['x2']= $x1;
							$data['post_id_real']= $post_id_real;
							$data['total_count']= $total_count;
							$data['commentsarray']= $commentsarray;
							// $data['total']= $total;
							// $data['session_face']= $session_face;
							$data['author_cur']=  Auth::user()->auid;
							$data['author_id']=  Auth::user()->auid;
							$data['level']=  $level;
							// $data['author_ault']= $author_ault;
							$data['page']= $page;
							$data['pagination']= $pagination;
							// $data['profile_uid']= $profile_uid;
							$data['session']= 	(object) array( 	'authorname' => Auth::user()->authorname,
																	'auid' => Auth::user()->auid,
																	'email' => Auth::user()->email
																	);
							// 	//<--necessary Classes for view
							$data['Author']= new Author();
							$data['Personal']= new Personal();
							$data['Hide_Post']= new Hide_Post();

							// print_r($commentsarray);
						return View::make('dashboard.comments', $data);
	}

	public static function replys($comnt_2_id='', $x2_value=0)	//<-- main comment replay 
	{
			$dashboarding= new DashboardingUpdates();
			$personal= new Personal();
			$friends= new Friends();
			$Commentz_2= new Commentz_2();
			$data='';$page=1;$pagination=false;
			$x2_value    = isset($x2_value) && !empty($x2_value) ? $x2_value : 1;
					 
				$total_count=$Commentz_2->Total_replys_no($comnt_2_id);

				$x2=$x2_value;
					if($x2){
						$second_count=$total_count-1;
						if($total_count>1){
							$commentsarray=$Commentz_2->load_comments_by_updt_id_count($comnt_2_id,$second_count);
						 }
					}

					if(!$x2){
						$page= empty($page) ? 1 : $page;
								$total_count= (isset($total_count) && !empty($total_count) )? $total_count : $Commentz_2->Total_replys_no($comnt_2_id); 
								$per_page= 4;
						$page= ($page=='last') ? $total_count : $page;

								//pagination class declaration
								$pagination = new Pagination($page, $per_page, $total_count);


								$commentsarray= isset($pagination) ? $Commentz_2->load_comments_by_updt_id_pagination($comnt_2_id, $per_page, $pagination->offset()) : false;
								//main search function calling
							
								if(empty($commentsarray) && $total_count>0){
									$page=$pagination->total_pages();
									$pagination = new Pagination($page, $per_page, $total_count);
									$commentsarray= isset($pagination) ? $Commentz_2->load_comments_by_updt_id_pagination($comnt_2_id, $per_page, $pagination->offset()) : false;
								}
		
					}

							$data['x2']= $x2;
							$data['comnt_2_id']= $comnt_2_id;
							$data['total_count']= $total_count;
							$data['commentsarray']= $commentsarray;
							// $data['total']= $total;
							// $data['session_face']= $session_face;
							$data['author_cur']=  Auth::user()->auid;
							$data['author_id']=  Auth::user()->auid;
							// $data['author_ault']= $author_ault;
							$data['page']= $page;
							$data['pagination']= $pagination;
							// $data['profile_uid']= $profile_uid;
							$data['session']= 	(object) array( 	'authorname' => Auth::user()->authorname,
																	'auid' => Auth::user()->auid,
																	'email' => Auth::user()->email
																	);
							// 	//<--necessary Classes for view
							$data['Author']= new Author();
							$data['Personal']= new Personal();
							$data['Hide_Post']= new Hide_Post();

							// print_r($commentsarray);
						return View::make('dashboard.replys', $data);
	}

}
