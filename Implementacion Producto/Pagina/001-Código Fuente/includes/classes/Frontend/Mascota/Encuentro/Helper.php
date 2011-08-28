<?php //es útf8
class Frontend_Mascota_Encuentro_Helper extends Frontend_Mascota_Helper{
	public function getInstance(){
		return self::getInstanceOf(__CLASS__);
	}
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
	public static function getUpdatableEncuentroFields(){
		return array(
			'encuentro_fecha'
			,'encuentro_hora'
//			,'activo'
			,'hora_encuentro'
			,'descripcion'
			,'notificacion_email'
			,'republicar_automaticamente'
			,'mostrar_telefono'
			,'tiene_mascota'
			,'estado_mascota'
			
//			,'fecha_publicacion'
//			,'fecha_expiracion'

//			,'id_domicilio'
//			,'id_mascota'
//			,'id_usuario'
		);
	}
	public static function getEncuentroEdicion($mascota){
		static $cache = array();
		if(!isset($cache[$mascota->getId()])){
			$encuentros = $mascota->getListEncuentro();
			if(!$encuentros)
				return null;
			$encuentros = new Core_Collection($encuentros);
			$encuentros = $encuentros->addFilterEq('activo', 'si');
			if(!$encuentros->count())
				return null;
			$encuentro = $encuentros->getFirst();
			$encuentro->loadNonTableColumn();
			$cache[$mascota->getId()] = $encuentro;
	//		var_dump($encuentro);
	//		die(__FILE__.__LINE__);
		}
		else $encuentro = $cache[$mascota->getId()];
		return $encuentro;
	}
	public static function getDomicilioEdicion($mascota){
		$encuentro = self::getEncuentroEdicion($mascota);
		if(!$encuentro)
			return null;
		$domicilio = $encuentro->getDomicilio();
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
			return 'mascotas/encuentro/agregar';
		return 'mascotas/encuentro/agregar/'.$paso.'/'.$preserve_mascota_edicion;
	}
	public static function getUrlEditar($id_mascota, $preserve_mascota_edicion=0, $paso=1){
		if(!isset($id_mascota)||$id_mascota=='new')
			return self::getUrlAgregar($preserve_mascota_edicion, $paso);
		return 'mascotas/encuentro/editar/'.$paso.'/'.$id_mascota.'/'.$preserve_mascota_edicion;
	}
	private static function setEncuentroEdicionInSession($encuentro){
		self::setUserSessionVar('encuentro_mascota_edicion', $encuentro);
	}
	public static function getEncuentroEdicionFromSession($id_mascota=null){
		if(self::getUserSessionVar('id_mascota_edicion') != $id_mascota)
			return null;
		return self::getUserSessionVar('encuentro_mascota_edicion');
	}
	public static function setCoincidenciasSeleccionadasInSession(&$coincidencias_seleccionadas){
		if($coincidencias_seleccionadas){
			$nuevas = array();
			foreach($coincidencias_seleccionadas as $coincidencia_seleccionada)
				if($coincidencia_seleccionada)
					$nuevas[] = $coincidencia_seleccionada;
			$coincidencias_seleccionadas = $nuevas;
		}
		if(!isset($coincidencias_seleccionadas))
			$coincidencias_seleccionadas = array();
		return self::setUserSessionVar('coincidencias_seleccionadas', $coincidencias_seleccionadas);
	}
	public static function getCoincidenciasSeleccionadasFromSession($id_mascota=null){
		if(self::getUserSessionVar('id_mascota_edicion') != $id_mascota)
			return null;
		return self::getUserSessionVar('coincidencias_seleccionadas');
	}
	public static function clearSessionVars(){
		parent::clearSessionVars();
		self::setUserSessionVar('encuentro_mascota_edicion', null);
		self::setUserSessionVar('coincidencias_seleccionadas', null);
	}
	public static function enviarNotificacionReencuentro($reencuentro, $id_mascota=null){
		//$usuario = self::getLogedUser();
		$perdida = $reencuentro->getPerdida();
		$id_mascota_perdida = $perdida->getIdMascota();
		$url_vista_confirmaciones_pendientes = Frontend_Mascota_Reencuentro_Helper::getUrlConfirmacionesPendientes($id_mascota_perdida);
		$url_confirmar = Frontend_Mascota_Reencuentro_Helper::getUrlConfirmar($id_mascota_perdida, $reencuentro->getId());
		$url_vista_confirmaciones_pendientes = Core_App::getUrlModel()->getUrl($url_vista_confirmaciones_pendientes);
		$url_confirmar = Core_App::getUrlModel()->getUrl($url_confirmar);
		$usuario = $perdida->getUsuario();
		$id_usuario = $usuario->getId();
		$id_encuentro = $reencuentro->getIdEncuentro();
		$id_perdida = $reencuentro->getIdPerdida();
		$id_reencuentro = $reencuentro->getId();
		if(!isset($id_mascota)){
			if($id_encuentro){
				$encuentro = new Saludmascotas_Model_Encuentro();
				$encuentro->setId($id_encuentro);
				if($encuentro->load()){
					$id_mascota = $encuentro->getIdMascota();
				}
			}
		}
		if(!isset($id_mascota)){
			if($id_perdida){
				$perdida = new Saludmascotas_Model_Perdida();
				$perdida->setId($id_perdida);
				if($perdida->load()){
					$id_mascota = $perdida->getIdMascota();
				}
			}
		}
		
		$asunto = 'Coincidencia en Perdida';
		$mensaje = <<<asunto
Alguien ha marcado tu perdida como posible coincidencia de su mascota encontrada.<br />
Puedes ver y confirmar dicha coincidencia <a href="$url_confirmar">click aquí</a> o copiando y pegando el siguiente enlace en tu navegador:<br />
$url_confirmar
<br />
o ver todas las demas confirmaciones pendientes para tu publicación haciendo <a href="$url_vista_confirmaciones_pendientes">click aqui</a> o copiando y pegando el siguiente enlace en tu navegador:
$url_vista_confirmaciones_pendientes
<br />
asunto;
		$asunto_type = 'notificacion_reencuentro_encuentro';
		
		$notificacion = new Saludmascotas_Model_Notificacion();
		$notificacion
			->setIdUsuarioTo($id_usuario)
			->setIdEncuentro($id_encuentro)
			->setIdPerdida($id_perdida)
			->setIdReencuentro($id_reencuentro)
			->setIdMascota($id_mascota)
			->setHora(time())
			->setMensaje($mensaje)
			->setAsunto($asunto)
			->setAsuntoType($asunto_type)
		;
//		header('content-type:text/plain');
//		var_dump($notificacion->getData());
//		die(__FILE__.__LINE__);
		if($notificacion->insert()){
			Core_App::getInstance()->addErrorMessage("Notificacion guardada", true);
			$notificacion->fromSelf();
			return $notificacion->enviar();
		}
		Core_App::getInstance()->addErrorMessage("No se pudo enviar notificación", true);
		foreach($notificacion->getTranslatedErrors() as $error){
			Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription(), true);
		}
		return false;
	}
	public static function crearReencuentros($encuentro, $coincidencias_seleccionadas){
		$return = true;
		$coincidencias_seleccionadas_previas = $encuentro->getIdsCoincidenciasSeleccionadas();
		$coincidencias_agregadas = array();
		$id_encuentro = $encuentro->getId();
		if($coincidencias_seleccionadas){
			$usuario = self::getLogedUser();
			$id_usuario = $usuario->getId();
			foreach($coincidencias_seleccionadas as $id_perdida){
				if($coincidencias_seleccionadas_previas && in_array($id_perdida, $coincidencias_seleccionadas_previas))
					continue;
				$reencuentro = new Saludmascotas_Model_Reencuentro();
				$reencuentro
					->setIdEncuentro($id_encuentro)
					->setIdPerdida($id_perdida)
					->setIdUsuario($id_usuario)
					->setConfirmado(false)
					->setHoraReencuentro(time())
					->setIniciadoPor('encuentro')
				;
				$resultado = $reencuentro->insert();
				if($resultado){
					$coincidencias_agregadas[] = $id_perdida;
					Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Reencuentro Iniciado Correctamente'), true);
					self::enviarNotificacionReencuentro($reencuentro, $encuentro->getIdMascota());
				}
				else{
					$return = false;
					Core_App::getInstance()->addErrorMessage("No se pudo registrar el reencuentro");
					foreach($encuentro->getTranslatedErrors() as $error){
						Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
					}
				}

			}
		}
		if($coincidencias_seleccionadas_previas){
			$coincidencias_a_eliminar = array_diff($coincidencias_seleccionadas_previas, $coincidencias_seleccionadas);
			if($coincidencias_a_eliminar && count($coincidencias_a_eliminar)){
				foreach($coincidencias_a_eliminar as $id_perdida){
					$reencuentro = new Saludmascotas_Model_Reencuentro();
					$reencuentro
						->setIdEncuentro($id_encuentro)
						->setIdPerdida($id_perdida)
					;
					if($reencuentro->delete()){
						Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Reencuentro Eliminado Correctamente'), true);
					}
					else{
						Core_App::getInstance()->addErrorMessage(self::getInstance()->__t('No se pudo eliminar el reencuentro'), true);
						foreach($reencuentro->getTranslatedErrors() as $error){
							Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription(), true);
						}
					}
				}
			}
		}
		return $return;
	}

	public static function actionAgregarEditarEncuentro($encuentro, $to_session=true, $id_mascota=null, $domicilio_mascota=null){
		if(!is_a($encuentro,'Frontend_Model_Encuentro')){
			$array = $encuentro->getData();
			$encuentro = new Frontend_Model_Encuentro();
			$encuentro->loadFromArray($array);
		}
		$errors = array();
		if($to_session){
			self::setEncuentroEdicionInSession($encuentro);
			Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Encuentro guardada en sesión'), true);
		}
		if(!$encuentro->validateFields() || $errors){
			Core_App::getInstance()->addErrorMessage(self::getInstance()->__t("No se pudo registrar la encuentro"));
			Core_Helper::LoadValidationTranslation();
			if($encuentro->getValidationMessages())
			foreach($encuentro->getValidationMessages() as $key=>$messages){
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
			$encuentro->setIdDomicilio($id_domicilio);
		}
		else{
			//if(!$mascota->getIdDomicilio())
			$encuentro->setIdDomicilio($domicilio_mascota->getId());
		}
		if(!$encuentro->hasId()){/** aca hay que agregar a la base de datos*/
			$encuentro->setIdUsuario($usuario->getId());
			$encuentro->setIdMascota($id_mascota);
			$resultado = $encuentro->insertFromUserInput()?true:false;
			if($resultado){
				Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Encuentro registrada correctamente'), true);
			}
			else{
				Core_App::getInstance()->addErrorMessage("No se pudo registrar la encuentro");
				foreach($encuentro->getTranslatedErrors() as $error){
					Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
				}
			}
		}
		else{/** aca hay que actualizar el registro*/
			$resultado = $encuentro->updateFromUserInput(null)?true:false;
//			header('content-type:text/plain');
//			var_dump($encuentro);
//			die(__FILE__.__LINE__);
//			
			if($resultado){
				Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Sus datos han sido actualizados'));
			}
			else{
				Core_App::getInstance()->addErrorMessage("No se pudo actualizar sus datos");
				foreach($encuentro->getTranslatedErrors() as $error){
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
