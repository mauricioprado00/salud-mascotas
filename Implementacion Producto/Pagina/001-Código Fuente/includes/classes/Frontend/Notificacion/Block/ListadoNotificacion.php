<?php
class Frontend_Notificacion_Block_ListadoNotificacion extends Core_Block_List_Abstract{
	public function __construct(){
		parent::__construct();
		$this
			->setTemplate("notificacion/listado.phtml")
			->setMaxItems(6)
		;
	}
	private $search_object = null;
	private function initializeSearchObject(){
		if($this->search_object===null){
			$this->search_object = new Frontend_Model_Notificacion();
			$wheres = array(
				Db_Helper::equal('id_usuario_to')
			);
			$usuario = Frontend_Usuario_Model_User::getLogedUser();
			$this->search_object->setIdUsuarioTo($usuario->getId());
			$this->search_object->setWhereByArray($wheres);
			//echo Core_Helper::DebugVars($this->search_object->searchGetSql());
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
		$this->initializeSearchObject();
//		echo $this->search_object->searchGetSql();
//		die(__FILE__.__LINE__);
		$count = $this->search_object->searchCount();
		return($count);
	}
	protected function getPagina(){
		return((int)Core_App::getInstance()->getPagina());
	}
	protected function getUrlPagina($pagina=null){
		return $this->getUrl(Frontend_Notificacion_Helper::getUrlListado($pagina));
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
		return($this->getSearchObject()->search('hora', 'desc', $max_items, $start_item, get_class($this->getSearchObject())));
	}
}
?>