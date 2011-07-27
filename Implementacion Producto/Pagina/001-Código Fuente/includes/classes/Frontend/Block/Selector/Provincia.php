<?
class Frontend_Block_Selector_Provincia extends Core_Block_Selector{
	public function __construct(){
		parent::__construct();
		$this
			->setTemplate('page/selector/typeable.phtml')
			->setUrlLoad('location/searchProvincia')
			->setEntityToList(new Saludmascotas_Model_Provincia())
		;
	}
	protected function prepareEntityToList(){
		$id_pais = 0;
		if($this->hasData('id_pais')){
			$id_pais = $this->getIdPais();
		}
		$provincia = $this->getEntityToList();
		$provincia
			->setWhere(Db_Helper::equal('id_pais', $this->getData('id_pais')))
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