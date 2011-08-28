(function(){
	
	mapaGmap = function(){
		
	}
	mapaGmap.prototype = {
		maparea: null,
		map:null,
		markers:[],
		icon_types:{},
		infowindows:[],
		gmapopt:{
			zoom:14,
			center:new google.maps.LatLng(-1884.95593220339010000000/60, -3869.95999999995900000000/60),
			mapTypeId: google.maps.MapTypeId.HYBRID//HYBRID,ROADMAP,SATELLITE,TERRAIN
		},
		init: function(params){/*maparea,gmap_options(zoom,center,mapTypeId)*/
			this.maparea = params.maparea;
			this.jqcontainer = jQuery('#'+this.maparea);
			if(this.jqcontainer.length==0){
				alert("no existe el contenedor de mapa "+this.maparea);
				return;
			}
			//window.console.log(this.jqcontainer.get(0));
			if(params.gmap_options!=null)
				jQuery.extend(this.gmapopt, params.gmap_options)
			//window.console.log(params.gmap_options);
			if(params.lat!=null){
				if(params.lat!=''&&params.lat){
					this.gmapopt.center = new google.maps.LatLng(params.lat, params.lng);
				}
			}
			this.map = new google.maps.Map(this.jqcontainer.get(0), this.gmapopt);
			return this;
		},
		addIconType: function(params){
			var icon = params.icon;
			var name = params.name;
			var image_url = icon.url;
			var image_width = new google.maps.Size(icon.width, icon.height);
			var image_origin = new google.maps.Point(0,0);
			var image_hotspot = null;
			if(icon.hotspot_x!=null){
				image_hotspot = new google.maps.Point(icon.hotspot_x, icon.hotspot_y);
			}
			marker_icon = new google.maps.MarkerImage(image_url, image_width, image_origin, image_hotspot);
			this.icon_types['type_'+name] = marker_icon;
			return this;
		},
		getIconType: function(name){
			return this.icon_types['type_'+name];
		},
		addMarker: function(params/*lat,lng,title,image(url,width,height,hotspot_x,hotspot_y)*/){
			var latLng = new google.maps.LatLng(params.lat, params.lng);
			var title = params.title;
			var marker_params = {
		        position: latLng,
		        map: this.map,
		        title: title
		    };
			if(params.icon!=null){
				if(typeof(params.icon)=='string'){
					marker_params.icon = this.getIconType(params.icon);
				}
				else if(params.icon.url!=null && params.icon.width!=null && params.icon.height!=null){
					var image_url = params.icon.url;
					var image_width = new google.maps.Size(params.icon.width, params.icon.height);
					var image_origin = new google.maps.Point(0,0);
					var image_hotspot = null;
					if(params.icon.hotspot_x!=null){
						image_hotspot = new google.maps.Point(params.icon.hotspot_x, params.icon.hotspot_y);
					}
					marker_params.icon = new google.maps.MarkerImage(image_url, image_width, image_origin, image_hotspot);
				}
			}
			var marker = new google.maps.Marker(marker_params);
		    this.markers.push(marker);
		    return this.markers.length-1;
		},
		addMarkerListener: function(marker_id, event, callback){
			var marker = this.markers[marker_id];
			if(marker==null)
				return false;
			google.maps.event.addListener(marker, 'click', callback);
			return true;
		},
		addInfoWindow: function(marker_id, contentString){
			var that = this;
			var marker = this.markers[marker_id];
			if(marker==null)
				return false;
			this.infowindows[marker_id] = new google.maps.InfoWindow({
				content: contentString
			});
			google.maps.event.addListener(marker, 'click', function() {
				var infowindow = that.infowindows[marker_id];
				infowindow.open(that.map,marker);
//				setTimeout(function(){
//					that.changeInfoWindow(marker_id, 'holaaa');
//					that.reopenInfoWindow(marker_id);
//					infowindow.close();
//					alert("infowindow " + marker_id + "  cambiada");
//				}, 3000);
			});
			return true;
		},
		changeInfoWindow: function(marker_id, contentString){
			var infowindow = this.infowindows[marker_id];
			if(infowindow==null)
				return false;
			infowindow.content = contentString;
		},
		reopenInfoWindow: function(marker_id){
			var infowindow = this.infowindows[marker_id];
			if(infowindow==null)
				return false;
			var marker = this.markers[marker_id];
			infowindow.close();
			infowindow.open(this.map, marker);
		},
		setCenter: function(lat, lng){
			return this.map.setCenter(new google.maps.LatLng(lat, lng));
		},
		setZoom: function(zoom){
			return this.map.setZoom(zoom);
		}
	}
})();