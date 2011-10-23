<?
class Saludmascotas_Model_View_PatrullajeVisita extends Saludmascotas_Db_Model_View_Abstract{
	protected function init(){
		parent::init();
		$view = new Saludmascotas_Db_Model_View_Generic();
		
		$patrullaje = new Saludmascotas_Model_Patrullaje();
		$visita = new Saludmascotas_Model_VisitaBarrio();
		$view
			->addTableByModel($patrullaje, null, 'pa', true)
			->addTableByModel($visita, 'pa.id = vi.id_patrullaje', 'vi', true)
		;
		$this->addViewByModel($view, 'pv', false);
	}
	public function getPatrullaje($o=null){
		if(!isset($o))
			$o = new Saludmascotas_Model_Patrullaje();
		return $o->setData($this->toArrayAll('pa_'));
	}
	public function getVisitaBarrio($o=null){
		if(!isset($o))
			$o = new Saludmascotas_Model_VisitaBarrio();
		return $o->setData($this->toArrayAll('vi_'));
	}
}
?>