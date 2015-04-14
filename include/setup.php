<?php


function install_table_odudedate()
{

	global $wpdb;
	
	$tablename = $wpdb->prefix.'odudedate';
				
	$qry="CREATE TABLE IF NOT EXISTS `".$tablename."` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event` varchar(256) NOT NULL,
  `event_desp` text NOT NULL,
  `extra1` text NOT NULL,
  `extra2` text NOT NULL,
  `extra3` text NOT NULL,
  `country` varchar(256) NOT NULL,
  `day` int(2) NOT NULL,
  `month` int(2) NOT NULL,
  `year` int(4) NOT NULL,
  `user` varchar(256) NOT NULL,
  `link` text NOT NULL,
  `category` varchar(255) NOT NULL,
  `Holiday` int(1) NOT NULL,
  `Publish` int(1) NOT NULL,
  `pdate` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
			
	$wpdb->query($qry);
	
		$tablename2 = $wpdb->prefix.'odudedate_group';
	
			
	$qry="CREATE TABLE IF NOT EXISTS `".$tablename2."` (
  `gid` int(11) NOT NULL AUTO_INCREMENT,
  `gname` text NOT NULL,
  `code_name` varchar(20) NOT NULL,
  `timezone` varchar(50) NOT NULL,
  `info` text NOT NULL,
  PRIMARY KEY (`gid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
			
	$wpdb->query($qry);
	
	$qry="INSERT INTO `".$tablename2."` (`gid`, `gname`, `code_name`, `timezone`, `info`) VALUES
(1, 'All Group', 'all', 'America/Mexico_City', 'No Information');";

$wpdb->query($qry);
	

$content='<table border="0" cellspacing="1">
<tbody>
<tr>
<td>[odude_flag group="" size="ico" page="page"]</td>
<td> [odude_settimezone zone=""]</td>
</tr>
</tbody>
</table>
[odude_date group=""  layout="general"]

[odude_other group=""  layout="celeb"]

[odude_other group=""  layout="extra"]

[odude_calendar group=""]';


	
	$my_post = array(
  'post_title'    => 'Calendar',
  'post_content'  => $content,
  'post_status'   => 'publish',
  'post_author'   => 1,
  'post_type' => 'page',
  'comment_status' => 'closed'
);

// Insert the post into the database
wp_insert_post( $my_post );


		
}




function oddroptables()
{
	
	global $wpdb;
	
	$tablename = $wpdb->prefix.'odudedate';
	$qry = "DROP TABLE ".$tablename;
	$wpdb->query($qry);
	
	$tablename2 = $wpdb->prefix.'odudedate_group';
	$qry = "DROP TABLE ".$tablename2;
	$wpdb->query($qry);
	
	}



?>