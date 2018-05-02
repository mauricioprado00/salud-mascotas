(function(){
	function diasEnMes(mes, anio){
		var date = new Date(mes+'/29/'+anio);
		if((date.getMonth()+1)==mes){
			date = new Date(mes+'/30/'+anio);
			if((date.getMonth()+1)==mes){
				date = new Date(mes+'/31/'+anio);
				if((date.getMonth()+1)==mes){
					return 31;
				}
				return 30;
			}
			return 29;
		}
		return 28;
	}
	function calcularEdad(dia_nacimiento, mes_nacimiento, anio_nacimiento, dia_actual, mes_actual, anio_actual){
		var anios = anio_actual - anio_nacimiento;
		var meses = 0;
		var dias = 0;
		var edad = null;
		//filtro todas las fechas invalidas
		if(	anio_actual<anio_nacimiento ||//si el año futuro
			anio_actual==anio_nacimiento&&mes_actual<mes_nacimiento ||//si es mismo año y mes futuro 
			anio_actual==anio_nacimiento&&mes_actual==mes_nacimiento&&dia_actual<dia_nacimiento ){//si es mismo año y mes pero dia futuro
			edad = null;
		}
		else{
			if(anio_actual>anio_nacimiento && (mes_actual<mes_nacimiento || (mes_actual==mes_nacimiento && dia_actual<dia_nacimiento)))
				anios--;
			if(anios==0){
				anio_actual+=0;
				anio_nacimiento+=0;
				if(anio_actual==anio_nacimiento){
					meses = mes_actual - mes_nacimiento;
				}
				else{//año pasado
					meses = 12 + mes_actual - mes_nacimiento;
				}
				if(dia_actual<dia_nacimiento)
					meses--;
				if(meses){
					if(meses>1)
						edad = meses + ' meses';
					else
						edad = meses + ' mes';
				}
				else{
					if(mes_nacimiento == mes_actual)
						dias = dia_actual - dia_nacimiento;
					else dias = diasEnMes(mes_nacimiento, anio_actual) + dia_actual - dia_nacimiento;
					if(dias>1)
						edad = dias + ' dias';
					else edad = dias + ' dia';
				}
			}
			else{
				if(anios>1)
					edad = anios + ' años';
				else edad = anios + ' año';
			}
		}
		return edad;
	}
	window.calcular_edad = function(){
		
	}	
	window.calcular_edad.prototype = {
		jqedad: null,
		jqfecha_nacimiento: null,
		fecha_actual: null,
		init:function(select_edad, select_fecha_nacimiento, fecha_actual){
			this.jqedad = jQuery(select_edad);
			this.jqfecha_nacimiento = jQuery(select_fecha_nacimiento);
			this.fecha_actual = fecha_actual.split('/');
			this.fecha_actual[0] = new Number(this.fecha_actual[0])+0;
			this.fecha_actual[1] = new Number(this.fecha_actual[1])+0;
			this.fecha_actual[2] = new Number(this.fecha_actual[2])+0;
			this.jqedad.change(this.handle_edad_change.bindAsEventListener(this));
			this.jqfecha_nacimiento.change(this.handle_fecha_nacimiento_change.bindAsEventListener(this));
		},
		handle_edad_change: function(e){
			var edad = this.jqedad.val();
			edad = edad.replace(/^\s+|\s+$/g, '');
			if(edad=='')
				return;
			edad = edad.replace(/\s+/g, ' ');
			edad = edad.split(' ');
			var numero = new Number(edad[0].replace(/^\s+|\s+$/g, ''));
			var metrica = 'a';
			if(edad.length>1){
				var metrica_ingresada = edad[1];
				switch(metrica_ingresada.toLowerCase()){
					case 'dia':
					case 'dias':
					case 'días':
					case 'día':{
						metrica = 'd';
						break;
					}
					case 'mes':
					case 'meses':{
						metrica = 'm';
						break;
					}
					case 'anios':
					case 'anio':
					case 'año':
					case 'años':
					default:{
						metrica = 'a';
						break;
					}
				}
			}
			var label_metricas = [
				{
					'a':'año',
					'm':'mes',
					'd':'día'
				},
				{
					'a':'años',
					'm':'meses',
					'd':'días'
				}
			]
			edad = numero + ' ' + label_metricas[numero>1?1:0][metrica];
			this.jqedad.val(edad);
			var indexes = {'a':2,'m':1,'d':0};
			//window.console.log(this.fecha_actual);
			
			var fecha_nacimiento = this.fecha_actual.join('/');
			var interval = {'a':'yyyy','m':'m','d':'d'};
//			window.console.log(interval[metrica], -numero, this.fecha_actual.join('/'));
//			window.console.log([this.fecha_actual[1], this.fecha_actual[0], this.fecha_actual[2]].join('/'));
			fecha_nacimiento = DateAdd(interval[metrica], -numero, [this.fecha_actual[1], this.fecha_actual[0], this.fecha_actual[2]].join('/'));
			fecha_nacimiento = [fecha_nacimiento.getDate(), fecha_nacimiento.getMonth()+1, fecha_nacimiento.getFullYear()];
			fecha_nacimiento = fecha_nacimiento.join('/');
			fecha_nacimiento = fecha_nacimiento.replace(/([^0-9])([0-9])[/]/g,'$10$2/');
			this.jqfecha_nacimiento.val( fecha_nacimiento ); 
			return;
		},
		handle_fecha_nacimiento_change: function(e){
			var fecha_nacimiento = this.jqfecha_nacimiento.val();
			fecha_nacimiento = fecha_nacimiento.replace(/^\s+|\s+$/g, '');
			if(!fecha_nacimiento){
				return;
			}
			
			fecha_nacimiento = fecha_nacimiento.split('/');
			var dia_nacimiento = new Number(fecha_nacimiento[0])+0;
			var mes_nacimiento = new Number(fecha_nacimiento[1])+0;
			var anio_nacimiento = new Number(fecha_nacimiento[2])+0;

			var dia_actual = this.fecha_actual[0];
			var mes_actual = this.fecha_actual[1];
			var anio_actual = this.fecha_actual[2];
			
			var edad = calcularEdad(dia_nacimiento, mes_nacimiento, anio_nacimiento, dia_actual, mes_actual, anio_actual);
			if(edad==null)
				edad = 'fecha incorrecta';
			this.jqedad.val(edad).focus();
		}
	}
})();


