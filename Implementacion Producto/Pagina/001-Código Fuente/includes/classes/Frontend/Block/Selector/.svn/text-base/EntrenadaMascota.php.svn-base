<?//es útf8
class Frontend_Block_Selector_EntrenadaMascota extends Core_Block_Selector{
	public function __construct(){
		parent::__construct();
		$this
			->setEntityToList(new Core_Object())
			->setAllwaysShowSelectMessage(true)
			->setSelectMessage('¿Está Entrenada?')
		;
	}
	public function getAllOptions(){
		$values = Frontend_Mascota_Helper::getEntrenadaMascota();
		$return = array();
		foreach($values as $value){
			$return[] = c(new Core_Object)->setId($value)->setNombre($value);
		}
		return $return;
	}
}
?>