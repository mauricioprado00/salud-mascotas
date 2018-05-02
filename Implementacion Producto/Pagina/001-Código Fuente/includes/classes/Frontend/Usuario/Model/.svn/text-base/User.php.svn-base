<?php //es útf8
/**
 *@referencia Domicilio(id_domicilio) Frontend_Model_Domicilio(id)
*/

class Frontend_Usuario_Model_User extends Saludmascotas_Model_User{
	protected static $class = __CLASS__; 
	public function init(){
		parent::init();
		$this
			->setFieldLabel('nombre','Nombre')
			//->addValidator('nombre', c(new Zend_Validate_NotEmpty()))
			->addValidator('nombre', c(new Zend_Validate_Alpha(array('allowWhiteSpace' => true))))

			->setFieldLabel('apellido','Apellido')
			->addValidator('apellido', c(new Zend_Validate_Alpha(array('allowWhiteSpace' => true))))

			->setFieldLabel('telefono','Telefono')
			//->addValidator('telefono', c(new Zend_Validate_NotEmpty()))

			->setFieldLabel('email','Email')
			->addValidator('email', c(new Zend_Validate_NotEmpty()))
			->addValidator('email', c(new Zend_Validate_EmailAddress()))

			->setFieldLabel('username','Nombre de Usuario')
			//->addValidator('username', c(new Zend_Validate_NotEmpty()))
			->addValidator('username', c(new Zend_Validate_Regex(array('pattern' => '/^[A-Za-z0-9]+(_[A-Za-z0-9]+)?$/'))))

			->setFieldLabel('password','Contraseña')
			->addValidator('password', c(new Zend_Validate_NotEmpty()))

		;
	}
	public static $_logged_user = null;
	public static function getLogedUser(){
		if(isset(self::$_logged_user))
			return self::$_logged_user;
		$_this = new self();
		if(!$_this->isLoged())
			return(null);
		return(self::$_logged_user = $_this);
	}
	protected function translateDuplicatedKey($error){
		$ret = parent::translateDuplicatedKey($error);
		if($meta = $error->getMeta()){
			$key = $meta->getKey();
			switch($key->getColumnName()){
				case 'email':{
					$ret = 'Ya existe un usuario con el <b>Email</b> <em>\''.$meta->getValor().'\'</em>';
					break;
				}
				case 'username':{
					$ret = 'El <b>nombre de usuario</b> <em>\'' . $meta->getValor() . '\'</em> ya esta utilizado';
					break;
				}
			}
			//$ret = 'La clave Nº'.$matches['nro_campo'].$key_name.' con el valor \''.$matches['valor'].'\' esta duplicado';
		}
		return $ret;
	}
}

?>