<?
class Saludmascotas_Model_View_MascotaReencuentro extends Saludmascotas_Db_Model_View_Abstract{
	protected function init(){
		parent::init();
		$view = new Saludmascotas_Db_Model_View_Generic();
		
		$reencuentro = new Saludmascotas_Model_Reencuentro();
		$mascota = new Saludmascotas_Model_Mascota();
		$encuentro = new Saludmascotas_Model_Encuentro();
		$perdida = new Saludmascotas_Model_Perdida();
		$view
			->addTableByModel($reencuentro, null, 're', true)
			->addTableByModel($encuentro, 'en.id=re.id_encuentro', 'en', false, array())
			->addTableByModel($perdida, 'pe.id=re.id_perdida', 'pe', false, array())
			->addTableByModel($mascota, '(en.id_mascota = ma.id or pe.id_mascota = ma.id)', 'ma', true)
		;
		$this->addViewByModel($view, 'me', false);
		$this->setGroupBy('re_id');
	}
	public function groupByReencuentro($group=true){
		if(!$group)
			return $this->setGroupBy();
		return $this->setGroupBy('re_id');
	}
	public function getReencuentro($o=null){
		if(!isset($o))
			$o = new Saludmascotas_Model_Reencuentro();
		return $o->setData($this->toArrayAll('re_'));
	}
	public function getMascota($o=null){
		if(!isset($o))
			$o = new Saludmascotas_Model_Mascota();
		return $o->setData($this->toArrayAll('ma_'));
	}
}
?>