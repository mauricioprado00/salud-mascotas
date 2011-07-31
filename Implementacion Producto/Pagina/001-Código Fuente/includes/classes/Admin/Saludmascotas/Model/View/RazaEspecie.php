<?
class Admin_Saludmascotas_Model_View_RazaEspecie extends Saludmascotas_Db_Model_View_Abstract{
	protected function init(){
		parent::init();
		$view = new Saludmascotas_Db_Model_View_Generic();
		$view
			->addTable('sm_raza',null,'r',array(
				'id'=>'r.id','nombre'=>'r.nombre'))
			->addTable('sm_especie','r.id_especie = e.id','e',array(
				'especie'=>'e.nombre'))
		;
		$this->addView($view, 're', array(
			'id','nombre','especie'));
	}
}
?>