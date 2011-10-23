<?
class Saludmascotas_Model_View_MascotaAdopcionConciliacion extends Saludmascotas_Db_Model_View_Abstract{
	protected function init(){
		parent::init();
		$view = new Saludmascotas_Db_Model_View_Generic();
		
		$adopcion_conciliacion = new Saludmascotas_Model_AdopcionConciliacion();
		$mascota = new Saludmascotas_Model_Mascota();
		$adopcion_solicitud = new Saludmascotas_Model_AdopcionSolicitud();
		$adopcion_oferta = new Saludmascotas_Model_AdopcionOferta();
		$view
			->addTableByModel($adopcion_conciliacion, null, 're', true)
			->addTableByModel($adopcion_solicitud, 'en.id=re.id_adopcion_solicitud', 'en', false, array())
			->addTableByModel($adopcion_oferta, 'pe.id=re.id_adopcion_oferta', 'pe', false, array())
			->addTableByModel($mascota, '(en.id_mascota = ma.id or pe.id_mascota = ma.id)', 'ma', true)
		;
		$this->addViewByModel($view, 'me', false);
		$this->setGroupBy('re_id');
	}
	public function groupByAdopcionConciliacion($group=true){
		if(!$group)
			return $this->setGroupBy();
		return $this->setGroupBy('re_id');
	}
	public function getAdopcionConciliacion($o=null){
		if(!isset($o))
			$o = new Saludmascotas_Model_AdopcionConciliacion();
		return $o->setData($this->toArrayAll('re_'));
	}
	public function getMascota($o=null){
		if(!isset($o))
			$o = new Saludmascotas_Model_Mascota();
		return $o->setData($this->toArrayAll('ma_'));
	}
}
?>