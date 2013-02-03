<?php

$appmaps_key = 'appmaps';

/**
 * Disable automatic geocoding
 */
function appmaps_disable_geocoding() {
	if ( get_option('appmaps_active') == 'yes' ) {
		remove_action( 'added_post_meta', 'cp_do_update_geocode', 10, 4 );
		remove_action( 'updated_post_meta', 'cp_do_update_geocode', 10, 4 );
	}
}
add_action( 'appthemes_init', 'appmaps_disable_geocoding' );


/**
 * Add meta box on the listing edit admin page
 */
function appmaps_setup_meta_box() {
	if ( get_option('appmaps_active') == 'yes' ) {
		add_meta_box( 'appmaps-meta-box', __( 'Listing location', 'appmaps' ), 'appmaps_custom_meta_box', 'ad_listing', 'normal', 'high' );
	}
}
add_action( 'admin_menu', 'appmaps_setup_meta_box' );


/**
 * show the map in a custom meta box
 */
function appmaps_custom_meta_box() {
	global $wpdb, $post, $meta_boxes;

	// use nonce for verification
	wp_nonce_field( basename( __FILE__ ), 'appmaps_wpnonce', false, true );

	$post_id = $post->ID;
	$appmaps_latlng = appmaps_get_geocode( $post_id );
	if ( $appmaps_latlng ) {
		$appmaps_latitude = $appmaps_latlng['lat'];
		$appmaps_longitude = $appmaps_latlng['lng'];
	} else {
		$appmaps_latitude = get_option('appmaps_lat');
		$appmaps_longitude = get_option('appmaps_lng');
	}

	$appmaps_gmaps_lang = esc_attr( get_option('appmaps_gmaps_lang') );
	$appmaps_gmaps_region = esc_attr( get_option('appmaps_gmaps_region') );
	echo '<script src="http://maps.google.com/maps/api/js?sensor=false&amp;language='.$appmaps_gmaps_lang.'&amp;region='.$appmaps_gmaps_region.'" type="text/javascript"></script>';
?>
<script type="text/javascript">
//<![CDATA[
var newmap;
var js_logged_in = false;

Function.prototype.method = function (name, func) {
	this.prototype[name] = func;
	return this;
};

function mapmaker () {
	this.map;
	this.icon0;
	this.shadow0;
	this.infoWindow;
	this.newpoints = new Array();
	this.overlay;
	this.point;
	this.editmode = false;
	this.editnum;
	this.originalLat;
	this.originalLng;
	this.savedmap = false;
};

function init() {
	newmap = new mapmaker();
	newmap.Create();

	newmap.addpoint("<?php echo $appmaps_latitude; ?>","<?php echo $appmaps_longitude; ?>","<?php _e( 'Move marker', 'appmaps' ); ?>","<?php _e( 'Move this marker to right place', 'appmaps' ); ?>");
	newmap.editMarker(0);
};


mapmaker.method('Create', function() {
	var centerLocation = new google.maps.LatLng(<?php echo $appmaps_latitude; ?>,<?php echo $appmaps_longitude; ?>);
	var myOptions = {
		zoom: 13,
		center: centerLocation,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		mapTypeControlOptions: {
			style: google.maps.MapTypeControlStyle.DEFAULT
		}
	}
	this.map = new google.maps.Map(document.getElementById("appmaps_map"), myOptions);

	var self = this;
	google.maps.event.addListener(this.map, 'click', function(latlng) {
		self.onClick(latlng);
	});

});


mapmaker.method('onClick', function(point) {
	if ( this.editmode == false ) {
		if ( document.getElementById("appmaps_lockcheck").checked == true || document.getElementById("lockcheck").value==1 ) {
			document.getElementById("appmaps_longitude").value = point.latLng.lng();
			document.getElementById("appmaps_latitude").value = point.latLng.lat();
		} else {
			if (this.map.getZoom() < 17) this.map.setCenter(point, this.map.getZoom() + 1 );
		}
	} else {
		document.getElementById("appmaps_longitude").value = point.latLng.lng();
		document.getElementById("appmaps_latitude").value = point.latLng.lat();
		var num = this.editnum;
		newmap.moveMarker(num);
	}
});


mapmaker.method('moveMarker', function(num) {
	this.newpoints[num][0] = document.forms.post.appmaps_latitude.value;
	this.newpoints[num][1] = document.forms.post.appmaps_longitude.value;
	if ( num > -1 ) {
		return this.newpoints[num][4].setPosition(new google.maps.LatLng(this.newpoints[num][0],this.newpoints[num][1]));
	}
});


mapmaker.method('editMarker', function(num) {
	if ( newmap.editmode != true ) {
		this.originalLat = this.newpoints[num][0];
		this.originalLng = this.newpoints[num][1];
		this.map.setCenter(new google.maps.LatLng(this.newpoints[num][0],this.newpoints[num][1]),this.map.getZoom());
		//populate form fields
		document.forms.post.appmaps_latitude.value = this.newpoints[num][0];
		document.forms.post.appmaps_longitude.value = this.newpoints[num][1];
		this.editmode = true;
		this.editnum = num;
	}
});


mapmaker.method('addpoint', function(appmaps_latitude,appmaps_longitude,name,stuff) {
	var point = new google.maps.LatLng(appmaps_latitude, appmaps_longitude);
	var marker = newmap.createMarker(point,this.icon0,stuff);
	var newpoint = new Array(appmaps_latitude,appmaps_longitude,name,stuff,marker);
	this.newpoints[this.newpoints.length] = newpoint;
	return false;
});


mapmaker.method('createMarker', function(point, icon, stuff) {
	var marker = new google.maps.Marker({
		map: this.map,
		position: point,
	});

	var infoWindow = new google.maps.InfoWindow({
		maxWidth: 270,
		content: stuff,
		disableAutoPan: false
	});

	infoWindow.open(this.map, marker);

	google.maps.event.addListener(marker, 'click', function() {
		infoWindow.open(this.map, marker);
	});

	return marker;
});


function addLoadEvent(func) {
	var oldonload = window.onload;
	if ( typeof window.onload != 'function' ) {
		window.onload = func
	} else {
		window.onload = function() {
			oldonload();
			func();
		}
	}
}


addLoadEvent(init);
//]]>
</script>
<style>
div#appmaps_map {
	border: 3px solid #BBBBBB;
	height: 400px;
}
</style>

	<table class="form-table ad-meta-table">

		<tr>
			<th style="width:20%"><label for="appmaps_latitude"><?php _e('Latitude:', 'appmaps'); ?></label></th>
			<td><input type="text" value="<?php echo $appmaps_latitude; ?>" class="text" name="appmaps_latitude" id="appmaps_latitude"></td>
		</tr>

		<tr>
			<th style="width:20%"><label for="appmaps_longitude"><?php _e('Longitude:', 'appmaps'); ?></label></th>
			<td><input type="text" value="<?php echo $appmaps_longitude; ?>" class="text" name="appmaps_longitude" id="appmaps_longitude"></td>
		</tr>

		<tr>	
			<th colspan="2" style="padding:0px;"><div id="appmaps_map"></div></th>
		</tr>		

		<input type="hidden" value="1" class="check" id="appmaps_lockcheck">

	</table>
