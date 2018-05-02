<?
class Frontend_Block_Selector_CantidadColores extends Core_Block_Selector{
	public function __construct(){
		parent::__construct();
		$this
			->setEntityToList(new Core_Object())
			->setAllwaysShowSelectMessage(true)
			->setSelectMessage('Cantidad Colores')
		;
	}
	public function getAllOptions(){
		$cantidad_colores = Frontend_Mascota_Helper::getCantidadColores();
		$return = array();
		foreach($cantidad_colores as $cantidad){
			$return[] = c(new Core_Object)->setId($cantidad)->setNombre($cantidad);
		}
		return $return;
	}
}
?>