<?php //es útf8
class Frontend_Mascota_Reencuentro_Helper extends Frontend_Mascota_Reencuentro_HelperUno{
	public static function getUrlConfirmacionesPendientes($id_mascota){
		return 'mascotas/reencuentro/confirmaciones_pendientes/'.$id_mascota;
	}
	public static function getUrlConfirmar($id_mascota, $id_reencuentro){
		return 'mascotas/reencuentro/confirmar/' . $id_mascota . '/' . $id_reencuentro;
	}
	public static function getMascota($id_mascota){
		$mascota = new Frontend_Model_Mascota();
		$mascota->setId($id_mascota);
		if(!$mascota->load()){
			Core_App::getInstance()->addErrorMessage("La mascota no existe", true);
			return null;
		}
		$usuario = self::getLogedUser();
		if($mascota->getIdDueno()!=$usuario->getId()){
			Core_App::getInstance()->addErrorMessage("La mascota que intentas editar no es tuya", true);
			return null;
		}
		if(!$mascota->esEstadoEncuentro() && !$mascota->esEstadoPerdida()){
			Core_App::getInstance()->addErrorMessage("No hay reencuentros por resolver, la mascota ya se encuentra en posesión de su dueño", true);
			return null;
		}
		return $mascota;
	}
	public static function getReencuentro($id_reencuentro){
		$reencuentro = new Saludmascotas_Model_Reencuentro();
		$reencuentro->setId($id_reencuentro);
		if(!$reencuentro->load()){
			Core_App::getInstance()->addErrorMessage("No existe el reencuentro", true);
			return null;
		}
		return $reencuentro;
	}
	public static function getPerdida($mascota, $reencuentro){
		$perdida = $mascota->getPerdidaActual();
		if($reencuentro->getIdPerdida()!=$perdida->getId()){
			Core_App::getInstance()->addErrorMessage("Lo sentimos ha ocurrido un error el reencuentro no coincide con tu mascota", true);
			return null;
		}
		return $perdida;
	}
	public static function getEncuentro($mascota, $reencuentro){
		$encuentro = $mascota->getEncuentroActual();
		if($reencuentro->getIdEncuentro()!=$encuentro->getId()){
			Core_App::getInstance()->addErrorMessage("Lo sentimos ha ocurrido un error el reencuentro no coincide con tu mascota", true);
			return null;
		}
		return $encuentro;
	}
//	movida a HelperUno
//	private static function confirmarReencuentro($reencuentro){
//		//$reencuentro->setConfirmado(true);
//		$resultado = $reencuentro->update(array('confirmado'=>'si'))?true:false;
//		if($resultado){
//			Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('El reencuentro ha sido confirmado'), true);
//			return true;
//		}
//		else{
//			Core_App::getInstance()->addErrorMessage("No se pudo confirmar el reencuentro");
//			foreach($encuentro->getTranslatedErrors() as $error){
//				Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
//			}
//		}
//		return false;
//	}
	private static function darBajaPerdida($perdida){
		$resultado = $perdida->update(array('activo'=>'no'))?true:false;
		if($resultado){
			Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('El anuncio de perdida ha sido dado de baja'), true);
			self::eliminarReencuentrosPerdidaNoConfirmados($perdida);
			return true;
		}
		else{
			Core_App::getInstance()->addErrorMessage("No se pudo dar de baja el anuncio de perdida");
			foreach($encuentro->getTranslatedErrors() as $error){
				Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
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
//			Core_App::getInstance()->addErrorMessage("No se pudo cambiar el estado de la mascota");
//			foreach($encuentro->getTranslatedErrors() as $error){
//				Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
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
	public static function enviarNotificacionReencuentroPerdidaConfirmado($reencuentro, $id_mascota=null){
		$mensaje = 'alguien ha confirmado una mascota de tu encuentro';
		$asunto = 'alguien ha confirmado una mascota de tu encuentro';
		$asunto_type = 'notificacion_reencuentro_perdida_confirmado';
		if($reencuentro->getIdEncuentro()){
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
	private function eliminarReencuentrosPerdidaNoConfirmados($perdida){
		$reencuentro = c(new Saludmascotas_Model_Reencuentro())
			->setWhere(
				Db_Helper::equal('id_perdida', $perdida->getId()), 
				Db_Helper::equal('confirmado','no')
			)
		;
		$reencuentros = $reencuentro->search('iniciado_por', 'asc', null, null, get_class($reencuentro));
		foreach($reencuentros as $reencuentro){
			$reencuentro->update(array('activo'=>'no'));
			if($reencuentro->getIniciadoPor()=='encuentro'){
				self::enviarNotificacionReencuentroPerdidaEliminado($reencuentro);
			}
		}
	}
	public static function enviarNotificacionReencuentroPerdidaEliminado($reencuentro){
		$mensaje = 'Una mascota coincidente de tu encuentro no era correcta, ha sido resuelta';
		$asunto = 'Una mascota coincidente de tu encuentro no era correcta, ha sido resuelta';
		$asunto_type = 'notificacion_reencuentro_perdida_eliminado';
		//var_dump($reencuentro->getData());
		if($reencuentro->getIdEncuentro()){
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