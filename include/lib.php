<?php
function ODudeGroup($field,$code,$user,$formname='country',$selected='')
  {
    
	global $wpdb;
	global $default_code;
	global $default_name;
	if($field=="all" && $code=="all" && $user="admin")
		{
		$f='<select id="'.$formname.'" name="'.$formname.'">';
		$getgalimages = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."odudedate_group");
			if(count($getgalimages))
			{
			
				foreach($getgalimages as $val)
				{
				$gname=$val->gname;
				$code_name=$val->code_name;
				
				if($code_name==$selected)
					$sel='selected="selected"';
				else
					$sel='';
				
				$f.='<option value="'.$code_name.'" '.$sel.'>'.$gname.'</option>';
								
				}
			}
			else
			{
				$f.='<option value="'.$default_code.'">'.$default_name.'</option>';
			
			}
			$f.="</select>";
			return $f;
		}
		else if($field!="all" && $user="admin")
		{
		
		$sql = $wpdb->prepare("SELECT $field as field FROM ".$wpdb->prefix."odudedate_group where code_name=%s",array($code));
		$getgalimages = $wpdb->get_results($sql);
		//$getgalimages = $wpdb->get_results("SELECT $field as field FROM ".$wpdb->prefix."odudedate_group where code_name='$code'");
			if(count($getgalimages))
			{
			
				foreach($getgalimages as $val)
				{
				$field=$val->field;
				
				return $field;
								
				}
			}
			else
			{
			  return "Not Found";
			}
			
		}
		else
		{
			return "US";
		}
  }


function toSafeURL($str, $replace=array(), $delimiter='-') {
 if( !empty($replace) ) {
  $str = str_replace((array)$replace, ' ', $str);
 }

 $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
 $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
 $clean = strtolower(trim($clean, '-'));
 $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

 return $clean;
}



function odude_createthumb($filepath,$thumbpath,$thmbwidth,$thmbheight,$resizetype)
{
	//Creating thumbnail 
	$newheight = $thmbheight;
	$newwidth = $thmbwidth;
	
	$phpThumb = new phpThumb();
	
	$phpThumb->setSourceData(file_get_contents($filepath));
	$output_filename = $thumbpath;
	
	$phpThumb->setParameter('w', $newwidth);
	$phpThumb->setParameter('h', $newheight);
	$phpThumb->setParameter('q', 100);
	
	if($resizetype == 'exact'){
		
		$phpThumb->setParameter('iar', 1);
	}
	if($resizetype == 'crop'){
		
		$phpThumb->setParameter('zc', 1);
	}
	
	$phpThumb->GenerateThumbnail();
	$phpThumb->RenderToFile($output_filename);
	
	$phpThumb->purgeTempFiles();
}


function thumbImage($src,$new)
{
	//Saving external site Images
global $keylink;
if (strpos($src, $keylink) !== false)
	{
		//echo "image from own site";
	}
	else
	{
		global $wpdb;
		 $w_thumb_size=get_option('w_thumb_size');
		  $h_thumb_size=get_option('h_thumb_size');
		   $w_large_size=get_option('w_large_size');
		    $h_large_size=get_option('h_large_size');
			
			 
		$uploaddir = wp_upload_dir();
		$path = $uploaddir['basedir'].'/odude-date/'.$new.'.jpg';
		$pathurl = $uploaddir['baseurl'].'/odude-date/'.$new.'.jpg';

		//$src is http url
		$img = imagecreatefromjpeg($src);
		imagejpeg($img, $path);

			if(getimagesize($path))
			{
			    //echo '<h3 style="color: green;">Image Downloaded Successfully</h3>';
				
				$tablename = $wpdb->prefix.'odudedate';
				$qry = "update `".$tablename."` set extra3='$pathurl' where id='$new'";
				$wpdb->query($qry);
				
				$thumb_path = $uploaddir['basedir'].'/odude-date/thumb_'.$new.'.jpg';
				
				$image = wp_get_image_editor( $path );
				if ( ! is_wp_error( $image ) ) {
					//$image->rotate( 90 );
					$image->resize( $w_thumb_size, $h_thumb_size, true );
					$image->save( $thumb_path );
				}
				
				//Creating static thumbnail
				//odude_createthumb($path,$thumb_path,$w_thumb_size,$h_thumb_size,$picture_aspect_ratio);
				
				$large_path = $uploaddir['basedir'].'/odude-date/large_'.$new.'.jpg';
				//Creating static large pic
				//odude_createthumb($path,$large_path,$w_large_size,$h_large_size,$picture_aspect_ratio);
				
				$image = wp_get_image_editor( $path );
				if ( ! is_wp_error( $image ) ) {
					$image->set_quality( 100 );
					$image->resize( $w_large_size, $h_large_size, true );
					$image->save( $large_path );
				}
				
				
			}
			else
			{
				echo 'Image Download Failed';
			}
	}
	

}

