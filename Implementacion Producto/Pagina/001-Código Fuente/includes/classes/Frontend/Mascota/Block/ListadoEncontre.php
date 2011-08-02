<?php
class Frontend_Mascota_Block_ListadoEncontre extends Frontend_Mascota_Block_ListadoUsuario{
	public function __construct(){
		parent::__construct();
//		$this
//			->setTemplate("mascota/usuario/listado.phtml")
//			->setMaxItems(4)
//		;
	}
	private $search_object = null;
	private function initializeSearchObject(){
		if($this->search_object===null){
			$this->search_object = new Frontend_Model_Mascota();
			$estados[] = Saludmascotas_Model_EstadoMascota::getEstadoEnGuarda()->getId();
			$estados[] = Saludmascotas_Model_EstadoMascota::getEstadoVista()->getId();
			$wheres = array(
				Db_Helper::equal('id_dueno'),
				Db_Helper::equal('activa','si'),
				Db_Helper::in('id_estadomascota', true, $estados),
			);
			$usuario = Frontend_Usuario_Model_User::getLogedUser();
			$this->search_object->setIdDueno($usuario->getId());
			$this->search_object->setWhereByArray($wheres);
		}
	}
	public function getSearchObject(){
		$this->initializeSearchObject();
		return($this->search_object);
	}
	protected function getUrlPagina($pagina=null){
		return $this->getUrl(Frontend_Mascota_Helper::getUrlEncontre($pagina));
	}
}
?>