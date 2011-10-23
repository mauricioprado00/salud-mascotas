<?php //es útf8
class Frontend_Mascota_Adopcion_Oferta_Helper extends Frontend_Mascota_Helper{
	public function getInstance(){
		return self::getInstanceOf(__CLASS__);
	}
	public static function getUrlAgregarAdopcionOfertaAutomaticamente($id_mascota){
		return 'mascotas/adopcion/oferta/automatica/'.$id_mascota;
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
	public static function getUpdatableAdopcionOfertaFields(){
		return array(
//			'extravio_fecha'
//			,'extravio_hora'
//			,'activo'
//			,'destacado'
//			'hora_extravio'
			'descripcion'
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
	public static function getAdopcionOfertaEdicion($mascota){
		static $cache = array();
		if(!isset($cache[$mascota->getId()])){
			$adopcion_ofertas = $mascota->getListAdopcionOferta();
			if(!$adopcion_ofertas)
				return null;
			$adopcion_ofertas = new Core_Collection($adopcion_ofertas);
			$adopcion_ofertas = $adopcion_ofertas->addFilterEq('activo', 'si');
			if(!$adopcion_ofertas->count())
				return null;
			$adopcion_oferta = $adopcion_ofertas->getFirst();
			$adopcion_oferta->loadNonTableColumn();
			$cache[$mascota->getId()] = $adopcion_oferta;
	//		var_dump($adopcion_oferta);
	//		die(__FILE__.__LINE__);
		}
		else $adopcion_oferta = $cache[$mascota->getId()];
		return $adopcion_oferta;
	}
	public static function getDomicilioEdicion($mascota){
		$user = self::getLogedUser();
		$adopcion_oferta = self::getAdopcionOfertaEdicion($mascota);
		if($adopcion_oferta){
			$domicilio = $adopcion_oferta->getDomicilio();
			if($domicilio){
				$domicilio->loadNonTableColumn();
				if($domicilio->getId()==$user->getIdDomicilio()){
					$domicilio->setMidomicilio('si');
				}
				return $domicilio;
			}
		}
		$domicilio = $user->getDomicilio();
		$domicilio->setMidomicilio('si');
		return $domicilio;
	}
	public static function getUrlAgregar($preserve_mascota_edicion=0, $paso=1){
		if($paso==1&&$preserve_mascota_edicion==false)
			return 'mascotas/adopcion/oferta/agregar';
		return 'mascotas/adopcion/oferta/agregar/'.$paso.'/'.$preserve_mascota_edicion;
	}
	public static function getUrlEditar($id_mascota, $preserve_mascota_edicion=0, $paso=1){
		if(!isset($id_mascota)||$id_mascota=='new')
			return self::getUrlAgregar($preserve_mascota_edicion, $paso);
		return 'mascotas/adopcion/oferta/editar/'.$paso.'/'.$id_mascota.'/'.$preserve_mascota_edicion;
	}
	private static function setAdopcionOfertaEdicionInSession($adopcion_oferta){
		self::setUserSessionVar('adopcion_oferta_mascota_edicion', $adopcion_oferta);
	}
	public static function getAdopcionOfertaEdicionFromSession($id_mascota=null){
		if(self::getUserSessionVar('id_mascota_edicion') != $id_mascota)
			return null;
		return self::getUserSessionVar('adopcion_oferta_mascota_edicion');
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
		self::setUserSessionVar('adopcion_oferta_mascota_edicion', null);
		self::setUserSessionVar('coincidencias_seleccionadas', null);
	}
	public static function enviarNotificacionAdopcionConciliacion($adopcion_conciliacion, $id_mascota=null){
		//$usuario = self::getLogedUser();
		$adopcion_solicitud = $adopcion_conciliacion->getAdopcionSolicitud();
		$id_mascota_adopcion_solicitud = $adopcion_solicitud->getIdMascota();
		$url_vista_confirmaciones_pendientes = Frontend_Mascota_Adopcion_Conciliacion_Helper::getUrlConfirmacionesPendientes($id_mascota_adopcion_solicitud);
		$url_confirmar = Frontend_Mascota_Adopcion_Conciliacion_Helper::getUrlConfirmar($id_mascota_adopcion_solicitud, $adopcion_conciliacion->getId());
		$url_vista_confirmaciones_pendientes = Core_App::getUrlModel()->getUrl($url_vista_confirmaciones_pendientes);
		$url_confirmar = Core_App::getUrlModel()->getUrl($url_confirmar);
		$usuario = $adopcion_solicitud->getUsuario();
		$id_usuario = $usuario->getId();
		$id_adopcion_oferta = $adopcion_conciliacion->getIdAdopcionOferta();
		$id_adopcion_solicitud = $adopcion_conciliacion->getIdAdopcionSolicitud();
		$id_adopcion_conciliacion = $adopcion_conciliacion->getId();
		if(!isset($id_mascota)){
			if($id_adopcion_oferta){
				$adopcion_oferta = new Saludmascotas_Model_AdopcionOferta();
				$adopcion_oferta->setId($id_adopcion_oferta);
				if($adopcion_oferta->load()){
					$id_mascota = $adopcion_oferta->getIdMascota();
				}
			}
		}
		if(!isset($id_mascota)){
			if($id_adopcion_solicitud){
				$adopcion_solicitud = new Saludmascotas_Model_AdopcionSolicitud();
				$adopcion_solicitud->setId($id_adopcion_solicitud);
				if($adopcion_solicitud->load()){
					$id_mascota = $adopcion_solicitud->getIdMascota();
				}
			}
		}
		
		$asunto = 'Coincidencia en AdopcionSolicitud';
		$mensaje = <<<asunto
Alguien ha visto tu aviso de solicitud de adopción y  quiere regalarte una mascota.<br />
Puedes ver y su anuncio en caso de que recibas su mascota <a href="$url_confirmar">click aquí</a> o copiando y pegando el siguiente enlace en tu navegador:<br />
$url_confirmar
<br />
o ver todas las demas confirmaciones pendientes para tu publicación haciendo <a href="$url_vista_confirmaciones_pendientes">click aqui</a> o copiando y pegando el siguiente enlace en tu navegador:
$url_vista_confirmaciones_pendientes
<br />
asunto;
		$asunto_type = 'notificacion_adopcion_conciliacion_adopcion_oferta';
		
		$notificacion = new Saludmascotas_Model_Notificacion();
		$notificacion
			->setIdUsuarioTo($id_usuario)
			->setIdAdopcionOferta($id_adopcion_oferta)
			->setIdAdopcionSolicitud($id_adopcion_solicitud)
			->setIdAdopcionConciliacion($id_adopcion_conciliacion)
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
	public static function crearAdopcionConciliacions($adopcion_oferta, $coincidencias_seleccionadas){
		$return = true;
		$coincidencias_seleccionadas_previas = $adopcion_oferta->getIdsCoincidenciasSeleccionadas();
		$coincidencias_agregadas = array();
		$id_adopcion_oferta = $adopcion_oferta->getId();
		if($coincidencias_seleccionadas){
			$usuario = self::getLogedUser();
			$id_usuario = $usuario->getId();
			foreach($coincidencias_seleccionadas as $id_adopcion_solicitud){
				if($coincidencias_seleccionadas_previas && in_array($id_adopcion_solicitud, $coincidencias_seleccionadas_previas))
					continue;
				$adopcion_conciliacion = new Saludmascotas_Model_AdopcionConciliacion();
				$adopcion_conciliacion
					->setIdAdopcionOferta($id_adopcion_oferta)
					->setIdAdopcionSolicitud($id_adopcion_solicitud)
					->setIdUsuario($id_usuario)
					->setConfirmado(false)
					->setHoraAdopcionConciliacion(time())
					->setIniciadoPor('adopcion_oferta')
				;
				$resultado = $adopcion_conciliacion->insert();
				if($resultado){
					Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('AdopcionConciliacion Iniciado Correctamente'), true);
					self::enviarNotificacionAdopcionConciliacion($adopcion_conciliacion, $adopcion_oferta->getIdMascota());
				}
				else{
					$return = false;
					Core_App::getInstance()->addErrorMessage("No se pudo registrar el adopcion_conciliacion");
					foreach($adopcion_oferta->getTranslatedErrors() as $error){
						Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
					}
				}

			}
		}
		if($coincidencias_seleccionadas_previas){
			$coincidencias_a_eliminar = array_diff($coincidencias_seleccionadas_previas, $coincidencias_seleccionadas);
			if($coincidencias_a_eliminar && count($coincidencias_a_eliminar)){
				foreach($coincidencias_a_eliminar as $id_adopcion_solicitud){
					$adopcion_conciliacion = new Saludmascotas_Model_AdopcionConciliacion();
					$adopcion_conciliacion
						->setIdAdopcionOferta($id_adopcion_oferta)
						->setIdAdopcionSolicitud($id_adopcion_solicitud)
					;
					if($adopcion_conciliacion->delete()){
						Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('AdopcionConciliacion Eliminado Correctamente'), true);
					}
					else{
						Core_App::getInstance()->addErrorMessage(self::getInstance()->__t('No se pudo eliminar el adopcion_conciliacion'), true);
						foreach($adopcion_conciliacion->getTranslatedErrors() as $error){
							Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription(), true);
						}
					}
				}
			}
		}
		return $return;
	}
	public static function actionAgregarAdopcionOfertaAutomatico($id_mascota){
		$adopcion_oferta = new Frontend_Model_AdopcionOferta();
		$adopcion_oferta
			->setActivo('si')
			->setDestacado('no')
			->setNotificacionEmail('si')
			->setRepublicarAutomaticamente('si')
			->setQuiereDestacar('no')
			->setMostrarTelefono('si')
		;
		return self::actionAgregarEditarAdopcionOferta($adopcion_oferta, false, $id_mascota, null);
	}
	public static function actionAgregarEditarAdopcionOferta($adopcion_oferta, $to_session=true, $id_mascota=null, $domicilio_mascota=null){
		if(!is_a($adopcion_oferta,'Frontend_Model_AdopcionOferta')){
			$array = $adopcion_oferta->getData();
			$adopcion_oferta = new Frontend_Model_AdopcionOferta();
			$adopcion_oferta->loadFromArray($array);
		}
		$errors = array();
		if($to_session){
			self::setAdopcionOfertaEdicionInSession($adopcion_oferta);
			Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('AdopcionOferta guardada en sesión'), true);
		}
		if(!$adopcion_oferta->validateFields() || $errors){
			Core_App::getInstance()->addErrorMessage(self::getInstance()->__t("No se pudo registrar la adopcion_oferta"));
			Core_Helper::LoadValidationTranslation();
			if($adopcion_oferta->getValidationMessages())
			foreach($adopcion_oferta->getValidationMessages() as $key=>$messages){
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
//		die(__FILE__.__LINE__);
		if(!isset($domicilio_mascota) || $domicilio_mascota->getMidomicilio()=='si'){
			if(!($id_domicilio = $usuario->getIdDomicilio())){
				Core_App::getInstance()->addErrorMessage(self::getInstance()->__t("No se pudo registrar la mascota, su domicilio es incorrecto"), true);
				return false;
			}
			$adopcion_oferta->setIdDomicilio($id_domicilio);
		}
		else{
			//if(!$mascota->getIdDomicilio())
			$adopcion_oferta->setIdDomicilio($domicilio_mascota->getId());
		}
		if(!$adopcion_oferta->hasId()){/** aca hay que agregar a la base de datos*/
			$adopcion_oferta->setIdUsuario($usuario->getId());
			$adopcion_oferta->setIdMascota($id_mascota);
			$resultado = $adopcion_oferta->insertFromUserInput()?true:false;
			if($resultado){
				Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('AdopcionOferta registrada correctamente'), true);
			}
			else{
				Core_App::getInstance()->addErrorMessage("No se pudo registrar la adopcion_oferta");
				foreach($adopcion_oferta->getTranslatedErrors() as $error){
					Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
				}
			}
		}
		else{/** aca hay que actualizar el registro*/
			$resultado = $adopcion_oferta->updateFromUserInput(null)?true:false;
//			header('content-type:text/plain');
//			var_dump($adopcion_oferta);
//			die(__FILE__.__LINE__);
//			
			if($resultado){
				Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Sus datos han sido actualizados'));
			}
			else{
				Core_App::getInstance()->addErrorMessage("No se pudo actualizar sus datos");
				foreach($adopcion_oferta->getTranslatedErrors() as $error){
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