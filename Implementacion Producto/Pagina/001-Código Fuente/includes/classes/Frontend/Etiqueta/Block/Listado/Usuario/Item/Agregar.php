<?php
class Frontend_Etiqueta_Block_Listado_Usuario_Item_Agregar extends Frontend_Etiqueta_Block_Listado{
	public function __construct(){
		parent::__construct();
		$this
			->setTemplate("etiqueta/listado/usuario/item/agregar.phtml")
		;
	}
	public function getMascota(){
		$parent_block = $this->getParentBlock();
		return $parent_block->getEntity();
	}
	public function esEtiquetaMascota($etiqueta){
		$mascota = $this->getMascota();
		return $mascota->esEtiquetaMascota($etiqueta);
	}
}
?>