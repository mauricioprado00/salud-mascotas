<?php //es útf8
/**
 *@referencia Etiqueta(id_etiqueta) Frontend_Model_Etiqueta(id)
 *@referencia Mascota(id_mascota) Frontend_Model_Mascota(id)
*/
class Frontend_Model_EtiquetaMascota extends Saludmascotas_Model_EtiquetaMascota{
	protected static $class = __CLASS__; 
	public function _construct(){
		parent::_construct();
		//artificiales
//		$this->setNonTableColumn('id_pais', 'provincia', 'localidad', 'barrio');

		$this

//			->setFieldLabel('entrenada','Entrenamiento')
//			->addValidator('entrenada', c(new Zend_Validate_NotEmpty(array('allowWhiteSpace' => true))))

//			->setFieldLabel('nombre','Nombre')
//			->addValidator('nombre', c(new Zend_Validate_NotEmpty(array('allowWhiteSpace' => false))))
		;
	}
	public function loadNonTableColumn(){
		//$time = $this->getData('hora_encuentro', null, array());
		//$time = $this->getHoraEtiquetaMascota();
		//var_dump($time);
//		$time = explode(' ', $time);
//		$this
//			->setData('encuentro_fecha', $time[0], array())
//			->setEtiquetaMascotaHora($time[1])
//		;
		return $this;
	}
	public function commitNonTableColumn(){
//		$hora_encuentro = $this->getData('encuentro_fecha', null, array());
//		$hora_encuentro .= ' ' . $this->getEtiquetaMascotaHora();
//		$this->setHoraEtiquetaMascota($hora_encuentro, array());
	}
	public function updateFromUserInput($data=null, $use_null_values=false, $match_fields=array('id')){
		$this->commitNonTableColumn();
		$updated = parent::update($data,$use_null_values, $match_fields);
		return $updated;
	}
	public function insertFromUserInput($data=null,$use_null_values=false, $get_sql=false){
		$this->commitNonTableColumn();
//		$this->setFechaPublicacion(time());
//		$this->setFechaExpiracion(time()+60*60*24*Saludmascotas_Model_Config::findConfigValue('sm/dias_expiracion_encuentro', 7*4/*4 semanas*/));
		$inserted = parent::insert($data,$use_null_values, $get_sql);
		return $inserted;
	}
}

?>