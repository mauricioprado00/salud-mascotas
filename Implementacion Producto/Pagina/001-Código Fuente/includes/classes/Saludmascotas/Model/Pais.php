<?
/**
 *@listar Provincia Saludmascotas_Model_Provincia
*/
class Saludmascotas_Model_Pais extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$this->setId(null)
			->setNombre(null)
		;
	}
	public function getDbTableName() 
	{
		return 'sm_pais';
	}
}
?>