<?
class Saludmascotas_Model_EstadoMascota extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$this->setId(null)
			->setNombre(null)
		;
	}
	public static function getEstadoByName($nombre_estado_mascota){
		$estado_mascota = new self();
		$estado_mascota->setNombre($nombre_estado_mascota);
		if(!$estado_mascota->load()){
			return false;
		}
		return $estado_mascota;
	}
	public function getDbTableName() 
	{
		return 'sm_estadomascota';
	}
}
?>