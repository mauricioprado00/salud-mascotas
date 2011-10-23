<?
/**
 *@referencia Barrio(id_barrio) Saludmascotas_Model_Barrio(id)
 *@referencia Patrullaje(id_patrullaje) Saludmascotas_Model_Patrullaje(id)
*/
class Saludmascotas_Model_VisitaBarrio extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$this->setId(null)
			->setIdBarrio(null)
			->setIdPatrullaje(null)
		;
	}
	public function getDbTableName() 
	{
		return 'sm_visita_barrio';
	}
}
?>