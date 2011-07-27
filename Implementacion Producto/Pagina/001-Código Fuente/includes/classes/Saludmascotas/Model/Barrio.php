<?
/**
 *@referencia Localidad(id_localidad) Saludmascotas_Model_Localidad(id)
*/
class Saludmascotas_Model_Barrio extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$this->setId(null)
			->setIdLocalidad(null)
			->setNombre(null)
		;
	}
	public function getDbTableName() 
	{
		return 'sm_barrio';
	}
}
?>