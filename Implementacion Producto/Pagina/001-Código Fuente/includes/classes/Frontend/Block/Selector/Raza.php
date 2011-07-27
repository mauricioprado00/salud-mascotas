<?
class Frontend_Block_Selector_Raza extends Core_Block_Selector{
	public function __construct(){
		parent::__construct();
		$this
			->setTemplate('page/selector/typeable.phtml')
			->setUrlLoad('animal/searchRaza')
			->setAllwaysShowSelectMessage(true)
			->setEntityToList(new Saludmascotas_Model_Raza())
		;
	}
	protected function prepareEntityToList(){
		$id_especie = 0;
		if($this->hasData('id_especie')){
			$id_especie = $this->getIdEspecie();
		}
		$raza = $this->getEntityToList();
		$raza
			->setWhere(Db_Helper::equal('id_especie', $this->getData('id_especie')))
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