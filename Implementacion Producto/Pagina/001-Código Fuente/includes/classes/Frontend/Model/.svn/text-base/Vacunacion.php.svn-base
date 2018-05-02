<?php //es útf8
/**
 *@referencia Domicilio(id_domicilio) Frontend_Model_Domicilio(id)
 *@referencia Mascota(id_mascota) Frontend_Model_Mascota(id)
 *@referencia Usuario(id_usuario) Frontend_Usuario_Model_User(id)
*/
class Frontend_Model_Vacunacion extends Saludmascotas_Model_Vacunacion{
	protected static $class = __CLASS__; 
	public function _construct(){
		parent::_construct();
		//artificiales
//		$this->setNonTableColumn('id_pais', 'provincia', 'localidad', 'barrio');

		$this

//			->setFieldLabel('entrenada','Entrenamiento')
//			->addValidator('entrenada', c(new Zend_Validate_NotEmpty(array('allowWhiteSpace' => true))))

			->setFieldLabel('fecha_inicio','Fecha de Inicio')
			->addValidator('fecha_inicio', c(new Zend_Validate_NotEmpty(array('allowWhiteSpace' => false))))

			->setFieldLabel('fecha_fini','fin')
			->addValidator('fecha_fini', c(new Zend_Validate_NotEmpty(array('allowWhiteSpace' => false))))
		;
	}
	public function loadNonTableColumn(){
		return $this;
	}
	public function commitNonTableColumn(){
	}
	public function updateFromUserInput($data=null, $use_null_values=false, $match_fields=array('id')){
		$this->commitNonTableColumn();
		$updated = parent::update($data,$use_null_values, $match_fields);
		return $updated;
	}
	public function insertFromUserInput($data=null,$use_null_values=false, $get_sql=false){
		$this->commitNonTableColumn();
		$inserted = parent::insert($data,$use_null_values, $get_sql);
		return $inserted;
	}
	public function getUrlEditar(){
		return Frontend_Vacunacion_Helper::getUrlEditarVacunacion($this->getId());
	}
	public function getUrlEliminar(){
		return Frontend_Vacunacion_Helper::getUrlEliminarVacunacion($this->getId());
	}
}

?>