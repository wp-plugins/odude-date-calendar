
<div class="wrap">
<h2>Welcome to ODude Date Admin Dashboard.</h2><hr>

<a href="http://www.odude.com"><img src="http://odude.com/icon-128x128.jpg" border="1"></a>
<br>
Visit <a href="http://www.odude.com/wordpress/">ODude.com</a> for more information.

<?php
$uploaddir = wp_upload_dir();
	
	
		$custom_upload_folder= $uploaddir['basedir'] . '/odude-date';
		if (!is_dir($custom_upload_folder))
		 {
			mkdir($custom_upload_folder);
		 }
		
		echo "<br><br>Checking Settings...<hr>";
 
if(is_writable($custom_upload_folder))
{ 
    echo "Media upload folder is writeable. [OK]"; 
} 
else
{ 
   echo "The directory is not writeable. ".$custom_upload_folder." [Solve this issue by creating this folder]"; 
} 

?>
		

</div>