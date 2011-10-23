(function(){
	window.finalizar_castracion = function(){
		
	}	
	window.finalizar_castracion.prototype = {
		jqelements: null,
		url: null,
		init:function(params){
			this.jqelements = jQuery(params.select_elements);
			this.url = params.url;
			this.jqelements.addClass('finalizar_castracion_holder')
			this.jqelements.find('.link').click(this.handle_click_link.bindAsEventListener(this));
			this.jqelements.find('.cancelar').click(this.handle_click_cancelar.bindAsEventListener(this));
		},
		handle_click_link: function(e){
			var jqthis = jQuery(e.target);
			var jqli = jqthis.parents('.finalizar_castracion_holder');
			jqli.find('.form_finalizar_castracion').show();
			jqthis.hide();
			return false;
		},
		handle_click_cancelar: function(e){
			var jqthis = jQuery(e.target);
			var jqli = jqthis.parents('.finalizar_castracion_holder');
			jqli.find('.link').show();
			jqli.find('.form_finalizar_castracion').hide();
			return false;
		}
	}
})();


