(function(){
	etiquetaMascota = function(){
		
	}
	etiquetaMascota.prototype = {
		jqcontainer: null,
		id_mascota: null,
		url_action: null,
		init: function(params/**/){
			this.jqcontainer = jQuery(params.select_container);
			this.id_mascota = params.id_mascota;
			this.url_action = params.url_action;
			this.jqcontainer.find('.change').click(this.change_handle_click.bindAsEventListener(this));
			return this;
		},
		change_handle_click: function(e){
			var jqel = jQuery(e.target);
			var jqli = jqel.parents('li:first');
			var id_etiqueta = jqel.parent().attr('data-id');
			var id_mascota = this.id_mascota;
			jQuery.ajax({
				type:'post',
				url: this.url_action,
				data:{
					id_etiqueta: id_etiqueta,
					id_mascota: id_mascota
				}
			});
			if(jqli.hasClass('etiqueta_mascota_agregada'))
				jqli.removeClass('etiqueta_mascota_agregada');
			else jqli.addClass('etiqueta_mascota_agregada');
			return false;
		}
	}
})();