<?
/**
 *@referencia Pais(id_pais) Saludmascotas_Model_Pais(id)
 *@listar Localidad Saludmascotas_Model_Localidad
*/
class Saludmascotas_Model_Provincia extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$this->setId(null)
			->setIdPais(null)
			->setNombre(null)
		;
	}
	public function getDbTableName() 
	{
		return 'sm_provincia';
	}
}
?>