<?php
}


/**
 * save new coordinates for the ad listing
 */
function appmaps_save_meta_box( $post_id, $post ) {
	global $wpdb;

	// make sure something has been submitted from our nonce
	if ( ! isset( $_POST['appmaps_wpnonce'] ) )
		return $post_id;

	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times
	if ( ! wp_verify_nonce( $_POST['appmaps_wpnonce'], basename( __FILE__ ) ) )
		return $post_id;

	// verify if this is an auto save routine.
	// if it is our form and it has not been submitted, dont want to do anything
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return $post_id;

	// lastly check to make sure this user has permissions to save post fields
	if ( ! current_user_can( 'edit_post', $post_id ) )
		return $post_id;

	if ( $post->post_type == 'revision' )
		return $post_id;

	// update coordinates
	if ( ! empty( $_POST['appmaps_latitude'] ) && ! empty( $_POST['appmaps_longitude'] ) ) {
		$category = get_the_terms( $post_id, 'ad_cat' );
		if ( ! $category || is_wp_error( $category ) )
			$category[0]->name = '';
		cp_update_geocode( $post_id, $category[0]->name, $_POST['appmaps_latitude'], $_POST['appmaps_longitude'] );
	}

}
add_action( 'save_post', 'appmaps_save_meta_box', 1, 2 );


function appmaps_get_geocode( $post_id, $cat = '' ) {
	global $wpdb;
	$table = $wpdb->prefix . 'cp_ad_geocodes';
	if ( $cat )
		$row = $wpdb->get_row( $wpdb->prepare( "SELECT lat, lng FROM $table WHERE post_id = %d AND category = %s LIMIT 1", $post_id, $cat ) );
	else
		$row = $wpdb->get_row( $wpdb->prepare( "SELECT lat, lng FROM $table WHERE post_id = %d LIMIT 1", $post_id ) );

	if ( is_object( $row ) )
		return array( 'lat' => $row->lat, 'lng' => $row->lng );
	else
		return false;
}


