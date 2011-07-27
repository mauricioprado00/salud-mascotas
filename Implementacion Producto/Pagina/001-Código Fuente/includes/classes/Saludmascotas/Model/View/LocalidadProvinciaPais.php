<?
class Saludmascotas_Model_View_LocalidadProvinciaPais extends Saludmascotas_Db_Model_View_Abstract{
	protected function init(){
		parent::init();
		$view = new Saludmascotas_Db_Model_View_Generic();
		$view
			->addTable('sm_localidad',null,'c',array(
				'id'=>'c.id','id_provincia'=>'c.id_provincia','nombre'=>'c.nombre'))
			->addTable('sm_provincia','c.id_provincia = pr.id','pr',array(
				'id'=>'pr.id','id_pais'=>'pr.id_pais','provincia'=>'pr.nombre'))
			->addTable('sm_pais', 'pr.id_pais = p.id', 'p', array('pais'=>'p.nombre'))
		;
		$this->addView($view, 'vlpp', array(
			'id','id_pais','pais','id_provincia','provincia','nombre'));
	}
}
?>