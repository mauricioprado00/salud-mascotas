(function(){
	selectorCoincidencias = function(){
		
	}
	selectorCoincidencias.prototype = {
		jqcontainer: null,
		map_js_object_name: null,
		mapaGmap: null,
		markers_ids: [],
		rows: [],
		init: function(params/*container_id,map_js_object_name*/){
			this.jqcontainer = jQuery('#'+params.container_id);
			this.map_js_object_name = params.map_js_object_name;
			this.mapaGmap = window[this.map_js_object_name];
			if(this.mapaGmap==null){
				alert("error, no se encontro mapa para realizar coincidencias[js/coincidencias.js]");
				return this;
			}
			//window.console.log(,this.container,params.container_id);
			this.init_add_points();
			this.poolUrl();
		},
		init_add_points: function(){
			var that = this;
			this.jqcontainer.find('>ul>li').each(function(idx,item){
//.addMarker(<?php print json_encode(array(
//			'lat'=>$lat/60,
//			'lng'=>$lng/60,
//			'title'=>'mascota',
//			'icon'=>'normal',//
//		)); ?>); 
				var lat = jQuery(this).find('input.lat').val();
				lat = new Number(lat)+0;
				var lng = jQuery(this).find('input.lng').val();
				lng = new Number(lng)+0;
				var icon_type = jQuery(this).find('input.icon_type').val();
//				window.console.log('agregando', {
//					lat: lat,
//					lng: lng,
//					title: 'mascota',
//					icon: icon_type
//				});
				var marker_id = that.mapaGmap.addMarker({
					lat: lat,
					lng: lng,
					title: 'mascota',
					icon: icon_type
				});
				var contentString = jQuery(this).find('.infowindow_content').html();
				contentString += that.createLinks(this, marker_id);
				that.mapaGmap.addInfoWindow(marker_id, contentString);
				that.markers_ids[idx] = marker_id;
				that.rows[marker_id] = this;
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
		procesarAccion: function(accion, marker_id){
			var reopen = false;
			var text = '';
			switch(accion){
				case 'deseleccionar':{
					var jqel = jQuery(this.rows[marker_id]);
					jqel.find('input[type=checkbox]').removeAttr('checked');
					window.location.href = '#deseleccionado';
					reopen = true;					
					break;
				}
				case 'seleccionar':{
					var jqel = jQuery(this.rows[marker_id]);
					jqel.find('input[type=checkbox]').attr('checked','checked');
					window.location.href = '#seleccionado';
					reopen = true;
					break;
				}
			}
			if(reopen){
				var contentString = jqel.find('.infowindow_content').html();
				contentString += this.createLinks(jqel.get(0), marker_id) + text;
				this.mapaGmap.changeInfoWindow(marker_id, contentString);
				this.mapaGmap.reopenInfoWindow(marker_id);
			}
		},
		goLink: function(url_link){
			this.prev_link_url = this.current_link_url;
			this.current_link_url = url_link;
			url_link = url_link.toString().split('#');
			if(url_link.length>1){
				var patt = (/coincidencias[/]([a-zA-Z]+)([/]([0-9]+))?/g);
				ret = patt.exec(url_link[1]);
				if(ret!=null){
					if(ret.length==4&&ret[3]!=null){
						var accion = ret[1];
						var id = ret[3];
						id = parseInt(id);
						//window.console.log(accion + '     ' + id);
						this.procesarAccion(accion,id);
					}
				}
			}
		},
		switchPage:function(){
			if(this.current_link_url != document.location.href.toString()){
				this.goLink(document.location.href);
				return true;
			}
			return false;
		},
		poolUrl: function(){
			this.switchPage();
			setTimeout(this.poolUrl.bind(this), 100);
		},
	}
})();