<div id="updatez_reloader_just" class="hidden" time="<?php echo time(); ?>" <?php echo $author_ault; ?>>

</div>

<?php
			$page_url_array= unserialize (PAGE_URL_LIST);		//<----retrieve the array contant value to variable array

	if($updatesarray){
		$author_cur=$author_id;
		foreach($updatesarray as $data){

			$msg_id_or=!empty($data['updt_id']) ? $data['updt_id'] : 0;
			$msg_id=!empty($data['msg_id']) ? $data['msg_id'] : 0;
			$privacy=!empty($data['privacy']) ? $data['privacy'] : 1;
			$title=!empty($data['updates']) ? $data['updates'] : '';
			$informer=!empty($data['info']) ? $data['info'] : '';
			$outer_link=!empty($data['link']) ? $data['link'] : '';
			$orimessage=!empty($data['updates']) ? $data['updates'] : '';
			$message=!empty($data['updates']) ? $data['updates'] : '';
				// $message=tolink(htmlcode($data['updates']));
				// $message=get_text_compressed($message, false);
			 $identity=!empty($data['identity']) ? $data['identity'] : false;
				$time=!empty($data['created']) ? strtotime($data['created']) : time();
				$time= is_numeric($time) ? $time : strtotime($time);
			$uti_time=date("c", $time);
			$uploads=$data['uploads'];
			$msg_uid_real= $data['owner_id'];
			$msg_real=explode('#!', $msg_uid_real);
			$msg_uid=$msg_real[0];
			$video_link =getEmbeded_link($orimessage);		//<---function retreive video appearance
			$name_real=$data['name'];
			$name=explode('-/#!', $name_real);

		$username=\Authors\Author::find_authorname_by_auid($msg_uid);

		$auhor_profile_url=BASE_URL.$page_url_array['profile'].'/'.$username.'/home';


		  $title_tip = 'User Account';	

		$photo_name_photo =  \Authors\Personal::find_profile_photo_by_author_id($msg_uid);
		$photo_name_photo =empty($photo_name_photo) ? 'default.jpg' : $photo_name_photo;
		$face=return_image_forUser_profile($photo_name_photo, BASE_URL);  //<------calling the common function for image loading....-->//
		
		$privacy_color = ($privacy==0) ?"#EEE" : "";
		 if($author_cur!=$msg_uid && ($privacy==0)) {
		 			continue;
		 }
		 if(\Update\HidePost::Check_hiden_update($author_cur, $msg_id)) { 
		 			continue;
		 }
	$comment_checker=	( strstr($msg_id,'-forum') || strstr($msg_id,'-polling')) ? false : true;

	$outer_link_url='';
	$outer_link_attr =' called_page="dashboard" page_change="true"';
	$auhor_profile_attr=' called_page="dashboard" page_change="true" ';

		if( strstr($name_real,'Forum')){
				$informer= "have wrote on a forum page";
			$outer_link_url=   BASE_URL.$page_url_array['cloud_view'].'/'.Cloud::cloudname_by_cloud_id(Cloud_Forum::cloudname_by_forum_id($outer_link)).'#!forum?'.$outer_link;
			
		}else if( strstr($name_real,'Polling')){
				$informer= "have wrote on a polling page";
			$outer_link_url=   BASE_URL.$page_url_array['cloud_view'].'/'.Cloud::cloudname_by_cloud_id(Cloud_Poll::cloudname_by_poll_id($outer_link)).'#!polling?'.$outer_link;
		}else if( strstr($name_real,'company') ){
				$informer= "have wrote on a business page";
			$outer_link_url= BASE_URL.$page_url_array['webpage_view2'].'/'.$outer_link;
		}else if( strstr($name_real,'author') ){
			$outer_link_url= BASE_URL.$page_url_array['profile'].'/'.$outer_link; 
				$informer= "have made an update.";
			if($outer_link==$session->authorname && $username==$session->authorname){
				$informer= " have wrote";
				$outer_link_url= BASE_URL.$page_url_array['dashboard'].'/home';
				$auhor_profile_url= $outer_link_url;
			$outer_link_attr =' called_from="dashboard"  ';
			$auhor_profile_attr = $outer_link_attr;
			}else if($outer_link!=$session->authorname && $username==$session->authorname){
				$informer= ' have wrote on <span class="author">'.ucwords($outer_link).'</span> page';
			}
		}else if( strstr($name_real,'Cloud') ){
				$informer= "have wrote on a cloud page";
			$outer_link_url= BASE_URL.$page_url_array['cloud_view'].'/'.$outer_link;
		}else if( strstr($name_real,'Event') ){
				$informer= "have wrote on a Event page";
			$outer_link_url= BASE_URL.$page_url_array['event_view'].'/'.$outer_link;
		}			
	
	$username_string = ($username==$session->authorname) ? 'You'  : ucwords($username);
	?>
	<div class="updatez_each" id="<?php echo $msg_id;?>" style="background-color:<?php echo $privacy_color; ?>">
		<div class="updatez_each_vetical_line">	</div>

		<div class="updatez_each_img">
			<img src="<?php echo $face;?>" class='big_face' title='<?php echo $title_tip; ?>' alt='<?php echo $username; ?>'/>
		</div>
		
		<div class="updatez_each_data" >
			<div class="updatez_each_option">
				<div style="float:left" class="ui-icon ui-icon-gear ui-icon-carat-1-s"></div> 
					<div style="float:left;margin: 4px 0px 0px -4px;" class="ui-icon ui-icon-carat-1-s"></div>
					<div class="updatez_option_cont">
						<div class="updatez_each_spam updatez_option_each" style="">Report Spam</div>
						<div class="updatez_each_hide updatez_option_each" style="">Hide</div>
						<?php if($author_cur==$msg_uid) { ?>
						<div class="updatez_each_remove updatez_option_each" style="" >Remove</div>
					<?php if ($comment_checker) {?> 
							<?php if($privacy==1) { ?>
						<div class="updatez_each_mk_private updatez_option_each" style="" >Make Private</div>
								<?php }else if($privacy==0) {?>	
								<div class="updatez_each_mk_public updatez_option_each" style="" >Make Public</div>
						<?php } } } ?>	

					</div>
			</div>
			<div class="headInfo font_900">
			<a class="author" <?php echo $auhor_profile_attr; ?> href="<?php echo $auhor_profile_url; ?>"><?php echo $username_string;?></a>
				<a <?php echo $outer_link_attr; ?> href="<?php echo $outer_link_url; ?>"> <?php echo $informer;?></a>
			</div>
	
	<?php if(!empty($video_link) || !empty($uploads)){ ?>
			<div class="updatez_imgs">
					<ul class="bxslider">
						<?php if($uploads){ ?>
						<?php $paths = explode("," , $uploads);
								foreach($paths as $path){
									if($path){
									$newdata=return_image_forProducts($path, BASE_URL); ?>
								 <li> <a href="<?php echo $newdata; ?>" ><img src="<?php echo $newdata; ?>" alt=""></a></li>
						<?php } } } ?>
						<?php if($video_link){ ?>
								<li> <a href="<?php echo getEmbeded_link($video_link); ?>"><?php echo Expand_URL($video_link); ?></a></li>
						<?php } ?>
					</ul>
			</div>
		<?php } ?>
				
			<p> <?php echo $message;  ?>	</p> 
			<div class="time_ago">
				<time_data class="time_data" title="<?php echo $uti_time;?>"><?php echo time_ago($time);?> </time_data>
				<?php if ($comment_checker) { ?> 
					| <span class="commentopen font_800" id="<?php echo $msg_id;?>" title="Comment">comment </span>
				<?php } ?>
			</div>
			
		<?php if ($comment_checker) { ?> 
			<div class="message_container"  rel="<?php echo $msg_id;?>">
				<?php
					 echo App::make('DashboardController')->comments($msg_id, 1); 
				?>
			</div>


			<div class="messanger_includer" style="display:none;position:relative;" >
			  <div class="comment_each_arrow"></div>
				<?php if(isset($author_id)){ //<----confirming the authentication to do replys   ?>
						<div class="all_messagebody" style="overflow-x:hidden;position:relative;">
							<div class="messenger_img" style="height:35px;width:55px;">
								<img src="<?php echo $Comm_face;?>" class="small_face"/>
							</div> 
							<div class="messege_inc">
								<form method="post" action="">
									<textarea name="comment" class="comment" style="height:45px;width:86%" maxlength="200"  ></textarea>
										<br />
									<input type="submit"  value=" Comment "  id="<?php echo $msg_id;?>" class="comment_button button"/>
								</form>
							</div>
						</div>
				<?php }else {?>
						<div class="all_messagebody" style="overflow-x:hidden;position:relative;">
							<div class="messege_inc" >
								<div style="color:#333;font-size:16px;font-weight:bolder;padding: 15px 15px 15px 12%;">
								Please login to do Commenting... </div>
							</div>
						</div>
				<?php } ?>
			</div>
			<?php } ?>
		</div> 
	</div>
	<?php
	  }
	
	  if($total>12){
		$new_page=$page+1;
	  ?>
	 <!-- More Button here $msg_id values is a last message id value. -->
	 
	<div class="morebox" rel="<?php echo $profile_uid; ?>" id="<?php echo $new_page; ?>"  tot="<?php echo $total; ?>" >More	</div>

	  <?php
	  }
	  }
else
echo '<h3 class="noupdates">No Updates</h3>';
?>
