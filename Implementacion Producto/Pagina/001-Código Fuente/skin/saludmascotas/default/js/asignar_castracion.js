(function(){
	window.asginar_castracion = function(){
		
	}	
	window.asginar_castracion.prototype = {
		jqelements: null,
		url: null,
		init:function(params){
			this.jqelements = jQuery(params.select_elements);
			this.url = params.url;
			this.jqelements.addClass('asignar_castracion_holder')
			this.jqelements.find('.link').click(this.handle_click_link.bindAsEventListener(this));
			this.jqelements.find('.cancelar').click(this.handle_click_cancelar.bindAsEventListener(this));
//			this.jqedad = jQuery(select_edad);
//			this.jqfecha_nacimiento = jQuery(select_fecha_nacimiento);
//			this.fecha_actual = fecha_actual.split('/');
//			this.fecha_actual[0] = new Number(this.fecha_actual[0])+0;
//			this.fecha_actual[1] = new Number(this.fecha_actual[1])+0;
//			this.fecha_actual[2] = new Number(this.fecha_actual[2])+0;
//			this.jqedad.change(this.handle_edad_change.bindAsEventListener(this));
//			this.jqfecha_nacimiento.change(this.handle_fecha_nacimiento_change.bindAsEventListener(this));
		},
		handle_click_link: function(e){
			var jqthis = jQuery(e.target);
			var jqli = jqthis.parents('.asignar_castracion_holder');
			jqli.find('.form_asignar_castracion').show();
			jqthis.hide();
			return false;
		},
		handle_click_cancelar: function(e){
			var jqthis = jQuery(e.target);
			var jqli = jqthis.parents('.asignar_castracion_holder');
			jqli.find('.link').show();
			jqli.find('.form_asignar_castracion').hide();
			return false;
		}
//		handle_edad_change: function(e){
//			var edad = this.jqedad.val();
//			edad = edad.replace(/^\s+|\s+$/g, '');
//			if(edad=='')
//				return;
//			edad = edad.replace(/\s+/g, ' ');
//			edad = edad.split(' ');
//			var numero = new Number(edad[0].replace(/^\s+|\s+$/g, ''));
//			var metrica = 'a';
//			if(edad.length>1){
//				var metrica_ingresada = edad[1];
//				switch(metrica_ingresada.toLowerCase()){
//					case 'dia':
//					case 'dias':
//					case 'días':
//					case 'día':{
//						metrica = 'd';
//						break;
//					}
//					case 'mes':
//					case 'meses':{
//						metrica = 'm';
//						break;
//					}
//					case 'anios':
//					case 'anio':
//					case 'año':
//					case 'años':
//					default:{
//						metrica = 'a';
//						break;
//					}
//				}
//			}
//			var label_metricas = [
//				{
//					'a':'año',
//					'm':'mes',
//					'd':'día'
//				},
//				{
//					'a':'años',
//					'm':'meses',
//					'd':'días'
//				}
//			]
//			edad = numero + ' ' + label_metricas[numero>1?1:0][metrica];
//			this.jqedad.val(edad);
//			var indexes = {'a':2,'m':1,'d':0};
//			//window.console.log(this.fecha_actual);
//			
//			var fecha_nacimiento = this.fecha_actual.join('/');
//			var interval = {'a':'yyyy','m':'m','d':'d'};
////			window.console.log(interval[metrica], -numero, this.fecha_actual.join('/'));
////			window.console.log([this.fecha_actual[1], this.fecha_actual[0], this.fecha_actual[2]].join('/'));
//			fecha_nacimiento = DateAdd(interval[metrica], -numero, [this.fecha_actual[1], this.fecha_actual[0], this.fecha_actual[2]].join('/'));
//			fecha_nacimiento = [fecha_nacimiento.getDate(), fecha_nacimiento.getMonth()+1, fecha_nacimiento.getFullYear()];
//			fecha_nacimiento = fecha_nacimiento.join('/');
//			fecha_nacimiento = fecha_nacimiento.replace(/([^0-9])([0-9])[/]/g,'$10$2/');
//			this.jqfecha_nacimiento.val( fecha_nacimiento ); 
//			return;
//		},
//		handle_fecha_nacimiento_change: function(e){
//			var fecha_nacimiento = this.jqfecha_nacimiento.val();
//			fecha_nacimiento = fecha_nacimiento.replace(/^\s+|\s+$/g, '');
//			if(!fecha_nacimiento){
//				return;
//			}
//			
//			fecha_nacimiento = fecha_nacimiento.split('/');
//			var dia_nacimiento = new Number(fecha_nacimiento[0])+0;
//			var mes_nacimiento = new Number(fecha_nacimiento[1])+0;
//			var anio_nacimiento = new Number(fecha_nacimiento[2])+0;
//
//			var dia_actual = this.fecha_actual[0];
//			var mes_actual = this.fecha_actual[1];
//			var anio_actual = this.fecha_actual[2];
//			
//			var edad = calculateAgeFormatted(dia_nacimiento, mes_nacimiento, anio_nacimiento, dia_actual, mes_actual, anio_actual);
//			if(edad==null)
//				edad = 'fecha incorrecta';
//			this.jqedad.val(edad).focus();
//		}
	}
})();


