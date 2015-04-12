<br>Last Updated 100 Records<br>
<table border=1 width="100%">
<?php
global  $site_url;
    
	global $wpdb;
	
		$getgalimages = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."odudedate order by pdate desc limit 0,100");
		

	if(count($getgalimages))
			{
			
			foreach($getgalimages as $val)
				{
				$event = $val->event;
				$day=$val->day;
				$month=$val->month;
				$year=$val->year;
				$event_desc=$val->event_desp;
				$link=$val->link;
				$id=$val->id;
				$extra1=$val->extra1;
				$extra2=$val->extra2;
				$extra3=$val->extra3;
				$holiday=$val->Holiday;
				$pdate=$val->pdate;
				$country=$val->country;
				$category=$val->category;
				$user=$val->user;
				echo "<tr><td>$id</td><td><a href='".$site_url."calendar/$day/$month/$year/$country/' target='_blank'>$day/$month/$year </a></td><td>".conName($country)."</td><td><b>$event</b></td><td>$category</td><td>$user</td><td>$pdate</td></tr>";
							
				
				}
			
			}	
			else
			{
				return 'No Record';
			}


?>	</table>	