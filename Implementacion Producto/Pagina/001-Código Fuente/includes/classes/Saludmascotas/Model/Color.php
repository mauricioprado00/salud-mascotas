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
	public function getDbTableName() 
	{
		return 'sm_color';
	}
}
?>