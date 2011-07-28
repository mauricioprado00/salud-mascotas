<?php //es útf8
class Frontend_Mascota_Helper extends Frontend_Helper{
	public static function getUrl(){
		return 'mascotas';
	}
	public static function getUrlUsuario($numero_pag=null){
		return 'mascotas/usuario'.($numero_pag?'/'.$numero_pag:'');
	}
	public static function getUrlUploadPhoto($ajax=false, $jsonp_callback='', $id_mascota=null){
		return 'mascotas/fotos/'.($ajax?'ajax_':'').'upload'.($jsonp_callback?'/'.$jsonp_callback:'').($id_mascota?'/'.$id_mascota:'');
	}
	public static function getUrlPhoto(){
		return 'mascotas/fotos';
	}
	public static function getUrlAgregar($preserve_mascota_edicion=0, $paso=1){
		if($paso==1&&$preserve_mascota_edicion==false)
			return 'mascotas/agregar';
		return 'mascotas/agregar/'.$paso.'/'.$preserve_mascota_edicion;
	}
	public static function getUrlEditar($id_mascota, $preserve_mascota_edicion=0, $paso=1){
		if(!isset($id_mascota)||$id_mascota=='new')
			return self::getUrlAgregar($preserve_mascota_edicion, $paso);
		return 'mascotas/editar/'.$paso.'/'.$id_mascota.'/'.$preserve_mascota_edicion;
	}
	public static function getEntrenadaMascota(){
		return array('no','policia','caza','ciego','obediente');
	}
	public static function esgetEntrenadaMascotaValida($value){
		return in_array($value, self::getEntrenadaMascota());
	}
	public static function getSexoMascota($include_i_dont_know=false){
		if(!$include_i_dont_know)
			return array('hembra', 'macho');
		return array('hembra', 'macho', 'no sé');
	}
	public static function esgetSexoMascotaValida($value){
		return in_array($value, self::getSexoMascota());
	}
	public static function getTamanoMascota(){
		return array('extra small', 'small', 'medium', 'big');
	}
	public static function esgetTamanoMascotaValida($value){
		return in_array($value, self::getTamanoMascota());
	}
	public static function getCantidadColores(){
		return array(1,2,3,4,5);
	}
	public static function esgetCantidadColoresValida($value){
		return in_array($value, self::getCantidadColores());
	}
	public static function getUpdatableFields(){
		return array(
			//'id'
			//,'activa'
			'castrado'
			,'descripcion'
			//,'destacada'
			,'entrenada'
			,'fecha_nacimiento'
			,'nombre'
			,'para_adoptar'
			,'para_cruza'
			,'para_venta'
			,'pedigree'
			,'quiere_destacar'
			,'sexo'
			,'tamano'
			//,'id_domicilio'
			//,'id_dueno'
			//,'id_estadomascota'
			,'id_longitud_pelaje'
			,'id_manto'
			,'id_raza'
			
			
			//campos frontend
			,'edad'
			,'id_especie'
			,'raza'
			,'cantidad_colores'
			,'perdido'
			,'colores_seleccionados'
		);
	}
	public static function getUpdatableDomicilioFields(){
		return array(
			'midomicilio'
			
			,'id_pais'
			,'provincia'
			,'localidad'
			,'barrio'
			,'calle_numero'
			,'lat'
			,'lng'
		);
	}
	public static function getDomicilioUsuario($restore_private_data=true){
		$usuario = self::getLogedUser();
		$domicilio_usuario = $usuario->getDomicilio();
		if($domicilio_usuario && $restore_private_data)
			$domicilio_usuario->restorePrivateData();
		return $domicilio_usuario;
	}
	protected static function getUserSessionContext(){
		return array(__CLASS__);
	}
	public static function getDomicilioMascotaEdicionFromSession($id_mascota=null){
		if(self::getUserSessionVar('id_mascota_edicion') != $id_mascota)
			return null;
		return self::getUserSessionVar('domicilio_mascota_edicion');
//		$usuario = self::getLogedUser();
////		var_dump($usuario->getSessionVar('id_mascota_edicion'), $id_mascota, $usuario->getSessionVar('id_mascota_edicion') != $id_mascota, $usuario->getSessionVar('mascota_edicion'));
////		DIE(__FILE__.__LINE__);
//		if($usuario->getSessionVar('id_mascota_edicion', array(__CLASS__)) != $id_mascota)
//			return null;
//		return $usuario->getSessionVar('domicilio_mascota_edicion', array(__CLASS__));
	}
	public static function getMascotaEdicionFromSession($id_mascota=null){
		if(self::getUserSessionVar('id_mascota_edicion') != $id_mascota)
			return null;
		return self::getUserSessionVar('mascota_edicion');
//		$usuario = self::getLogedUser();
////		var_dump($usuario->getSessionVar('id_mascota_edicion'), $id_mascota, $usuario->getSessionVar('id_mascota_edicion') != $id_mascota, $usuario->getSessionVar('mascota_edicion'));
////		DIE(__FILE__.__LINE__);
////		$usuario->setSessionVar('id_mascota_edicion', null);
////		$usuario->setSessionVar('mascota_edicion', null);
//		if($usuario->getSessionVar('id_mascota_edicion', array(__CLASS__)) != $id_mascota)
//			return null;
//		return $usuario->getSessionVar('mascota_edicion', array(__CLASS__));
	}
	private static function setMascotaEdicionInSession($mascota){
		self::setUserSessionVar('id_mascota_edicion', $mascota->getId());
		self::setUserSessionVar('mascota_edicion', $mascota);
//		$usuario = self::getLogedUser();
//		$usuario->setSessionVar('id_mascota_edicion', $mascota->getId(), array(__CLASS__));
//		$usuario->setSessionVar('mascota_edicion', $mascota, array(__CLASS__));
	}
	private static function setDomicilioMascotaEdicionInSession($domicilio){
		self::setUserSessionVar('domicilio_mascota_edicion', $domicilio);
//		$usuario = self::getLogedUser();
//		$usuario->setSessionVar('domicilio_mascota_edicion', $domicilio, array(__CLASS__));
	}

