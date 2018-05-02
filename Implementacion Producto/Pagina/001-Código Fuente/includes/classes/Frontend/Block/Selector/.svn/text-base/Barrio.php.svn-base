<?
class Frontend_Block_Selector_Barrio extends Core_Block_Selector{
	public function __construct(){
		parent::__construct();
		$this
			->setTemplate('page/selector/typeable.phtml')
			->setUrlLoad('location/searchBarrio')
			->setEntityToList(new Saludmascotas_Model_Barrio())
		;
	}
	protected function prepareEntityToList(){
		$id_localidad = 0;
		if($this->hasData('id_localidad')){
			$id_localidad = $this->getIdPais();
		}
		$barrio = $this->getEntityToList();
		$barrio
			->setWhere(Db_Helper::equal('id_localidad', $this->getData('id_localidad')))
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