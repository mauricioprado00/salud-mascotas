(function(){
	window.selector_colores = function(){
		
	}
	selector_colores.prototype = {
		container_id: null,
		init:function(params){
			try{
				this.container_id = params.container_id;
				this.jqcontainer = jQuery('#'+this.container_id);
				this.jqselect_cant = this.jqcontainer.find('select');
				this.jqpaleta = this.jqcontainer.find('.paleta');
				this.jqcolores_seleccionados = this.jqcontainer.find('.colores_seleccionados');
				this.jqcolores_seleccionados.find('li').each(this.initialize_colores_seleccionados.bind(this));
				this.jqpaleta.find('li').click(this.handle_paleta_li_click.bindAsEventListener(this));
				this.jqselect_cant.change(this.handle_select_cant_change.bindAsEventListener(this)).change();
			}
			catch(e){
				window.console.log(e);
			}
		},
		initialize_colores_seleccionados: function(idx, _this){
			var jqthis = jQuery(_this);
			var color = jqthis.find('input').val();
			jqthis.css('background-color', '#'+color);
			jqthis.click(this.handle_color_seleccionado_click.bindAsEventListener(this));
		},
		handle_color_seleccionado_click: function(e){
			var _this = e.target;
			var jqthis = jQuery(_this);
			this.togglePaleta(jqthis);
		},
		togglePaleta: function(jqli){
			var jqseleccionando = this.jqcolores_seleccionados.find('.seleccionando');
			if(jqseleccionando.length){
				jqseleccionando.removeClass('seleccionando');
				if(jqli.get(0)!=jqseleccionando.get(0)){
					jqli.addClass('seleccionando');
				}
				else this.jqpaleta.hide();
			}
			else{
				jqli.addClass('seleccionando');
				this.jqpaleta.show();
			}
			
		}, 
		handle_paleta_li_click: function(e){
			var jqseleccionando = this.jqcolores_seleccionados.find('.seleccionando');
			var color = jQuery(e.target).attr('data-color');
			jqseleccionando.css('background-color', '#'+color);
			jqseleccionando.find('input').val(color);
			this.togglePaleta(jqseleccionando);
		},
		handle_select_cant_change: function(e){
			var _this = e.target;
			var jqthis = jQuery(_this);
			var cant = new Number(jqthis.val());
			this.jqcolores_seleccionados.find('li').removeClass('show_color');
			this.jqcolores_seleccionados.find('input').removeAttr('checked');
			if(cant){
				this.jqcolores_seleccionados.find('li:lt('+(cant)+')').addClass('show_color');
				this.jqcolores_seleccionados.find('input:lt('+(cant)+')').attr('checked','checked');
			}
//			this.jqcolores_seleccionados.find('li').each(function(i, _this){
//				
//			});
		}
	}
})();
