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
		if($domicilio_mascota->getMidomicilio()=='si' || !isset($domicilio_mascota)){
			if(!($id_domicilio = $usuario->getIdDomicilio())){
				Core_App::getInstance()->addErrorMessage(self::getInstance()->__t("No se pudo registrar la mascota, su domicilio es incorrecto"), true);
			}
			$mascota->setIdDomicilio($id_domicilio);
		}
		else{
			//if(!$mascota->getIdDomicilio())
				$mascota->setIdDomicilio($domicilio_mascota->getId());
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
			self::setDomicilioMascotaEdicionFromSession($domicilio);
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
			if($domicilio->getId()){
				$usuario = self::getLogedUser();
				$domicilio_usuario = $usuario->getDomicilio();
				if($domicilio->getId()!=$domicilio_usuario->getId()){
					$resultado = $domicilio->delete();
					if($resultado){
						Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Domicilio de la mascota eliminado correctamente'), true);
					}
					else{
						Core_App::getInstance()->addErrorMessage("No se pudo eliminar el domicilio de la mascota");
						foreach($domicilio->getTranslatedErrors() as $error){
							Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
						}
					}
				}
				else{
					$resultado = true;
				}
			}
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