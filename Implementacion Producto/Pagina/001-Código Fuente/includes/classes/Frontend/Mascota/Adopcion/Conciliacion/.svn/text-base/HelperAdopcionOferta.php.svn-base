<?php //es útf8
class Frontend_Mascota_Adopcion_Conciliacion_HelperAdopcionOferta extends Frontend_Mascota_Adopcion_Conciliacion_HelperAdopcionSolicitud{
	public function getInstance(){
		return self::getInstanceOf(__CLASS__);
	}
//	movida a HelperUno
//	private static function confirmarAdopcionConciliacion($adopcion_conciliacion){
//		//$adopcion_conciliacion->setConfirmado(true);
//		$resultado = $adopcion_conciliacion->update(array('confirmado'=>'si'))?true:false;
//		if($resultado){
//			Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('El adopcion_conciliacion ha sido confirmado'), true);
//			return true;
//		}
//		else{
//			Core_App::getInstance()->addErrorMessage("No se pudo confirmar el adopcion_conciliacion", true);
//			foreach($adopcion_solicitud->getTranslatedErrors() as $error){
//				Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription(), true);
//			}
//		}
//		return false;
//	}
	private static function darBajaAdopcionOferta($adopcion_oferta){
		$resultado = $adopcion_oferta->update(array('activo'=>'no'))?true:false;
		if($resultado){
			Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('El anuncio de adopcion_oferta ha sido dado de baja'), true);
			self::eliminarAdopcionConciliacionsAdopcionOferta($adopcion_oferta);
			return true;
		}
		else{
			Core_App::getInstance()->addErrorMessage("No se pudo dar de baja el anuncio de adopcion_oferta", true);
			foreach($adopcion_solicitud->getTranslatedErrors() as $error){
				Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription(), true);
			}
		}
		return false;
	}
