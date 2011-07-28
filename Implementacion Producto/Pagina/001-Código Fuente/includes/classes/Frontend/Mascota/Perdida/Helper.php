<?php //es útf8
class Frontend_Mascota_Perdida_Helper extends Frontend_Mascota_Helper{
//	public static function getUrl(){
//		return 'mascotas';
//	}
//	public static function getUrlUsuario($numero_pag=null){
//		return 'mascotas/usuario'.($numero_pag?'/'.$numero_pag:'');
//	}
//	public static function getUrlUploadPhoto($ajax=false, $jsonp_callback='', $id_mascota=null){
//		return 'mascotas/fotos/'.($ajax?'ajax_':'').'upload'.($jsonp_callback?'/'.$jsonp_callback:'').($id_mascota?'/'.$id_mascota:'');
//	}
//	public static function getUrlPhoto(){
//		return 'mascotas/fotos';
//	}
	public static function getUpdatablePerdidaFields(){
		return array(
			'extravio_fecha'
			,'extravio_hora'
//			,'activo'
//			,'destacado'
			,'hora_extravio'
			,'descripcion'
			,'notificacion_email'
			,'republicar_automaticamente'
			,'quiere_destacar'
			,'mostrar_telefono'
			
//			,'fecha_publicacion'
//			,'fecha_expiracion'
//			,'id_domicilio'
//			,'id_mascota'
//			,'id_usuario'
		);
	}
	public static function getPerdidaEdicion($mascota){
		static $cache = array();
		if(!isset($cache[$mascota->getId()])){
			$perdidas = $mascota->getListPerdida();
			if(!$perdidas)
				return null;
			$perdidas = new Core_Collection($perdidas);
			$perdidas = $perdidas->addFilterEq('activo', 'si');
			if(!$perdidas->count())
				return null;
			$perdida = $perdidas->getFirst();
			$perdida->loadNonTableColumn();
			$cache[$mascota->getId()] = $perdida;
	//		var_dump($perdida);
	//		die(__FILE__.__LINE__);
		}
		else $perdida = $cache[$mascota->getId()];
		return $perdida;
	}
	public static function getDomicilioEdicion($mascota){
		$perdida = self::getPerdidaEdicion($mascota);
		if(!$perdida)
			return null;
		$domicilio = $perdida->getDomicilio();
		if(!$domicilio)
			return null;
		$domicilio->loadNonTableColumn();
		$user = self::getLogedUser();
		if($domicilio->getId()==$user->getIdDomicilio()){
			$domicilio->setMidomicilio('si');
		}
		return $domicilio;
	}
	public static function getUrlAgregar($preserve_mascota_edicion=0, $paso=1){
		if($paso==1&&$preserve_mascota_edicion==false)
			return 'mascotas/perdida/agregar';
		return 'mascotas/perdida/agregar/'.$paso.'/'.$preserve_mascota_edicion;
	}
	public static function getUrlEditar($id_mascota, $preserve_mascota_edicion=0, $paso=1){
		if(!isset($id_mascota)||$id_mascota=='new')
			return self::getUrlAgregar($preserve_mascota_edicion, $paso);
		return 'mascotas/perdida/editar/'.$paso.'/'.$id_mascota.'/'.$preserve_mascota_edicion;
	}
	private static function setPerdidaEdicionInSession($perdida){
		self::setUserSessionVar('perdida_mascota_edicion', $perdida);
	}
	public static function getPerdidaEdicionFromSession($id_mascota=null){
		if(self::getUserSessionVar('id_mascota_edicion') != $id_mascota)
			return null;
		return self::getUserSessionVar('perdida_mascota_edicion');
	}
	public static function clearSessionVars(){
		parent::clearSessionVars();
		self::setUserSessionVar('perdida_mascota_edicion', null);
	}
	public static function actionAgregarEditarPerdida($perdida, $to_session=true, $id_mascota=null, $domicilio_mascota=null){
		if(!is_a($perdida,'Frontend_Model_Perdida')){
			$array = $perdida->getData();
			$perdida = new Frontend_Model_Perdida();
			$perdida->loadFromArray($array);
		}
		$errors = array();
		if($to_session){
			self::setPerdidaEdicionInSession($perdida);
			Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Perdida guardada en sesión'), true);
		}
		if(!$perdida->validateFields() || $errors){
			Core_App::getInstance()->addErrorMessage(self::getInstance()->__t("No se pudo registrar la perdida"));
			Core_Helper::LoadValidationTranslation();
			if($perdida->getValidationMessages())
			foreach($perdida->getValidationMessages() as $key=>$messages){
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
			return true;
		}
		if($domicilio_mascota->getMidomicilio()=='si' || !isset($domicilio_mascota)){
			if(!($id_domicilio = $usuario->getIdDomicilio())){
				Core_App::getInstance()->addErrorMessage(self::getInstance()->__t("No se pudo registrar la mascota, su domicilio es incorrecto"), true);
				return false;
			}
			$perdida->setIdDomicilio($id_domicilio);
		}
		else{
			//if(!$mascota->getIdDomicilio())
			$perdida->setIdDomicilio($domicilio_mascota->getId());
		}
		if(!$perdida->hasId()){/** aca hay que agregar a la base de datos*/
			$perdida->setIdUsuario($usuario->getId());
			$perdida->setIdMascota($id_mascota);
			$resultado = $perdida->insert()?true:false;
			if($resultado){
				Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Perdida registrada correctamente'), true);
			}
			else{
				Core_App::getInstance()->addErrorMessage("No se pudo registrar la perdida");
				foreach($perdida->getTranslatedErrors() as $error){
					Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
				}
			}
		}
		else{/** aca hay que actualizar el registro*/
			$resultado = $perdida->update(null)?true:false;
//			header('content-type:text/plain');
//			var_dump($perdida);
//			die(__FILE__.__LINE__);
//			
			if($resultado){
				Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Sus datos han sido actualizados'));
			}
			else{
				Core_App::getInstance()->addErrorMessage("No se pudo actualizar sus datos");
				foreach($perdida->getTranslatedErrors() as $error){
					Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
				}
			}
 		}
		return($resultado);
	}
//	public static function actionAgregarEditarDomicilio($domicilio, $to_session=true){
//		if(!is_a($domicilio,'Frontend_Model_Domicilio')){
//			$array = $domicilio->getData();
//			$domicilio = new Frontend_Model_Domicilio();
//			$domicilio->loadFromArray($array);
//		}
//		$errors = array();
//		if($to_session){
//			self::setDomicilioMascotaEdicionFromSession($domicilio);
//		}
//		if($domicilio->getMidomicilio()=='no'){
//			if(((float)$domicilio->getLng())==0 || ((float)$domicilio->getLat())==0 ){
//				$errors[] = 'Debe seleccionar un punto en el mapa';
//			}
//			if(!$domicilio->validateFields()||$errors){
//				Core_App::getInstance()->addErrorMessage("No se pudo actualizar el domicilio");
//				Core_Helper::LoadValidationTranslation();
//				foreach($errors as $message){
//					Core_App::getInstance()->addErrorMessage(self::getInstance()->__t($message));
//				}
//				if($domicilio->getValidationMessages())
//				foreach($domicilio->getValidationMessages() as $key=>$messages){
//					foreach($messages as $message){
//						Core_App::getInstance()->addErrorMessage($message);
//					}
//				}
//				return false;
//			}
//		}
//		if($to_session){
//			Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Domicilio mascota guardada en sesión'), true);
//			return true;
//		}
//		if($domicilio->getMidomicilio()=='no'){
//			$usuario = self::getLogedUser();
//			if($domicilio->getId()==$usuario->getIdDomicilio())
//				$domicilio->setId(null);
//			if(!$domicilio->hasId()){/** aca hay que agregar a la base de datos*/
//				$resultado = $domicilio->insert()?true:false;
//				//echo Core_Helper::DebugVars(get_class($domicilio),$domicilio->getData());
//				if($resultado){
//					
//				}
//				else{
//					Core_App::getInstance()->addErrorMessage("El domicilio de su mascota no pudo ser registrado");
//					foreach($domicilio->getTranslatedErrors() as $error){
//						Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
//					}
//				}
//			}
//			else{/** aca hay que actualizar el registro*/
//				//$actualizada = true;// actualizarEnLaBase()
//				$resultado = $domicilio->update(null)?true:false;
//				//echo Core_Helper::DebugVars(Inta_Db::getInstance()->getLastQuery());
//				if($resultado){
//				}
//				else{
//					Core_App::getInstance()->addErrorMessage("El domicilio de su mascota no pudo ser actualizado");
//					foreach($domicilio->getTranslatedErrors() as $error){
//						Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
//					}
//				}
//	 		}
//	 	}
//	 	else{
//			if($domicilio->getId()){
//				$usuario = self::getLogedUser();
//				$domicilio_usuario = $usuario->getDomicilio();
//				if($domicilio->getId()!=$domicilio_usuario->getId()){
//					$resultado = $domicilio->delete();
//					if($resultado){
//						Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Domicilio de la mascota eliminado correctamente'), true);
//					}
//					else{
//						Core_App::getInstance()->addErrorMessage("No se pudo eliminar el domicilio de la mascota");
//						foreach($domicilio->getTranslatedErrors() as $error){
//							Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
//						}
//					}
//				}
//				else{
//					$resultado = true;
//				}
//			}
//		}
//		return($resultado);
//	}
//	public static function getColorsAsCollection(){
//		return Saludmascotas_Model_Color::getColorsAsCollection();
////		$return = array();
////		$color = new Saludmascotas_Model_Color();
////		$colores = $color->search();
////		$col = new Core_Collection();
////		foreach($colores as $color)
////			$col->addItem($color, strtolower($color->getColorRgb()));
////		return $col;
//	}
}
?>