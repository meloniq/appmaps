<?php
  if (!current_user_can('manage_options')) {
    wp_die( __('You do not have sufficient permissions to access this page.') );
  }

  //Update options
  if( isset( $_POST['options_update']) ) {
    update_option('appmaps_active', $_POST['appmaps_active']);
    update_option('appmaps_lat', $_POST['appmaps_lat']);
    update_option('appmaps_lng', $_POST['appmaps_lng']);
  	update_option('appmaps_api_key', $_POST['appmaps_api_key']);
  	update_option('appmaps_gmaps_loc', $_POST['appmaps_gmaps_loc']);
    
    echo '<div class="updated"><p><strong>' . __('Settings saved', 'appmaps') . '</strong></p></div>';
  }

//google maps locations
	$gmaps_domains = array( 'http://maps.google.com', 
                          'http://maps.google.at',
                          'http://maps.google.com.au',
                          'http://maps.google.com.ba',
                          'http://maps.google.be',
                          'http://maps.google.com.br',
                          'http://maps.google.ca',
                          'http://maps.google.ch',
                          'http://maps.google.cz',
                          'http://maps.google.de',
                          'http://maps.google.dk',
                          'http://maps.google.es',
                          'http://maps.google.fi',
                          'http://maps.google.fr',
                          'http://maps.google.it',
                          'http://maps.google.co.jp',
                          'http://maps.google.nl',
                          'http://maps.google.no',
                          'http://maps.google.co.nz',
                          'http://maps.google.pl',
                          'http://maps.google.ru',
                          'http://maps.google.se',
                          'http://maps.google.tw',
                          'http://maps.google.co.th',
                          'http://maps.google.co.uk'
                          );

	?>	
<script type="text/javascript">
// <![CDATA[
  jQuery(document).ready(function() {
    jQuery("#tabs-wrap").tabs({fx: {opacity: 'toggle', duration: 200}});
  });
// ]]>
</script>
<div class="wrap">
  <div class="icon32" id="icon-options-general"><br /></div>
  <h2><?php _e('General Settings', 'appmaps'); ?></h2>
  <form name="mainform" method="post" action="">
    <input type="hidden" value="1" name="options_update">

    <div id="tabs-wrap" class="">
      <ul class="tabs">
        <li class=""><a href="#tab1"><?php _e('General', 'appmaps'); ?></a></li>
      </ul>
      
      <div id="tab1" class="">
        <table class="widefat fixed" style="width:850px; margin-bottom:20px;">
          <thead>
            <tr>
              <th width="200px" scope="col"><?php _e('General', 'appmaps'); ?></th>
              <th scope="col">&nbsp;</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><?php _e('Activate?', 'appmaps'); ?></td>
              <td>
                <select name="appmaps_active">
                  <option value="no" <?php if(get_option('appmaps_active') == 'no'){ echo 'selected="selected"'; } ?> ><?php _e('No', 'appmaps'); ?></option>
                  <option value="yes" <?php if(get_option('appmaps_active') == 'yes'){ echo 'selected="selected"'; } ?> ><?php _e('Yes', 'appmaps'); ?></option>
                </select>
                <br /><small><?php _e('If "YES" is selected, then plugin will add a map to edit listing page.', 'mnet-langbf'); ?></small>
              </td>
            </tr>
            <tr>
              <td><?php _e('Default latitude', 'appmaps'); ?></td>
              <td>
                <input type="text" value="<?php echo get_option('appmaps_lat'); ?>" style="min-width:500px;" id="appmaps_lat" name="appmaps_lat" /><br />
                <small><?php _e('Default latitude on the map.', 'appmaps'); ?></small>
              </td>
            </tr>
            <tr>
              <td><?php _e('Default longitude', 'appmaps'); ?></td>
              <td>
                <input type="text" value="<?php echo get_option('appmaps_lng'); ?>" style="min-width:500px;" id="appmaps_lng" name="appmaps_lng" /><br />
                <small><?php _e('Default longitude on the map.', 'appmaps'); ?></small>
              </td>
            </tr>
            <tr>
              <td><?php _e('Google Maps API Key', 'appmaps'); ?></td>
              <td>
                <input type="text" value="<?php echo get_option('appmaps_api_key'); ?>" style="min-width:500px;" id="appmaps_api_key" name="appmaps_api_key" /><br />
                <small><?php _e('Get free API key for', 'appmaps'); ?> <a href="http://code.google.com/apis/maps/documentation/javascript/v2/introduction.html#Obtaining_Key" target="_new" title=""><?php _e('Google Maps','appmaps') ?></a>.</small>
              </td>
            </tr>
            <tr>
              <td><?php _e('Google Maps Location', 'appmaps'); ?></td>
              <td>
                <select name="appmaps_gmaps_loc" id="appmaps_gmaps_loc">
                <?php 
                  foreach($gmaps_domains as $key){
                    if($key == get_option('appmaps_gmaps_loc')){ 
                      $selected = 'selected="selected"'; 
                    }else{ 
                      $selected = ''; 
                    }
                    echo '<option value="'.$key.'" '.$selected.'>'.$key.'</option>';
                  } 
                ?>
                </select><br />
                <small><?php _e('Default location of map.', 'appmaps'); ?></small>
              </td>
            </tr>
          </tbody>
        </table>

      </div>


      <p class="submit">
        <input type="submit" name="Submit" class="button-primary" value="<?php _e('Save Changes', 'appmaps'); ?>" />
      </p>

    </div>    
  </form>

</div>    
<div class="clear"></div>