//	movida a HelperUno
//	private static function actualizarMascotaConDueno($mascota){
//		$mascota->setEstadoConDueno();
//		$id_estadomascota = $mascota->getIdEstadomascota();
//		$resultado = $mascota->update(array('id_estadomascota'=>$id_estadomascota))?true:false;
//		if($resultado){
//			Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Se ha cambiado el estado de la mascota'), true);
//			return true;
//		}
//		else{
//			Core_App::getInstance()->addErrorMessage("No se pudo cambiar el estado de la mascota", true);
//			foreach($adopcion_solicitud->getTranslatedErrors() as $error){
//				Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription(), true);
//			}
//		}
//		return false;
//	}
	public static function confirmarAdopcionConciliacionAdopcionOferta($baja_anuncio, $baja_mascota, $adopcion_conciliacion, $adopcion_oferta, $mascota){
		$resultado = self::confirmarAdopcionConciliacion($adopcion_conciliacion);
		if(!$resultado)
			return false;
		self::enviarNotificacionAdopcionConciliacionAdopcionOfertaConfirmado($adopcion_conciliacion, $mascota->getId());
		if(!$baja_anuncio)
			return true;
		if(!self::darBajaAdopcionOferta($adopcion_oferta))
			return false;
		if($baja_mascota)
			$params = array('activa'=>'no');
		else $params = null;
		if(!self::actualizarMascotaSinAdopcion($mascota, $params))
			return false;
		return true;
	}
	public static function finalizarAdopcionOferta($baja_mascota, $adopcion_oferta, $mascota){
//		$resultado = self::confirmarAdopcionConciliacion($adopcion_conciliacion);
//		if(!$resultado)
//			return false;
//		self::enviarNotificacionAdopcionConciliacionAdopcionOfertaConfirmado($adopcion_conciliacion, $mascota->getId());
//		if(!$baja_anuncio)
//			return true;
		$usuario = self::getLogedUser();
		$id_domicilio = $usuario->getIdDomicilio();
		if(!isset($id_domicilio)){
			$id_domicilio = $adopcion_oferta->getIdDomicilio();
		}
		if(isset($id_domicilio)){
			$mascota->setIdDomicilio($id_domicilio)->update();
		}
		if(!self::darBajaAdopcionOferta($adopcion_oferta))
			return false;
		if($baja_mascota)
			$params = array('activa'=>'no');
		else $params = null;
		if(!self::actualizarMascotaSinAdopcion($mascota, $params))
			return false;
		return true;
	}
	public static function enviarNotificacionAdopcionConciliacionAdopcionOfertaConfirmado($adopcion_conciliacion, $id_mascota=null){
		$asunto = 'Recepción de adopción';
		$asunto_type = 'notificacion_adopcion_conciliacion_adopcion_oferta_confirmado';
		if($adopcion_conciliacion->getIdAdopcionSolicitud()){
//			$usuario = self::getLogedUser();
//			$id_usuario = $usuario->getId();			
			$adopcion_solicitud = $adopcion_conciliacion->getAdopcionSolicitud();
			$id_mascota_adopcion_solicitud = $adopcion_solicitud->getIdMascota();
			$url_vista_confirmaciones_pendientes = Frontend_Mascota_Adopcion_Conciliacion_Helper::getUrlConfirmacionesPendientes($id_mascota_adopcion_solicitud);
			$url_finalizar = Frontend_Mascota_Adopcion_Conciliacion_Helper::getUrlFinalizarAnuncioAdopcionSolicitud($id_mascota_adopcion_solicitud);
			$url_vista_confirmaciones_pendientes = Core_App::getUrlModel()->getUrl($url_vista_confirmaciones_pendientes);
			$url_finalizar = Core_App::getUrlModel()->getUrl($url_finalizar);
			$usuario = $adopcion_solicitud->getUsuario();
			$id_usuario = $usuario->getId();
			$id_adopcion_solicitud = $adopcion_conciliacion->getIdAdopcionSolicitud();
			$id_adopcion_oferta = $adopcion_conciliacion->getIdAdopcionOferta();
			$id_adopcion_conciliacion = $adopcion_conciliacion->getId();

			$mensaje = <<<asunto
Alguien ha confirmado haber entregado una mascota en adopción.<br />
Puedes ver esta y todas las demas confirmaciones de tu publicación haciendo <a href="$url_vista_confirmaciones_pendientes">click aquí</a> o copiar y pegar esta direccion en tu navegador<br />
$url_vista_confirmaciones_pendientes
<br />
o puedes finalizar el anuncio haciendo <a href="$url_finalizar">click aquí</a> o copiando y pegando la siguiente url en el navegador<br />
$url_finalizar
<br />
asunto;

			$notificacion = new Saludmascotas_Model_Notificacion();
			$notificacion
				->setIdUsuarioTo($id_usuario)
				->setIdAdopcionSolicitud($id_adopcion_solicitud)
				->setIdAdopcionOferta($id_adopcion_oferta)
				->setIdAdopcionConciliacion($id_adopcion_conciliacion)
				->setIdMascota($id_mascota)
				->setHora(time())
				->setMensaje($mensaje)
				->setAsunto($asunto)
				->setAsuntoType($asunto_type)
			;
			if($notificacion->insert()){
				Core_App::getInstance()->addSuccessMessage("Notificacion enviada", true);
				$notificacion->fromSelf();
				$notificacion->enviar();
				return true;
			}
			Core_App::getInstance()->addErrorMessage("No se pudo enviar notificación", true);
			foreach($notificacion->getTranslatedErrors() as $error){
				Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription(), true);
			}	
		}
		else{
			$mensaje = 'Alguien ha confirmado la información que le proveiste';
			$email_to = $adopcion_conciliacion->getEmail();
			$nombre_to = $adopcion_conciliacion->getNombre();
			if(!$email_to)
				return false;
			$notificacion = new Saludmascotas_Model_Notificacion();
			$notificacion
				->fromSelf()
				->setMensaje($mensaje)
				->setAsunto($asunto)
			;
//			var_dump($mensaje, $notificacion->getData());
//			die(__FILE__.__LINE__);
			$enviado = $notificacion->enviar($email_to, $nombre_to);
			if($enviado){
				Core_App::getInstance()->addSuccessMessage("Notificacion enviada", true);
			}
			else{
				Core_App::getInstance()->addErrorMessage("No se pudo enviar notificación", true);
			}
			return $enviado;
		}
		return false;
	}
	private function eliminarAdopcionConciliacionsAdopcionOferta($adopcion_oferta){
		$wheres = array();
		$adopcion_conciliacion = c(new Saludmascotas_Model_AdopcionConciliacion())
			->setWhere(
				Db_Helper::equal('id_adopcion_oferta', $adopcion_oferta->getId())
				//,Db_Helper::equal('confirmado','no')
			)
		;
		$adopcion_conciliacions = $adopcion_conciliacion->search('iniciado_por', 'asc', null, null, get_class($adopcion_conciliacion));
		foreach($adopcion_conciliacions as $adopcion_conciliacion){
			if($adopcion_conciliacion->esConfirmado()){//si esta confirmado
				$adopcion_solicitud = $adopcion_conciliacion->getAdopcionSolicitud();//corroboro el adopcion_solicitud
				if(isset($adopcion_solicitud)){
					if($adopcion_solicitud->esActivo()){//este activo
						continue;//y no lo borro para permitir seguirlo viendo a dicho usuario
					}
				}
			}
			$adopcion_conciliacion->update(array('activo'=>'no'));
			if($adopcion_conciliacion->getIniciadoPor()=='adopcion_solicitud'){
				if($adopcion_conciliacion->esConfirmado()){
					
				}
				else{
					self::enviarNotificacionAdopcionConciliacionAdopcionOfertaEliminado($adopcion_conciliacion);
				}
			}
		}
	}
	public static function enviarNotificacionAdopcionConciliacionAdopcionOfertaEliminado($adopcion_conciliacion){
		$asunto = 'Una solicitud adopción rechazada';
		$asunto_type = 'notificacion_adopcion_conciliacion_adopcion_oferta_eliminado';
		//var_dump($adopcion_conciliacion->getData());
		if($adopcion_conciliacion->getIdAdopcionSolicitud()){
			$mensaje = 'La mascota que solicitaste para adopción fué entregada a otra persona';
//			$usuario = self::getLogedUser();
//			$id_usuario = $usuario->getId();			
			$adopcion_solicitud = $adopcion_conciliacion->getAdopcionSolicitud();
			$usuario = $adopcion_solicitud->getUsuario();
			$id_usuario = $usuario->getId();
			$id_adopcion_solicitud = $adopcion_conciliacion->getIdAdopcionSolicitud();
			$id_adopcion_oferta = $adopcion_conciliacion->getIdAdopcionOferta();
			$id_adopcion_conciliacion = $adopcion_conciliacion->getId();

			$notificacion = new Saludmascotas_Model_Notificacion();
			$notificacion
				->setIdUsuarioTo($id_usuario)
				->setIdAdopcionSolicitud($id_adopcion_solicitud)
				->setIdAdopcionOferta($id_adopcion_oferta)
				->setIdAdopcionConciliacion($id_adopcion_conciliacion)
				->setIdMascota($id_mascota)
				->setHora(time())
				->setMensaje($mensaje)
				->setAsunto($asunto)
				->setAsuntoType($asunto_type)
			;
			if($notificacion->insert()){
				//Core_App::getInstance()->addSuccessMessage("Notificacion enviada", true);
				$notificacion->fromSelf();
				$notificacion->enviar();
				return true;
			}
//			Core_App::getInstance()->addErrorMessage("No se pudo enviar notificación", true);
//			foreach($notificacion->getTranslatedErrors() as $error){
//				Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription(), true);
//			}	
		}
		else{
			$mensaje = 'El usuario a quien solicitaste una mascota en adopción ya no ofrece mas dicha mascota';
			$email_to = $adopcion_conciliacion->getEmail();
			$nombre_to = $adopcion_conciliacion->getNombre();
			if(!$email_to)
				return false;
			$notificacion = new Saludmascotas_Model_Notificacion();
			$notificacion
				->fromSelf()
				->setMensaje($mensaje)
				->setAsunto($asunto)
			;
			$enviado = $notificacion->enviar($email_to, $nombre_to);
			if($enviado){
				Core_App::getInstance()->addSuccessMessage("Notificacion enviada", true);
			}
			else{
				Core_App::getInstance()->addErrorMessage("No se pudo enviar notificación", true);
			}
			return $enviado;
		}
		return false;
	} 

//	public static function getUrl(){
//		return 'mascotas';
//	}
//	public static function getUrlUsuario($numero_pag=null){
//		return 'mascotas/usuario'.($numero_pag?'/'.$numero_pag:'');
//	}
//	public static function getUrlPhoto(){
//		return 'mascotas/fotos';
//	}

}
?>