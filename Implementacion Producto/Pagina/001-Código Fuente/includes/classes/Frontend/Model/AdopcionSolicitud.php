<?php //es útf8
/**
 *@referencia Domicilio(id_domicilio) Frontend_Model_Domicilio(id)
 *@referencia Mascota(id_mascota) Frontend_Model_Mascota(id)
 *@referencia Usuario(id_usuario) Frontend_Usuario_Model_User(id)
*/
class Frontend_Model_AdopcionSolicitud extends Saludmascotas_Model_AdopcionSolicitud{
	protected static $class = __CLASS__; 
	public function _construct(){
		parent::_construct();
		//artificiales
//		$this->setNonTableColumn('adopcion_solicitud_fecha', 'adopcion_solicitud_hora');
//		$this->setNonTableColumn('id_pais', 'provincia', 'localidad', 'barrio');

//		$this->addAutofilterFieldInput('adopcion_solicitud_fecha', array('Mysql_Helper','filterDateInput'));
//		$this->addAutofilterFieldOutput('adopcion_solicitud_fecha', array('Mysql_Helper','filterDateOutput'));
		$this

//			->setFieldLabel('entrenada','Entrenamiento')
//			->addValidator('entrenada', c(new Zend_Validate_NotEmpty(array('allowWhiteSpace' => true))))

//			->setFieldLabel('adopcion_solicitud_fecha','Fecha de AdopcionSolicitud')
//			->addValidator('adopcion_solicitud_fecha', c(new Zend_Validate_NotEmpty(array('allowWhiteSpace' => false))))
//
//			->setFieldLabel('adopcion_solicitud_hora','Hora de AdopcionSolicitud')
//			->addValidator('adopcion_solicitud_hora', c(new Zend_Validate_NotEmpty(array('allowWhiteSpace' => false))))
		;
	}
	public function loadNonTableColumn(){
//		$time = $this->getData('hora_adopcion_solicitud', null, array());
//		//$time = $this->getHoraAdopcionSolicitud();
//		//var_dump($time);
//		$time = explode(' ', $time);
//		$this
//			->setData('adopcion_solicitud_fecha', $time[0], array())
//			->setAdopcionSolicitudHora($time[1])
//		;
		return $this;
	}
	public function commitNonTableColumn(){
//		$hora_adopcion_solicitud = $this->getData('adopcion_solicitud_fecha', null, array());
//		$hora_adopcion_solicitud .= ' ' . $this->getAdopcionSolicitudHora();
//		$this->setHoraAdopcionSolicitud($hora_adopcion_solicitud, array());
	}
	public function updateFromUserInput($data=null, $use_null_values=false, $match_fields=array('id')){
		$this->commitNonTableColumn();
		$updated = parent::update($data,$use_null_values, $match_fields);
		return $updated;
	}
	public function insertFromUserInput($data=null,$use_null_values=false, $get_sql=false){
		$this->commitNonTableColumn();
		$this->setFechaPublicacion(time());
		$this->setFechaExpiracion(time()+60*60*24*Saludmascotas_Model_Config::findConfigValue('sm/dias_expiracion_adopcion_solicitud', 7*4/*4 semanas*/));
		$inserted = parent::insert($data,$use_null_values, $get_sql);
		return $inserted;
	}
	public function getUrlEditar($preserve_mascota_edicion=0, $paso=1){
		$mascota = $this->getMascota();
		return Frontend_Mascota_AdopcionSolicitud_Helper::getUrlEditar($mascota->getId(), $preserve_mascota_edicion, $paso);
	}
}

?>