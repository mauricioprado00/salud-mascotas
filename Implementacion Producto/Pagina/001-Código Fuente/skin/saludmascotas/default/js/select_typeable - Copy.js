(function(){
	function nullevent(){
		return false;
	}
	window.select_typeable = function(){
	}
	window.select_typeable.prototype = {
		jqcontrol: null,
		jqoptions: null,
		jqdropdown_button: null,
		jqselect: null,
		jqinput: null,
		parent_loader:null,
		url_search: null,
		aditional_data: null,
		init: function(params){
			if(window.select_typeable.registered_items == null){
				window.select_typeable.registered_items = [];
			}
			if(params.aditional_data!=null){
				this.aditional_data = params.aditional_data.split(',');
			}
			window.select_typeable.registered_items.push(this);
			var that = this;
			this.url_search = params.url_search;
			this.jqcontrol = jQuery('#'+params.control_id);
			this.jqoptions = this.jqcontrol.find('ul.options');
			this.jqdropdown_button = this.jqcontrol.find('.dropdown_button');
			this.jqselect = this.jqcontrol.find('select');
			this.jqinput = this.jqcontrol.find('input');
			this.jqinput.attr('name', this.jqselect.attr('name'));
			this.jqinput.attr('id', this.jqselect.attr('id'));
			this.jqinput.attr('title', this.jqselect.attr('title'));
			this.jqselect.removeAttr('name');
			this.jqselect.removeAttr('id');
			this.jqselect.removeAttr('title');
			this.parent_loader = params.parent_loader;
//			this.jqinput.hover(function(){
//				that.addClass('actashover');
//			})
//			this.jqinput.blur(function(){
//				that.removeClass('actashover');
//			})
			var tagName = this.getParentLoader().get(0).tagName.toLowerCase();
			if(tagName=='input'){
				var handler = this.on_search.bindAsEventListener(this,'input');
				this.getParentLoader().blur(handler).change(handler);	
			}
			else if(tagName=='select'){
				this.getParentLoader().change(this.on_search.bindAsEventListener(this,'select'));
			}
			this.jqinput.keyup(this.handle_input_keyup.bindAsEventListener(this));
			this.jqinput.focus(this.handle_input_focus.bindAsEventListener(this));
			this.jqinput.blur(this.handle_input_blur.bindAsEventListener(this));
			this.jqdropdown_button.click(this.handle_dropdown_button_click.bindAsEventListener(this));
			this.reload_options(false);
			this.jqselect.change(this.reload_options.bind(this));
		},
		collect_aditional_data: function(aditional_data){
			if(aditional_data==null)
				aditional_data = {};
			if(this.aditional_data!=null){
				jQuery(this.aditional_data).each(function(idx, name){
					var select = '#'+name;
					var jqcontrol = jQuery(select);
					if(jqcontrol.length==0){
						window.console.log('cant find ' + select);
						return;
					}
					aditional_data[name] = jqcontrol.val();
				});
			}
			return aditional_data;
		},
		show_options: function(){
			jQuery(window.select_typeable.registered_items).each(function(idx, item){
				item.jqoptions.hide();
			});
			this.jqoptions.show();
		},
		hide_options: function(){
			this.jqoptions.hide();
		},
		handle_dropdown_button_click: function(){
			if(this.jqcontrol.hasClass('select_typeable_downed')){
				this.jqcontrol.removeClass('select_typeable_downed')
				this.jqoptions.find('li').show();
				//this.hide_options();
			}
			else{
				this.jqcontrol.addClass('select_typeable_downed')
				this.jqinput.focus();
				//this.show_options();
			}
		},
		handle_input_blur: function(e){
			this.jqcontrol.removeClass('select_typeable_focused').removeClass('select_typeable_typing')
			//this.hide_options();
		},
		handle_input_focus: function(e){
			this.jqcontrol.addClass('select_typeable_focused')
		},
		handle_input_keyup: function(e){
			var val = this.jqinput.val();
			this.jqoptions.find('li').each(function(){
				try{
				if(jQuery(this).text().split(val).length==1)
					jQuery(this).hide();
				else jQuery(this).show();
				}catch(e){
//						window.console.log(e);
				}
			});
			this.jqcontrol.addClass('select_typeable_typing')
			//this.show_options();
		},
		contador_search: 0,
		on_search: function( event, type ){
			if(type=='select'){
				var text = this.getParentLoader().val();
				if(text=='')
					return;
				this.searchData = {
					text: text
				};
			}
			else if(type=='input'){
				var text = this.getParentLoader().val();
				if(text=='')
					return;
				this.searchData = {
					text: text
				};
			}
			//this.searchText = searchText;
			this.contador_search ++;
			setTimeout( this.on_search_timeout.bind(this), type=='select'?0:1000 );
		},
		on_search_timeout: function(){
			this.contador_search--;
			if( this.contador_search==0 ){
				this.do_search( this.searchText );
			}
		},
		do_search: function(){
			this.collect_aditional_data(this.searchData);
			jQuery.ajax({
				data: this.searchData,
				dataType: 'json',
				success: this.on_search_return.bind(this),
				type:'post',
				url: this.url_search,
			})
//			window.console.log(this.searchData);
//			window.console.log('doing search');
		},
		on_search_return: function(data){
			this.reload_options_from_array(data.resultados, false);
			if(data.resultados.length){
				this.show_options();
			}
			//window.console.log(data);
		},
		getParentLoader: function(){
			//window.console.log(jQuery('#'+this.parent_loader), this.parent_loader);
			return jQuery('#'+this.parent_loader);
		},
		reload_options: function(empty_input){
			var that = this;
			if(empty_input!=false)
				this.jqinput.val('');
			this.jqoptions.html('');
			this.jqselect.find('option').each(function(){
				if(!jQuery(this).val())
					return;
				jqli = jQuery('<li></li>').text(jQuery(this).text());
				jqli.appendTo(that.jqoptions);
				jqli
					.click(function(){
						that.jqinput.val(jQuery(this).text()).change();
						that.hide_options();
						return false;
					})
					.mouseover(function(){that.jqinput.focus();})
				;
			});
		},
		reload_options_from_array: function(array, empty_input){
			var that = this;
			if(empty_input!=false)
				this.jqinput.val('');
			that.jqoptions.html('');
//			window.console.log(array);
			jQuery(array).each(function(idx, el){
//				window.console.log(el);
				jqli = jQuery('<li></li>').text(el);
				jqli.appendTo(that.jqoptions);
				jqli
					.click(function(){
						that.jqinput.val(jQuery(this).text()).change();
						that.hide_options();
						return false;
					})
					.mouseover(function(){that.jqinput.focus();})
				;
			});
		}
	}
	window.create_typeable = function(params){
		var x = new select_typeable();
		x.init(params);
	}
})();