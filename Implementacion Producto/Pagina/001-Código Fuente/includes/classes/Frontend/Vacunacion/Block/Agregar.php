<?php

class Frontend_Vacunacion_Block_Agregar extends Core_Block_Template{
	private $links = array();
	public function __construct(){
		parent::__construct();
		$this->setTemplate('vacunacion/agregar.phtml');
	}
	public function addLink($url, $label){
		$this->links[] = new Core_Object(array('url'=>$url, 'label'=>$label));
		return $this;
	}
	public function getLinks(){
		return $this->links;
	}
}