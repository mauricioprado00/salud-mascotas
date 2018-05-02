<?
class Saludmascotas_Model_Color extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$this->setId(null)
			->setNombre(null)
			->setColorRgb(null)
		;
	}
	public static function getColorsAsCollection(){
		$return = array();
		$color = new Saludmascotas_Model_Color();
		$colores = $color->search();
		$col = new Core_Collection();
		foreach($colores as $color)
			$col->addItem($color, strtolower($color->getColorRgb()));
		return $col;
	}
	public static function getColoresFromRgb($arr_rgb){
//		var_dump($arr_rgb);
//		die(__FILE__.__LINE__);
		$return = array();
		if($arr_rgb){
			foreach($arr_rgb as $rgb){
				$color = new self();
				$color->setColorRgb($rgb);
				if($color->load())
					$return[] = $color;
			}
		}
		if(!$return)
			return null;
		return $return;
	}
	public static function getNombresColoresFromRgb($arr_rgb){
		$colores = self::getColoresFromRgb($arr_rgb);
		if(!$colores)
			return null;
		$return = array();
		foreach($colores as $color)
			$return[] = $color->getNombre();
		return $return;
	}
	public function getDbTableName() 
	{
		return 'sm_color';
	}
}
?>