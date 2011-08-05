<?php //es útf8
class Frontend_Mascota_Reencuentro_HelperPerdida extends Frontend_Mascota_Reencuentro_HelperEncuentro{
//	movida a HelperUno
//	private static function confirmarReencuentro($reencuentro){
//		//$reencuentro->setConfirmado(true);
//		$resultado = $reencuentro->update(array('confirmado'=>'si'))?true:false;
//		if($resultado){
//			Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('El reencuentro ha sido confirmado'), true);
//			return true;
//		}
//		else{
//			Core_App::getInstance()->addErrorMessage("No se pudo confirmar el reencuentro", true);
//			foreach($encuentro->getTranslatedErrors() as $error){
//				Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription(), true);
//			}
//		}
//		return false;
//	}
	private static function darBajaPerdida($perdida){
		$resultado = $perdida->update(array('activo'=>'no'))?true:false;
		if($resultado){
			Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('El anuncio de perdida ha sido dado de baja'), true);
			self::eliminarReencuentrosPerdida($perdida);
			return true;
		}
		else{
			Core_App::getInstance()->addErrorMessage("No se pudo dar de baja el anuncio de perdida", true);
			foreach($encuentro->getTranslatedErrors() as $error){
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
//			foreach($encuentro->getTranslatedErrors() as $error){
//				Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription(), true);
//			}
//		}
//		return false;
//	}
	public static function confirmarReencuentroPerdida($baja_anuncio, $reencuentro, $perdida, $mascota){
		$resultado = self::confirmarReencuentro($reencuentro);
		if(!$resultado)
			return false;
		self::enviarNotificacionReencuentroPerdidaConfirmado($reencuentro, $mascota->getId());
		if(!$baja_anuncio)
			return true;
		if(!self::darBajaPerdida($perdida))
			return false;
		if(!self::actualizarMascotaConDueno($mascota))
			return false;
		return true;
	}
	public static function finalizarPerdida($baja_mascota, $perdida, $mascota){
//		$resultado = self::confirmarReencuentro($reencuentro);
//		if(!$resultado)
//			return false;
//		self::enviarNotificacionReencuentroPerdidaConfirmado($reencuentro, $mascota->getId());
//		if(!$baja_anuncio)
//			return true;
		$usuario = self::getLogedUser();
		$id_domicilio = $usuario->getIdDomicilio();
		if(!isset($id_domicilio)){
			$id_domicilio = $perdida->getIdDomicilio();
		}
		if(isset($id_domicilio)){
			$mascota->setIdDomicilio($id_domicilio)->update();
		}
		if(!self::darBajaPerdida($perdida))
			return false;
		if($baja_mascota)
			$params = array('activa'=>'no');
		else $params = null;
		if(!self::actualizarMascotaConDueno($mascota, $params))
			return false;
		return true;
	}
	public static function enviarNotificacionReencuentroPerdidaConfirmado($reencuentro, $id_mascota=null){
		$asunto = 'Confirmación coincidencia en Encuentro';
		$asunto_type = 'notificacion_reencuentro_perdida_confirmado';
		if($reencuentro->getIdEncuentro()){
//			$usuario = self::getLogedUser();
//			$id_usuario = $usuario->getId();			
			$encuentro = $reencuentro->getEncuentro();
			$id_mascota_encuentro = $encuentro->getIdMascota();
			$url_vista_confirmaciones_pendientes = Frontend_Mascota_Reencuentro_Helper::getUrlConfirmacionesPendientes($id_mascota_encuentro);
			$url_finalizar = Frontend_Mascota_Reencuentro_Helper::getUrlFinalizarAnuncioEncuentro($id_mascota_encuentro);
			$url_vista_confirmaciones_pendientes = Core_App::getUrlModel()->getUrl($url_vista_confirmaciones_pendientes);
			$url_finalizar = Core_App::getUrlModel()->getUrl($url_finalizar);
			$usuario = $encuentro->getUsuario();
			$id_usuario = $usuario->getId();
			$id_encuentro = $reencuentro->getIdEncuentro();
			$id_perdida = $reencuentro->getIdPerdida();
			$id_reencuentro = $reencuentro->getId();

			$mensaje = <<<asunto
Alguien ha confirmado tu perdida como coincidencia de su mascota encontrada.<br />
puedes ver esta y todas las demas confirmaciones de tu publicación haciendo <a href="$url_vista_confirmaciones_pendientes">click aquí</a> o copiar y pegar esta direccion en tu navegador<br />
$url_vista_confirmaciones_pendientes
<br />
o puedes finalizar el anuncio haciendo <a href="$url_finalizar">click aquí</a> o copiando y pegando la siguiente url en el navegador<br />
$url_finalizar
<br />
asunto;

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
			$email_to = $reencuentro->getEmail();
			$nombre_to = $reencuentro->getNombre();
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
	private function eliminarReencuentrosPerdida($perdida){
		$wheres = array();
		$reencuentro = c(new Saludmascotas_Model_Reencuentro())
			->setWhere(
				Db_Helper::equal('id_perdida', $perdida->getId())
				//,Db_Helper::equal('confirmado','no')
			)
		;
		$reencuentros = $reencuentro->search('iniciado_por', 'asc', null, null, get_class($reencuentro));
		foreach($reencuentros as $reencuentro){
			if($reencuentro->esConfirmado()){//si esta confirmado
				$encuentro = $reencuentro->getEncuentro();//corroboro el encuentro
				if(isset($encuentro)){
					if($encuentro->esActivo()){//este activo
						continue;//y no lo borro para permitir seguirlo viendo a dicho usuario
					}
				}
			}
			$reencuentro->update(array('activo'=>'no'));
			if($reencuentro->getIniciadoPor()=='encuentro'){
				if($reencuentro->esConfirmado()){
					
				}
				else{
					self::enviarNotificacionReencuentroPerdidaEliminado($reencuentro);
				}
			}
		}
	}
	public static function enviarNotificacionReencuentroPerdidaEliminado($reencuentro){
		$asunto = 'Una mascota coincidente de tu encuentro no era correcta, ha sido resuelta';
		$asunto_type = 'notificacion_reencuentro_perdida_eliminado';
		//var_dump($reencuentro->getData());
		if($reencuentro->getIdEncuentro()){
			$mensaje = 'La mascota que seleccionaste en tu anuncio de encuentro no era coincidente con la perdida, esta ha sido resuelta';
//			$usuario = self::getLogedUser();
//			$id_usuario = $usuario->getId();			
			$encuentro = $reencuentro->getEncuentro();
			$usuario = $encuentro->getUsuario();
			$id_usuario = $usuario->getId();
			$id_encuentro = $reencuentro->getIdEncuentro();
			$id_perdida = $reencuentro->getIdPerdida();
			$id_reencuentro = $reencuentro->getId();

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
			$mensaje = 'El usuario a quien ayudaste con la información ya encontró a su mascota';
			$email_to = $reencuentro->getEmail();
			$nombre_to = $reencuentro->getNombre();
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