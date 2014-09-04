<?php

	function url_maker($your_string){		//<---this function is used to create nice/safe URL
			// return rawurlencode($your_string);
				$str= preg_replace('/\s+/', '_', $your_string);
				$str=  str_replace('&', 'and', $str);
				$str=  str_replace('%', 'percent', $str);
				$str=  str_replace('/', 'by', $str);
				$str=  str_replace('-', '_', $str);
				$str=  str_replace("'", '', $str);
				$str=  str_replace('"', '', $str);
				$str=  str_replace('^', '', $str);
				$str=  str_replace('$', '', $str);
				$str=  str_replace('#', '', $str);
				$str=  str_replace('*', '', $str);
				$str=  str_replace('(', '', $str);
				$str=  str_replace(')', '', $str);
				$str=  str_replace(',', '_', $str);
				$str=  str_replace('|', '', $str);
				$str=  str_replace("\\", '', $str);
			   $str=  str_replace('?', '', $str);
			  $string = preg_replace('/_+/', '_', $str);
			return  strtolower(substr($string,0,70));
	}

	function url_maker_reverse($str){		//<---this function is used to create nice/safe URL
			// return rawurlencode($your_string);
				$str=  str_replace('by', '/', $str);
				$str=  str_replace('-', ' ', $str);
				$str=  str_replace('_', ' ', $str);
			  $string = preg_replace('/_+/', '_', $str);
			return  ucwords($string);
	}

	function simple_minify_js( $js ) {		//<--- this function helps to minify  JavavScript File the given string file
			 // remove comments
			$js = preg_replace('/\/\/\S*[^\n]+\n?/','',$js);
		
			// Normalize whitespace
			// $js = preg_replace( '/\s+/', ' ', $js );
		
			// Remove spaces before and after comment
			// $js = preg_replace( '/(\s+)(\/\*(.*?)\*\/)(\s+)/', '$2', $js );

			// Remove comment blocks, everything between /* and */, unless
			// preserved with /*! ... */ or /** ... */
			// $js = preg_replace( '~/\*(?![\!|\*])(.*?)\*/~', '', $js );

			// Remove ; before }
			// $js = preg_replace( '/;(?=\s*})/', '', $js );

			// Remove space after , : ; { } */ >
			// $js = preg_replace( '/(,|:|;|\{|}|\*\/|>) /', '$1', $js );

			// Remove space before , ; { } ( ) >
			// $js = preg_replace( '/ (,|;|\{|}|\(|\)|>)/', '$1', $js );


			  // minify
			// $js = preg_replace('/^\s+|\n|\r|\s+$/m', '', $js);  
			
			 // $js = (preg_match('/^\\/@(?:cc_on|if|elif|else|end)\\b/', $js));

			return trim( $js );
	}
	
	function simple_minify_css( $css ) {		//<--- this function helps to minify  CSS the given string file
			// Normalize whitespace
			$css = preg_replace( '/\s+/', ' ', $css );

			// Remove spaces before and after comment
			$css = preg_replace( '/(\s+)(\/\*(.*?)\*\/)(\s+)/', '$2', $css );

			// Remove comment blocks, everything between /* and */, unless
			// preserved with /*! ... */ or /** ... */
			$css = preg_replace( '~/\*(?![\!|\*])(.*?)\*/~', '', $css );

			// Remove ; before }
			$css = preg_replace( '/;(?=\s*})/', '', $css );

			// Remove space after , : ; { } */ >
			$css = preg_replace( '/(,|:|;|\{|}|\*\/|>) /', '$1', $css );

			// Remove space before , ; { } ( ) >
			$css = preg_replace( '/ (,|;|\{|}|\(|\)|>)/', '$1', $css );

			// Strips leading 0 on decimal values (converts 0.5px into .5px)
			$css = preg_replace( '/(:| )0\.([0-9]+)(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}.${2}${3}', $css );

			// Strips units if value is 0 (converts 0px to 0)
			$css = preg_replace( '/(:| )(\.?)0(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}0', $css );

			// Converts all zeros value into short-hand
			$css = preg_replace( '/0 0 0 0/', '0', $css );

			// Shortern 6-character hex color codes to 3-character where possible
			$css = preg_replace( '/#([a-f0-9])\\1([a-f0-9])\\2([a-f0-9])\\3/i', '#\1\2\3', $css );

			return trim( $css );
	}
	
	function nl2br_reverse($str){		//<---this function is used to create nice/safe URL
			// return rawurlencode($your_string);
			$html = 'this <br>is<br/>some<br />text <br    />!';
			$html = preg_replace('#<br\s*/?>#i', " ", $html);
			return  ucwords($html);
	}

	function sitemapper ($url_product='', $displaydate=''){	//<---this function helps to create sitemepa
		echo "  
				<url> 
				<loc>".$url_product."</loc>  
				<lastmod>".$displaydate."</lastmod>  
				<changefreq>daily</changefreq>  
				<priority>0.8</priority>  
				</url>  
				";  


	}
 
	function showShortNumber($n, $precision = 2) {		//<---this function helps to show the number in short format
		if ($n < 1000000) {
			// Anything less than a million
			$n_format = number_format($n);
		} else if ($n < 1000000000) {
			// Anything less than a billion
			$n_format = number_format($n / 1000000, $precision) . 'M';
		} else {
			// At least a billion
			$n_format = number_format($n / 1000000000, $precision) . 'B';
		}

		return $n_format;
	}

	function startsWith($haystack, $needle){
		 return (substr($haystack, 0, strlen($needle)) === $needle);
	}

	function endsWith($haystack, $needle){
		return substr($haystack, -strlen($needle))===$needle;
	}
	//<-----this function is used tofind the closest value from array
	function closest_array_High_val($array, $number) {
		sort($array);
		foreach ($array as $a) {
			if ($a >= $number) return $a;
		}
		return end($array); // or return NULL;
	}

	//<-----this function is used do simple ENCODE
	function simple_encode($string='') {
		 return strtr(base64_encode($string), '+/=', '-_~');// or return NULL;
	}

	//<-----this function is used do simple DECODE
	function simple_decode($string='') {
		 return base64_decode(strtr($string, '-_~', '+/=')); // or return NULL;
	}

	//<-----this function is used to cremove special charetor
	function name_filter($String){
		return trim(preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $String));
	}

	//<-----this function is used to convert the time period into human readable format
	function FormateTimePeriod($seconds_input){
	  $time = date("d:H:i",$seconds_input);
		$timer_arr=explode(":",$time);
			$day =$timer_arr[0]-1;
			$hour =$timer_arr[1]-1;
			$minute =$timer_arr[2];
		// return $time;
		if($day>0){ 
			return $day." day";
		}else if($hour>0 || $minute>0){
			return (($hour>0 ? ($hour."hr : ") : "" ).$minute."min");
		}
	}

	//<----this function to help find the final date for the rapeated event adjustment
	function FindTheRepeatedDay_END($defaulter2='', $start_time="", $end_time=""){
							//<---this section calculates the repeate day starts
		$reap_day= (date("d", $start_time));
		$reap_month= ((date("m", $start_time)));
		$reap_year= ((date("y", $start_time)));
		$reap_hour=(date("H", $end_time));
		$reap_minu=(date("i", $end_time));
							//<---this section calculates the repeate modes
			$repeater_data_array=explode(',',$defaulter2);
			$repeated_mode=$repeater_data_array[0];
			$repeated_never=$repeater_data_array[1];
			$repeated_occu=$repeater_data_array[2];
			$repeated_end_date=$repeater_data_array[3];
			if($repeated_mode==0){
					$repeated_occu=0;
					$repeated_end_date=0;
				}
				if($defaulter2==',,,'){
					$repeat_interval=1;
					$repeat_interval_2=7;
					$year1=(date("Y", $end_time));
					$month1=(date("m", $end_time));
					$day1=(date("d", $end_time));
				$startDate= date("Y-m-d 00:00:00", $start_time);	//<-- this is for repeat start time
			}else if($defaulter2!=',,,'){
					if($repeated_mode==0){			 //<------sets the non option to create repeater value	
							$year1=$reap_year;
							$month1=$reap_month;
							$day1=$reap_day;
							$repeat_interval=1;  //-->>one day
							$repeat_interval_2=7;
							$startDate= date("Y-m-d 00:00:00", $start_time);	//<-- this is for repeat start time
					}
					else if($repeated_mode==1){		 //<------sets the daily reapeater to create repeater value	
							if(!empty($repeated_occu) && empty($repeated_never) && empty($repeated_end_date)){	
																								//<---repeated ends after number of occurence
								$year1=$reap_year;
								$month1=$reap_month;
								$day1=$reap_day+($repeated_occu);
							}else if(!empty($repeated_end_date) && empty($repeated_never) && empty($repeated_occu)){
																								//<---repeated ends after BY DATE
								$year1=date("Y",strtotime($repeated_end_date));
								$month1=date("m",strtotime($repeated_end_date));
								$day1=date("d",strtotime($repeated_end_date));
							}else{																//<---repeated with never ends to value
									$year1=2037;
									$month1=12;
									$day1=$reap_day;
									$never_end_mode=1;
							}
						$repeat_interval=1;  //-->>one day
						$repeat_interval_2=7;
						$startDate= date("Y-m-d 00:00:00", $start_time);	//<-- this is for repeat start time
					}
					else if($repeated_mode==2){				 //<------sets the weekly reapeater to create repeater value	
							if(!empty($repeated_occu) && empty($repeated_never) && empty($repeated_end_date)){
																								//<---repeated ends after number of occurence
								$year1=$reap_year;
								$month1=$reap_month;
								$day1=$reap_day+($repeated_occu*7);
								
							}else if(!empty($repeated_end_date) && empty($repeated_never) && empty($repeated_occu)){
																								//<---repeated ends after a date of occurence
								$year1=date("Y",strtotime($repeated_end_date));
								$month1=date("m",strtotime($repeated_end_date));
								$day1=date("d",strtotime($repeated_end_date));
							}else{ 															//<---repeated with neve ends to value
									$year1=2037;
									$month1=12;
									$day1=$reap_day;
									$never_end_mode=1;
							}
						$repeat_interval=7;  //-->>one week
						$repeat_interval_2=7;
						$startDate= date("Y-m-d 00:00:00", $start_time);	//<-- this is for repeat start time
					}
					else if($repeated_mode==3 || $repeated_mode==4 ){				 //<------sets the ALL WEEK DAYS REAPEATER 	
							if(!empty($repeated_occu) && empty($repeated_never) && empty($repeated_end_date)){
																								//<---repeated ends after number of occurence
								$year1=$reap_year;
								$month1=$reap_month;
								$day1=$reap_day+($repeated_occu*7);
								
							}else if(!empty($repeated_end_date) && empty($repeated_never) && empty($repeated_occu)){
																								//<---repeated ends after a date of occurence
								$year1=date("Y",strtotime($repeated_end_date));
								$month1=date("m",strtotime($repeated_end_date));
								$day1=date("d",strtotime($repeated_end_date));
							}else{ 															//<---repeated with neve ends to value
									$year1=2037;
									$month1=12;
									$day1=$reap_day;
									$never_end_mode=1;
							}
						$repeat_interval=1;  //-->>daily
						$repeat_interval_2= $repeated_mode==3 ? 5 : 2;
						if($repeated_mode==3){
							$startDate = (date("D", $start_time) === "Mon") ? date("Y-m-d 00:00:00",  $start_time)
														: date("Y-m-d 00:00:00", strtotime('next monday', $start_time));
						}else{
							$startDate = (date("D", $start_time) === "Sat") ? date("Y-m-d 00:00:00",  $start_time)
														: date("Y-m-d 00:00:00", strtotime('next saturday', $start_time));
						}
					}
					else{
								$year1=$reap_year;
								$month1=$reap_month;
								$day1=$reap_day+1;
								$repeated_mode=0;
								$repeat_interval=1;  //-->>one day
								$repeat_interval_2=7;
						$startDate= date("Y-m-d 00:00:00", $start_time);	//<-- this is for repeat start time
					}
			}else{  //<------sets the default repeater value		
					$repeat_interval=1;
					$year1=(date("Y", $end_time));
					$month1=(date("m", $end_time));
					$day1=(date("d", $end_time));
				$startDate= date("Y-m-d 00:00:00", $start_time);	//<-- this is for repeat start time
			}
				$endDate=date("Y-m-d 00:00:00",mktime($reap_hour, $reap_minu,0,$month1,$day1,$year1)) ;
				
		 $new_end_date = $end_time>=strtotime(FINAL_DATE) ?  date("Y-m-d 00:00:00",strtotime(FINAL_DATE)) : $endDate; 
				
		return array( 	"repeater"=>$repeat_interval,
						"repeater_2"=>$repeat_interval_2,
						"mode" => $repeated_mode,
						"startDate" => $startDate,
						"endDate" => $new_end_date
						);
	}

		//<----this function to help creating compressed string data
	function get_text_compressed($text='', $checker=true){
			// return ($checker) ?  gzcompress($text) :  gzuncompress($text) ;
			return ($checker) ?  ($text) :  ($text) ;
	}

		//<----this function to help remove lines string data
	function filter_string($text=''){
			$text = preg_replace("/[\r\n]+/", "\n", $text);
			// $text = nl2br($text);
			return ($text);
	}

	//<>-----function for Compare two array values
	function compareDates($array1, $array2)	{
		$different_checker=array(); $i=0;
		$datedif_array=array(7=>1, 6=>2, 5=>3, 4=>4, 3=>5, 2=>6, 1=> 7);
		  	foreach ($array1 as $key => $date_each){
				$date_cur = new DateTime($date_each);
				$date_cur_prev = new DateTime($array2[$key]) ;
					$diff = $date_cur_prev->diff($date_cur)->format("%a");
					if($diff>=1){
						$different_checker[$i]= $diff >7 ? 0 : $diff+1;
					}
					 $i++;
			}
			$data=count($different_checker)==1 ? false : array_unique($different_checker);
			return count($data)==1 ? $data[0] : 0;
	}

		//<>-----function for Compare two array values
	function time_remain($date_in_milli="", $day_from=NULL){
			$datetime2 =empty($day_from) ?  new DateTime() :new DateTime(date("Y-m-d H:i:s", $day_from));
			$date_in_milli = is_numeric($date_in_milli) ? $date_in_milli : strtotime($date_in_milli);
			$datetime1 = new DateTime(date("Y-m-d H:i:s", $date_in_milli));
			$interval = $datetime2->diff($datetime1);
				$all_date =$interval->format("%R/%d/%h/%i/%s");
				$all_date_arr=explode("/",$all_date);
				
				$check =isset($all_date_arr[0]) ? $all_date_arr[0] : 0;
				$date =isset($all_date_arr[1]) ? (int) $all_date_arr[1] : 0;
				$hour =isset($all_date_arr[2]) ? $all_date_arr[2] : 0;
				$minut =isset($all_date_arr[3]) ? $all_date_arr[3] : 0;
				$second =isset($all_date_arr[4]) ? $all_date_arr[4] : 0;
				if($check=="-" && $date>0){
						return "expired";
				}else if(strtotime(FINAL_DATE)<=$date_in_milli){
						return "No expiry";
				}else{
					if(!empty($date)){
						return $date. " days";
					}else if(!empty($hour)){
						return $hour. " hrs";
					}else if(!empty($minut)){
						return $minut." min";
					}else{
						return "expired";
					}
				}
	}

	//<----this function to help creating time ago creation
	function validate_alphanumeric_underscore($str){
		return preg_match('/^[a-z0-9_-]+$/',$str);
	}

	//<----this function to help creating time ago creation
	function time_ago($time_in){
		$time_in = is_numeric($time_in) ?  $time_in :  strtotime($time_in) ; 
		$m = time()-$time_in; $o='just now';
		$t = array('year'=>31556926,'month'=>2629744,'week'=>604800,'day'=>86400,'hour'=>3600,'minute'=>60,'second'=>1);
		foreach($t as $u=>$s){
			if($s<=$m){$v=floor($m/$s); $o="$v $u".($v==1?'':'s').' ago'; break;}
		}
		return $o;
	}
	//<----this function to help creating time ago creation
	function time_ago_short($time_in){
		$time_in = is_numeric($time_in) ?  $time_in :  strtotime($time_in) ; 
		$m = time()-$time_in; $o='just now';
		$t = array('year'=>31556926,'month'=>2629744,'week'=>604800,'day'=>86400,'hour'=>3600,'minute'=>60,'second'=>1);
		foreach($t as $u=>$s){
			if($s<=$m){$v=floor($m/$s); $o="$v $u".($v==1?'':'s'); break;}
		}
		return $o;
	}

	//<----this function to find fullday_name
	function fullday_name($day_name_in=""){
		
			$day_name = ($day_name_in=="sun" ? "sunday" : ($day_name_in=="mon" ? "monday" : ($day_name_in=="tue" ? "tuesday" : 
						($day_name_in=="wed" ? "wednesday" : ($day_name_in=="thu" ? "thursday" : ($day_name_in=="fri" ? "friday" : 
							($day_name_in=="sat" ? "saturday" : ""	)	) ) )) )) ;
		return $day_name;
	}

