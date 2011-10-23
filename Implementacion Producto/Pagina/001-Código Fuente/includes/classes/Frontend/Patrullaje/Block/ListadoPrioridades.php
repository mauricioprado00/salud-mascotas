<?php
class Frontend_Patrullaje_Block_ListadoPrioridades extends Core_Block_List_Abstract{
	private $links = array();
	public function __construct(){
		parent::__construct();
		$this
			->setTemplate("patrullaje/prioridades.phtml")
			->setMaxItems(50)
		;
	}
	public function addLink($url, $label){
		$this->links[] = new Core_Object(array('url'=>$url, 'label'=>$label));
		return $this;
	}
	public function getLinks(){
		return $this->links;
	}
	private $search_object = null;
	private function initializeSearchObject(){
		if($this->search_object===null){
			$this->search_object = new Saludmascotas_Model_Barrio();
//			var_dumP($ids);
//			die(__FILE__.__LINE__);
//			$estados[] = Saludbarrios_Model_EstadoBarrio::getEstadoEnGuarda()->getId();
//			$estados[] = Saludbarrios_Model_EstadoBarrio::getEstadoVista()->getId();
			$wheres = array(
//				Db_Helper::equal('id_dueno'),
//				Db_Helper::equal('activa','si'),
//				Db_Helper::in('estado_adopcion',true,array('no','oferta')),
//				Db_Helper::in('id_estadobarrio', false, $estados),
			);
//			$usuario = Frontend_Usuario_Model_User::getLogedUser();
//			$this->search_object->setIdDueno($usuario->getId());
			$this->search_object->setWhereByArray($wheres);
		}
	}
	public function getSearchObject(){
		$this->initializeSearchObject();
//		var_dump(get_class($this->search_object));
//		die(__FILE__.__LINE__);
		return($this->search_object);
	}
	protected function searchCount(){
		static $count = null;
		if($count!==null){
			return($count);
		}
		$search_object = $this->getSearchObject();
		$this->initializeSearchObject();
//		echo $search_object->searchGetSql();
//		die(__FILE__.__LINE__);
		$count = $search_object->searchCount();
		return($count);
	}
	protected function getPagina(){
		return((int)Core_App::getInstance()->getPagina());
	}
	protected function getUrlPagina($pagina=null){
		return $this->getUrl(Frontend_Barrio_Helper::getUrlUsuario($pagina));
	}
	protected function search(){
		$start_item = 0;
		$max_items = $this->getMaxItems();
		//$pagina = $this->getPagina();
		$pagina = 0;
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
		
		$entityes = $this->getSearchObject()->search(null, 'ASC', $max_items, $start_item, get_class($this->getSearchObject()));
		$collection = new Core_Collection($entityes);
		$ids = $this->getIdsBarrios();
		$entityes = array();
		foreach($ids as $id){
			$subcol = $collection->addFilterEq('id', $id);
			if(!$subcol)continue;
			if(!$subcol->count())continue;
			$entityes[] = $subcol->getFirst();
		}
//		var_dump(count($entityes));
//		die(__FILE__.__LINE__);
		return $entityes;
	}
//	public function getUrlNuevaBarrio(){
//		return $this->getUrl(Frontend_Barrio_Helper::getUrlAgregar());
//	}
}
?>