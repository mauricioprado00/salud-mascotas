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
		$this

			->setFieldLabel('entrenada','Entrenamiento')
			->addValidator('entrenada', c(new Zend_Validate_NotEmpty(array('allowWhiteSpace' => true))))
		;
	}

//	public function update($data=null, $use_null_values=false, $match_fields=array('id')){
//		$updated = parent::update($data,$use_null_values, $match_fields);
//		return $updated;
//	}
//	public function insert($data=null,$use_null_values=false, $get_sql=false){
//		$inserted = parent::insert($data,$use_null_values, $get_sql);
//		return $inserted;
//	}
	public function getUrlEditar($preserve_mascota_edicion=0, $paso=1){
		$mascota = $this->getMascota();
		return Frontend_Mascota_Perdida_Helper::getUrlEditar($mascota->getId(), $preserve_mascota_edicion, $paso);
	}
}

?>