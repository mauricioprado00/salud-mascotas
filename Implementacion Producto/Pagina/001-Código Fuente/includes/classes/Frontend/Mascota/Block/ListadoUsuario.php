<?php
class Frontend_Mascota_Block_ListadoUsuario extends Core_Block_List_Abstract{
	public function __construct(){
		parent::__construct();
		$this
			->setTemplate("mascota/usuario/listado.phtml")
			->setMaxItems(4)
		;
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
				Db_Helper::in('id_estadomascota', false, $estados),
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
	protected function searchCount(){
		static $count = null;
		if($count!==null){
			return($count);
		}
		$search_object = $this->getSearchObject();
//		echo $search_object->searchGetSql();
//		die(__FILE__.__LINE__);
		$count = $search_object->searchCount();
		return($count);
	}
	protected function getPagina(){
		return((int)Core_App::getInstance()->getPagina());
	}
	protected function getUrlPagina($pagina=null){
		return $this->getUrl(Frontend_Mascota_Helper::getUrlUsuario($pagina));
	}
	protected function search(){
		$start_item = 0;
		$max_items = $this->getMaxItems();
		$pagina = $this->getPagina();
//echo '<div style="display:none;" class="consultasql">';
//		echo $this->getSearchObject()->searchGetSql(null,'ASC', $max_items, $start_item, true, null, true);
//echo '</div>';
		if($pagina){
			$start_item = $pagina * $max_items;
			//return($this->getSearchObject()->search(null,'ASC', $max_items, $start_item));
		}
//echo '<div style="display:none;" class="consultasql">';
//		echo $this->getSearchObject()->search(null,'ASC', $max_items, $start_item, true, null, true);
//echo '</div>';
		return($this->getSearchObject()->search(null, 'ASC', $max_items, $start_item, get_class($this->getSearchObject())));
	}
}
?>