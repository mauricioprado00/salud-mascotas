<?//es útf8
class Frontend_Block_Selector_SexoMascota extends Core_Block_Selector{
	public function __construct(){
		parent::__construct();
		$this
			->setEntityToList(new Core_Object())
			->setAllwaysShowSelectMessage(true)
			->setSelectMessage('Sexo')
		;
	}
	public function getAllOptions(){
		$values = Frontend_Mascota_Helper::getSexoMascota($this->getIncludeDontKnow()?true:false, $this->getIncludeDontCare()?true:false);
		$return = array();
		foreach($values as $value){
			$return[] = c(new Core_Object)->setId($value)->setNombre($value);
		}
		return $return;
	}
}
?>