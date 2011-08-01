<?
class Saludmascotas_Model_View_MascotaEncuentro extends Saludmascotas_Db_Model_View_Abstract{
	protected function init(){
		parent::init();
		$view = new Saludmascotas_Db_Model_View_Generic();
		
		$mascota = new Saludmascotas_Model_Mascota();
		$encuentro = new Saludmascotas_Model_Encuentro();
		$raza = new Saludmascotas_Model_Raza();
		$domicilio = new Saludmascotas_Model_Domicilio();
		$view
			->addTableByModel($encuentro, null, 'en', true)
			->addTableByModel($mascota, 'en.id_mascota = ma.id', 'ma', true)
			->addTableByModel($raza, 'ra.id = ma.id_raza', 'ra', true)
			->addTableByModel($domicilio, 'do.id = en.id_domicilio', 'do', true)
		;
		$this->addViewByModel($view, 'me', false);
	}
	public function getEncuentro($o=null){
		if(!isset($o))
			$o = new Saludmascotas_Model_Encuentro();
		return $o->setData($this->toArrayAll('en_'));
	}
	public function getMascota($o=null){
		if(!isset($o))
			$o = new Saludmascotas_Model_Mascota();
		return $o->setData($this->toArrayAll('ma_'));
	}
	public function getRaza($o=null){
		if(!isset($o))
			$o = new Saludmascotas_Model_Raza();
		return c(new Saludmascotas_Model_Raza())->setData($this->toArrayAll('ra_'));
	}
	public function getDomicilio($o=null){
		if(!isset($o))
			$o = new Saludmascotas_Model_Domicilio();
		return $o->setData($this->toArrayAll('do_'));
	}
}
?>