//  Function to calculate remaining days, hours, minutes and seconds between current date and end date..

//  Returns the remaining time .. in days or hrs or mins or secs...

//#######################################################

	function CalRemTime($ending_time){
		$ending_time = is_numeric($ending_time) ? $ending_time : strtotime($ending_time);
		$ClosingDay=date("d",$ending_time);
		$ClosingMonth=date("m",$ending_time);
		$ClosingYear=date("Y",$ending_time);
		
			$ClosingTime = mktime(24,60,60,$ClosingMonth,$ClosingDay,$ClosingYear);

			$TimeDifference = $ClosingTime - time();

			$RemainingDays = ($TimeDifference - ($TimeDifference % 86400)) / 86400;

			$TimeDifference = $TimeDifference - ($RemainingDays * 86400);

			$RemainingHours = ($TimeDifference - ($TimeDifference % 3600)) / 3600;

			$TimeDifference = $TimeDifference - ($RemainingHours * 3600);

			$RemainingMinutes = ($TimeDifference - ($TimeDifference % 60)) / 60;

			$TimeDifference = $TimeDifference - ($RemainingMinutes * 60);

			$RemainingSeconds = ($TimeDifference - ($TimeDifference % 1)) / 1;

			if($RemainingDays>0){
					$sValue = "$RemainingDays days";
					return $sValue;
			}else{
					if($RemainingHours>0){
							$sValue = "$RemainingHours hrs:$RemainingMinutes mins:$RemainingSeconds Secs";
							return $sValue;
					}else{
							if($RemainingMinutes>0){
									$sValue = "$RemainingMinutes mins:$RemainingSeconds Secs";
									return $sValue;
							}else{
									if($RemainingSeconds>0){
											$sValue = "$RemainingSeconds secs";
											return $sValue;
									}else{
											$sValue = "Bidding time is over...";
											return $sValue;
									}

							}

					}

			}

	}

	function hex2rgb($hex) {	//<----- this function converts the color from Hexadecimal value to RGB format 
	   $hex = str_replace("#", "", $hex);

	   if(strlen($hex) == 3) {
		  $r = hexdec(substr($hex,0,1).substr($hex,0,1));
		  $g = hexdec(substr($hex,1,1).substr($hex,1,1));
		  $b = hexdec(substr($hex,2,1).substr($hex,2,1));
	   } else {
		  $r = hexdec(substr($hex,0,2));
		  $g = hexdec(substr($hex,2,2));
		  $b = hexdec(substr($hex,4,2));
	   }
	   $rgb = array($r, $g, $b);
	   //return implode(",", $rgb); // returns the rgb values separated by commas
	   return $rgb; // returns an array with the rgb values
	}
	
	function rgb2hex($rgb) {		//<----- this function converts the color from RGB format to Hexadecimal value
	   $hex = "#";
	   $hex .= str_pad(dechex($rgb[0]), 2, "0", STR_PAD_LEFT);
	   $hex .= str_pad(dechex($rgb[1]), 2, "0", STR_PAD_LEFT);
	   $hex .= str_pad(dechex($rgb[2]), 2, "0", STR_PAD_LEFT);

	   return $hex; // returns the hex value including the number sign (#)
	}
	
	function getcolorname($mycolor) {	//<---this function converts the rgb format color array into human readable color
		// mycolor should be a 3 element array with the r,g,b values 
		// as ints between 0 and 255. 
		$mycolor=array(255,0,0);
		$colors = array(
			"red"       =>array(255,0,0),
			"yellow"    =>array(255,255,0),
			"green"     =>array(0,255,0),
			"cyan"      =>array(0,255,255),
			"blue"      =>array(0,0,255),
			"orange"   =>array(255,165,0),
			"white"     =>array(255,255,255),
			"black"     =>array(0,0,0)
		);

		$tmpdist = 255*3;
		$tmpname = "none";
		foreach($colors as $colorname => $colorset) {        
			echo $r_dist = (pow($mycolor[0],2) - pow($colorset[0],2));
			echo $g_dist = (pow($mycolor[1],2) - pow($colorset[1],2));       
			echo $b_dist = (pow($mycolor[2],2) - pow($colorset[2],2));
			$totaldist = sqrt($r_dist + $g_dist + $b_dist);
			if ($totaldist < $tmpdist) {        
				$tmpname = $colorname;
				$tmpdist = $totaldist;
				break;
			}
		}
		return $tmpname;
	}

	//<----this function to help creating Address in required format
	function address_to_create($adress_array){
		$divider='!';
		$address='';
		$i=1;
		$arr_count=count($adress_array);
			foreach($adress_array as $adress_each){
				$address= !empty($adress_each) 	 ? $address.'|'.$i.'|'.$adress_each      : $address.'|'.$i.'|' ;
				$i++;
				if($arr_count>=$i)
				$address=$address.$divider;
			}
			return $address;
	}	

		//<----this function to help Output/Print Address in required format
	function address_to_print($address){
			$oneline_addres ='';
			$all_line =''; 	$i=0;
			$final_addres_array =array();
			// print_r($address);
		if(( !empty($address))){
				$final_addres_arra = array_filter($address);
				$final_addres_arra = array_unique($final_addres_arra);

				foreach($address as $key=>$value){
				  if($i==0){
					$all_line=  !empty($value) ? $value : $all_line;
				  }else{
					$all_line=  !empty($value) ? $all_line.',<br/>'.$value : $all_line;
				   }
				   $i++;
				}
			$oneline_addres = implode(",",$final_addres_arra);
		$final_addres_array['full_line'] = $oneline_addres;		//<---this adds full single address with commas
		$final_addres_array['all_line'] = $all_line;		//<---this adds full single address with commas
		$final_addres_array['total'] = count($final_addres_arra);		//<----this give the total number of fields
		}
		return $final_addres_array;		//<---return as array
	}
	
			//<----this function to help  wrapp each words witha string
	function letter_wrapper($text='', $length=15, $str=' '){
		return $text;
	}	
	    
			//<----this function find and return the image path alone
	function return_image($img_path, $path_alter, $base_url){
		$path_doc = $path_alter!='' ? SITE_ROOT."public/uploads/".$path_alter."/" : SITE_ROOT."public/uploads/" ;
		$path_url = $path_alter!='' ? $base_url."uploads/".$path_alter."/" : $base_url."uploads/" ;
		if (file_exists ($path_doc.$img_path)){
			return $path_url.$img_path;
		}else{
			return $path_url."default.jpg";
		}

	}	
	  	    
			//<----this function find and return the image path alone
	function return_image_withByPhot_rel($image_path, $image_section, $image_name, $base_url){
				$img_path_real1 = $image_path.'/'. $image_section.'/'.$image_name ;
				$img_path_real2 = $image_path.'/'.$image_name ;
		if (file_exists (SITE_ROOT."public/uploads/".$img_path_real1)){
			$new_path= $base_url."uploads/".$img_path_real1;
		}else if (file_exists (SITE_ROOT."public/uploads/".$img_path_real2)){
			$new_path= $base_url."uploads/".$img_path_real2;
		}else{
				$new_path= $base_url."uploads/default.jpg";
		}
			return $new_path;
		
	}	
			//<----this function find and return the image path withe the anchor link and class

	function return_image_forBackground($img_path,    $base_url=BASE_URL){
		if($img_path){
			if (file_exists (SITE_ROOT."public/uploads/events/background/".$img_path)){
				$new_path= $base_url."uploads/events/background/".$img_path;
			}else if (file_exists (SITE_ROOT."public/uploads/companypage/background/".$img_path)){
				$new_path= $base_url."uploads/companypage/background/".$img_path;
			}else if (file_exists (SITE_ROOT."public/uploads/cloudpage/background/".$img_path)){
				$new_path= $base_url."uploads/cloudpage/background/".$img_path;
			}else if (file_exists (SITE_ROOT."public/uploads/product/background/".$img_path)){
				$new_path= $base_url."uploads/product/background/".$img_path;
			}else{
		$new_path= $base_url."uploads/background_default.jpg";
			}	
		}else{
			$new_path= $base_url."uploads/background_default.jpg";
		}
		return $new_path;
	}
	
	function return_image_forProducts_dshBoard_by_id($img_id,   $base_url=BASE_URL ,$user_upload){
		$img_path=$user_upload::Get_Upload_Image_NAME($img_id);
		$img_path= $img_path ? $img_path : "default.jpg";
		if (file_exists (SITE_ROOT."public/uploads/product/products/".$img_path)){
			$new_path= $base_url."uploads/product/products/".$img_path;
		}else if (file_exists (SITE_ROOT."public/uploads/dashboard/".$img_path)){
			$new_path= $base_url."uploads/dashboard/".$img_path;
		}else{
				$new_path= $base_url."uploads/dashboard/default.png";
		}
		return $new_path;
	}	
		
	function return_image_forProducts_Promotion_by_id($img_id, $base_url=BASE_URL ,$user_upload){
		$img_path=$user_upload::Get_Upload_Image_NAME($img_id);
		$img_path= $img_path ? $img_path : "default.jpg";
		if (file_exists (SITE_ROOT."public/uploads/promotion/promotion/".$img_path)){
			$new_path= $base_url."uploads/promotion/promotion/".$img_path;
		}else{
				$new_path= $base_url."uploads/dashboard/default.png";
		}
		return $new_path;
	}	
	
	function return_image_forProducts($img_path,    $base_url=BASE_URL){
		if(strlen($img_path)>5){
			if (file_exists (SITE_ROOT."public/uploads/product/products/".$img_path)){
				$new_path= $base_url."uploads/product/products/".$img_path;
			}else if (file_exists (SITE_ROOT."public/uploads/dashboard/".$img_path)){
				$new_path= $base_url."uploads/dashboard/".$img_path;
			}else{
					$new_path= $base_url."uploads/product_default.png";
			}
		}else{
				$new_path= $base_url."uploads/product_default.png";
		}
		return $new_path;
	}	
	 			//<----this function find and return the image path withe the anchor link and class

	function return_image_forPageHeader($img_path,    $base_url=BASE_URL){
		if(strlen($img_path)>5){
			if (file_exists (SITE_ROOT."public/uploads/events/page_header/".$img_path)){
				$new_path= $base_url."uploads/events/page_header/".$img_path;
			}else if (file_exists (SITE_ROOT."public/uploads/companypage/page_header/".$img_path)){
				$new_path= $base_url."uploads/companypage/page_header/".$img_path;
			}else if (file_exists (SITE_ROOT."public/uploads/cloudpage/page_header/".$img_path)){
				$new_path= $base_url."uploads/cloudpage/page_header/".$img_path;
			}else if (file_exists (SITE_ROOT."public/uploads/product/page_header/".$img_path)){
				$new_path= $base_url."uploads/product/page_header/".$img_path;
			}else{
					$new_path= $base_url."uploads/page_head_default.jpg";
			}
		}else{
				$new_path= $base_url."uploads/page_head_default.jpg";
		}
		return $new_path;
	}	
	 			//<----this function find and return the image path withe the anchor link and class

	function return_image_forEvents_logo($img_path="default.jpg"){
		if(strlen($img_path)>5){
			if (file_exists (SITE_ROOT."public/uploads/events/logo/".$img_path)){
				$new_path= BASE_URL."uploads/events/logo/".$img_path;
			}else if (file_exists (SITE_ROOT."public/uploads/companypage/logo/".$img_path)){
				$new_path= BASE_URL."uploads/companypage/logo/".$img_path;
			}else if (file_exists (SITE_ROOT."public/uploads/cloudpage/logo/".$img_path)){
				$new_path= BASE_URL."uploads/cloudpage/logo/".$img_path;
			}else if (file_exists (SITE_ROOT."public/uploads/product/logo/".$img_path)){
				$new_path= BASE_URL."uploads/product/logo/".$img_path;
			}else{
					$new_path= BASE_URL."uploads/default.jpg";
			}
		}else{
				$new_path= BASE_URL."uploads/default.jpg";
		}
		return $new_path;
	}	
	 
	function return_image_forUser_profile($img_path){
		if(strlen($img_path)>5){
			if (file_exists (SITE_ROOT."public/uploads/user_profile/".$img_path)){
				$new_path= BASE_URL."uploads/user_profile/".$img_path;
			}else{
					$new_path= BASE_URL."uploads/user_profile/default.jpg";
			}
		}else{
				$new_path= BASE_URL."uploads/default.jpg";
		}
		return $new_path;
	}		 
	function return_image_forPromotion($img_path){
		if(strlen($img_path)>5){
			if (file_exists (SITE_ROOT."public/uploads/promotion/promotion/".$img_path)){
				$new_path= BASE_URL."uploads/promotion/promotion/".$img_path;
			}else{
					$new_path= BASE_URL."uploads/promotion/promotion/default.jpg";
			}
		}else{
				$new_path= BASE_URL."uploads/default.jpg";
		}
		return $new_path;
	}	
	 
			//<>----this function find and return the image path withe the anchor link and with specified class 
	function load_calender_Uploads_appoi_edit($img_path, $base_document='', $base_url){
		if (file_exists ($base_document."uploads/".$img_path)){
			echo "<img src='{$base_url}uploads/".$img_path."' id='eventPhoto' class='f-left'/>";
		}else if (file_exists ($base_document."uploads/dashboard/".$img_path)){
			echo "<img src='{$base_url}uploads/dashboard/".$img_path."' id='eventPhoto' class='f-left'/>";
		}else if (file_exists ($base_document."uploads/background_img/".$img_path)){
			echo "<img src='{$base_url}uploads/background_img/".$img_path."' id='eventPhoto' class='f-left'/>";
		}else if (file_exists ($base_document."uploads/cloudpage/".$img_path)){
			echo "<img src='{$base_url}uploads/cloudpage/".$img_path."' id='eventPhoto' class='f-left'/>";
		}else if (file_exists ($base_document."uploads/company/".$img_path)){
			echo "<img src='{$base_url}uploads/company/".$img_path."' id='eventPhoto' class='f-left'/>";
		}else if (file_exists ($base_document."uploads/logo_img/".$img_path)){
			echo "<img src='{$base_url}uploads/logo_img/".$img_path."' id='eventPhoto' class='f-left'/>";
		}else{
		echo "<img src='{$base_url}uploads/user_profile/".$img_path."' id='eventPhoto' class='f-left'/>";
		}

	}		
	
	function load_photos_for_product_Uploads($img_path, $base_url){
		// echo dirname(__FILE__)."/../public/uploads/".$img_path;
		if (file_exists (dirname(__FILE__)."/../public/uploads/".$img_path)){

		echo '<div class="img_holder" rel="'.$img_path.'">
					<img src="'.$base_url.'uploads/'.$img_path.'"  class="imag_in" id="'.$img_path.'"/>
						<div class="img_close" title="Remove this"></div>
				</div>';
		
		}else{
							// echo "<a href='{$base_url}uploads/user_profile/".$img_path."' rel='facebox'>
									// <img src='{$base_url}uploads/user_profile/".$img_path."' class='imgpreview' /></a>";

			echo '<div class="img_holder" rel="'.$img_path.'">
					<img src="'.$base_url.'uploads/user_profile/'.$img_path.'"  class="imag_in" id="'.$img_path.'"/>
						<div class="img_close" title="Remove this"></div>
				</div>';		
		}

	}	
	 
			//<>----this function find and return the image path withe the anchor link and with specified class 
	function load_user_uploads($img_path='', $base_document='', $base_url, $root=''){
			if (file_exists ($base_document."uploads/".$root.$img_path)){
				echo "<a href='{$base_url}uploads/".$root.$img_path."' >
					<img src='{$base_url}uploads/".$root.$img_path."' class='profile_img_preview' /></a>";
		}else{ echo "<a href='{$base_url}uploads/user_profile/default.jpg' >
				<img src='{$base_url}uploads/user_profile/default.jpg' class='profile_img_preview' /></a>";
		}
	}	
	 
			//<>----this function find and return the image path withe the anchor link and with specified class 
	function Getting_Image_path_alone($img_path, $base_document='', $base_url){

		if (file_exists ($base_document."uploads/".$img_path)){
						return $base_url."uploads/".$img_path;
		}else if (file_exists ($base_document."uploads/dashboard/".$img_path)){
						return $base_url."uploads/dashboard/".$img_path;
		}else if (file_exists ($base_document."uploads/background_img/".$img_path)){
						return $base_url."uploads/background_img/".$img_path;
		}else if (file_exists ($base_document."uploads/cloudpage/".$img_path)){
						return $base_url."uploads/cloudpage/".$img_path;
		}else if (file_exists ($base_document."uploads/company/".$img_path)){
						return $base_url."uploads/company/".$img_path;
		}else if (file_exists ($base_document."uploads/logo_img/".$img_path)){
						return $base_url."uploads/logo_img/".$img_path;
		}else if (file_exists ($base_document."uploads/user_profile/".$img_path)){
						return $base_url."uploads/user_profile/".$img_path;
		}else{
						return false;
		}

	}	
	  	
			//<>----this function find return week number from given timestamp 
	function getWeeks($timestamp){
		 $maxday    = date("t",$timestamp);
			$thismonth = getdate($timestamp);
			$timeStamp = mktime(0,0,0,$thismonth['mon'],1,$thismonth['year']);    //Create time stamp of the first day from the give date.
			$startday  = date('w',$timeStamp);    //get first day of the given month
			$day = $thismonth['mday'];
			$weeks = 0;
			$week_num = 0;
			for ($i=0; $i<($maxday+$startday); $i++) {
					if(($i % 7) == 0){
						$weeks++;
					}
					if($day == ($i - $startday + 1)){
						$week_num = $weeks;
					}
			}     
		return $week_num;
    }
	
			//<>----this function helps to remove the unwanted query fron url
	function removeqsvar($url, $_tobe_removed) {
		$returns = "";
		$new_url = explode('?', $url);
		$new_url=$new_url[0];
			if(!empty($url)){
				parse_str( parse_url( $url, PHP_URL_QUERY ), $my_array_of_vars );
				// foreach ($_tobe_removed_arr as $_tobe_removed){
					if (array_key_exists($_tobe_removed, $my_array_of_vars)) {
						unset($my_array_of_vars[$_tobe_removed]);
					}
				// }
			}
			foreach ($my_array_of_vars as $key =>$value){
				$returns.=  !empty($returns) ? '&'.$key.'='.$value : $key.'='.$value;
			}	
			// echo $returns;
			$seperator = !empty($returns) ? '?' :"";
			$returns= $new_url.$seperator.$returns;
			return $returns;
	}

	
		//<>----this function helps to create query fron url array
	function createQuery_array($url) {
		$returns = "";
		$new_url = explode('?', $url);
		$new_url=$new_url[0].'?';
			if(!empty($url)){
				parse_str( parse_url( $url, PHP_URL_QUERY ), $my_array_of_vars );
				
			}
	
			return $my_array_of_vars;
	}

	function Filter_the_Getval($url, $_tobe_removed, $checker=false) {
		$returns = "";
		$new_url = explode('?', $url);
		$new_url=$new_url[0].'?';
			if(!empty($url)){
				parse_str( parse_url( $url, PHP_URL_QUERY ), $my_array_of_vars );
				// foreach ($_tobe_removed_arr as $_tobe_removed){
					if (array_key_exists($_tobe_removed, $my_array_of_vars)) {
						$returns = $my_array_of_vars[$_tobe_removed];
					}
				// }
			}
			if($checker){
				$filterer = array("http://", "https://", "\\");
				$returns = str_replace($filterer, "", $returns);
			}
		return (strpos($returns,'logout') !== false) ? str_replace("logout", "", $returns) : $returns; 
	}
	
	function get_page_direct_to($url) {
		$page_url_array= unserialize (PAGE_URL_LIST);		//<----retrieve the array contant value to variable array
			$new_url = explode('/', $url);
			$new_url= count($new_url)>1 ? $new_url[1] : 'index';
			$new_url=empty($new_url) ? 'index' : $new_url;
			$new_url=in_array($new_url, $page_url_array) ?  $new_url : 'null';

		return $new_url; 
	}
	
			//<>----this function find  scrolling map boundary
	function latlonBoundryAdjst($lat, $lon) {

		$locX1 =($lat)-(0.00562);
		$locX2 =($lat)+(0.00329);
		$locY1 =($lon)-(0.00824);
		$locY2 =($lon)+(0.00792);
		$locX1=(round($locX1*10000)/10000);
		$locX2=(round($locX2*10000)/10000);
		$locY1=(round($locY1*10000)/10000);
		$locY2=(round($locY2*10000)/10000);
		
		$zoomBoundri=$locY1.','.$locX1.','.$locY2.','.$locX2;
		return $zoomBoundri;
	}

			//<>----this function to strip zero from date
	function strip_zeros_from_date( $marked_string="" ) {
		  // first remove the marked zeros
		  $no_zeros = str_replace('*0', '', $marked_string);
		  // then remove any remaining marks
		  $cleaned_string = str_replace('*', '', $no_zeros);
			return $cleaned_string;
	}

		// function for redirecting locations pages
	function redirect_to( $location = NULL, $check=true) {
		
		$current_loc ="$_SERVER[REQUEST_SCHEME]://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$current_loc=removeqsvar($current_loc, 'ref');
		// $separator = (parse_url($current_loc, PHP_URL_QUERY) == NULL) ? '?' : '&';
		$separator =  (strpos($current_loc,'?') === false) ? '?' : '&';
		$ref ="ref=".$current_loc;
		$ref = 	startsWith($current_loc, BASE_URL.'logout') ? "" : $ref;
		$ref = 	startsWith($location, BASE_URL.'login') ? $ref : "" ;
		// echo	$ref;
		// echo '<br />';
		echo $location;
		  if ($location != NULL) {
			$current_loc = $ref ? $location .$separator.$ref  : $location;
					header("Location: {$current_loc}");
					exit;
		  }
	}

				//<>----this function to help array sorting
	function arraysort_by_created($a, $b){
		return strcmp($a["created"], $b["created"]);
	}

		//<>----- finding the day number from the day in words
	function dayName($dayNmae=''){
			//checking each given name in switch case 
			switch ($dayNmae) {
				case "Sun":
				   return 0;
					break;
				case "Mon":
				   return 1;
					break;
				case "Tue":
				   return 2;
					break;
				case "Wed":
				   return 3;
					break;
				case "Thu":
				   return 4;
					break;
				case "Fri":
				   return 5;
					break;
				case "Sat":
				   return 6;
					break;
				} 
	}

		//function for outputting the messages
	function output_message($message="") {
			  if (!empty($message)) { 
				return "<p class=\"message\">{$message}</p>";
			  } else {
				return "";
			  }
	}

		//function for autoloading each classes from class name
	function MyAutoload($className){
	// function __autoload($class_name) {
	// echo $className.'<br/>';
		$className = (strpos($className,'Zend') !== false) ? $className : strtolower($className);
		$ext = ".php";
		$extensions = array(".php", ".class.php", ".inc");
			$paths = explode(PATH_SEPARATOR, get_include_path());
			// print_R($paths);
			// echo '<br/>';
				foreach ($paths as $path) {
				    $filename = $path . DIRECTORY_SEPARATOR . $className;
				   // echo $filename;
					// echo '<br/>';
					// foreach ($extensions as $ext) {
						$filename=str_replace('\\', "/", $filename);
				   // echo $filename . $ext;
					// echo '<br/>';
						if (is_readable($filename . $ext)) {
							require_once ($filename . $ext);
							break;
					   }
				   // }
				}
				
	}
		//function for autoloading each classes from class name


	// Auto-loading classes declare here
	spl_autoload_register('MyAutoload');

	//<>----- this function to load layouts auto wrapper
	function include_layout_template($template="") {
		include(SITE_ROOT.DS.'public'.DS.'layouts'.DS.$template);
	}

		//<>----- this function helps to find the latitude and longitude 
	function getLatLng($opts) {
		/* grab the XML */
		$url = 'http://maps.googleapis.com/maps/api/geocode/xml?'
				. 'address=' . $opts['address'] . '&sensor=' . $opts['sensor'];

		$dom = new DomDocument();
		$dom->load($url);  
		/* A response containing the result */
			$response = array();

		$xpath = new DomXPath($dom);
		$statusCode = $xpath->query("//status");

				/* ensure a valid StatusCode was returned before comparing */
		if ($statusCode != false && $statusCode->length > 0
						&& $statusCode->item(0)->nodeValue == "OK") {

				$latDom = $xpath->query("//location/lat");
				$lonDom = $xpath->query("//location/lng");
				$addressDom = $xpath->query("//formatted_address");

			/* if there's a lat, then there must be lng :) */
			if ($latDom->length > 0) {

					$response = array (
										'status' => true,
										'message' => 'Success',
										'lat' => $latDom->item(0)->nodeValue,
										'lon' => $lonDom->item(0)->nodeValue,
										'address'	=> $addressDom->item(0)->nodeValue
								);
					// $response = array (
										// 'status' => true,
										// 'message' => 'Success',
										// 'lat' => '38.905987',
										// 'lon' => '-77.033417',
										// 'address'	=> 'OK'
										// );

		return $response;
	}	
				}	

				$response = array (
									'status' => false,
									'message' => "Oh snap! Error in Geocoding. Please check Address"
									);
				return $response;
			}
			
		//<>-----function for convering the time into time stamp
	function time_stamp($session_time){ 
			 
			$time_difference = time() - $session_time ; 
			$seconds = $time_difference ; 
			$minutes = round($time_difference / 60 );
			$hours = round($time_difference / 3600 ); 
			$days = round($time_difference / 86400 ); 
			$weeks = round($time_difference / 604800 ); 
			$months = round($time_difference / 2419200 ); 
			$years = round($time_difference / 29030400 ); 

			if($seconds <= 60) {
				echo"$seconds seconds ago"; 
			}else if($minutes <=60){
			   if($minutes==1){
				 echo"one minute ago"; 
				}
			   else{
			   echo"$minutes minutes ago"; 
			   }
			}else if($hours <=24)			{
			   if($hours==1){
					echo"one hour ago";
			   }else{
					echo"$hours hours ago";
			  }
			}else if($days <=7){
			  if($days==1){
				echo"one day ago";
			   }else{
				echo"$days days ago";
			  }
			}else if($weeks <=4){
			  if($weeks==1){
					echo"one week ago";
			   } else {
					echo"$weeks weeks ago";
			  }
			}else if($months <=12){
			   if($months==1){
				echo"one month ago";
			   }else{
				echo"$months months ago";
			  }
			}else{
				if($years==1){
				   echo"one year ago";
				}else{
				  echo"$years years ago";
				}
			}
	} 

		//<>-----function for converting  text link
	function textlink($text){
		$text = html_entity_decode($text);
		$text = " ".$text;
		if(preg_match('(((f|ht){1}tp://)[-a-zA-Z0-9@:%_\+.~#?&//=]+)',$text,$a)){
		}else if(preg_match('(((f|ht){1}tps://)[-a-zA-Z0-9@:%_\+.~#?&//=]+)',$text,$a)){
		}else{
			$a=false;
		}
			return $text;
	}

	function getEmbeded_link($url){		//<>-----function for converting  url into embeded data link
		$data="";
		if(preg_match("/youtu/",$url) or preg_match("/youtube/",$url)){
			$data_arr= explode('v=',$url);
			$data_link =count($data_arr)>1 ? $data_arr[1] : "";
			$data="http://www.youtube.com/embed/".$data_link."?rel=0&amp;wmode=transparent";
		}
		return $data;
	}

		//<>-----function for convering the html tags to codes 
	function htmlcode($text){
		$stvarno = array ("<", ">");
		$zamjenjeno = array ("&lt;","&gt;");
		$final = str_replace($stvarno, $zamjenjeno, $text);
		return $final;
	}

		//<>-----function for strip slashes clear
	function clear($text){
		$final = stripslashes(stripslashes( $text));
		return $final;
	}

		//<>-----function for Convert  To Links
	function tolink($text){
			$text = " ".$text;
			$text = preg_replace('#(((f|ht){1}tp://)[-a-zA-Z0-9@:%_\+.~/?&//=]+)#',
					'<a href="\\1" target="_blank" rel="nofollow">\\1</a>', $text);
			$text = preg_replace('(((f|ht){1}tps://)[-a-zA-Z0-9@:%_\+.~#?&//=]+)',
					'<a href="\\1" target="_blank" rel="nofollow">\\1</a>', $text);
			$text = preg_replace('#([[:space:]()[{}])(www.[-a-zA-Z0-9@:%_\+.~/?&//=]+)#',
			'\\1<a href="http://\\2" target="_blank" rel="nofollow">\\2</a>', $text);
			$text = preg_replace('([_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,4})',
			'<a href="mailto:\\1"  rel="nofollow">\\1</a>', $text);
			return $text;
	}

		//<>-----function to Expand Given URL
	function Expand_URL($url){
			$returns = "";
		if(!empty($url)){
			if(preg_match("/youtu/",$url) or preg_match("/youtube/",$url)){
					if(preg_match("/v=/",$url))
				$splits = explode("=",$url);
				else
				$splits = explode("be/",$url);

					if(!empty($splits[1])){
						if(preg_match("/feature/i", $splits[1])){
						$splits[1] = str_replace("&feature","",$splits[1]);	
						}
							$returns = '<iframe width="100%" height="250" src="http://www.youtube.com/embed/'.$splits[1].'?wmode=transparent" frameborder="0"></iframe>';
					}
			} else if(preg_match("/vimeo/",$url)){
					$splits = explode("com/",$url);
					$returns = '<iframe src="http://player.vimeo.com/video/'.$splits[1].'?title=0&amp;byline=0&amp;portrait=0" width="410" height="250" frameborder="0"></iframe>';
			}
		}
		return $returns;
	}

		//<>-----function for Tag display words were shorten here
	function ShortenText($text,$chars=20) {
		// Change to the number of characters you want to display
			if( strlen($text)-$chars>5){
				$text = substr($text,0,($chars+3));
				$text = $text.'...';
			}else if( strlen($text)>=$chars){
				$text = substr($text,0,$chars);
			}
			return $text;
	}

		//<>-----function for Compare two array values
	function compareTwoArray ($array1, $array2)	{
		  foreach ($array1 as $key => $value){
			if ($array2[$key] != $value){
			  return false;
			}
		  }
		  return true;
	}


		//<>-----function for Compare two array values
	function mime_type_asdsadasdsadadto_image_type( $mime_type ) {
		switch ( $mime_type ) {
			  case 'image/gif':
				return 'IMAGETYPE_GIF';
			  case 'image/jpeg':
				return 'IMAGETYPE_JPEG';
			  case 'image/pjpeg':
				return 'IMAGETYPE_JPEG';
			  case 'image/jpg':
				return 'IMAGETYPE_JPEG';
			  case 'image/png':
				return IMAGETYPE_PNG;
			  case 'image/psd':
				return IMAGETYPE_PSD;
			  case 'image/bmp':
				return IMAGETYPE_BMP;
			  case 'image/tiff':
				return IMAGETYPE_TIFF_II;
			  case 'image/jp2':
				return IMAGETYPE_JP2;
			  case 'image/iff':
				return IMAGETYPE_IFF;
			  case 'image/vnd.wap.wbmp':
				return IMAGETYPE_WBMP;
			  case 'image/xbm':
				return IMAGETYPE_XBM;
		}
	}

