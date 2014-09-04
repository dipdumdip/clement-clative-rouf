 {? global $page_url_array ?}

 @if($x2)
   @if($total_count>1)
    <div class="comnt_2_com" rel="{{ $comnt_2_id }}">
      <div class="view_comments_2" rel="{{ $comnt_2_id }}">View all {{ $total_count }} reply </div>
    </div>
   @endif
  @elseif(!$x2)
    <div class="comnt_2_com" rel="{{ $comnt_2_id }}">
      <div class="shrink_comments_2" rel="{{ $comnt_2_id }}">Shrink reply Total:{{ $total_count }}</div>
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
         {? $REplayface=return_image_forUser_profile($photo_name);  //<------calling the common function for image loading....-->// ?}
         {?  $auhor_profile_url=BASE_URL.$page_url_array['profile'].'/'.$username.'/home'; ?}
  
        <div class="replay_each_arrow"></div>
        <div class="comnt_2_com">
          <div class="msg_img_2">
            <img src="{{ $REplayface }}" class="cmnt_face_2" alt="{{ $username }}"/>
          </div> 
          <div class="messege_inc_2">
            @if($author_id==$com_uid )
            <div class="message_each_del_2" id="{{ $com_id }}" title='Delete Reply'></div>
            @endif
            <div style="float:left;"><b><a page_change="true" called_page="profile" href="{{ $auhor_profile_url }}" class="link_a">{{ $username }}</a></b></div>
              <div style="float:left;margin-left:30px" class="messagetime_2">
                <time_data class="reply_time_data" title="{{ $uti_time }}">{{ time_ago($time) }}</time_data>       
              </div> 
              <div style="clear:both;" >
                    <p>{{ $comment }}</p>
              </div>
          </div>
        </div>
    
        @endforeach
          
          @if(!$x2)
              @if($pagination->total_pages() > 1)
          <div class="comnt_2_com" rel="{{  $comnt_2_id }}" style="padding:0;">
            <ul class="pagination_2" style="float:right;">
                @if($pagination->has_previous_page()) 
                   <li id=" {{ $pagination->previous_page() }}" class="search_paginator_2" total_page=" {{ $total_count}}" >
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
                        <li id="{{$i}}" class="search_paginator_clickd_2" total_page="{{ $total_count }}" style="background-color:orange;">{{ $i}}</li>
                     @else
                       <li id="{{$i}}" class="search_paginator_2" total_page="{{ $total_count }}" >{{$i}}</li>
                     @endif
                   @endfor

                  @if($pagination->has_next_page())
                    <li id="{{ $pagination->next_page()}}"  class="search_paginator_2"   total_page="{{$total_count}}">
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

