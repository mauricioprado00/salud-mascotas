<?php
class Frontend_Mascota_Block_Fotos_AddEdit extends Core_Block_Template{
	function _construct(){
		parent::_construct();
		$this
			->setTemplate('mascota/fotos/add_edit.phtml')
		;
	}	
}
?>