<link rel="stylesheet" href="<?= WP_PLUGIN_URL . '/buddystream/css/buddystream.css';?>" type="text/css" />
<br/>
<?php include "AdminMenu.php"; ?>

<?php
  if ($_POST) {      
      update_site_option('bs_lastfm_user_settings_maximport', trim(strip_tags(strtolower($_POST['bs_lastfm_user_settings_maximport']))));
      update_site_option('bs_lastfm_hide_sitewide', trim(strip_tags(strtolower($_POST['bs_lastfm_hide_sitewide']))));
      echo '<div class="updated" style="margin-top:50px;"><p><strong>' . __('Settings saved.', 'buddystream_lang') . '</strong></p></div>';
   }
?>

<div class="wrap"><br/>
        <h2 style="float: left; line-height: 5px; padding-left: 5px;">
            <?php echo __('Last.fm API'); ?>
        </h2>
        <br /><br /><br />

        <div class="bs_info_box">
            <?php echo __('A Last.fm API key or connection is NOT required to get the user\'s song histories. '); ?>
        </div>

      <form method="post" action="">
          <table class="form-table">
           
            <tr valign="top">
                <th scope="row"><h2><?php echo __('User options', 'buddystream_lang');?></h2></th>
                <td></td>
            </tr>

            <tr valign="top">
            <th><?php echo __( 'Hide Last.fm song history from appearing in the sidewide activity stream?', 'buddystream_lang' );?></th>
            <th>
            <input type="radio" name="bs_lastfm_hide_sitewide" id="facestream_user_settings_syncupdatesbp" value="1" <?php if(get_site_option('bs_lastfm_hide_sitewide')==1){echo'checked';}?>> <?php echo __( 'Yes', 'buddystream_lang' );?>
            <input type="radio" name="bs_lastfm_hide_sitewide" id="facestream_user_settings_syncupdatesbp" value="0" <?php if(get_site_option('bs_lastfm_hide_sitewide')==0){echo'checked';}?>> <?php echo __( 'No', 'buddystream_lang' );?>
            </th>

            <tr valign="top">
                <th><?php echo __('Maximum amount of songs to import per user, per day (empty = unlimited):', 'buddystream_lang'); ?></th>
                <th>
                    <input type="text" name="bs_lastfm_user_settings_maximport" value="<?php echo get_site_option('bs_lastfm_user_settings_maximport'); ?>" size="5" />
                </th>
            </tr>
        </table>
       <p class="submit"><input type="submit" class="button-primary" value="<?php echo __('Save Changes') ?>" /></p>
    </form>
</div>