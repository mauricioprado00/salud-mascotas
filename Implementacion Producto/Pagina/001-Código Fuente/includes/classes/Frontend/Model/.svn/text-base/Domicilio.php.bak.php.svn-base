<?php //es útf8

class Frontend_Model_Domicilio extends Saludmascotas_Model_Domicilio{
	protected static $class = __CLASS__; 
	public function _construct(){
		parent::_construct();
		//artificiales
		$this
			->setData('id_pais', null)
			->setData('provincia', null)
			->setData('localidad', null)
			->setData('barrio', null)
		;
		$this
			->setFieldLabel('id_pais','Pais')
			//->addValidator('nombre', c(new Zend_Validate_NotEmpty()))
			->addValidator('id_pais', c(new Zend_Validate_NotEmpty(array('allowWhiteSpace' => true))))

			->setFieldLabel('provincia','Provincia')
			//->addValidator('nombre', c(new Zend_Validate_NotEmpty()))
			->addValidator('provincia', c(new Zend_Validate_Alpha(array('allowWhiteSpace' => true))))

			->setFieldLabel('localidad','Localidad')
			//->addValidator('nombre', c(new Zend_Validate_NotEmpty()))
			->addValidator('localidad', c(new Zend_Validate_Alpha(array('allowWhiteSpace' => true))))

			->setFieldLabel('barrio','Barrio')
			//->addValidator('barrio', c(new Zend_Validate_NotEmpty()))
			->addValidator('barrio', c(new Zend_Validate_Alpha(array('allowWhiteSpace' => true))))

			->setFieldLabel('calle_numero','Domicilio')
			//->addValidator('nombre', c(new Zend_Validate_NotEmpty()))
			->addValidator('calle_numero', c(new Zend_Validate_Alnum(array('allowWhiteSpace' => true))))

//			->setFieldLabel('nombre','Nombre')
//			//->addValidator('nombre', c(new Zend_Validate_NotEmpty()))
//			->addValidator('nombre', c(new Zend_Validate_Alpha(array('allowWhiteSpace' => true))))
//
//			->setFieldLabel('apellido','Apellido')
//			->addValidator('apellido', c(new Zend_Validate_Alpha(array('allowWhiteSpace' => true))))
//
//			->setFieldLabel('telefono','Telefono')
//			//->addValidator('telefono', c(new Zend_Validate_NotEmpty()))
//
//			->setFieldLabel('email','Email')
//			->addValidator('email', c(new Zend_Validate_NotEmpty()))
//			->addValidator('email', c(new Zend_Validate_EmailAddress()))
//
//			->setFieldLabel('username','Nombre de Usuario')
//			//->addValidator('username', c(new Zend_Validate_NotEmpty()))
//			->addValidator('username', c(new Zend_Validate_Regex(array('pattern' => '/^[A-Za-z0-9]+(_[A-Za-z0-9]+)?$/'))))
//
//			->setFieldLabel('password','Contraseña')
//			->addValidator('password', c(new Zend_Validate_NotEmpty()))

		;
	}
	private function fixName($name){
		return preg_replace('/\\s+/', ' ', strtolower(trim($name)));
	}
	private function addLocations(){
		//hacer algo con los barrios, localidad, provincia (agregar si no existe y poner los ids en vez de los nombre)
		$existe_localidad = true;
		$existe_barrio = true;
		
		//inserto provincia
		$provincia = new Saludmascotas_Model_Provincia();
		$existe_provincia = $provincia
			->setIdPais($this->getIdPais())
			->setNombre($this->fixName($this->getData('provincia')))
			->load(null, null, false)
		;
		if(!$existe_provincia){
			if(!$provincia->insert()){//die(__FILE__.__LINE__);
				return false;
			}
		}
		//$this->setIdProvincia($provincia->getId());

		//inserto localidad
		$localidad = new Saludmascotas_Model_Localidad();
		$localidad
			->setIdProvincia($provincia->getId())
			->setNombre($this->fixName($this->getData('localidad')))
		;
		if(!$existe_provincia){//die(__FILE__.__LINE__);
			$existe_localidad = false;
		}
		else{
			$existe_localidad = $localidad->load(null, null, false);
		}
		if(!$existe_localidad){
			if(!$localidad->insert()){//die(__FILE__.__LINE__);
				return false;
			}
		}
		//$this->setIdLocalidad($localidad->getId());

		//inserto barrio
		$barrio = new Saludmascotas_Model_Barrio();
		$barrio
			->setIdLocalidad($localidad->getId())
			->setNombre($this->fixName($this->getData('barrio')))
		;
		if(!$existe_localidad){//die(__FILE__.__LINE__);
			$existe_barrio = false;
		}
		else{
			$existe_barrio = $barrio->load(null, null, false);
		}
		if(!$existe_barrio){
//			var_dump($this->fixName($this->getData('barrio')),$this->getData('barrio'), $this->getData(), $barrio->getData());
//			die(__FILE__.__LINE__);
			if(!$barrio->insert()){
//				var_dump($barrio->getTranslatedErrors());
//				die(__FILE__.__LINE__);
				return false;
			}
		}
		$this->setIdBarrio($barrio->getId());
		$this->savePrivateData();
		//borro campos no existentes en modelo real de bd
//		$this
//			->unsetData('id_pais')
//			->unsetData('provincia')
//			->unsetData('localidad')
//			->unsetData('barrio')
//		;
//		var_dump($this->getData());
//		die(__FILE__.__LINE__);
		return $this;
	}
	public function load($data=null, $only_if_unique=false, $reset_data_if_error=true){
		$this->savePrivateData();
		return parent::load($data, $only_if_unique, $reset_data_if_error);
	}
	private $_private_data = null;
	public function savePrivateData(){
		if($this->hasData('barrio')&&$this->getData('barrio'))
			$this->_private_data = array(
				'id_pais'=>$this->getData('id_pais'),
				'provincia'=>$this->getData('provincia'),
				'localidad'=>$this->getData('localidad'),
				'barrio'=>$this->getData('barrio'),
			); 
		$this
			->unsetData('id_pais')
			->unsetData('provincia')
			->unsetData('localidad')
			->unsetData('barrio')
		;
		return $this;
	}
	public function restorePrivateData(){
//		var_dump($this->_private_data);
//		die(__FILE__.__LINE__);
		if(!isset($this->_private_data)){
			if($barrio = $this->getBarrio()){
				$this->setBarrio($barrio->getNombre());
				if($localidad = $barrio->getLocalidad()){
					$this->setLocalidad($localidad->getNombre());
					if($provincia = $localidad->getProvincia()){
						$this->setProvincia($provincia->getNombre());
						if($pais = $provincia->getPais()){
							$this->setIdPais($pais->getId());
						}
					}
				}
			}
//			var_dump($this->getData());
//			die(__FILE__.__LINE__);
		}
		else 
			$this
				->setData('id_pais', $this->_private_data['id_pais'])
				->setData('provincia', $this->_private_data['provincia'])
				->setData('localidad', $this->_private_data['localidad'])
				->setData('barrio', $this->_private_data['barrio'])
			;
		return $this;
	}
//	private $_private_data = array();
//    public function setData($key, $value=null, $filter=null){
//    	if(in_array($key, array('id_pais', 'provincia', 'localidad', 'barrio'))){
//			$this->_private_data[$key] = $value;
//			return $this;
//		}
//		return parent::setData($key, $value, $filter);
//    }
//    public function getBarrio(){
//		
//	}

	
	public function insert($data=null,$use_null_values=false, $get_sql=false){
		$this->addLocations();
		return parent::insert($data,$use_null_values, $get_sql);
	}
	public function update($data=null, $use_null_values=false, $match_fields=array('id')){
		$this->addLocations();
		return parent::update($data,$use_null_values, $match_fields);
	}
//	public static $_logged_user = null;
//	public static function getLogedUser(){
//		if(isset(self::$_logged_user))
//			return self::$_logged_user;
//		$_this = new self();
//		if(!$_this->isLoged())
//			return(null);
//		return(self::$_logged_user = $_this);
//	}
//	protected function translateDuplicatedKey($error){
//		$ret = parent::translateDuplicatedKey($error);
//		if($meta = $error->getMeta()){
//			$key = $meta->getKey();
//			switch($key->getColumnName()){
//				case 'email':{
//					$ret = 'Ya existe un usuario con el <b>Email</b> <em>\''.$meta->getValor().'\'</em>';
//					break;
//				}
//				case 'username':{
//					$ret = 'El <b>nombre de usuario</b> <em>\'' . $meta->getValor() . '\'</em> ya esta utilizado';
//					break;
//				}
//			}
//			//$ret = 'La clave Nº'.$matches['nro_campo'].$key_name.' con el valor \''.$matches['valor'].'\' esta duplicado';
//		}
//		return $ret;
//	}
}

?>