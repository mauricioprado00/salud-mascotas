<?php
class Frontend_Block_Domicilio_GmapView extends Core_Block_Template{
	private $_icon_types = array();
	public function __construct(){
		parent::__construct();
		$this->setTemplate('domicilio/gmap_view.phtml');
		$this->setJsObjectName('mapa_'.uniqid());
	}
	public function addIconType($name,$file, $hotspot){
		$this->_icon_types[$name] = (object)array('file'=>$file,'hotspot'=>$hotspot);
		return $this;
	}
	public function getIconTypesConfig($include_pin=true){
		$icon_types_config = array();
		foreach($this->_icon_types as $name=>$icon_type){
			$file = $icon_type->file;
			if(!$file)
				return null;
			$url = $this->getSkinUrl($file);
			$file_loc = $this->getSkinPath($file);
			list($width, $height) = getimagesize($file_loc);
			if(!$width||!$height)
				return null;
			$config = array(
				'url'=>$url,
				'width'=>$width,
				'height'=>$height,
			);
			$hot_spot = $icon_type->hotspot;
			if($hot_spot){
				$hot_spot = explode(',', $hot_spot);
				$config['hotspot_x'] = $this->getHotspotX($hot_spot[1], $width);
				$config['hotspot_y'] = $this->getHotspotY($hot_spot[0], $height);
			}
			$icon_types_config['it_'.$name] = $config;
		}
		if($include_pin){
			$config = $this->getPinConfig();
			if($config)
				$icon_types_config['normal'] = $config;
		}
		return $icon_types_config;
	}
	public function getPinConfig(){
		$pin_file = $this->getPinFile();
		if(!$pin_file)
			return null;
		$pin_url = $this->getSkinUrl($pin_file);
		$pin_file_loc = $this->getSkinPath($pin_file);
		list($width, $height) = getimagesize($pin_file_loc);
		if(!$width||!$height)
			return null;
		$config = array(
			'url'=>$pin_url,
			'width'=>$width,
			'height'=>$height,
		);
		$hot_spot = $this->getPinHotspot();
		if($hot_spot){
			$hot_spot = explode(',', $hot_spot);
			$config['hotspot_x'] = $this->getHotspotX($hot_spot[1], $width);
			$config['hotspot_y'] = $this->getHotspotY($hot_spot[0], $height);
		}
		return $config;
	}
	private function getHotspotX($x, $width){
		if($x=='right')
			return intval($width);
		elseif($x=='left')
			return 0;
		elseif($x=='middle')
			return intval($width/2);
		return intval($x);
	}
	private function getHotspotY($y, $height){
		if($y=='bottom')
			return intval($height);
		elseif($y=='top')
			return 0;
		elseif($y=='middle')
			return intval($height/2);
		return intval($y);
	}
}

?>