<?php //es útf8

class Frontend_Model_Domicilio extends Saludmascotas_Model_Domicilio{
	protected static $class = __CLASS__; 
	public function _construct(){
		parent::_construct();
		//artificiales
		$this->setNonTableColumn('id_pais', 'provincia', 'localidad', 'barrio', 'midomicilio', 'pais');
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
	public function loadNonTableColumn(){
		//$this->setNonTableColumn('id_pais', 'provincia', 'localidad', 'barrio', 'midomicilio', 'pais');
		if($barrio = $this->getBarrio()){
			$this->setBarrio($barrio->getNombre());
			if($localidad = $barrio->getLocalidad()){
				$this->setLocalidad($localidad->getNombre());
				if($provincia = $localidad->getProvincia()){
					$this->setProvincia($provincia->getNombre());
					if($pais = $provincia->getPais()){
						$this->setPais($pais->getNombre());
						$this->setIdPais($pais->getId());
					}
				}
			}
		}
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
		return $this;
	}
	public function restorePrivateData(){
		if($barrio = $this->getBarrio()){
			$this->setBarrio($barrio->getNombre());
			if($localidad = $barrio->getLocalidad()){
				$this->setLocalidad($localidad->getNombre());
				if($provincia = $localidad->getProvincia()){
					$this->setProvincia($provincia->getNombre());
					if($pais = $provincia->getPais()){
						$this->setIdPais($pais->getId());
						$this->setPais($pais->getNombre());
					}
				}
			}
		}
		return $this;
	}

	
	public function insertFromUserInput($data=null,$use_null_values=false, $get_sql=false){
		$this->addLocations();
		return parent::insert($data,$use_null_values, $get_sql);
	}
	public function updateFromUserInput($data=null, $use_null_values=false, $match_fields=array('id')){
		$this->addLocations();
		return parent::update($data,$use_null_values, $match_fields);
	}
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