<?
class Frontend_Block_Selector_Especie extends Core_Block_Selector{
	public function __construct(){
		parent::__construct();
		$this
			->setEntityToList(new Saludmascotas_Model_Especie())
			->setAllwaysShowSelectMessage(true)
			->setSelectMessage('Especie')
		;
	}
//	protected function prepareEntityToList(){
//		if($this->hasData('only_alowed_to_user')){
//			$agencia = $this->getEntityToList();
//		//	var_dump($agencia->crearFiltroSoloPermitidasAlUsuario());
//			$agencia->setWhere($agencia->crearFiltroSoloPermitidasAlUsuario());
//		}
//	}
	
}
?>