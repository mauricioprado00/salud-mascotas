(function(){
	function handle_click_holder(){
		jQuery(this).parents('.holding').find('input,textarea').focus();
	}
	function handle_change_input(){
		var that = this;
		var es_vacio = this.value == '';
		if(!es_vacio)
			jQuery(this).parents('.holding').find('.holder').hide();
		else jQuery(this).parents('.holding').find('.holder').show();
	};
	function handle_change_select(){
		var that = this;
		var es_vacio = this.value == '' || this.value==0 || this.value=='0';
		if(!es_vacio)
			jQuery(this).removeClass('not_selected');
		else jQuery(this).addClass('not_selected')
	};
	jQuery(document).ready(function(){
		jQuery('.holder').click(handle_click_holder);
		jQuery('form .holding input, form .holding textarea').change(handle_change_input).mouseup(handle_change_input).keyup(handle_change_input).change();
		jQuery('.holding select').change(handle_change_select).change();
	});
})();
