(function(){
	patrullajePrioridadesSelector = function(){
		
	}
	patrullajePrioridadesSelector.prototype = {
		jqcontainer: null,
		init: function(params/**/){
			this.jqcontainer = jQuery(params.select_container);
			this.jqcontainer.find('.selector_barrio').click(this.selector_barrio_handle_click.bindAsEventListener());
			this.jqcontainer.find('.deselector_barrio').click(this.deselector_barrio_handle_click.bindAsEventListener());
			return this;
		},
		selector_barrio_handle_click: function(e){
			var jqtarget = jQuery(e.target);
			jqtarget.hide();
			jqtarget.parent().find('.deselector_barrio').css('display','block');
			jqtarget.parent().find('input').attr('checked','checked');
		},
		deselector_barrio_handle_click: function(e){
			var jqtarget = jQuery(e.target);
			jqtarget.hide();
			jqtarget.parent().find('.selector_barrio').css('display','block');
			jqtarget.parent().find('input').attr('checked','');
		}
	}
})();