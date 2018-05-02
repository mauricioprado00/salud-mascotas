<?php
class Frontend_Mascota_Castracion_Block_ListadoPendientes extends Core_Block_List_Abstract{
	private $links = array();
	public function __construct(){
		parent::__construct();
		$this
			->setTemplate("mascota/usuario/listado.phtml")
			->setMaxItems(4)
			->setShowAddButton(false)
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
	protected function prepareSqlInCastraciones(){
		$usuario = Frontend_Usuario_Model_User::getLogedUser();
		$castracion = new Saludmascotas_Model_Castracion();
		$castracion->setWhere(Db_Helper::equal('id_usuario_pa', $usuario->getId()));
		return $castracion->searchGetSql(null, 'ASC', null, 0, true, array('id_mascota'));;
	}
	private function initializeSearchObject(){
		if($this->search_object===null){
			$this->search_object = new Frontend_Model_Mascota();
//			$estados[] = Saludmascotas_Model_EstadoMascota::getEstadoEnGuarda()->getId();
//			$estados[] = Saludmascotas_Model_EstadoMascota::getEstadoVista()->getId();
			
			$in_castraciones_pendientes = $this->prepareSqlInCastraciones();
			//var_dump($in_castraciones_pendientes);die(__FILE__.__LINE__);
			$wheres = array(
				//Db_Helper::equal('id_dueno'),
				Db_Helper::equal('activa','si'),
				Db_Helper::equal('estado_castracion'),
				Db_Helper::custom('id in ('.$in_castraciones_pendientes.')')
				//Db_Helper::in('estado_adopcion',true,array('no','oferta')),
				//Db_Helper::in('id_estadomascota', false, $estados),
			);
			
			//$this->search_object->setIdDueno($usuario->getId());
			$this->search_object->setEstadoCastracion('solicitada');
			$this->search_object->setWhereByArray($wheres);
			//var_dump($this->search_object->searchGetSql());die(__FILE__.__LINE__);
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
//	public function getUrlNuevaMascota(){
//		return $this->getUrl(Frontend_Mascota_Helper::getUrlAgregar());
//	}
}
?>