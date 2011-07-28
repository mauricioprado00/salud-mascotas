(function(){
	function daysInMonth(month, year){
		var date = new Date(month+'/29/'+year);
		if((date.getMonth()+1)==month){
			date = new Date(month+'/30/'+year);
			if((date.getMonth()+1)==month){
				date = new Date(month+'/31/'+year);
				if((date.getMonth()+1)==month){
					return 31;
				}
				return 30;
			}
			return 29;
		}
		return 28;
	}
	function calculateAge(birth_day, birth_month, birth_year, current_day, current_month, current_year){
		var years = current_year - birth_year;
		var months = 0;
		var days = 0;
		var age = null;
		//filter invalid dates
		if(	current_year<birth_year ||//future year
			current_year==birth_year&&current_month<birth_month ||//same year and future month 
			current_year==birth_year&&current_month==birth_month&&current_day<birth_day ){//same year and month but future day
			age = null;
		}
		else{
			if(current_year>birth_year && (current_month<birth_month || (current_month==birth_month && current_day<birth_day)))
				years--;
			if(years==0){
				current_year+=0;
				birth_year+=0;
				if(current_year==birth_year){
					months = current_month - birth_month;
				}
				else{//year pasado
					months = 12 + current_month - birth_month;
				}
				if(current_day<birth_day)
					months--;
				if(months){
					age = {'m':months};
				}
				else{
					if(birth_month == current_month)
						days = current_day - birth_day;
					else days = daysInMonth(birth_month, current_year) + current_day - birth_day;
					age = {'d':days};
				}
			}
			else{
				age = {'y':years};
			}
		}
		return age;
	}
	function calculateAgeFormatted(birth_day, birth_month, birth_year, current_day, current_month, current_year, names){
		if(names==null)
			names = 'es';
		if(typeof(names)=='string'){
			var i18n = {
				'en': [
					{
						'y':'year',
						'm':'month',
						'd':'day'
					},
					{
						'y':'years',
						'm':'months',
						'd':'days'
					},
				],
				'es': [
					{
						'y':'año',
						'm':'mes',
						'd':'dia'
					},
					{
						'y':'años',
						'm':'meses',
						'd':'dias'
					},
				]
			};
			names = i18n[names];
		}
		var age = calculateAge(birth_day, birth_month, birth_year, current_day, current_month, current_year);
		if(!age)//invalid date
			return null;
		var vars = ['y','m','d'];
		var varname;
		for(var i=0;i<vars.length;i++){
			varname = vars[i];
			if(age[varname]!=null)
				return age = age[varname] + ' ' + names[age[varname]>1?1:0][varname];
		}
		return null;//never happens
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
			
			var edad = calculateAgeFormatted(dia_nacimiento, mes_nacimiento, anio_nacimiento, dia_actual, mes_actual, anio_actual);
			if(edad==null)
				edad = 'fecha incorrecta';
			this.jqedad.val(edad).focus();
		}
	}
})();