//#####################################################################################################   ----C0MM0N	
	//<>-----function helps to get hte current IP address of the user
	function getIp(){
        if (getenv('HTTP_CLIENT_IP')) {
            $userIp = getenv('HTTP_CLIENT_IP');
        } else if (getenv('HTTP_X_FORWARDED_FOR')) {
            $userIp = getenv('HTTP_X_FORWARDED_FOR');
        } else if (getenv('REMOTE_ADDR')) {
            $userIp = getenv('REMOTE_ADDR');
        } else {
            $userIp = '';
        }
        return $userIp;
    }
	
		//<>-----function for create name out of an email
	function EmailToName($email) {
		$output = str_replace(array('.', '-', '_', ',', ':'), ' ', substr($email, 0, strpos($email, '@')));
		$output = str_replace(array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9), '', $output);
		$output = ucwords($output);
		return $output;
	}


	
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
defined('SITE_ROOT') ? null : define('SITE_ROOT', dirname(__FILE__).DS."..".DS);
defined('BASE_URL') ? null : define('BASE_URL', $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/');
defined('BASE_URL_FULL') ? null : define('BASE_URL_FULL', $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/');

	//<----contant to declare the base document path from here all other paths are related
defined('SITE_ROOT') ? null : define('SITE_ROOT', dirname(__FILE__).DS."..".DS);

	//<----contant to declare the library directory  path
defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.'includes'.DS);

defined('BASE_DOCUMENT') ? null : define('BASE_DOCUMENT', SITE_ROOT.'public'.DS);
defined('JS_APP_URL') ? null : define('JS_APP_URL', " \n\tvar APP_URL= '".BASE_URL."';"	);

 //Section includes library paths 
defined('SITE_ROOT') ? null : define('SITE_ROOT', dirname(__FILE__).DS."..".DS);
set_include_path(get_include_path().PATH_SEPARATOR.SITE_ROOT.'models');
set_include_path(get_include_path().PATH_SEPARATOR.SITE_ROOT);

	// Page links array defined here....
defined('PAGE_URL_LIST') ? null : 
							define ("PAGE_URL_LIST",
										serialize (array(
													'index' => '',
													'get' => 'get',
													'appointment' => 'appointment',
													'cloud_view' => 'cloud_view',
													'create' => 'create',
													'dashboard' => 'dashboard',
													'event_view' => 'events',
													'friends_list' => 'friends',
													'login' => 'login',
													'logout' => 'logout',
													'main_dashboard' => 'workspace',
													'product_view' => 'sales',
													'promotion' => 'promotion',
													'profile' => 'profile',
													'search' => 'search',
													'settings' => 'settings',
													'webpage_view2' => 'business',
													'webpage_view3' => 'companypage',
													'webpage_view4' => 'bookingpage',
													'webpage2' => 'webpage2',	
													'email_confirm' => 'email_confirm',	
													'take_order' => 'take_order',	
													'mailer' => 'mailer',	
													'ask_board' => 'livechek',	
													'classified_view' => 'classified_view',	
													'development' => 'development',	
													'sitemap' => 'sitemap',	
													'404' => '404'	
													)
												)
									);
 // defined('PAGE_URL_LIST') ? null :define ("PAGE_URL_LIST",serialize (array('index' => '','appointment' => 'appointment','cloud_view' => 'cloud_view','create' => 'create','dashboard' => 'dashboard','event_view' => 'event_view','friends_list' => 'friends_list','login' => 'login','logout' => 'logout','main_dashboard' => 'main_dashboard','product_view' => 'product_view','promotion' => 'promotion','profile' => 'profile','search' => 'search','settings' => 'settings','timeline' => 'timeline','webpage_view2' => 'webpage_view2','webpage_view3' => 'webpage_view3','webpage_view4' => 'webpage_view4','webpage2' => 'webpage2','email_confirm' => 'email_confirm','take_order' => 'take_order','mailer' => 'mailer','ask_board' => 'ask_board','classified_view' => 'classified_view','development' => 'development','sitemap' => 'sitemap','404' => '404')));
defined('ADMIN_PAGE_URL_LIST') ? null : 
							define ("ADMIN_PAGE_URL_LIST",
										serialize (array(
													'index' => '',
													'login' => 'login',
													'logout' => 'logout',
													'404' => '404'	
													)
												)
									);
$page_url_array= unserialize (PAGE_URL_LIST);		//<----retrieve the array contant value to variable array
$admin_page_url_array= unserialize (ADMIN_PAGE_URL_LIST);		//<----retrieve the array contant value to variable array

defined('JS_APP_ADMIN_URL_ARRAY') ? null : define('JS_APP_ADMIN_URL_ARRAY', "\n\tvar APP_URL_ARRAY = {
																						'index' 		: APP_URL,
																						'login' 		: APP_URL+'login',
																						'logout' 		: APP_URL+'logout',
																						'404' 			: APP_URL+'404'
																					};\n"
													);
