(function(){
	function handle_change_midomicilio(){
		jQuery('.midomicilio_contenido').hide();
		jQuery('.midomicilio_'+this.valor+'_contenido').show();
	}
	jQuery(document).ready(function(){
		
		jQuery('#midomicilio_no').change(handle_change_midomicilio.bind({valor:'no'}));
		jQuery('#midomicilio_si').change(handle_change_midomicilio.bind({valor:'si'}));
//		jQuery('.holder').click(handle_click_holder);
//		jQuery('form .holding input').change(handle_change_input).mouseup(handle_change_input).keyup(handle_change_input).change();
//		jQuery('.holding select').change(handle_change_select).change();
	});
})();
