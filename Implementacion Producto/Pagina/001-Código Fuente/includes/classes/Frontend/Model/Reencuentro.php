<?php //es útf8
/**
 *@referencia Usuario(id_usuario) Frontend_Model_Usuario_Model_User(id)
 *@referencia Perdida(id_perdida) Frontend_Model_Perdida(id)
 *@referencia Encuentro(id_encuentro) Frontend_Model_Encuentro(id)
*/
class Frontend_Model_Reencuentro extends Saludmascotas_Model_Reencuentro{
	protected static $class = __CLASS__; 
	public function _construct(){
		parent::_construct();
		//artificiales
//		$this->setNonTableColumn('edad', 'id_especie', 'raza', 'cantidad_colores', 'perdido', 'colores_seleccionados', 'estado');
//		$this->setNonTableColumn('id_pais', 'provincia', 'localidad', 'barrio');
		$this
			->setFieldLabel('descripcion','Descripcion')
			->addValidator('descripcion', c(new Zend_Validate_NotEmpty(array('allowWhiteSpace' => true))))
		;
	}
	public function addExtraValidators(){
		$this
			->setFieldLabel('nombre','Nombre')
			->addValidator('nombre', c(new Zend_Validate_Alpha(array('allowWhiteSpace' => true))))

			->setFieldLabel('email','Email')
			->addValidator('email', c(new Zend_Validate_NotEmpty()))
			->addValidator('email', c(new Zend_Validate_EmailAddress()))
		;
		return $this;
	}
	public function loadNonTableColumn(){}
//	public function updateFromUserInput($data=null, $use_null_values=false, $match_fields=array('id')){}
//	public function insertFromUserInput($data=null,$use_null_values=false, $get_sql=false){}
}

?>