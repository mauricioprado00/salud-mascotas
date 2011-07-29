<?php //es útf8
/**
 *@referencia Domicilio(id_domicilio) Frontend_Model_Domicilio(id)
 *@referencia Mascota(id_mascota) Frontend_Model_Mascota(id)
 *@referencia Usuario(id_usuario) Frontend_Usuario_Model_User(id)
*/
class Frontend_Model_Perdida extends Saludmascotas_Model_Perdida{
	protected static $class = __CLASS__; 
	public function _construct(){
		parent::_construct();
		//artificiales
		$this->setNonTableColumn('extravio_fecha', 'extravio_hora');
//		$this->setNonTableColumn('id_pais', 'provincia', 'localidad', 'barrio');

		$this->addAutofilterFieldInput('extravio_fecha', array('Mysql_Helper','filterDateInput'));
		$this->addAutofilterFieldOutput('extravio_fecha', array('Mysql_Helper','filterDateOutput'));
		$this

//			->setFieldLabel('entrenada','Entrenamiento')
//			->addValidator('entrenada', c(new Zend_Validate_NotEmpty(array('allowWhiteSpace' => true))))

			->setFieldLabel('extravio_fecha','Fecha de Extravío')
			->addValidator('extravio_fecha', c(new Zend_Validate_NotEmpty(array('allowWhiteSpace' => false))))

			->setFieldLabel('extravio_hora','Hora de Extravío')
			->addValidator('extravio_hora', c(new Zend_Validate_NotEmpty(array('allowWhiteSpace' => false))))
		;
	}
	public function loadNonTableColumn(){
		$time = $this->getData('hora_extravio', null, array());
		//$time = $this->getHoraExtravio();
		//var_dump($time);
		$time = explode(' ', $time);
		$this
			->setData('extravio_fecha', $time[0], array())
			->setExtravioHora($time[1])
		;
	}
	public function commitNonTableColumn(){
		$hora_extravio = $this->getData('extravio_fecha', null, array());
		$hora_extravio .= ' ' . $this->getExtravioHora();
		$this->setHoraExtravio($hora_extravio, array());
	}
	public function updateFromUserInput($data=null, $use_null_values=false, $match_fields=array('id')){
		$this->commitNonTableColumn();
		$updated = parent::update($data,$use_null_values, $match_fields);
		return $updated;
	}
	public function insertFromUserInput($data=null,$use_null_values=false, $get_sql=false){
		$this->commitNonTableColumn();
		$this->setFechaPublicacion(time());
		$this->setFechaExpiracion(time()+60*60*24*Saludmascotas_Model_Config::findConfigValue('sm/dias_expiracion_perdida', 7*4/*4 semanas*/));
		$inserted = parent::insert($data,$use_null_values, $get_sql);
		return $inserted;
	}
	public function getUrlEditar($preserve_mascota_edicion=0, $paso=1){
		$mascota = $this->getMascota();
		return Frontend_Mascota_Perdida_Helper::getUrlEditar($mascota->getId(), $preserve_mascota_edicion, $paso);
	}
}

?>