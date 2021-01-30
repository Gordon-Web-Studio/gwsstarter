<?php
/**
 * WP_Rig\WP_Rig\Google_Map\Component class
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig\Google_Map;

use WP_Rig\WP_Rig\Component_Interface;
use WP_Rig\WP_Rig\Templating_Component_Interface;
use function WP_Rig\WP_Rig\wp_rig;
use function add_filter;
use function add_action;
use function get_post_type;

/**
 * Class for Google_Map.
 */
class Component implements Component_Interface, Templating_Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'google_map';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_filter( 'acf/fields/google_map/api', array( $this, 'acf_google_map_api' ) );
	}

	/**
	 * Gets template tags to expose as methods on the Template_Tags class instance, accessible through `wp_rig()`.
	 *
	 * @return array Associative array of $method_name => $callback_info pairs. Each $callback_info must either be
	 *               a callable or an array with key 'callable'. This approach is used to reserve the possibility of
	 *               adding support for further arguments in the future.
	 */
	public function template_tags() : array {
		return array(
			'enqueue_googlemap_scripts'    => array( $this, 'enqueue_googlemap_scripts' ),
			'initiate_google_map_script'   => array( $this, 'initiate_google_map_script' ),
			'initiate_animated_map_script' => array( $this, 'initiate_animated_map_script' ),
		);
	}

	/**
	 * Enqueue Google map script.
	 */
	public function enqueue_googlemap_scripts() {
		// If the AMP plugin is active, return early.
		if ( wp_rig()->is_amp() ) {
			return;
		}

		// Enqueue the google map api url script.
		$google_map_api_key = get_field( 'google_map_api_key', 'options' );

		if ( isset( $google_map_api_key ) ) {

			wp_enqueue_script( 'googleapis-maps', 'https://maps.googleapis.com/maps/api/js?key=' . $google_map_api_key, array( 'jquery' ), 'latest', true );

			wp_script_add_data( 'googleapis-maps', 'defer', true );
		}
	}

	/**
	 * Add Google Map API Key to ACF.
	 */
	public function acf_google_map_api() {
		$google_map_api_key = get_field( 'google_map_api_key', 'options' );

		if ( ! isset( $google_map_api_key ) ) {
			return;
		}

		$api['key'] = $google_map_api_key;

		return $api;
	}

	/**
	 * Initiate Google map script.
	 *
	 * @access public
	 * @param string $block_id The block ID.
	 * @param string $block_slug The block slug.
	 * @param string $selector target item for google-map.
	 * @return void
	 */
	public function initiate_google_map_script( $block_id, $block_slug, $selector = '.acf-map' ) {

		// Google map settings.
		$gmap_id       = str_replace( '-', '_', $block_id );
		$gmap_initiate = '
		jQuery( function( $ ) {

			/**
			 * initializeGoogleMap
			 *
			 * Adds Google Map script initializer to the block HTML.
			 *
			 * @return  void
			 */
			var initialize_gmap_' . $gmap_id . ' = function() {

				/**
				 * initMap
				 *
				 * Renders a Google Map onto the selected jQuery element
				 *
				 * @date    22/10/19
				 * @since   5.8.6
				 *
				 * @param   jQuery $el The jQuery element.
				 * @return  object The map instance.
				 */
				function initMap( $el ) {

					// Find marker elements within map.
					var $markers = $el.find(".marker");

					// Create gerenic map.
					var mapArgs = {
						zoom        : $el.data("zoom") || 16,
						mapTypeId   : google.maps.MapTypeId.ROADMAP
					};
					map = new google.maps.Map( $el[0], mapArgs );

					// Add markers.
					map.markers = [];
					$markers.each(function(){
						initMarker( $(this), map );
					});

					// Center map based on markers.
					centerMap( map );

					// Return map instance.
					return map;
				}

				/**
				 * initMarker
				 *
				 * Creates a marker for the given jQuery element and map.
				 *
				 * @date    22/10/19
				 * @since   5.8.6
				 *
				 * @param   jQuery $el The jQuery element.
				 * @param   object The map instance.
				 * @return  object The marker instance.
				 */
				function initMarker( $marker, map ) {

					// Get position from marker.
					var lat = $marker.data("lat");
					var lng = $marker.data("lng");
					var latLng = {
						lat: parseFloat( lat ),
						lng: parseFloat( lng )
					};

					// Create marker instance.
					var marker = new google.maps.Marker({
						position : latLng,
						map: map
					});

					// Append to reference for later use.
					map.markers.push( marker );

					// If marker contains HTML, add it to an infoWindow.
					if( $marker.html() ){

						// Create info window.
						var infowindow = new google.maps.InfoWindow({
							content: $marker.html()
						});

						// Show info window when marker is clicked.
						google.maps.event.addListener(marker, "click", function() {
							infowindow.open( map, marker );
						});
					}
				}

				/**
				 * centerMap
				 *
				 * Centers the map showing all markers in view.
				 *
				 * @date    22/10/19
				 * @since   5.8.6
				 *
				 * @param   object The map instance.
				 * @return  void
				 */
				function centerMap( map ) {

					// Create map boundaries from all map markers.
					var bounds = new google.maps.LatLngBounds();
					map.markers.forEach(function( marker ){
						bounds.extend({
							lat: marker.position.lat(),
							lng: marker.position.lng()
						});
					});

					// Case: Single marker.
					if( map.markers.length == 1 ){
						map.setCenter( bounds.getCenter() );

					// Case: Multiple markers.
					} else{
						map.fitBounds( bounds );
					}
				}

				$( "' . $selector . '" ).each( function() {
					var map = initMap( $( "' . $selector . '" ) );
				} );
			}

			// Initialize each block on page load (front end).
			$(document).ready(function() {
				initialize_gmap_' . $gmap_id . '();
			});

			// Initialize dynamic block preview (editor).
			if ( window.acf ) {
				window.acf.addAction( "render_block_preview/type=' . $block_slug . '", initialize_gmap_' . $gmap_id . ' );
			}
		} );
		';

		wp_add_inline_script( 'googleapis-maps', $gmap_initiate );
	}

	/**
	 * Initiate Animated Google map script.
	 *
	 * @access public
	 * @param string $block_id The block ID.
	 * @param string $block_slug The block slug.
	 * @param array  $origin Origin lat and lng.
	 * @param array  $destinations Destination list of lat and lng.
	 * @param array  $settings List of settings.
	 * @param array  $center Center location lat and lng.
	 * @return void
	 */
	public function initiate_animated_map_script(
		$block_id,
		$block_slug,
		$origin,
		$destinations,
		$settings = null,
		$center = null
	) {

		if ( ! $block_id || ! $block_slug || empty( $origin ) || empty( $destinations ) ) {
			return;
		}

		$origin_latlng = $origin['lat'] . ',' . $origin['lng'];
		$destinations  = json_encode( $destinations );

		$defaults = array(
			'draggable'                 => false,
			'pan_control'               => false,
			'street_view_control'       => false,
			'scroll_wheel'              => false,
			'scale_control'             => false,
			'disable_default_ui'        => true,
			'disable_double_click_zoom' => true,
			'zoom'                      => 3,
			'styles'                    => '[{"featureType":"all","elementType":"geometry","stylers":[{"visibility":"on"}]},{"featureType":"all","elementType":"geometry.fill","stylers":[{"visibility":"on"}]},{"featureType":"all","elementType":"geometry.stroke","stylers":[{"visibility":"on"}]},{"featureType":"all","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"all","elementType":"labels.text","stylers":[{"visibility":"off"}]},{"featureType":"all","elementType":"labels.text.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"visibility":"off"}]},{"featureType":"all","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"administrative","elementType":"geometry","stylers":[{"visibility":"on"},{"color":"#333739"},{"weight":0.8}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#259d76"}]},{"featureType":"landscape.natural","elementType":"labels.text","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"all","stylers":[{"color":"#259d76"},{"lightness":-7}]},{"featureType":"poi.park","elementType":"all","stylers":[{"color":"#259d76"}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"color":"#333739"},{"weight":0.3},{"lightness":10}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#259d76"},{"lightness":-28}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#259d76"},{"visibility":"on"},{"lightness":-15}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#259d76"},{"lightness":-18}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#259d76"},{"lightness":-34}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#333739"}]}]',
			'plane_color'               => '#ffffff',
			'path_color'                => '#fad000',
		);

		// Parse settings.
		$settings = wp_parse_args( $settings, $defaults );

		// Google map settings.
		$animated_map_id       = str_replace( '-', '_', $block_id );
		$animated_map_initiate = '

		let map = {};

		/**
		 * initializeAnimatedMap
		 *
		 * Adds Animated Map script initializer to the block HTML.
		 *
		 * @return  void
		 */
		var initialize_animap_' . $animated_map_id . ' = function() {

			var countries = ' . $destinations . ',
				originLat = ' . $origin['lat'] . ',
				originLng = ' . $origin['lng'] . ',
				settingsCenterLat = ' . ( ! empty( $center ) ? $center['lat'] : $origin['lat'] ) . ',
				settingsCenterLng = ' . ( ! empty( $center ) ? $center['lng'] : $origin['lng'] ) . ';

			function loadMap() {

				var options = {
					draggable: ' . wp_rig()->bool( $settings['draggable'] ) . ',
					panControl: ' . wp_rig()->bool( $settings['pan_control'] ) . ',
					streetViewControl: ' . wp_rig()->bool( $settings['street_view_control'] ) . ',
					scrollwheel: ' . wp_rig()->bool( $settings['scroll_wheel'] ) . ',
					scaleControl: ' . wp_rig()->bool( $settings['scale_control'] ) . ',
					disableDefaultUI: ' . wp_rig()->bool( $settings['disable_default_ui'] ) . ',
					disableDoubleClickZoom: ' . wp_rig()->bool( $settings['disable_double_click_zoom'] ) . ',
					zoom: isMobile() ? 2 : ' . $settings['zoom'] . ',
					center: new google.maps.LatLng( isMobile() ? originLat : settingsCenterLat , isMobile() ? originLng : settingsCenterLng ),
					styles: ' . $settings['styles'] . '
				};
				mapObject = new google.maps.Map(document.getElementById("mapCanvas"), options);
			}

			var planeSymbol	= {
				path: "M22.1,15.1c0,0-1.4-1.3-3-3l0-1.9c0-0.2-0.2-0.4-0.4-0.4l-0.5,0c-0.2,0-0.4,0.2-0.4,0.4l0,0.7c-0.5-0.5-1.1-1.1-1.6-1.6l0-1.5c0-0.2-0.2-0.4-0.4-0.4l-0.4,0c-0.2,0-0.4,0.2-0.4,0.4l0,0.3c-1-0.9-1.8-1.7-2-1.9c-0.3-0.2-0.5-0.3-0.6-0.4l-0.3-3.8c0-0.2-0.3-0.9-1.1-0.9c-0.8,0-1.1,0.8-1.1,0.9L9.7,6.1C9.5,6.2,9.4,6.3,9.2,6.4c-0.3,0.2-1,0.9-2,1.9l0-0.3c0-0.2-0.2-0.4-0.4-0.4l-0.4,0C6.2,7.5,6,7.7,6,7.9l0,1.5c-0.5,0.5-1.1,1-1.6,1.6l0-0.7c0-0.2-0.2-0.4-0.4-0.4l-0.5,0c-0.2,0-0.4,0.2-0.4,0.4l0,1.9c-1.7,1.6-3,3-3,3c0,0.1,0,1.2,0,1.2s0.2,0.4,0.5,0.4s4.6-4.4,7.8-4.7c0.7,0,1.1-0.1,1.4,0l0.3,5.8l-2.5,2.2c0,0-0.2,1.1,0,1.1c0.2,0.1,0.6,0,0.7-0.2c0.1-0.2,0.6-0.2,1.4-0.4c0.2,0,0.4-0.1,0.5-0.2c0.1,0.2,0.2,0.4,0.7,0.4c0.5,0,0.6-0.2,0.7-0.4c0.1,0.1,0.3,0.1,0.5,0.2c0.8,0.2,1.3,0.2,1.4,0.4c0.1,0.2,0.6,0.3,0.7,0.2c0.2-0.1,0-1.1,0-1.1l-2.5-2.2l0.3-5.7c0.3-0.3,0.7-0.1,1.6-0.1c3.3,0.3,7.6,4.7,7.8,4.7c0.3,0,0.5-0.4,0.5-0.4S22,15.3,22.1,15.1z",
				fillColor: "' . $settings['plane_color'] . '",
				fillOpacity: 1,
				scale: 1.2,
				anchor: new google.maps.Point(11, 11),
				strokeWeight: 0
			};

			function animate(oPlan) {
				// Convert the points into google latlng objects
				var sP = new google.maps.LatLng(oPlan.startPoint[0],oPlan.startPoint[1]);
				var eP = new google.maps.LatLng(oPlan.endPoint[0],oPlan.endPoint[1]);

				// Polyline for the planes path
				oPlan.planePath = new google.maps.Polyline({
					path: [sP, eP],
					strokeColor: "' . $settings['path_color'] . '",
					strokeWeight: 0,
					icons: [{
						icon: planeSymbol,
						offset: "0%"
					}],
					map: mapObject,
					geodesic: true
				});

				oPlan.trailPath = new google.maps.Polyline({
					path: [sP, sP],
					strokeColor: "' . $settings['path_color'] . '",
					strokeWeight: 2.5,
					strokeOpacity: 0.6,
					map: mapObject,
					geodesic: true
				});

				oPlan.googleStartPoint = sP;
				oPlan.googleEndPoint = eP;

				// Execute the animation plan
				oPlan.animLoop = window.requestAnimationFrame(function(){
					execute(oPlan);
				});
			}

			function execute(oPlan) {
				oPlan.animIndex+=0.25;

				// Draw trail
				var nextPoint =	google.maps.geometry.spherical.interpolate(oPlan.googleStartPoint,oPlan.googleEndPoint,oPlan.animIndex/100);
				oPlan.trailPath.setPath([oPlan.googleStartPoint, nextPoint]);

				// Plane movement
				oPlan.planePath.icons[0].offset=Math.min(oPlan.animIndex,100)+"%";
				oPlan.planePath.setPath(oPlan.planePath.getPath());

				// If the plan is not finished, keep executing it.
				if(oPlan.animIndex<100) {
					oPlan.animLoop = window.requestAnimationFrame(function(){
						execute(oPlan);
					});
				}
				else{
					// Decrement the active number of animations to signal
					// the wait loop to start spinning up more animations.
					activeAnimations--;
				}
			}

			// This animation wait function prevents executing the animations
			// for all locations at once.
			// To bypass this, update the maximumAnimations var to 300.
			function wait()
			{
				setTimeout(() =>
				{
					if (activeAnimations < maximumAnimations)
					{
						setTimeout(() => {
							animateNext();
						}, 100);

					}
					else
					{
						setTimeout(() => {
							wait();
						}, 100);
					}
				}, 100);
			}

			// Function called to execute one new animation
			function animateNext()
			{
				var oPlan = {};
				oPlan.index = i;
				oPlan.startPoint = [' . $origin_latlng . '];
				oPlan.endPoint = [countries[i].location.lat,countries[i].location.lng];
				oPlan.animLoop = false;
				oPlan.animIndex = 0;
				oPlan.planePath = false;
				oPlan.trailPath = false;
				animPlans.push(oPlan);
				animate(oPlan);

				activeAnimations++;

				i++;
				if (i >= total) return;

				wait();
			}

			// Get real window width
			function getWindowWidth() {
				let windowWidth = 0;
				if ( typeof window.innerWidth == "number" ) {
					windowWidth = window.innerWidth;
				} else {
					if ( document.documentElement && document.documentElement.clientWidth ) {
						windowWidth = document.documentElement.clientWidth;
					} else {
						if ( document.body && document.body.clientWidth ) {
							windowWidth = document.body.clientWidth;
						}
					}
				}
				return windowWidth;
			}

			// Responsive issues.
			function isMobile() {
				let ww = getWindowWidth();
				if ( ww < 768 ) {
					return true;
				} else if ( ww >= 768 ) {
					return false;
				}
			}

			/* Configuration */
			var maximumAnimations = 10;

			/* Global Variables */
			var animPlans = [];
			var total = countries.length;
			var i = 0;
			var activeAnimations = 0;

			/* Start Methods */
			loadMap();
			animateNext();
		}

		// Initialize each block on page load (front end).
		window.addEventListener( "load", () => {
			initialize_animap_' . $animated_map_id . '();
		} );

		// Initialize dynamic block preview (editor).
		if ( window.acf ) {
			window.acf.addAction( "render_block_preview/type=' . $block_slug . '", initialize_animap_' . $animated_map_id . ' );
		}
		';

		wp_add_inline_script( 'googleapis-maps', $animated_map_initiate );
	}
}