defined('JS_APP_URL_ARRAY') ? null : define('JS_APP_URL_ARRAY', "\n\tvar APP_URL_ARRAY = {
																						'index' 		: '".BASE_URL."',
																						'get' 			: '".BASE_URL.$page_url_array['get']."',
																						'appointment' 	: '".BASE_URL.$page_url_array['appointment']."',
																						'cloud_view' 	: '".BASE_URL.$page_url_array['cloud_view']."',
																						'create' 		: '".BASE_URL.$page_url_array['create']."',
																						'dashboard'		: '".BASE_URL.$page_url_array['dashboard']."',
																						'event_view' 	: '".BASE_URL.$page_url_array['event_view']."',
																						'friends_list'	: '".BASE_URL.$page_url_array['friends_list']."',
																						'login' 		: '".BASE_URL.$page_url_array['login']."',
																						'logout' 		: '".BASE_URL.$page_url_array['logout']."',
																						'main_dashboard': '".BASE_URL.$page_url_array['main_dashboard']."',
																						'product_view' 	: '".BASE_URL.$page_url_array['product_view']."',
																						'promotion' 	: '".BASE_URL.$page_url_array['promotion']."',
																						'profile' 		: '".BASE_URL.$page_url_array['profile']."',
																						'search' 		: '".BASE_URL.$page_url_array['search']."',
																						'settings' 		: '".BASE_URL.$page_url_array['settings']."',
																						'webpage_view2' : '".BASE_URL.$page_url_array['webpage_view2']."',
																						'webpage_view3' : '".BASE_URL_FULL.$page_url_array['webpage_view3']."',
																						'webpage_view4' : '".BASE_URL_FULL.$page_url_array['webpage_view4']."',
																						'webpage2' 		: '".BASE_URL.$page_url_array['webpage2']."',	
																						'email_confirm' : '".BASE_URL.$page_url_array['email_confirm']."',	
																						'take_order' 	: '".BASE_URL.$page_url_array['take_order']."',	
																						'mailer' 		: '".BASE_URL.$page_url_array['mailer']."',	
																						'ask_board' 	: '".BASE_URL.$page_url_array['ask_board']."',	
																					  'classified_view' : '".BASE_URL.$page_url_array['classified_view']."',	
																						'404' 			: '".BASE_URL.$page_url_array['404']."'
																					};\n"
													);
