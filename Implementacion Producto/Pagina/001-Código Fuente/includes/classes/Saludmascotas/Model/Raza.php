<?
/**
 *@referencia Especie(id_especie) Saludmascotas_Model_Especie(id)
*/
class Saludmascotas_Model_Raza extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$this->setId(null)
			->setIdEspecie(null)
			->setNombre(null)
			->setDescripcion(null)
		;
	}
	public function getDbTableName() 
	{
		return 'sm_raza';
	}
}
?>