<?
/**
 *@referencia Provincia(id_provincia) Saludmascotas_Model_Provincia(id)
 *@listar Barrio Saludmascotas_Model_Barrio
*/
class Saludmascotas_Model_Localidad extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$this->setId(null)
			->setIdProvincia(null)
			->setNombre(null)
		;
	}
	public function getDbTableName() 
	{
		return 'sm_localidad';
	}
}
?>