// defined('JS_APP_URL_ARRAY') ? null : define('JS_APP_URL_ARRAY', "\tvar APP_URL_ARRAY = {'index' : 'index','appointment' : 'appointment','cloud_view' : 'cloud_view','create' : 'create','dashboard' : 'dashboard','event_view' : 'event_view','friends_list' : 'friends_list','login' : 'login','logout' : 'logout','main_dashboard' : 'main_dashboard','product_view' : 'product_view','profile' : 'profile','search' : 'search','settings' : 'settings','timeline' : 'timeline','webpage_view2' : 'webpage_view2','webpage2' : 'webpage2','404' : '404'};"	);
	   //<----intialize the url of application for javascript ajax files
defined('JS_APP_URL_BASE') ? null : define('JS_APP_URL_BASE', " \n\tvar APP_URL_BASE= '".BASE_URL."_coordinator/';"	);
defined('JS_APP_ADMIN_URL_BASE') ? null : define('JS_APP_ADMIN_URL_BASE', " \n\tvar APP_URL_BASE= '".BASE_URL."_admin_coordinator/';"	);
defined('JS_APP_URL') ? null : define('JS_APP_URL', " \n\tvar APP_URL= '".BASE_URL."';"	);
defined('JS_APP_URL_FULL') ? null : define('JS_APP_URL_FULL', " \n\tvar APP_URL_FULL= '".BASE_URL_FULL."';"	);
		//<----intialize the url of application for javascript ajax files
		
defined('JS_APP_NAME') ? null : define('JS_APP_NAME', "\n\tvar APP_NAME='ximglue';\n"	);	 

defined('APP_BACKGROUND_IMG') ? null : define('APP_BACKGROUND_IMG', BASE_URL."img/bgdot.jpg")	;	 
?>