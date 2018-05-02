<?php //es útf8
class Frontend_Mascota_Adopcion_Conciliacion_HelperAdopcionSolicitud extends Frontend_Mascota_Helper{
	public function getInstance(){
		return self::getInstanceOf(__CLASS__);
	}
	protected static function confirmarAdopcionConciliacion($adopcion_conciliacion){
		//$adopcion_conciliacion->setConfirmado(true);
		$resultado = $adopcion_conciliacion->update(array('confirmado'=>'si'))?true:false;
		if($resultado){
			Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('El adopcion_conciliacion ha sido confirmado'), true);
			return true;
		}
		else{
			Core_App::getInstance()->addErrorMessage("No se pudo confirmar el adopcion_conciliacion", true);
			foreach($adopcion_oferta->getTranslatedErrors() as $error){
				Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription(), true);
			}
		}
		return false;
	}
	private static function darBajaAdopcionSolicitud($adopcion_solicitud){
		$resultado = $adopcion_solicitud->update(array('activo'=>'no'))?true:false;
		if($resultado){
			Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('El anuncio de adopcion_solicitud ha sido dado de baja'), true);
			self::eliminarAdopcionConciliacionsAdopcionSolicitud($adopcion_solicitud);
			return true;
		}
		else{
			Core_App::getInstance()->addErrorMessage("No se pudo dar de baja el anuncio de adopcion_solicitud", true);
			foreach($adopcion_oferta->getTranslatedErrors() as $error){
				Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription(), true);
			}
		}
		return false;
	}
	protected static function actualizarMascotaSinAdopcion($mascota, $update=null){
		if(!isset($update))
			$update = array();
		$mascota->setEstadoAdopcionNo();
		$mascota->setParaAdoptar(false);
		$estado_adopcion = $mascota->getEstadoAdopcion();
		$para_adoptar = $mascota->getParaAdoptar();
		$update['estado_adopcion'] = $estado_adopcion;
		$update['para_adoptar'] = $para_adoptar;
		$resultado = $mascota->update($update)?true:false;
		if($resultado){
			if(!isset($update['activa']) || $update['activa']=='si')
				Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Se ha cambiado el estado de la mascota'), true);
			else 
				Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Se ha eliminado la mascota'), true);
			return true;
		}
		else{
			Core_App::getInstance()->addErrorMessage("No se pudo cambiar el estado de la mascota", true);
			foreach($adopcion_oferta->getTranslatedErrors() as $error){
				Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription(), true);
			}
		}
		return false;
	}
	public static function confirmarAdopcionConciliacionAdopcionSolicitud($baja_anuncio, $adopcion_conciliacion, $adopcion_solicitud, $mascota){
		$resultado = self::confirmarAdopcionConciliacion($adopcion_conciliacion);
		if(!$resultado)
			return false;
		self::enviarNotificacionAdopcionConciliacionAdopcionSolicitudConfirmado($adopcion_conciliacion, $mascota->getId());
		if(!$baja_anuncio)
			return true;
		if(!self::darBajaAdopcionSolicitud($adopcion_solicitud))
			return false;
		if(!self::actualizarMascotaSinAdopcion($mascota, array('activa'=>'no')))
			return false;
		return true;
	}
	public static function finalizarAdopcionSolicitud($adopcion_solicitud, $mascota){
//		$resultado = self::confirmarAdopcionConciliacion($adopcion_conciliacion);
//		if(!$resultado)
//			return false;
//		self::enviarNotificacionAdopcionConciliacionAdopcionSolicitudConfirmado($adopcion_conciliacion, $mascota->getId());
//		if(!$baja_anuncio)
//			return true;
		$id_domicilio = $adopcion_solicitud->getIdDomicilio();
		if(!isset($id_domicilio)){
			$usuario = self::getLogedUser();
			$id_domicilio = $usuario->getIdDomicilio();
		}
		if(isset($id_domicilio)){
			$mascota->setIdDomicilio($id_domicilio)->update();
		}
		if(!self::darBajaAdopcionSolicitud($adopcion_solicitud))
			return false;
		if(!self::actualizarMascotaSinAdopcion($mascota, array('activa'=>'no')))
			return false;
		return true;
	}
	public static function enviarNotificacionAdopcionConciliacionAdopcionSolicitudConfirmado($adopcion_conciliacion, $id_mascota=null){
		$asunto_type = 'notificacion_adopcion_conciliacion_adopcion_solicitud_confirmado';
		$asunto = 'Entrega mascota en adopción';
		if($adopcion_conciliacion->getIdAdopcionOferta()){
//			$usuario = self::getLogedUser();
//			$id_usuario = $usuario->getId();			
			$adopcion_oferta = $adopcion_conciliacion->getAdopcionOferta();
			$id_mascota_adopcion_oferta = $adopcion_oferta->getIdMascota();
			$url_vista_confirmaciones_pendientes = Frontend_Mascota_Adopcion_Conciliacion_Helper::getUrlConfirmacionesPendientes($id_mascota_adopcion_oferta);
			$url_finalizar = Frontend_Mascota_Adopcion_Conciliacion_Helper::getUrlFinalizarAnuncioAdopcionOferta($id_mascota_adopcion_oferta);
			$url_vista_confirmaciones_pendientes = Core_App::getUrlModel()->getUrl($url_vista_confirmaciones_pendientes);
			$url_finalizar = Core_App::getUrlModel()->getUrl($url_finalizar);
			$usuario = $adopcion_oferta->getUsuario();
			$id_usuario = $usuario->getId();
			$id_adopcion_oferta = $adopcion_conciliacion->getIdAdopcionOferta();
			$id_adopcion_solicitud = $adopcion_conciliacion->getIdAdopcionSolicitud();
			$id_adopcion_conciliacion = $adopcion_conciliacion->getId();
			
			$mensaje = <<<asunto
Alguien ha confirmado recibir la macota que regalabas.<br />
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
				->setIdAdopcionOferta($id_adopcion_oferta)
				->setIdAdopcionSolicitud($id_adopcion_solicitud)
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
			$asunto = 'El usuario que reporto la mascota encontrada ha confirmado que es tu mascota';
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
	private function eliminarAdopcionConciliacionsAdopcionSolicitud($adopcion_solicitud){
		$adopcion_conciliacion = c(new Saludmascotas_Model_AdopcionConciliacion())
			->setWhere(
				Db_Helper::equal('id_adopcion_solicitud', $adopcion_solicitud->getId())
				//,Db_Helper::equal('confirmado','no')
			)
		;
		$adopcion_conciliacions = $adopcion_conciliacion->search('iniciado_por', 'asc', null, null, get_class($adopcion_conciliacion));
		foreach($adopcion_conciliacions as $adopcion_conciliacion){
			if($adopcion_conciliacion->esConfirmado()){//si esta confirmado
				$adopcion_oferta = $adopcion_conciliacion->getAdopcionOferta();//corroboro la adopcion_oferta
				if(isset($adopcion_oferta)){
					if($adopcion_solicitud->esActivo()){//este activo
						continue;//y no lo borro para permitir seguirlo viendo a dicho usuario
					}
				}
			}
			$adopcion_conciliacion->update(array('activo'=>'no'));
			if($adopcion_conciliacion->getIniciadoPor()=='adopcion_oferta'){
				if($adopcion_conciliacion->esConfirmado()){
					
				}
				else{
					self::enviarNotificacionAdopcionConciliacionAdopcionSolicitudEliminado($adopcion_conciliacion);
				}
			}
		}
	}
	public static function enviarNotificacionAdopcionConciliacionAdopcionSolicitudEliminado($adopcion_conciliacion){
		$asunto = 'Entrega de adopción rechazada';
		$asunto_type = 'notificacion_adopcion_conciliacion_adopcion_solicitud_eliminado';
		//var_dump($adopcion_conciliacion->getData());
		if($adopcion_conciliacion->getIdAdopcionOferta()){
			$mensaje = 'El anunciante de la solicitud de adopción ha rechazado tu oferta';
//			$usuario = self::getLogedUser();
//			$id_usuario = $usuario->getId();			
			$adopcion_oferta = $adopcion_conciliacion->getAdopcionOferta();
			$usuario = $adopcion_oferta->getUsuario();
			$id_usuario = $usuario->getId();
			$id_adopcion_oferta = $adopcion_conciliacion->getIdAdopcionOferta();
			$id_adopcion_solicitud = $adopcion_conciliacion->getIdAdopcionSolicitud();
			$id_adopcion_conciliacion = $adopcion_conciliacion->getId();

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
			$mensaje = 'El reclamo que hiciste sobre la mascota encontrada no era correcto, esta mascota fué devuelta a su dueño';
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