<?php
class Frontend_Mascota_Block_View extends Core_Block_Template{
	public function __construct(){
		parent::__construct();
		$this
			->setTemplate("mascota/view.phtml")
		;
		$this->setExtraData(new Core_Collection());
	}
	public function addExtraData($label, $text){
		$extra_data = new Core_Object();
		$extra_data->setLabel($label)->setText($text);
		$this->getExtraData()->addItem($extra_data);
		return $this;
	}
}
?>