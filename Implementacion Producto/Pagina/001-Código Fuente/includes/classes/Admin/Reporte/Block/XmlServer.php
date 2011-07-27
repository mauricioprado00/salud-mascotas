<?php
class Admin_Reporte_Block_XmlServer extends Jqgrid_Block_XmlServer{
	public function __construct(){
		parent::__construct();
		$this->setEntityClassname('Inta_Model_Reporte_Actividad');
		$this->setHardFiltros(array(
			Db_Helper::equal('id_usuario_logeado', Admin_User_Model_User::getLogedUser()->getId())
		));
//		$post = Core_Http_Post::getParameters('Core_Object');
//		if($post->hasCaption()){
//			//var_dump(get_class(simplexml_load_string('<title>'.$post->getCaption().'</title>')));
//			$this->addParam('title','sdf');
//			//$params->setTitle($post->getCaption());
//		}
	}
	protected function prepareEntityToList($entity, $page, $rows, $sidx, $sord, $comparator){
		$entity->setWhereByArray($this->getWheres($comparator));
	}

	protected function getParams(){
		$params = parent::getParams();
		$post = Core_Http_Post::getParameters('Core_Object');
		if($post->hasCaption())
			$params->setTitle($post->getCaption());
		return $params;
	}
}
?>