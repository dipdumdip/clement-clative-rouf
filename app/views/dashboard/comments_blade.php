@extends('layout.main')
 {? global $page_url_array ?}

@section('content')

 @if($x2)
   @if($total_count>2)
        <div class="all_messagebody">
          <div class="view_comments" rel="<?php echo $post_id_real; ?>">
            View all <?php echo $total_count; ?> comments
          </div>
      </div>
   @endif
  @elseif(!$x2)
  <div class="all_messagebody">
    <div class="shrink_comments" rel="<?php echo $post_id_real; ?>">
      Shrink comments Total:<?php echo $total_count; ?>
       </div>
  </div>
 @endif

   @if($commentsarray)
      @foreach ($commentsarray as $cdata)
         {? $com_id=$cdata->cmnt_id; ?}
         {? $comment=get_text_compressed($cdata->comments , false); ?}
         {? $comment=tolink(htmlcode($comment)); ?}
         {? $time=$cdata->created; ?}
         {? $uti_time=date("c", $time); ?}
         {? $com_uid=$cdata->author_id_fk; ?}
           {{--  User Avatar --}}
         {? $username=\Authors\Author::find_authorname_by_auid($com_uid); ?}

         {? $photo_name =  \Authors\Personal::find_profile_photo_by_author_id($com_uid); ?}
         {? $photo_name =empty($photo_name) ? 'default.jpg' : $photo_name; ?}
         {? $cface=return_image_forUser_profile($photo_name);  //<------calling the common function for image loading....-->// ?}
         {?  $auhor_profile_url=BASE_URL.$page_url_array['profile'].'/'.$username.'/home'; ?}
  

      <div class="comment_each_arrow"></div>
        <div class="all_messagebody" style="overflow-x:hidden;position:relative;">
          <div class="comment_each_vetical_line"></div>
          <div class="message_all_img">
            <img src="{{ $cface }}" class="small_face" alt="{{ $username }}"/>
          </div> 
            <div class="messege_inc">
               @if($author_id==$com_uid )
              <span class="message_each_del" id='{{ $com_id.'-'.$level}}' title='Delete Comment'></span>
              @endif

              <span class="message_each_reply" id='{{ $com_id.'-'.$level }}' title='Reply Comment'></span>
              <div style="float:left;"><b><a page_change="true" called_page="profile" href="{{ $auhor_profile_url }}">
                <?php echo $username; ?></a></b> </div>
              <div style="float:left;margin-left:30px" class="messagetime">
                <time_data class="comment_time_data" title="{{ $uti_time }}">{{ time_ago($time) }} </time_data>
              </div> 
              <div style="clear:both;" >
                <p>{{ $comment }}</p>
              </div>
            </div>
            <div class="message_comments_container" style="padding:0px 4px 0px 40px; position:relative;">
              {? $x=1; ?}
              {? $comnt_2_id= $com_id.'-'.$level; ?}
              {{-- include('load_2_comments.php'); --}} 
              {? echo App::make('DashboardController')->replys($com_id); ?}

             
            </div>  
          <div class="messanger_includer_2 hidden">
               @if(isset($session->author_id))

          <div class="replay_each_arrow"></div>
            <div class="messege_inc_2" >
              <form method="post" action="">
              <textarea name="comment" class="comment_2" maxlength="140"></textarea>
              <br />
              <input type="submit"  value=" reply " onclick="return false;"  id="{{ $comnt_2_id }}" class="comment_button_2 button_2"/>
              </form>
            </div>  
          @else
              <div class="messege_inc_2" >
                  <div style="color:#333;font-size:14px;font-weight:bolder;padding: 15px 15px 15px 15px;">
                  <span style="float:left;margin:3px 5px 0 0;">Please login to do Commenting... </span>
                  <a href="{{ BASE_URL.$page_url_array['login']}}" style="padding:2px 10px 2px 10px;"class="more_data_loading">login</a></div>
              </div>
          @endif
          </div>
         </div>      
        @endforeach
          
          @if(!$x2)
              @if($pagination->total_pages() > 1)
          <div class="all_messagebody" rel="{{  $post_id_real }}" style="padding:0;">
            <ul class="pagination_1" style="float:right;">
                @if($pagination->has_previous_page()) 
                   <li id=" {{ $pagination->previous_page() }}" class="search_paginator_1" total_page=" {{ $total_count}}" >
                    &laquo; Previous</li>
                @elseif(!$pagination->has_previous_page())
                  <li style="background-color:white;">
                   &laquo; Previous</li>
                @endif
                  {? $start = $pagination->total_pages()>5 ? $page-2 : 1; ?}
                  {? $end = $pagination->total_pages()>5 ? $page+2 : 5; ?}
                  {? $start = $start<1 ? 1 : $start; ?}
                  {? $end = $end>$pagination->total_pages() ? $pagination->total_pages() : $end; ?}
                  @for($i=$start; $i <= $end; $i++) 
                     @if($i == $page) 
                        <li id="{{$i}}" class="search_paginator_clickd_1" total_page="{{ $total_count }}" style="background-color:orange;">{{ $i}}</li>
                     @else
                       <li id="{{$i}}" class="search_paginator_1" total_page="{{ $total_count }}" >{{$i}}</li>
                     @endif
                   @endfor

                  @if($pagination->has_next_page())
                    <li id="{{ $pagination->next_page()}}"  class="search_paginator_1"   total_page="{{$total_count}}">
                    {{ "Next &raquo;</li>"; }}
                  @elseif(!$pagination->has_next_page()) 
                    {{ '<li style="background-color:white;">';}}
                    {{  "Next &raquo;</li>";}} 
                   @endif
                
               @endif
            </ul>
          </div>
         @endif
     @endif

@stop