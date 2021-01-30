/**
 * Google Map initiate
 *
 * Renders a Google Map onto the selected jQuery element
 *
 */
( function( $ ) {
	/**
	 * initMap
	 *
	 * Renders a Google Map onto the selected jQuery element
	 *
	 * @date    22/10/19
	 * @since   5.8.6
	 *
	 * @param { Object } $el The jQuery element.
	 * @return { Object } The map instance.
	 */
	function initMap( $el ) { // eslint-disable-line

		// Find marker elements within map.
		const $markers = $el.find( '.marker' );

		// Create gerenic map + styles in night mode.
		const mapArgs = {
			zoom: $el.data( 'zoom' ) || 16,
			mapTypeId: google.maps.MapTypeId.ROADMAP, // eslint-disable-line
			styles: [
				{
					elementType: 'geometry',
					stylers: [
						{
							color: '#ebe3cd',
						},
					],
				},
				{
					elementType: 'labels.text.fill',
					stylers: [
						{
							color: '#523735',
						},
					],
				},
				{
					elementType: 'labels.text.stroke',
					stylers: [
						{
							color: '#f5f1e6',
						},
					],
				},
				{
					featureType: 'administrative',
					elementType: 'geometry.stroke',
					stylers: [
						{
							color: '#c9b2a6',
						},
					],
				},
				{
					featureType: 'administrative.land_parcel',
					elementType: 'geometry.stroke',
					stylers: [
						{
							color: '#dcd2be',
						},
					],
				},
				{
					featureType: 'administrative.land_parcel',
					elementType: 'labels.text.fill',
					stylers: [
						{
							color: '#ae9e90',
						},
					],
				},
				{
					featureType: 'landscape.natural',
					elementType: 'geometry',
					stylers: [
						{
							color: '#dfd2ae',
						},
					],
				},
				{
					featureType: 'poi',
					elementType: 'geometry',
					stylers: [
						{
							color: '#dfd2ae',
						},
					],
				},
				{
					featureType: 'poi',
					elementType: 'labels.text.fill',
					stylers: [
						{
							color: '#93817c',
						},
					],
				},
				{
					featureType: 'poi.park',
					elementType: 'geometry.fill',
					stylers: [
						{
							color: '#a5b076',
						},
					],
				},
				{
					featureType: 'poi.park',
					elementType: 'labels.text.fill',
					stylers: [
						{
							color: '#447530',
						},
					],
				},
				{
					featureType: 'road',
					elementType: 'geometry',
					stylers: [
						{
							color: '#f5f1e6',
						},
					],
				},
				{
					featureType: 'road.arterial',
					elementType: 'geometry',
					stylers: [
						{
							color: '#fdfcf8',
						},
					],
				},
				{
					featureType: 'road.highway',
					elementType: 'geometry',
					stylers: [
						{
							color: '#f8c967',
						},
					],
				},
				{
					featureType: 'road.highway',
					elementType: 'geometry.stroke',
					stylers: [
						{
							color: '#e9bc62',
						},
					],
				},
				{
					featureType: 'road.highway.controlled_access',
					elementType: 'geometry',
					stylers: [
						{
							color: '#e98d58',
						},
					],
				},
				{
					featureType: 'road.highway.controlled_access',
					elementType: 'geometry.stroke',
					stylers: [
						{
							color: '#db8555',
						},
					],
				},
				{
					featureType: 'road.local',
					elementType: 'labels.text.fill',
					stylers: [
						{
							color: '#806b63',
						},
					],
				},
				{
					featureType: 'transit.line',
					elementType: 'geometry',
					stylers: [
						{
							color: '#dfd2ae',
						},
					],
				},
				{
					featureType: 'transit.line',
					elementType: 'labels.text.fill',
					stylers: [
						{
							color: '#8f7d77',
						},
					],
				},
				{
					featureType: 'transit.line',
					elementType: 'labels.text.stroke',
					stylers: [
						{
							color: '#ebe3cd',
						},
					],
				},
				{
					featureType: 'transit.station',
					elementType: 'geometry',
					stylers: [
						{
							color: '#dfd2ae',
						},
					],
				},
				{
					featureType: 'water',
					elementType: 'geometry.fill',
					stylers: [
						{
							color: '#b9d3c2',
						},
					],
				},
				{
					featureType: 'water',
					elementType: 'labels.text.fill',
					stylers: [
						{
							color: '#92998d',
						},
					],
				},
			],
		};
		const map = new google.maps.Map( $el[ 0 ], mapArgs ); // eslint-disable-line

		// Add markers.
		map.markers = [];
		$markers.each( function() {
			initMarker( $( this ), map );
		} );

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
	 * @param { Array } $marker Map Marker.
	 * @param { Object } map The map instance.
	 */
	function initMarker( $marker, map ) {
		// Get position from marker.
		const lat = $marker.data( 'lat' );
		const lng = $marker.data( 'lng' );
		const latLng = {
			lat: parseFloat( lat ),
			lng: parseFloat( lng ),
		};

		// Create marker instance.
		const marker = new google.maps.Marker( { // eslint-disable-line
			position: latLng,
			map,
		} );

		// Append to reference for later use.
		map.markers.push( marker );

		// If marker contains HTML, add it to an infoWindow.
		if ( $marker.html() ) {
			// Create info window.
			const infowindow = new google.maps.InfoWindow( { // eslint-disable-line
				content: $marker.html(),
			} );

			// Show info window when marker is clicked.
			google.maps.event.addListener( marker, 'click', function() { // eslint-disable-line
				infowindow.open( map, marker );
			} );
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
	 * @param   { Object } map The map instance.
	 */
	function centerMap( map ) {
		// Create map boundaries from all map markers.
		const bounds = new google.maps.LatLngBounds(); // eslint-disable-line
		map.markers.forEach( function( marker ) {
			bounds.extend( {
				lat: marker.position.lat(),
				lng: marker.position.lng(),
			} );
		} );

		// Case: Single marker.
		if ( map.markers.length === 1 ) {
			map.setCenter( bounds.getCenter() );

		// Case: Multiple markers.
		} else {
			map.fitBounds( bounds );
		}
	}
}( jQuery ) );
