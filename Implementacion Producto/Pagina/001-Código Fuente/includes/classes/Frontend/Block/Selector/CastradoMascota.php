<?//es útf8
class Frontend_Block_Selector_CastradoMascota extends Core_Block_Selector{
	public function __construct(){
		parent::__construct();
		$this
			->setEntityToList(new Core_Object())
			->setAllwaysShowSelectMessage(true)
			->setSelectMessage('¿Castrado?')
		;
	}
	public function getAllOptions(){
		$values = Frontend_Mascota_Helper::getCastradaMascota($this->getIncludeDontKnow()?true:false);
		$return = array();
		foreach($values as $value){
			$return[] = c(new Core_Object)->setId($value)->setNombre($value);
		}
		return $return;
	}
}
?>