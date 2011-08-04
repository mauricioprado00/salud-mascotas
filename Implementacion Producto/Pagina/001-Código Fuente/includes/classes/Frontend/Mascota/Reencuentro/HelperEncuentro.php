<?php //es útf8
class Frontend_Mascota_Reencuentro_HelperEncuentro extends Frontend_Mascota_Helper{
	protected static function confirmarReencuentro($reencuentro){
		//$reencuentro->setConfirmado(true);
		$resultado = $reencuentro->update(array('confirmado'=>'si'))?true:false;
		if($resultado){
			Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('El reencuentro ha sido confirmado'), true);
			return true;
		}
		else{
			Core_App::getInstance()->addErrorMessage("No se pudo confirmar el reencuentro", true);
			foreach($perdida->getTranslatedErrors() as $error){
				Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription(), true);
			}
		}
		return false;
	}
	private static function darBajaEncuentro($encuentro){
		$resultado = $encuentro->update(array('activo'=>'no'))?true:false;
		if($resultado){
			Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('El anuncio de encuentro ha sido dado de baja'), true);
			self::eliminarReencuentrosEncuentro($encuentro);
			return true;
		}
		else{
			Core_App::getInstance()->addErrorMessage("No se pudo dar de baja el anuncio de encuentro", true);
			foreach($perdida->getTranslatedErrors() as $error){
				Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription(), true);
			}
		}
		return false;
	}
	protected static function actualizarMascotaConDueno($mascota, $update=null){
		if(!isset($update))
			$update = array();
		$mascota->setEstadoConDueno();
		$id_estadomascota = $mascota->getIdEstadomascota();
		$update['id_estadomascota'] = $id_estadomascota;
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
			foreach($perdida->getTranslatedErrors() as $error){
				Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription(), true);
			}
		}
		return false;
	}
	public static function confirmarReencuentroEncuentro($baja_anuncio, $reencuentro, $encuentro, $mascota){
		$resultado = self::confirmarReencuentro($reencuentro);
		if(!$resultado)
			return false;
		self::enviarNotificacionReencuentroEncuentroConfirmado($reencuentro, $mascota->getId());
		if(!$baja_anuncio)
			return true;
		if(!self::darBajaEncuentro($encuentro))
			return false;
		if(!self::actualizarMascotaConDueno($mascota, array('activa'=>'no')))
			return false;
		return true;
	}
	public static function finalizarEncuentro($encuentro, $mascota){
//		$resultado = self::confirmarReencuentro($reencuentro);
//		if(!$resultado)
//			return false;
//		self::enviarNotificacionReencuentroEncuentroConfirmado($reencuentro, $mascota->getId());
//		if(!$baja_anuncio)
//			return true;
		if(!self::darBajaEncuentro($encuentro))
			return false;
		if(!self::actualizarMascotaConDueno($mascota, array('activa'=>'no')))
			return false;
		return true;
	}
	public static function enviarNotificacionReencuentroEncuentroConfirmado($reencuentro, $id_mascota=null){
		$asunto = 'Confirmación coincidencia en Perdida';
		$asunto_type = 'notificacion_reencuentro_encuentro_confirmado';
		$asunto = 'Alguien ha confirmado una mascota de tu perdida';
		if($reencuentro->getIdPerdida()){
//			$usuario = self::getLogedUser();
//			$id_usuario = $usuario->getId();			
			$perdida = $reencuentro->getPerdida();
			$id_mascota_perdida = $perdida->getIdMascota();
			$url_vista_confirmaciones_pendientes = Frontend_Mascota_Reencuentro_Helper::getUrlConfirmacionesPendientes($id_mascota_perdida);
			$url_finalizar = Frontend_Mascota_Reencuentro_Helper::getUrlFinalizarAnuncioPerdida($id_mascota_perdida);
			$url_vista_confirmaciones_pendientes = Core_App::getUrlModel()->getUrl($url_vista_confirmaciones_pendientes);
			$url_finalizar = Core_App::getUrlModel()->getUrl($url_finalizar);
			$usuario = $perdida->getUsuario();
			$id_usuario = $usuario->getId();
			$id_perdida = $reencuentro->getIdPerdida();
			$id_encuentro = $reencuentro->getIdEncuentro();
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
				->setIdPerdida($id_perdida)
				->setIdEncuentro($id_encuentro)
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
			$enviado = $notificaion->enviar($email_to, $nombre_to);
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
	private function eliminarReencuentrosEncuentro($encuentro){
		$reencuentro = c(new Saludmascotas_Model_Reencuentro())
			->setWhere(
				Db_Helper::equal('id_encuentro', $encuentro->getId())
				//,Db_Helper::equal('confirmado','no')
			)
		;
		$reencuentros = $reencuentro->search('iniciado_por', 'asc', null, null, get_class($reencuentro));
		foreach($reencuentros as $reencuentro){
			if($reencuentro->esConfirmado()){//si esta confirmado
				$perdida = $reencuentro->getPerdida();//corroboro la perdida
				if(isset($perdida)){
					if($encuentro->esActivo()){//este activo
						continue;//y no lo borro para permitir seguirlo viendo a dicho usuario
					}
				}
			}
			$reencuentro->update(array('activo'=>'no'));
			if($reencuentro->getIniciadoPor()=='perdida'){
				if($reencuentro->esConfirmado()){
					
				}
				else{
					self::enviarNotificacionReencuentroEncuentroEliminado($reencuentro);
				}
			}
		}
	}
	public static function enviarNotificacionReencuentroEncuentroEliminado($reencuentro){
		$mensaje = 'Una mascota coincidente de tu perdida no era correcta, ha sido resuelta';
		$asunto = 'Una mascota coincidente de tu perdida no era correcta, ha sido resuelta';
		$asunto_type = 'notificacion_reencuentro_encuentro_eliminado';
		//var_dump($reencuentro->getData());
		if($reencuentro->getIdPerdida()){
//			$usuario = self::getLogedUser();
//			$id_usuario = $usuario->getId();			
			$perdida = $reencuentro->getPerdida();
			$usuario = $perdida->getUsuario();
			$id_usuario = $usuario->getId();
			$id_perdida = $reencuentro->getIdPerdida();
			$id_encuentro = $reencuentro->getIdEncuentro();
			$id_reencuentro = $reencuentro->getId();

			$notificacion = new Saludmascotas_Model_Notificacion();
			$notificacion
				->setIdUsuarioTo($id_usuario)
				->setIdPerdida($id_perdida)
				->setIdEncuentro($id_encuentro)
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
			$enviado = $notificaion->enviar($email_to, $nombre_to);
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