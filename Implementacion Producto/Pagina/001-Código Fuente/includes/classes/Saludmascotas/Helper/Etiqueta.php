<?php
class Saludmascotas_Helper_Etiqueta extends Saludmascotas_Helper{
	public function getInstance(){
		return self::getInstanceOf(__CLASS__);
	}
	private $_etiquetas;
	public function getEtiquetasUsuario(){
		if(!isset($this->_etiquetas)){
			$this->_etiquetas = $this->initEtiquetasUsuario();
		}
		return $this->_etiquetas;
	}
	private function initEtiquetasUsuario(){
		$usuario = Frontend_Usuario_Model_User::getLogedUser();
//		var_dump($usuario);
//		die(__FILE__.__LINE__);
		if(!$usuario)
			return null;
		$etiqueta = new Frontend_Model_Etiqueta();
		$etiqueta
			->setIdUsuario($usuario->getId())
		;
		$etiqueta->setWhere(Db_Helper::equal('id_usuario'));
		$collection = $etiqueta->search('nombre', null, null, null, get_class($etiqueta));
//		var_dump($collection);
//		die(__FILE__.__LINE__);
		if(!$collection)
			return null;
		$collection = new Core_Collection($collection);
		$tree = $collection->addFilterEq('id_parent', null);
//		var_dump(!$tree||!$tree->count());
//		die(__FILE__.__LINE__);
		if(!$tree||!$tree->count())
			return null;
		foreach($tree as $parent){
			$this->setChildsEtiquetasUsuario($collection, $parent);
		}
//		var_dump($tree);
//		die(__FILE__.__LINE__);
		return $tree;
	}
	private function setChildsEtiquetasUsuario($collection, $parent){
		$id_parent = $parent->getId();
		$childs = $collection->addFilterEq('id_parent', $id_parent);
//		var_dump($childs && $childs->count());
		if($childs && $childs->count()){
			foreach($childs as $child){
				$this->setChildsEtiquetasUsuario($collection, $child);
			}
		}
		else $childs = null;
		$parent->setChilds($childs);
		return $this;
	}
	public function getEtiquetaSeleccionada($in_tree=true){
		if(!Core_Http_Get::hasParameters())
			return false;
		$gets = Core_Http_Get::getParameters('Core_Object');
		if(!$gets->hasEt())
			return false;
		$etiqueta = new Frontend_Model_Etiqueta();
		$etiqueta->setId($gets->getEt());
		if(!$etiqueta->load())
			return null;
		return $etiqueta;
	}
	public function esEtiquetaSeleccionada($etiqueta){
		//var_dump(get_class($etiqueta), $etiqueta->getId());
		$etiqueta_seleccionada = $this->getEtiquetaSeleccionada();
		if(!$etiqueta_seleccionada)
			return false;
		return $etiqueta_seleccionada->getId() == $etiqueta->getId();
	}
}
?>