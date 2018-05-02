<?
/**
 *@referencia Spa(id_spa) Saludmascotas_Model_User(id)
 *@referencia domicilio(id_domicilio) Saludmascotas_Model_Domicilio(id)
*/
class Saludmascotas_Model_Vacunacion extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$this->setTableColumn(
			'id'
			,'activo'
			,'fecha_inicio'
			,'fecha_fin'
			,'texto'
			,'id_spa'
			,'id_domicilio'
		);
		$this->addAutofilterFieldInput('fecha_inicio', array('Mysql_Helper','filterTimestampInput'));
		$this->addAutofilterFieldOutput('fecha_inicio', array('Mysql_Helper','filterTimestampOutput'));
		$this->addAutofilterFieldInput('fecha_fin', array('Mysql_Helper','filterTimestampInput'));
		$this->addAutofilterFieldOutput('fecha_fin', array('Mysql_Helper','filterTimestampOutput'));
	}
	public function setActivo($set=true){
		$this->setData('activo', $set?'si':'no');
		return $this;
	}
	public function esActivo(){
		return $this->getActivo()=='si';
	}
	public function getDbTableName() 
	{
		return 'sm_vacunacion';
	}
}
?>