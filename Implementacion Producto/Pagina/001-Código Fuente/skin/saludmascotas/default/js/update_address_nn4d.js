(function(){
  var myLoc = null;
  

	window.MapaNavTeq = {
		id_lat: null,
		id_lgn: null,
		txt_my_house: '',
		custom_onload: null,
		initialize: function( id_lat, id_lgn, id_mapa, txt_my_house, custom_onload ) {
			this.id_lat = id_lat;
			this.id_lgn = id_lgn;
			this.id_mapa = id_mapa;
			this.txt_my_house = txt_my_house;
			if( custom_onload )
				this.custom_onload = custom_onload;
			//var latlng = new google.maps.LatLng(-34.397, 150.644);
			//var latlng = new google.maps.LatLng(-31.420575005, -64.50019078);
			Map24.loadApi( ["core_api", "wrapper_api"] , this.map24ApiLoaded.bind(this) );
		},
		setCustomOnload: function( custom_onload ){
			this.custom_onload = custom_onload.bind(this);
			return this;
		},
		map24ApiLoaded: function (){
			Map24.MapApplication.init( { NodeName: this.id_mapa } );
			Map24.MapApplication.Map.addListener( "Map24.Event.MapClick", this.on_click.bind(this) );
			if( this.custom_onload ){
				try{
					if( typeof( this.custom_onload )=='string' ){
						eval( this.custom_onload );
					}
					else{
						this.custom_onload();
					}
				}catch( e ){
					window.console.log( 'no funciono el custom_onload' );
				}
			} 
		},
		on_click: function( e ){
//			var content = "<br /><center><b>It was clicked on the following coordinates of the map:</b></center><hr />"+
//			"Longitude: "+ e.Coordinate.Longitude +"<br /> Latitude: " +e.Coordinate.Latitude;
			var link_text = 'clicked point - ' + e.Coordinate.Longitude + ', ' + e.Coordinate.Latitude;
			var onclick_attr = " onclick=\"Map24.MapApplication.center( {Longitude:    " + e.Coordinate.Longitude + ", Latitude: " + e.Coordinate.Latitude + "});\"";
			var content = '<a href="javascript:void(0);"' + onclick_attr + '>' + link_text + '</a><br />';
			document.getElementById("coordinates").innerHTML = content;
			e.stop(); 
		},
		geocoder: null,
		contador_geocode: 0,
		try_geocode: function( searchText ){
			this.searchText = searchText;
			this.contador_geocode ++;
			setTimeout( this.try_geocode_timeout.bind(this), 2000 );
		},
		try_geocode_timeout: function(){
			this.contador_geocode--;
			if( this.contador_geocode==0 ){
				this.geocode( this.searchText );
			}
		},
		geocode: function ( searchText ){
			if(Map24.trim( searchText ) == "") { /*alert("Please enter an address."); */return; }
			this.geocoder = null;			
			if( this.geocoder==null ){
				this.geocoder = new Map24.GeocoderServiceStub();
			}
			//var geocoder = new Map24.GeocoderServiceStub();
			//Geocodes the address. The address is passed in the Search field. The Alternatives field defines the number
			//of geocoded addresses that are returned in the response. You must pass the name of the callback function
			//that is called as soon as the client has received the response.
			if(!(typeof searchText)=='object')
				searchText = Map24.trim( searchText );
			this.conn = this.geocoder.geocode( { SearchText: searchText, MaxNoOfAlternatives: 10, CallbackFunction: this.geocoderCallback.bind(this) } );
			return this;
		},
		geocoderCallback: function( locs ){
			//Center the map view on the first element in the array of geocoded addresses. A Map24.Location object has several
			//methods and properties which you can find in the API documentation for the corresponding class.
			Map24.MapApplication.center({
				Longitude: locs[0].getLongitude(), Latitude: locs[0].getLatitude(), MinimumWidth: 2500 
			});
			
//			//Create a list that shows the results.
			var result = "<h3>Search results:</h3><hr />";
			
			//Iterate through the array of locations.
			result += '<ul>';
			for( var i=0; i<locs.length; i++ ){
				var loc = locs[i];
				var link_text = [];
				if( loc.getStreet() ){
					link_text.push( loc.getStreet() );
				}
				if( loc.getZip() ){
					link_text.push( loc.getZip() );
				}
				if( loc.getDistrict() ){
					link_text.push( loc.getDistrict() );
				}
				if( loc.getCity() ){
					link_text.push( loc.getCity() );
				}
				if( loc.getCounty() ){
					link_text.push( loc.getCounty() );
				}
				if( loc.getCountry() ){
					link_text.push( loc.getCountry() );
				}
				link_text = link_text.join( ', ' );
				//Output all relevant properties of all locations.
				//result += "<b>Result Nr."+(i+1)+"</b><br />";
				//window.console.log( locs[i] );
				
				var onclick_attr = " onclick=\"Map24.MapApplication.center( {Longitude:    " + locs[i].getLongitude() + ", Latitude: " + locs[i].getLatitude() + "});\"";
//				result += "Longitude "+[i+1]+": "+locs[i].getLongitude()+"<br />";
//				result += "Latitude "+[i+1]+": "+locs[i].getLatitude()+"<br />";
//				
//				result += "City: "+locs[i].getCity()+"<br />";
//				result += "Zip: "+locs[i].getZip()+"<br />";
//				result += "County: "+locs[i].getCounty()+"<br />";
//				result += "State: "+locs[i].getState()+"<br />";
//				result += "Country: "+locs[i].getCountry()+"<br />";
//				result += "<input type=\"button\" value=\"Center on Result\"" + onclick_attr + " />";
//				result += "<hr/>";
				result += '<li><a href="javascript:void(0);"' + onclick_attr + '>' + link_text + '</a></li>';
				
				
			}
			result += '</ul>';
			document.getElementById( "geocodingresults" ).innerHTML = result;
		},
		add_my_house_on_click_listener: function(){
			Map24.MapApplication.Map.addListener( "Map24.Event.MapClick", this.add_my_house_on_click.bind(this) );
			return this;
		},
		add_null_on_click_listener: function(){
			Map24.MapApplication.Map.addListener( "Map24.Event.MapClick", function(){} );
			return this;
		},
		remove_my_house_on_click_listener: function(){
			Map24.MapApplication.Map.removeListener( "Map24.Event.MapClick", this.add_my_house_on_click.bind(this) );
			return this;
		},
		add_my_house_on_click: function( e ){
			this.add_my_house( e.Coordinate.Longitude, e.Coordinate.Latitude );
			e.stop();
		},
		add_test_house_location: function(){
			this.add_house_location( 500, 3138.7654, "This is a plain tooltip for the location which is visible on the interactive map." );
		},
		my_house_location: null,
		add_my_house_custom_listener: null,
		setAddMyHouseCustomListener: function( add_my_house_custom_listener ){
			this.add_my_house_custom_listener = add_my_house_custom_listener;
			return this;
		},
		add_my_house: function( Longitude, Latitude ){
			this.remove_my_house();
			this.my_house_location = this.add_house_location( Longitude, Latitude, this.txt_my_house );
			if( this.add_my_house_custom_listener ){
				try{
					if( this.add_my_house_custom_listener ){
						if( typeof(this.add_my_house_custom_listener)=='string'){
							eval( this.add_my_house_custom_listener );
						}
						else{
							this.add_my_house_custom_listener( Longitude, Latitude );
						}
					}
				}
				catch( e ){
					window.console.log( "add_my_house_custom_listener error" );
				}
			}
			return this;
		},
		remove_my_house: function(){
			if( this.my_house_location ){
				this.remove_location( this.my_house_location );
				this.my_house_location = null;
			}
			return this;
		},
		add_house_location: function ( Longitude, Latitude, Description ){
			return this.add_location( Longitude, Latitude, Description, "http://maptpzone.navteq.com/AJAXAPI/images/pin_maparea_home.png" );
		},
		add_location: function ( Longitude, Latitude, Description, LogoURL ){
			//Create a new location. 
			var myLoc = new Map24.Location({
				Longitude: Longitude,
				Latitude: Latitude,
				Description: Description,
				LogoURL: LogoURL
			});
			//Commit the location. Only after calling commit() it is possible 
			//to execute further operations on the location such as hide and show.
			myLoc.commit();
			
			return myLoc;
		},
		//Show the location if it is currently hidden.
		show_location: function ( myLoc ){
			myLoc.show();
			return this;
		},	
		//Hide the location if it is currently shown.
		hide_location: function ( myLoc ) {
			myLoc.hide();
			return this;
		},	
		//Remove the location.
		remove_location: function ( myLoc ) {
			myLoc.remove();
			return this;
		}
	}
})()

