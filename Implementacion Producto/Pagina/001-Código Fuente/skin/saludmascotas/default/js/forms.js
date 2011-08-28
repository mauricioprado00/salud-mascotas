(function(){
	function handle_click_holder(){
		jQuery(this).parents('.holding').find('input,textarea').focus();
	}
	function handle_change_input(){
		var jqholder = jQuery(this).parents('.holding').find('.holder');
		if(jqholder.length==0)
			return;
		var that = this;
		var es_vacio = this.value == '';
		if(!es_vacio)
			jqholder.hide();
		else jqholder.show();
	};
	function handle_change_select(){
		var that = this;
		var es_vacio = this.value == '' || this.value==0 || this.value=='0';
		if(!es_vacio)
			jQuery(this).removeClass('not_selected');
		else jQuery(this).addClass('not_selected')
	};
	function totalRefresh(){
		setTimeout(function (){
			jQuery.floatLabel();
		}, 100);
	}
	jQuery.floatLabel =
	jQuery.fn.floatLabel = function(options){
		var who = this;
		var mal = this.length==null?true:false;
		mal = mal?true:this.length==0;
		mal = mal?true:typeof(this)=='function';
		var default_options = {refresh:true};
		if(options==null)
			options = {};
		jQuery.extend(options, default_options);
		if(mal)
			who = jQuery('.holding input,.holding textarea,.holding select');
		who.each(function(){
			try{
			if(options.refresh){
				var tagName = this.tagName.toLowerCase();
				if(tagName=='input' || tagName=='textarea'){
					handle_change_input.bind(this)();
				}
				else if(tagName=='select'){
					handle_change_select.bind(this)();
				}
			}
			}
			catch(e){
				window.console.log('no funca',e);
			}
		});
	}
	jQuery(document).ready(function(){
		jQuery('.holder').click(handle_click_holder);
		//jQuery('form .holding input, form .holding textarea').change(handle_change_input).mouseup(handle_change_input).keyup(handle_change_input).change();
		//jQuery('.holding select').change(handle_change_select).change();
		jQuery('form .holding input, form .holding textarea').change(totalRefresh).mouseup(handle_change_input).keyup(handle_change_input).change();
		jQuery('.holding select').change(totalRefresh).change();
	});
})();
