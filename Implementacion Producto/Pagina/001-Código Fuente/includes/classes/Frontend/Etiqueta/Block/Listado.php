<?php
class Frontend_Etiqueta_Block_Listado extends Core_Block_Template{
	public function __construct(){
		parent::__construct();
		$this
			->setTemplate("etiqueta/listado.phtml")
		;
	}
	public function renderEtiqueta($etiqueta){
		$renderer = $this->getChild('etiqueta_renderer');
		$previous = $renderer->getEtiqueta();
		$renderer->setEtiqueta($etiqueta);
		$html = $renderer->getHtml();
		$renderer->setEtiqueta($previous);
		return $html;
	}
	public function getEtiquetas(){
		return $this->getHelper('Saludmascotas/Etiqueta')->getEtiquetasUsuario();
	}
	public function getEtiquetaSeleccionada(){
		return $this->getHelper('Saludmascotas/Etiqueta')->getEtiquetaSeleccionada();
	}
	public function esEtiquetaSeleccionada($etiqueta){
		return $this->getHelper('Saludmascotas/Etiqueta')->esEtiquetaSeleccionada($etiqueta);
	}
//	private $_etiquetas;
//	public function getEtiquetas(){
//		return $this->getHelper('Saludmascotas/Etiqueta')->getEtiquetasUsuario();
////		var_dump(get_class());
////		die(__FILE__.__LINE__);
////		if(!isset($this->_etiquetas)){
////			$this->_etiquetas = $this->initEtiquetas();
////		}
////		return $this->_etiquetas;
//	}
//	private function initEtiquetas(){
//		$usuario = Frontend_Usuario_Model_User::getLogedUser();
////		var_dump($usuario);
////		die(__FILE__.__LINE__);
//		if(!$usuario)
//			return null;
//		$etiqueta = new Frontend_Model_Etiqueta();
//		$etiqueta
//			->setIdUsuario($usuario->getId())
//		;
//		$etiqueta->setWhere(Db_Helper::equal('id_usuario'));
//		$collection = $etiqueta->search();
////		var_dump($collection);
////		die(__FILE__.__LINE__);
//		if(!$collection)
//			return null;
//		$collection = new Core_Collection($collection);
//		$tree = $collection->addFilterEq('id_parent', null);
////		var_dump(!$tree||!$tree->count());
////		die(__FILE__.__LINE__);
//		if(!$tree||!$tree->count())
//			return null;
//		foreach($tree as $parent){
//			$this->setChilds($collection, $parent);
//		}
////		var_dump($tree);
////		die(__FILE__.__LINE__);
//		return $tree;
//	}
//	private function setChilds($collection, $parent){
//		$id_parent = $parent->getId();
//		$childs = $collection->addFilterEq('id_parent', $id_parent);
//		if($childs && $childs->count()){
//			foreach($childs as $child){
//				$this->setChilds($collection, $child);
//			}
//		}
//		else $childs = null;
//		$parent->setChilds($childs);
//		return $this;
//	}
}
?>