<?
class Frontend_Block_Selector_Veterinaria extends Core_Block_Selector{
	public function __construct(){
		parent::__construct();
		$this
			->setTemplate('page/selector/typeable.phtml')
			->setUrlLoad('user/searchVeterinaria')
			->setEntityToList(new Saludmascotas_Model_User())
		;
	}
	protected function prepareEntityToList(){
//		$id_pais = 0;
//		if($this->hasData('id_pais')){
//			$id_pais = $this->getIdPais();
//		}
		$user = $this->getEntityToList();
		$user
			->setWhere(Db_Helper::equal('tipo', 'veterinaria'))
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