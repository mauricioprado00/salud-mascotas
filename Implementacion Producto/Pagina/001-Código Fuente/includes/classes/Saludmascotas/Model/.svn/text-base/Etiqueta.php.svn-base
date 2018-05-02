<?
/**
 *@referencia Parent(id_parent) Saludmascotas_Model_Etiqueta(id)
 *@referencia Usuario(id_usuario) Saludmascotas_Model_Usuario(id)
*/
class Saludmascotas_Model_Etiqueta extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$this->setId(null)
			->setIdUsuario(null)
			->setIdParent(null)
			->setNombre(null)
		;
	}
	public function getParentsIds(){
		return $this->_getParentsIds();
	}
	public function isInParents($etiqueta){
		if(!$etiqueta)
			return false;
		if(!$etiqueta->getId())
			return false;
		$arr_ids = $this->getParentsIds();
////		if($arr_ids){
//			if($this->getId()==4){
//				var_dump($arr_ids);
//				die(__FILE__.__LINE__);
//			}
////		}
//		$arr_ids[] = $this->getId();
		return in_array($etiqueta->getId(), $arr_ids);
	}
	private function _getParentsIds(&$arr = array()){
		$parent = $this->getParent();
		if($parent){
			$arr[] = $parent->getId();
		}
		return $arr;
	}
	public function getDbTableName() 
	{
		return 'sm_etiqueta';
	}
}
?>