<?
class Frontend_Block_Selector_Localidad extends Core_Block_Selector{
	public function __construct(){
		parent::__construct();
		$this
			->setTemplate('page/selector/typeable.phtml')
			->setUrlLoad('location/searchLocalidad')
			->setEntityToList(new Saludmascotas_Model_Localidad())
		;
	}
	protected function prepareEntityToList(){
		$id_provincia = 0;
		if($this->hasData('id_provincia')){
			$id_provincia = $this->getIdPais();
		}
		$localidad = $this->getEntityToList();
		$localidad
			->setWhere(Db_Helper::equal('id_provincia', $this->getData('id_provincia')))
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