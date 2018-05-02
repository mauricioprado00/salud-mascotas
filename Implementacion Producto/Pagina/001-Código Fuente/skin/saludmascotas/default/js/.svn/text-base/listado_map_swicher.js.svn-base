(function(){
	listadoMapSwicher = function(){
		
	}
	listadoMapSwicher.prototype = {
		map_js_object_name: null,
		base_url: null,
		domicilios: null,
		domicilios_vistos: null,
		image_type_visto: null,
		image_type_no_visto: null,
		markers_ids: [],
		init: function(params/*map_js_object_name,base_url,domicilios{id,lat,lng},domicilios_vistos[id1,id2],image_type_visto,image_type_no_visto*/){
			this.map_js_object_name = params.map_js_object_name;
			this.mapaGmap = window[this.map_js_object_name];
			this.base_url = params.base_url;
			this.domicilios = params.domicilios;
			this.domicilios_vistos = params.domicilios_vistos;
			this.image_type_visto = params.image_type_visto;
			this.image_type_no_visto = params.image_type_no_visto;
			if(this.mapaGmap==null){
				alert("error, no se encontro mapa para cargar mascotas[js/listado_map_swicher.js]");
				return this;
			}
			this.init_add_points();
			return this;
		},
		init_add_points: function(){
			var that = this;
			jQuery(this.domicilios_vistos).each(function(idx, idx_domicilio){
				if(that.domicilios[idx_domicilio]==null)
					return;
				that.domicilios[idx_domicilio].visto = true;
			});
			jQuery(this.domicilios).each(function(idx_domicilio,domicilio){
				if(domicilio.current==true)
					return;
				var lat = domicilio.lat;
				var lng = domicilio.lng;
				var icon_type = domicilio.visto==true?that.image_type_visto:that.image_type_no_visto;
				var marker_id = that.mapaGmap.addMarker({
					lat: lat,
					lng: lng,
					title: 'mascota' +domicilio.visto==true?'(vista)':'',
					icon: icon_type
				});
				that.mapaGmap.addMarkerListener(marker_id, 'click', that.switchPage.bind(that,idx_domicilio));
				//var contentString = jQuery(this).find('.infowindow_content').html();
				//contentString += that.createLinks(this, marker_id);
				//that.mapaGmap.addInfoWindow(marker_id, contentString);
				that.markers_ids[idx_domicilio] = marker_id;
			});
		},
		createLinks: function(o,id){
			var jqcheckbox = jQuery(o).find('input[type=checkbox]');
			if(!jqcheckbox.length)
				return '';
			var jqcontainer = jQuery('<div></div>');
			var text = '';
			if(jqcheckbox.get(0).checked){
				text = '<hr />Has MARCADO la mascota como coincidencia';
				//jQuery('<b>Creo que es esta | </b>').appendTo(jqcontainer);
				jQuery('<a href="#coincidencias/deseleccionar/'+id+'">No esta no es</a>').appendTo(jqcontainer);
			}
			else{
				text = '<hr />Has DESMARCADO la mascota como coincidencia';
				jQuery('<a href="#coincidencias/seleccionar/'+id+'">Creo que es esta</a>').appendTo(jqcontainer);
				//jQuery('<b> | No esta no es</b>').appendTo(jqcontainer);
			}
			return '<hr />' + jqcontainer.html() + text;
		},
		switchPage:function(page){
			document.location.href = this.base_url + '/' + page ;
		}
	}
})();