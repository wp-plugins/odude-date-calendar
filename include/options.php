<?php
function odudedate_register_mysettings()
{	
	register_setting( 'odudedate-settings-group', 'fbid' ,'odude_fb_validate');
	register_setting( 'odudedate-settings-group', 'dfcode');
	register_setting( 'odudedate-settings-group', 'w_thumb_size');
	register_setting( 'odudedate-settings-group', 'h_thumb_size');
	register_setting( 'odudedate-settings-group', 'w_large_size');
	register_setting( 'odudedate-settings-group', 'h_large_size');
	
}


function odude_fb_validate($input) 
{
	return $input;
}

function odudedate_setting() 
{
	?>
<div class="wrap">
<h2>Your Plugin Name</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'odudedate-settings-group' ); ?>
    <?php do_settings_sections( 'odudedate-settings-group' ); ?>
    <table class="form-table">
       
	  
		  <tr valign="top">
        <th scope="row">Default Group Code</th>
        <td><?php echo ODudeGroup('all','all','admin','dfcode'); ?></td>
        </tr>
		
		 <tr valign="top">
        <th scope="row">Facebook ID</th>
        <td><input type="text" name="fbid" value="<?php echo get_option('fbid'); ?>" /></td>
        </tr>
		
		<tr valign="top">
        <th scope="row">Thumbnail Dimension (px)</th>
        <td>Width: <input type="text" name="w_thumb_size" value="<?php echo (get_option('w_thumb_size')==''?'100':get_option('w_thumb_size')); ?>" size="4" maxlength="3" /> Height: <input type="text" name="h_thumb_size" value="<?php echo (get_option('h_thumb_size')==''?'100':get_option('h_thumb_size')); ?>" size="4" maxlength="3" /></td>
        </tr>
		
			<tr valign="top">
        <th scope="row">Large Image Dimension (px)</th>
        <td>Width: <input type="text" name="w_large_size" value="<?php echo (get_option('w_large_size')==''?'400':get_option('w_large_size')); ?>" size="4" maxlength="3" /> Height: <input type="text" name="h_large_size" value="<?php echo (get_option('h_large_size')==''?'400':get_option('h_large_size')); ?>" size="4" maxlength="3" /></td>
        </tr>
		
		
			
    </table>

    <?php submit_button(); ?>

</form>
</div>
	
	<?php
}
?>