	public static function clearSessionVars(){
		self::setUserSessionVar('id_mascota_edicion', null);
		self::setUserSessionVar('mascota_edicion', null);
		self::setUserSessionVar('domicilio_mascota_edicion', null);
//		$usuario = self::getLogedUser();
//		$usuario->setSessionVar('id_mascota_edicion', null, array(__CLASS__));
//		$usuario->setSessionVar('mascota_edicion', null, array(__CLASS__));
//		$usuario->setSessionVar('domicilio_mascota_edicion', null, array(__CLASS__));
	}
	public static function getMascotaEdicion($id_mascota){
		$mascota = new Frontend_Model_Mascota();
		$mascota->setId($id_mascota);
		if($loaded = $mascota->load()){
			$usuario = self::getLogedUser();
			if($usuario->getId()==$mascota->getIdDueno()){
				$mascota->loadNonTableColumn();
				return $mascota;
			}
			Core_App::getInstance()->addErrorMessage("La mascota que intenta editar no le pertenece", true);
		}
		else{
			Core_App::getInstance()->addErrorMessage("La mascota a editar no existe", true);
		}
		return null;
	}
	public static function getDomicilioEdicion($mascota){
		$domicilio = $mascota->getDomicilio();
		if(!$domicilio)
			return null;
		$domicilio->loadNonTableColumn();
		$user = self::getLogedUser();
		if($domicilio->getId()==$user->getIdDomicilio()){
			$domicilio->setMidomicilio('si');
		}
		return $domicilio;
	}
	public static function actionAgregarEditarMascota($mascota, $to_session=true, $domicilio_mascota=null){
		if(!is_a($mascota,'Frontend_Model_Mascota')){
			$array = $mascota->getData();
			$mascota = new Frontend_Model_Mascota();
			$mascota->loadFromArray($array);
		}
		$errors = array();
		if(!$mascota->getCantidadColores()){
			$errors[] = 'Debe seleccionar los <b>colores</b>';
		}
		
		if(!$mascota->validateFields() || $errors){
			Core_App::getInstance()->addErrorMessage(self::getInstance()->__t("No se pudo registrar la mascota"));
			Core_Helper::LoadValidationTranslation();
			if($mascota->getValidationMessages())
				foreach($mascota->getValidationMessages() as $key=>$messages){
					foreach($messages as $message){
						Core_App::getInstance()->addErrorMessage($message);
					}
				}
			foreach($errors as $message){
				Core_App::getInstance()->addErrorMessage($message);
			}
			return false;
		}
		$usuario = self::getLogedUser();
		if($to_session){
			self::setMascotaEdicionInSession($mascota);
			Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Mascota guardada en sesión'), true);
			return true;
		}
		if(isset($domicilio_mascota)){
			if($domicilio_mascota->getMidomicilio()=='si' || !isset($domicilio_mascota)){
				if(!($id_domicilio = $usuario->getIdDomicilio())){
					Core_App::getInstance()->addErrorMessage(self::getInstance()->__t("No se pudo registrar la mascota, su domicilio es incorrecto"), true);
					return false;
				}
				$mascota->setIdDomicilio($id_domicilio);
			}
			else{
				//if(!$mascota->getIdDomicilio())
				$mascota->setIdDomicilio($domicilio_mascota->getId());
			}
		}
		if(!$mascota->hasId()){/** aca hay que agregar a la base de datos*/
			$mascota->setIdDueno($usuario->getId());
			$resultado = $mascota->insert()?true:false;
			if($resultado){
				Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Mascota registrada correctamente'), true);
			}
			else{
				Core_App::getInstance()->addErrorMessage("No se pudo registrar la mascota");
				foreach($mascota->getTranslatedErrors() as $error){
					Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
				}
			}
		}
		else{/** aca hay que actualizar el registro*/
			$resultado = $mascota->update(null)?true:false;
//			header('content-type:text/plain');
//			var_dump($mascota);
//			die(__FILE__.__LINE__);
//			
			if($resultado){
				Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Sus datos han sido actualizados'));
			}
			else{
				Core_App::getInstance()->addErrorMessage("No se pudo actualizar sus datos");
				foreach($mascota->getTranslatedErrors() as $error){
					Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
				}
			}
 		}
		return($resultado);
	}
	public static function actionAgregarEditarDomicilio($domicilio, $to_session=true){
		if(!is_a($domicilio,'Frontend_Model_Domicilio')){
			$array = $domicilio->getData();
			$domicilio = new Frontend_Model_Domicilio();
			$domicilio->loadFromArray($array);
		}
		$errors = array();
		if($to_session){
			self::setDomicilioMascotaEdicionInSession($domicilio);
		}
		if($domicilio->getMidomicilio()=='no'){
			if(((float)$domicilio->getLng())==0 || ((float)$domicilio->getLat())==0 ){
				$errors[] = 'Debe seleccionar un punto en el mapa';
			}
			if(!$domicilio->validateFields()||$errors){
				Core_App::getInstance()->addErrorMessage("No se pudo actualizar el domicilio");
				Core_Helper::LoadValidationTranslation();
				foreach($errors as $message){
					Core_App::getInstance()->addErrorMessage(self::getInstance()->__t($message));
				}
				if($domicilio->getValidationMessages())
				foreach($domicilio->getValidationMessages() as $key=>$messages){
					foreach($messages as $message){
						Core_App::getInstance()->addErrorMessage($message);
					}
				}
				return false;
			}
		}
		if($to_session){
			Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Domicilio mascota guardada en sesión'), true);
			return true;
		}
		if($domicilio->getMidomicilio()=='no'){
			$usuario = self::getLogedUser();
			if($domicilio->getId()==$usuario->getIdDomicilio())
				$domicilio->setId(null);
			if(!$domicilio->hasId()){/** aca hay que agregar a la base de datos*/
				$resultado = $domicilio->insert()?true:false;
				//echo Core_Helper::DebugVars(get_class($domicilio),$domicilio->getData());
				if($resultado){
					
				}
				else{
					Core_App::getInstance()->addErrorMessage("El domicilio de su mascota no pudo ser registrado");
					foreach($domicilio->getTranslatedErrors() as $error){
						Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
					}
				}
			}
			else{/** aca hay que actualizar el registro*/
				//$actualizada = true;// actualizarEnLaBase()
				$resultado = $domicilio->update(null)?true:false;
				//echo Core_Helper::DebugVars(Inta_Db::getInstance()->getLastQuery());
				if($resultado){
				}
				else{
					Core_App::getInstance()->addErrorMessage("El domicilio de su mascota no pudo ser actualizado");
					foreach($domicilio->getTranslatedErrors() as $error){
						Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
					}
				}
	 		}
	 	}
	 	else{
			if($domicilio->getId()){//si existe y debe ser el mismo domicilio del usuario
				$usuario = self::getLogedUser();
				$domicilio_usuario = $usuario->getDomicilio();
				if($domicilio->getId()!=$domicilio_usuario->getId()){//checkeamos que no sea el de usuario
					$resultado = $domicilio->delete();//y eliminamos el domicilio actual
					$resultado = true;//puede fallar esto, si borro el domicilio de una mascota o una perdida entonces puede ser que ese domicilio este ligadoa otra entidad, que no nos interesa en este punto
//					if($resultado){
//						Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Domicilio de la mascota eliminado correctamente'), true);
//					}
//					else{
//						Core_App::getInstance()->addErrorMessage("No se pudo eliminar el domicilio de la mascota");
//						foreach($domicilio->getTranslatedErrors() as $error){
//							Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
//						}
//					}
				}
				else{
					$resultado = true;
				}
			}
			else $resultado = true;
		}
		return($resultado);
	}
	public static function getColorsAsCollection(){
		return Saludmascotas_Model_Color::getColorsAsCollection();
//		$return = array();
//		$color = new Saludmascotas_Model_Color();
//		$colores = $color->search();
//		$col = new Core_Collection();
//		foreach($colores as $color)
//			$col->addItem($color, strtolower($color->getColorRgb()));
//		return $col;
	}
}
?>