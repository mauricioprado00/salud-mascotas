(function(){
	selectorTamanoMascota = function(){
		
	}
	selectorTamanoMascota.prototype = {
		id_container: null,
		id_selector_tamano: null,
		id_selector_especie: null,
		valid_options: null,
		jqContainer: null,
		jqSelectorTamano: null,
		jqSelectorEspecie: null,
		especie_actual: 0,
		init: function(params/*id_container,id_selector_tamano,id_selector_especie,valid_options*/){
			this.id_container = params.id_container;
			this.id_selector_tamano = params.id_selector_tamano;
			this.id_selector_especie = params.id_selector_especie;
			this.valid_options = params.valid_options;
			
			this.jqContainer = jQuery('#'+this.id_container);
			this.jqSelectorTamano = jQuery('#'+this.id_selector_tamano);
			this.jqSelectorEspecie = jQuery('#'+this.id_selector_especie);
			
			//this.jqSelectorTamano.change(this.selector_tamano_change.bindAsEventListener(this)).change();
			this.jqSelectorEspecie.change(this.selector_especie_change.bindAsEventListener(this));
			window.console.log(this.jqSelectorEspecie);
			
			this.create_selector();
		},
		create_selector: function(){
			var that = this;
			this.jqSelectorTamano.find('option').each(function(idx, option){
				if(option.value==''){
					return;
				}
				var option_name = option.value.toLowerCase().replace(/\s+/g, '_');
				var jqel = jQuery('<div></div>')
					.addClass('selector_tamano_option selector_tamano_option_'+option_name)
					.click(that.option_click_emulate.bind(that,option.value))
					.appendTo(that.jqContainer)
				;
			});
			var jqel = jQuery('<div></div>').css('clear','both').appendTo(that.jqContainer);
			this.change_especie();
		},
		option_click_emulate: function(value){
//			window.console.log('seteando valor ' + value);
//			window.console.log(this);
			this.jqSelectorTamano.val(value);//.find('option[value='+value+']').attr('selected','selected');
			this.init_selected();
		},
		change_especie: function(){
			this.jqContainer.removeClass('selector_tamano_'+this.especie_actual);
			this.especie_actual = parseInt(this.jqSelectorEspecie.val());
			this.jqContainer.addClass('selector_tamano_'+this.especie_actual);
			this.init_selected();
			//window.console.log(this.valid_options.indexOf(parseInt(this.especie_actual)), this.especie_actual,this.valid_options);
			if(this.valid_options.indexOf(this.especie_actual)!=-1){
				this.jqSelectorTamano.hide();
			}
			else{
				this.jqSelectorTamano.show();
			}
		},
		init_selected: function(){
			this.jqContainer.find('.selected').removeClass('selected');
			var val = this.jqSelectorTamano.val();
			var option_name = val.toLowerCase().replace(/\s+/g, '_');
			this.jqContainer.find('.selector_tamano_option_'+option_name).addClass('selected');
		},
		selector_especie_change: function(e){
			var _this = e.target;
			this.change_especie();
		}
	}
})();