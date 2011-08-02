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
	public static function getEstadoConDueno(){
		return self::getEstadoByName('con dueno');
	}
	public static function getEstadoPerdida(){
		return self::getEstadoByName('perdida');
	}
	public static function getEstadoEliminada(){
		return self::getEstadoByName('eliminada');
	}
	public static function getEstadoEnGuarda(){
		return self::getEstadoByName('en guarda');
	}
	public static function getEstadoVista(){
		return self::getEstadoByName('vista');
	}
	public function getDbTableName() 
	{
		return 'sm_estadomascota';
	}
}
?>