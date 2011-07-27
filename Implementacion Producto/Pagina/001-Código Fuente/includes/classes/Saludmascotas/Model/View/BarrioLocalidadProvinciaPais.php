<?
class Saludmascotas_Model_View_BarrioLocalidadProvinciaPais extends Saludmascotas_Db_Model_View_Abstract{
	protected function init(){
		parent::init();
		$view = new Saludmascotas_Db_Model_View_Generic();
		$view
			->addTable('sm_barrio',null,'b',array(
				'id'=>'b.id','id_localidad'=>'b.id_localidad','nombre'=>'b.nombre'))
			->addTable('sm_localidad','b.id_localidad = c.id','c',array(
				'id'=>'c.id','id_provincia'=>'c.id_provincia','localidad'=>'c.nombre'))
			->addTable('sm_provincia','c.id_provincia = pr.id','pr',array(
				'id'=>'pr.id','id_pais'=>'pr.id_pais','provincia'=>'pr.nombre'))
			->addTable('sm_pais', 'pr.id_pais = p.id', 'p', array('pais'=>'p.nombre'))
		;
		$this->addView($view, 'vblpp', array(
			'id','id_pais','pais','id_provincia','provincia','id_localidad','localidad','nombre'));
	}
}
?>