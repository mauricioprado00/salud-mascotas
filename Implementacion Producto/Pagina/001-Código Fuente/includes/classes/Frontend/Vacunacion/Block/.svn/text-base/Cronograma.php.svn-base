<?php

class Frontend_Vacunacion_Block_Cronograma extends Core_Block_Template{
	private $links = array();
	public function __construct(){
		parent::__construct();
		$this->setTemplate('vacunacion/cronograma.phtml');
	}
	public function addLink($url, $label){
		$this->links[] = new Core_Object(array('url'=>$url, 'label'=>$label));
		return $this;
	}
	public function getLinks(){
		return $this->links;
	}
	public function getEvents(){
		if(!($vacunaciones = $this->getVacunaciones()))
			return null;
		$events = array();
		foreach($vacunaciones as $vacunacion){
			$events[] = array(
				'title'=>$vacunacion->getTexto(),
				'start'=>$vacunacion->getFechaInicio(),
				'end'=>$vacunacion->getFechaFin(),
				'allDay'=>true,
				'url'=>$this->getUrl($vacunacion->getUrlEditar())
			);
		}
		return $events;
	}
}