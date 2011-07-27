<?
class Saludmascotas_Model_View_ProvinciaPais extends Saludmascotas_Db_Model_View_Abstract{
	protected function init(){
		parent::init();
		$view = new Saludmascotas_Db_Model_View_Generic();
		$view
			->addTable('sm_provincia',null,'pr',array(
				'id'=>'pr.id','id_pais'=>'pr.id_pais','nombre'=>'pr.nombre'))
			->addTable('sm_pais', 'pr.id_pais = p.id', 'p', array('pais'=>'p.nombre'))
		;
		$this->addView($view, 'vpp', array(
			'id','id_pais','pais','nombre'));
	}
}
?>