function iso2long($date)
{
return date("F jS, Y l", strtotime($date));

}


function conName($country)
{
return ODudeGroup('gname',$country,'admin');

}
function zoneName($country)
{
global $default_time;
$zone=ODudeGroup('timezone',$country,'admin');
if($zone=='Not Found')
return $default_time;
else
return $zone;

}

function cookieCon()
{
global $default_code;

	if(isset($_COOKIE["odude_cal"]))
	return $_COOKIE["odude_cal"];
	else
	return $default_code;

}

function cookieTime()
{
global $default_code;

	if(isset($_COOKIE["odude_cal_zone"]))
	return $_COOKIE["odude_cal_zone"];
	else
	return ODudeGroup('timezone',$default_code,'admin');

}
  
function odclean($string)
 {
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}

function odude_date_unique($param)
{
$param=strtolower($param);
$param=trim($param);
$param=preg_replace('/[^a-zA-Z0-9-]/', '_', $param );
return $param;
}

//Draw Calendar
function draw_calendar($month,$year,$country)
{

global  $site_url;


if($country=='')
$country=cookieCon();


if(is_home())
{
	$country=cookieCon();
}

//$cmonth=date('m');
$pmonth=$month-1;
$nmonth=$month+1;

$nyear=$year;
$pyear=$year;


if($pmonth==0)
{
$pmonth=12;
$pyear=$year-1;
}

if($nmonth==13)
{
$nmonth=1;
$nyear=$year+1;
}
	// Draw table for Calendar 
	$calendar = '<br><center><b>'.monthName($month).' '.$year.' - Calender of '.conName($country).'</b></center>';
	$calendar .='<div style="width: 100%" ><center><a href="'.$site_url.'calendar/1/'.$pmonth.'/'.$pyear.'/'.$country.'/" class="pure-button"><div style="font-size: smaller;"><< '.monthName($pmonth).'</div></a> <a href="'.$site_url.'calendar/1/'.date('m').'/'.date('Y').'/'.$country.'/" class="pure-button"><div style="font-size: smaller;">#</div></a> <a href="'.$site_url.'calendar/1/'.$nmonth.'/'.$nyear.'/'.$country.'/" class="pure-button"><div style="font-size: smaller;">'.monthName($nmonth).' >></div></a></center></div>';
	$calendar.='<div style="width: 98%"><table cellpadding="0" cellspacing="0" class="calendar">';

	// Draw Calendar table headings 
	$headings = array('SU','M','T','W','T','F','SA');
	$calendar.= '<tr class="calendar-row"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$headings).'</td></tr>';

	//days and weeks variable for now ... 
	$running_day = date('w',mktime(0,0,0,$month,1,$year));
	$days_in_month = date('t',mktime(0,0,0,$month,1,$year));
	$days_in_this_week = 1;
	$day_counter = 0;
	$dates_array = array();

	// row for week one 
	$calendar.= '<tr class="calendar-row">';

	// Display "blank" days until the first of the current week 
	for($x = 0; $x < $running_day; $x++):
		$calendar.= '<td class="calendar-day-np">&nbsp;</td>';
		$days_in_this_week++;
	endfor;

	// Show days.... 
	for($list_day = 1; $list_day <= $days_in_month; $list_day++):
		if($list_day==date('d') && $month==date('n') && $year==date('Y'))
		{
			$currentday='currentday';
		}else
		{
			$currentday='';
		}
		$calendar.= '<td class="calendar-day '.$currentday.'">';
		
			// Add in the day number
			if($list_day<date('d') && $month==date('n') && $year==date('Y'))
			{
				$showtoday='<strong class="overday">'.$list_day.'</strong>';
			}else
			{
				$showtoday=$list_day;
			}
			//$calendar.= '<div class="D'.$list_day.'"><div class="day-number"><a href="'.get_permalink().'?day='.$list_day.'&month='.$month.'&year='.$year.'&country='.$country.'">'.$showtoday.'</a></div></div>';
			//$calendar.= '<div class="D'.$list_day.'"><div class="day-number"><a href="http://localhost/datetimenow/calendar/'.$list_day.'/'.$month.'/'.$year.'/'.$country.'/">'.$showtoday.'</a></div></div>';
			$calendar.= '<div class="D'.$list_day.'"><div class="day-number"><a href="'.$site_url.'calendar/'.$list_day.'/'.$month.'/'.$year.'/'.$country.'/">'.$showtoday.'</a></div></div>';
		
		// Draw table end
		$calendar.= '</td>';
		if($running_day == 6):
			$calendar.= '</tr>';
			if(($day_counter+1) != $days_in_month):
				$calendar.= '<tr class="calendar-row">';
			endif;
			$running_day = -1;
			$days_in_this_week = 0;
		endif;
		$days_in_this_week++; $running_day++; $day_counter++;
	endfor;

	// Finish the rest of the days in the week
	if($days_in_this_week < 8):
		for($x = 1; $x <= (8 - $days_in_this_week); $x++):
			$calendar.= '<td class="calendar-day-np">&nbsp;</td>';
		endfor;
	endif;

	// Draw table final row
	$calendar.= '</tr>';

	// Draw table end the table 
	$calendar.= '</table></div>';
	
	// Finally all done, return result 
	return $calendar;
}

function monthName($month_int)
 {

$month_int = (int)$month_int;

$months = array("","January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

return $months[$month_int];

}
 function HolidayList($month,$year,$country)
  {
    
	global $wpdb;
	$f="<style>";
	$g="";
	
	$sql = $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."odudedate WHERE month='$month' and year=%d and category='general' and country=%s and Publish='1'",array($year,$country));
	$getgalimages = $wpdb->get_results($sql);
		
		
		//$getgalimages = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."odudedate WHERE month='$month' and year='".$year."' and category='general' and country='$country' and Publish='1'");

	if(count($getgalimages))
			{
			
			foreach($getgalimages as $val)
				{
				$event = $val->event;
				$day=$val->day;
				$event_desc=$val->event_desp;
				$link=$val->link;
				$id=$val->id;
				$extra1=$val->extra1;
				$extra2=$val->extra2;
				$extra3=$val->extra3;
				$holiday=$val->Holiday;
				
				if($holiday=='1')
				{
				$f.=".D$day,";
				}
				
				if($holiday=='0' && $event!='')
				{
				$g.=".D$day,";
				}
				
				
				}
			
			}	
			else
			{
				return '';
			}
		$f.="D0";	
		$g.="D0";
		$f.="{background: #f1caaf !important;
			background: -moz-linear-gradient(top,  #f1caaf 0%, #388be8 100%) !important;
			background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#6cb7f3), color-stop(100%,#388be8)) !important;
			background: -webkit-linear-gradient(top,  #6cb7f3 0%,#388be8 100%) !important;
			background: -o-linear-gradient(top,  #6cb7f3 0%,#388be8 100%) !important;
			background: -ms-linear-gradient(top,  #6cb7f3 0%,#388be8 100%) !important;
			background: linear-gradient(to bottom,  #FAD9D9 0%,#FDFDFD 100%) !important;
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#6cb7f3', endColorstr='#388be8',GradientType=0 ) !important; color:#FFF  !important; font-weight:bold; -moz-box-shadow:0px 0px 18px #1F68BA inset; -webkit-box-shadow:0px 0px 18px #FAD9D9 inset; box-shadow:0px 0px 18px #F4E0EA inset;
			}";
			$g.="{background: #f1caaf !important;
			background: -moz-linear-gradient(top,  #f1caaf 0%, #388be8 100%) !important;
			background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#6cb7f3), color-stop(100%,#388be8)) !important;
			background: -webkit-linear-gradient(top,  #6cb7f3 0%,#388be8 100%) !important;
			background: -o-linear-gradient(top,  #6cb7f3 0%,#388be8 100%) !important;
			background: -ms-linear-gradient(top,  #6cb7f3 0%,#388be8 100%) !important;
			background: linear-gradient(to bottom,  #9EF075 0%,#FDFDFD 100%) !important;
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#6cb7f3', endColorstr='#388be8',GradientType=0 ) !important; color:#FFF  !important; font-weight:bold; -moz-box-shadow:0px 0px 18px #1F68BA inset; -webkit-box-shadow:0px 0px 18px #9EF075 inset; box-shadow:0px 0px 18px #F4E0EA inset;
			}";
$f.=$g."</style>";
		return $f;
	}
	
  function getEventsList($day,$month,$year,$type,$country)
  {
	global $wpdb;
	
	$sql = $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."odudedate WHERE day = %d and month=%d and (year=%d or year='0') and category=%s and country=%s and Publish='1'",array($day,$month,$year,$type,$country));
	$getgalimages = $wpdb->get_results($sql);
		
	
	//$spdata="SELECT * FROM ".$wpdb->prefix."odudedate WHERE day = '$day' and month='$month' and (year='".$year."' or year='0') and category='$type' and country='$country' and Publish='1'";
	//$getgalimages = $wpdb->get_results($spdata);
	if(count($getgalimages))
			{
			
			foreach($getgalimages as $val)
				{
				$event = $val->event;
				$day=$val->day;
				$event_desc=$val->event_desp;
				$link=$val->link;
				$category=$val->category;
				$id=$val->id;
				$extra1=$val->extra1;
				$extra2=$val->extra2;
				$extra3=$val->extra3;
				if($extra3=="")
				$extra3="";
				else
				$extra3="<img src='$extra3'><br>";
				
				return "$event - $extra1 - $extra2 - ($id)";
				
				}
			
			}	
			else
			{
				return "0";
			}
	
	}
	
  
  
  
  function getEventsBox($day,$month,$year,$country,$category)
  {
	global $wpdb;
	global $current_user;
	$user = wp_get_current_user();
	//get_currentuserinfo();
	$datefor="$year-$month-$day";
	global  $site_url;
	global $keylink;
	$uploaddir = wp_upload_dir();
	
	if(is_home())
	{
		$country=cookieCon();
	}
	

		if($day=='' && $month=='' && $year=='')
		{
		 $datefor=date('Y')."-".date('m')."-".date('d');		
		//$getgalimages = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."odudedate WHERE day = '".date('d')."' and month='".date('m')."' and (year='".date('Y')."' or year='0') and (country='$country' or country='all')and category='$category' and Publish='1'");
		
		$sql = $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."odudedate WHERE day = '".date('d')."' and month='".date('m')."' and (year='".date('Y')."' or year='0') and (country=%s or country='all')and category=%s and Publish='1'",array($country,$category));
	$getgalimages = $wpdb->get_results($sql);
		
		
		
		}
		else if($day=='' && $month!='' && $year!='')
		{
		
		//$getgalimages = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."odudedate WHERE month='".$month."' and (year='".$year."' or year='0') and (country='$country' or country='all') and category='$category' and Publish='1'");
		
		$sql = $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."odudedate WHERE month='".$month."' and (year='".$year."' or year='0') and (country=%s or country='all') and category=%s and Publish='1'",array($country,$category));
	$getgalimages = $wpdb->get_results($sql);
		
		
		}
		else
		{
			//$getgalimages = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."odudedate WHERE day = '$day' and month='$month' and (year='".$year."' or year='0') and (country='$country' or country='all') and category='$category' and Publish='1'");
			
			$sql = $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."odudedate WHERE day = %d and month=%d and (year=%d or year='0') and (country=%s or country='all') and category=%s and Publish='1'",array($day,$month,$year,$country,$category));
	$getgalimages = $wpdb->get_results($sql);
			
		}
		$f="";
		//$f.="<h1>".iso2long('2014-1-01')."</h1>";
		
		//Displaying records
		if(count($getgalimages))
			{
			
				
				
				
				foreach($getgalimages as $val)
				{
				
				$event = $val->event;
				$day=$val->day;
				$link=$val->link;
				$event_desc=$val->event_desp;
				if($link=='')
				$event_desc="";
				
			
				$category=$val->category;
				$id=$val->id;
				$country=$val->country;
				$extra1=$val->extra1;
				
				if($event=="" && $extra1!="")
				$event="Information";
				
				$extra2=$val->extra2;
				$extra3=$val->extra3;
				$holiday=$val->Holiday;
				if($holiday=='0')
				$holiday="";
				else
				$holiday='<img src="'.plugins_url( '../css/images/red.png', __FILE__ ).'">';	
				//$holiday='<img src="'.plugins_url().'/odude-date/css/images/red.png">';
				$flag=do_shortcode('[odude_flag group="'.$country.'" size="ico" page=""]');
				
				
				if($extra3=="")
				{
				$extra3="";
				}
				else
				{
						$imglink='<a href="'.$site_url.'calendar/'.$id.'/'.toSafeURL($event, "'").'">';
						if (strpos($link, $keylink) !== false)
						$imglink='<a href="'.$link.'">';
				thumbImage($extra3,$id);
				$extra3=$imglink."<img src='".$uploaddir['baseurl']."/odude-date/thumb_$id.jpg'></a>";
				
				}
				
				$butt="";
				$newlink='<a href="'.$site_url.'calendar/'.$id.'/'.toSafeURL($event, "'").'">'.$event.'</a> ';
				
				
					if($link!='')
					{
						if (strpos($link, $keylink) !== false)
						{
						$butt="<a href='$link' class=\"pure-button pure-button-primary\">More Details</a>";
						$newlink='<a href="'.$link.'">'.$event.'</a> ';
						
						}
						else
						{
						$pic = plugins_url().'/odude-date/css/images/link.png';
						$butt="<a href='$link' class=\"pure-button pure-button-primary\" target='_blank'>More Details</a> <img src='$pic'>";
						}
					} 
					
	
				
					
					if ( is_super_admin()  ) 
					{
					
					$edit="<a href='".$site_url."wp-admin/admin.php?page=odudedate&edit=$id' class=\"pure-button pure-button-active\" target='_blank'>[Edit]</a>";
					}
					else if( in_array( "author", (array) $user->roles ))
					{
					
					$edit="<a href='".$site_url."wp-admin/admin.php?page=odudedate&edit=$id' class=\"pure-button pure-button-active\" target='_blank'>[Edit]</a>";
					}
					else
					{
					$edit="";
					}
				
				include(ODUDEDATE_PLUGIN_DIR . '/layout/'.$category.'.php');
				
		
			}
				
				
				
				
				
				return $f;
				
				
			}
			else
			{
				
				/*					
				if($day=='' && $month=='' && $year=='')
				return "<center>".iso2long(date('Y')."-".date('m')."-".date('d'))."</center>";
				else
				return "<center><b>".iso2long($datefor)."</b></center>";
				*/
				return "";
				
			}
		
		
  
  }
  function odude_time_ago( $date )
{
    if( empty( $date ) )
    {
        return "No date provided";
    }

    $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");

    $lengths = array("60","60","24","7","4.35","12","10");

    $now = time();

    $unix_date = strtotime( $date );

    // check validity of date

    if( empty( $unix_date ) )
    {
        return "Bad date";
    }

    // is it future date or past date

    if( $now > $unix_date )
    {
        $difference = $now - $unix_date;
        $tense = "ago";
    }
    else
    {
        $difference = $unix_date - $now;
        $tense = "from now";
    }

    for( $j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++ )
    {
        $difference /= $lengths[$j];
    }

    $difference = round( $difference );

    if( $difference != 1 )
    {
        $periods[$j].= "s";
    }

    return "$difference $periods[$j] {$tense}";

}
  
?>