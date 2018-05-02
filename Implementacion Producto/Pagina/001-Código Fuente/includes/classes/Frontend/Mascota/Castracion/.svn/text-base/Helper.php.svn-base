<?php //es útf8
class Frontend_Mascota_Castracion_Helper extends Frontend_Mascota_Helper{
	public function getInstance(){
		return self::getInstanceOf(__CLASS__);
	}
	public static function getUrlCastracionesPendientes(){
		return 'mascotas/castracion/pendientes';
	}
	public static function getUrlSetParaCastracion($id_mascota, $cancelar=false, $notificar_usuario=false){
		return 'mascotas/castracion/set_para_castracion/'.$id_mascota.($cancelar?'/1'.($notificar_usuario?'/1':''):'');
	}
	public static function getUrlAsignarCastracion(){
		return 'mascotas/castracion/asignar';
	}
	public static function getUrlFinalizarCastracion(){
		return 'mascotas/castracion/finalizar';
	}
	public static function getUrlConfirmarCastracion($id_mascota){
		return 'mascotas/castracion/confirmar/'.$id_mascota;
	}
	public static function actionConfirmarCastracion($id_mascota){
		if(!$id_mascota){
			Core_App::getInstance()->addErrorMessage(self::getInstance()->__t('Error en la acción, dirección inválida'), true);
			return false;
		}
		$mascota = new Frontend_Model_Mascota();
		$mascota->setId($id_mascota);
		if(!$mascota->load()){
			Core_App::getInstance()->addErrorMessage(self::getInstance()->__t('La mascota no existe'), true);
			return false;
		}
		$usuario = Frontend_Usuario_Model_User::getLogedUser();
		if($usuario->getId()!=$mascota->getIdDueno()){
			Core_App::getInstance()->addErrorMessage(self::getInstance()->__t('La mascota no le pertenece'), true);
			return false;
		}
		$castracion = $mascota->getCastracionActual(true);
		if(!$castracion){
			Core_App::getInstance()->addErrorMessage(self::getInstance()->__t('Error en la acción, no se pudo guardar'), true);
			return false;
		}
		$nombre = $mascota->getNombre();
		$mascota->setData(array())->setId($id_mascota);//para evitar updatear todos los campos
		$mascota->setEstadoCastracionRealizada();
		if(!$mascota->update()){
			Core_App::getInstance()->addErrorMessage(self::getInstance()->__t('Error en la acción, no se pudo guardar'), true);
			return false;
		}
		$id_castracion = $castracion->getId();
		$castracion->setData(array())->setId($id_castracion);//para evitar updatear todos los campos
		$castracion->setFechaConfirmacion(time());
		$castracion->setResultadoConfirmacionRealizada();
		if(!$castracion->update()){
			Core_App::getInstance()->addErrorMessage(self::getInstance()->__t('Error en la acción, no se pudo guardar'), true);
			return false;
		}
		Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Has confirmado la castración realizada para la mascota <em>'.$nombre.'</em>'), true);
		return true;
	}
	public static function finalizarCastracion($id_mascota, $finalizar_resultado){
		if(!$id_mascota){
			Core_App::getInstance()->addErrorMessage(self::getInstance()->__t('Error en la acción, dirección inválida'), true);
			return false;
		}
		$mascota = new Frontend_Model_Mascota();
		$mascota->setId($id_mascota);
		if(!$mascota->load()){
			Core_App::getInstance()->addErrorMessage(self::getInstance()->__t('La mascota no existe'), true);
			return false;
		}
//		$usuario = Frontend_Usuario_Model_User::getLogedUser();
//		if($usuario->getId()!=$mascota->getIdDueno()){
//			Core_App::getInstance()->addErrorMessage(self::getInstance()->__t('La mascota no le pertenece'), true);
//			return false;
//		}
		$castracion = $mascota->getCastracionActual(true);
		if(!$castracion){
			Core_App::getInstance()->addErrorMessage(self::getInstance()->__t('Error en la acción, no se pudo finalizar'), true);
			return false;
		}
		$nombre = $mascota->getNombre();
		$mascota->setData(array())->setId($id_mascota);//para evitar updatear todos los campos
		$mascota->setEstadoCastracionRealizada();
		if(!$mascota->update()){
			Core_App::getInstance()->addErrorMessage(self::getInstance()->__t('Error en la acción, no se pudo finalizar'), true);
			return false;
		}
		$id_castracion = $castracion->getId();
		$castracion->setData(array())->setId($id_castracion);//para evitar updatear todos los campos
		$castracion->setFechaConfirmacion(time());
		if($finalizar_resultado=='si')
			$castracion->setResultadoConfirmacionRealizada();
		else $castracion->setResultadoConfirmacionNoRealizada();
		if(!$castracion->update()){
			Core_App::getInstance()->addErrorMessage(self::getInstance()->__t('Error en la acción, no se pudo finalizar'), true);
			return false;
		}
		Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Has finalizado la castración para la mascota <em>'.$nombre.'</em>'), true);
		return true;
	}
	public static function setParaCastracion($id_mascota){
		if(!$id_mascota){
			Core_App::getInstance()->addErrorMessage(self::getInstance()->__t('Error en la acción, dirección inválida'), true);
			return false;
		}
		//Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Domicilio mascota guardada en sesión'), true);
		$mascota = new Frontend_Model_Mascota();
		$mascota->setId($id_mascota);
		if(!$mascota->load()){
			Core_App::getInstance()->addErrorMessage(self::getInstance()->__t('La mascota no existe'), true);
			return false;
		}
		$nombre = $mascota->getNombre();
		$mascota->setData(array())->setId($id_mascota);//para evitar updatear todos los campos
		$mascota->setEstadoCastracionSolicitada();
		if(!$mascota->update()){
			Core_App::getInstance()->addErrorMessage(self::getInstance()->__t('Error en la acción, no se pudo guardar'), true);
			return false;
		}
//		$q = Saludmascotas_Db::getInstance()->getLastQuery();
//		var_dump($q);die(__FILE__.__LINE__);
		$castracion = new Frontend_Model_Castracion();
		$castracion
			->setActivo('si')
			->setFechaSolicitud(time())
			->setIdMascota($id_mascota)
		;
		if(!$castracion->insert()){
			foreach($castracion->getTranslatedErrors() as $error){
				Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription(), true);
			}
			$mascota->setEstadoCastracionNo();
			$mascota->update();
			Core_App::getInstance()->addErrorMessage(self::getInstance()->__t('Error en la acción, no se pudo guardar'), true);
			return false;
		}
		$castracion->asignarSpa();
		self::enviarNotificacionAsignacionCatracionSpa($castracion);
		Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Has agregado a tu mascota <em>'.$nombre.'</em> para castrar, se te notificará en cuanto sea asignada'), true);
		return true;
	}
	public static function enviarNotificacionAsignacionCatracionSpa($castracion){
		$asunto = 'Asignacion Pendiente Castración';
		$url_asignaciones_pendientes = self::getUrlCastracionesPendientes();// Frontend_Mascota_Adopcion_Conciliacion_Helper::getUrlConfirmacionesPendientes($id_mascota_adopcion_solicitud);
		$url_asignaciones_pendientes = Core_App::getUrlModel()->getUrl($url_asignaciones_pendientes);
		
		$asunto_type = 'notificacion_castracion_pendiente_asignacion';
			$mensaje = <<<asunto
Un usuario ha solicitado una castración, se te ha nominado responsable por ser la protectora mas cercana.<br />
Debes asignar una fecha y entidad responsable de castración o rechazarla si no quieres responsabilizarte.<br />
Puedes ver esta y las demas castraciones pendientes haciendo <a href="$url_asignaciones_pendientes">click aquí</a> o copiar y pegar esta direccion en tu navegador<br />
$url_asignaciones_pendientes
<br />
asunto;
		$usuario = $castracion->getUsuario();
		$id_usuario = $usuario->getId();
		$notificacion = new Saludmascotas_Model_Notificacion();
		$notificacion
			->setIdUsuarioTo($id_usuario)
//			->setIdAdopcionSolicitud($id_adopcion_solicitud)
//			->setIdAdopcionOferta($id_adopcion_oferta)
//			->setIdAdopcionConciliacion($id_adopcion_conciliacion)
//			->setIdMascota($id_mascota)
			->setHora(time())
			->setMensaje($mensaje)
			->setAsunto($asunto)
			->setAsuntoType($asunto_type)
		;
		if($notificacion->insert()){
			Core_App::getInstance()->addSuccessMessage("Notificacion enviada a protectora de animales", true);
			$notificacion->fromSelf();
			$notificacion->enviar();
			return true;
		}
		Core_App::getInstance()->addErrorMessage("No se pudo enviar notificación", true);
		foreach($notificacion->getTranslatedErrors() as $error){
			Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription(), true);
		}	
	}
	public static function cancelarCastracion($id_mascota, $notificar_usuario=false){
		if(!$id_mascota){
			Core_App::getInstance()->addErrorMessage(self::getInstance()->__t('Error en la acción, dirección inválida'), true);
			return false;
		}
		//Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Domicilio mascota guardada en sesión'), true);
		$mascota = new Frontend_Model_Mascota();
		$mascota->setId($id_mascota);
		if(!$mascota->load()){
			Core_App::getInstance()->addErrorMessage(self::getInstance()->__t('La mascota no existe'), true);
			return false;
		}
		$nombre = $mascota->getNombre();
		$mascota->setData(array())->setId($id_mascota);//para evitar updatear todos los campos
		$mascota->setEstadoCastracionNo();
		if(!$mascota->update()){
			Core_App::getInstance()->addErrorMessage(self::getInstance()->__t('Error en la acción, no se pudo cancelar castracion'), true);
			return false;
		}
		$castracion = $mascota->getCastracionActual();
		if($castracion){
			$castracion->setActivo(false);
			if(!$castracion->update()){
				Core_App::getInstance()->addErrorMessage(self::getInstance()->__t('Error en la acción, no se pudo cancelar castracion'), true);
				return false;
			}
		}
		Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Has cancelado la castración de tu mascota <em>'.$nombre.'</em>'), true);
		if($notificar_usuario){
			//la cancela el spa
			//todo:enviar email
			self::enviarNotificacionCancelacionCatracionSpa($castracion, true);
		}
		else{//la cancela el usuario
			self::enviarNotificacionCancelacionCatracionSpa($castracion);
		}
		return true;
	}
	public static function enviarNotificacionCancelacionCatracionSpa($castracion, $to_dueno=false){
		$usuario = $castracion->getUsuario();
		$veterinario = $castracion->getVeterinario();
		if($to_dueno&&($mascota = $castracion->getMascota())&&($dueno=$mascota->getDueno())){
			$asunto = 'Cancelacion de Castración';
			$usuario = $dueno;
			$asunto_type = 'notificacion_castracion_cancelacion_dueno';
				$mensaje = <<<asunto
La castración ha sido cancelada.<br />
<br />
asunto;
			if($castracion->getFechaAsignacion()){
				$nombre = $veterinario->getNombre();
				$dia = date('d/m/Y', $castracion->getFechaAsignacion());
				$mensaje .= <<<asunto
La castración ya habia sido previamente asignada a $nombre para el día $dia.<br />
<br />
asunto;
			}
			else{
				$mensaje .= <<<asunto
La castración no había sido asignada todavía.<br />
<br />
asunto;
			}
			$id_usuario = $usuario->getId();
			$notificacion = new Saludmascotas_Model_Notificacion();
			$notificacion
				->setIdUsuarioTo($id_usuario)
	//			->setIdAdopcionSolicitud($id_adopcion_solicitud)
	//			->setIdAdopcionOferta($id_adopcion_oferta)
	//			->setIdAdopcionConciliacion($id_adopcion_conciliacion)
	//			->setIdMascota($id_mascota)
				->setHora(time())
				->setMensaje($mensaje)
				->setAsunto($asunto)
				->setAsuntoType($asunto_type)
			;
			if($notificacion->insert()){
				Core_App::getInstance()->addSuccessMessage("Notificacion enviada a dueño", true);
				$notificacion->fromSelf();
				$notificacion->enviar();
			}
			else{
				Core_App::getInstance()->addErrorMessage("No se pudo enviar notificación a dueño", true);
				foreach($notificacion->getTranslatedErrors() as $error){
					Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription(), true);
				}
			}
		}
		if($to_dueno&&$usuario){
			$asunto = 'Cancelacion de Castración';
			$asunto_type = 'notificacion_castracion_cancelacion_spa';
				$mensaje = <<<asunto
Un usuario ha cancelado su castracion.<br />
<br />
asunto;
			if($castracion->getFechaAsignacion()){
				$nombre = $veterinario->getNombre();
				$dia = date('d/m/Y', $castracion->getFechaAsignacion());
				$mensaje .= <<<asunto
La castración ya habia sido previamente asignada a $nombre para el día $dia.<br />
<br />
asunto;

			}
			else{
				$mensaje .= <<<asunto
La castración no había sido asignada todavía.<br />
<br />
asunto;
			}
			$id_usuario = $usuario->getId();
			$notificacion = new Saludmascotas_Model_Notificacion();
			$notificacion
				->setIdUsuarioTo($id_usuario)
	//			->setIdAdopcionSolicitud($id_adopcion_solicitud)
	//			->setIdAdopcionOferta($id_adopcion_oferta)
	//			->setIdAdopcionConciliacion($id_adopcion_conciliacion)
	//			->setIdMascota($id_mascota)
				->setHora(time())
				->setMensaje($mensaje)
				->setAsunto($asunto)
				->setAsuntoType($asunto_type)
			;
			if($notificacion->insert()){
				Core_App::getInstance()->addSuccessMessage("Notificacion enviada a protectora de animales", true);
				$notificacion->fromSelf();
				$notificacion->enviar();
			}
			else{
				Core_App::getInstance()->addErrorMessage("No se pudo enviar notificación a protectora de animales", true);
				foreach($notificacion->getTranslatedErrors() as $error){
					Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription(), true);
				}
			}
		}
		if($veterinario){
			$asunto = 'Cancelación de Castración';
			$url_asignaciones_pendientes = self::getUrlCastracionesPendientes();// Frontend_Mascota_Adopcion_Conciliacion_Helper::getUrlConfirmacionesPendientes($id_mascota_adopcion_solicitud);
			$url_asignaciones_pendientes = Core_App::getUrlModel()->getUrl($url_asignaciones_pendientes);
			
			$asunto_type = 'notificacion_castracion_cancelacion_veterinario';
			$nombre = $veterinario->getNombre();
			$dia = date('d/m/Y', $castracion->getFechaAsignacion());
				$mensaje = <<<asunto
Un usuario ha cancelado una castración que se te había asignado para el día $dia.<br />
<br />
asunto;
			$usuario = $veterinario;//$castracion->getUsuario();
			$id_usuario = $usuario->getId();
			$notificacion = new Saludmascotas_Model_Notificacion();
			$notificacion
				->setIdUsuarioTo($id_usuario)
	//			->setIdAdopcionSolicitud($id_adopcion_solicitud)
	//			->setIdAdopcionOferta($id_adopcion_oferta)
	//			->setIdAdopcionConciliacion($id_adopcion_conciliacion)
	//			->setIdMascota($id_mascota)
				->setHora(time())
				->setMensaje($mensaje)
				->setAsunto($asunto)
				->setAsuntoType($asunto_type)
			;
			if($notificacion->insert()){
				Core_App::getInstance()->addSuccessMessage("Notificacion enviada a veterinaria", true);
				$notificacion->fromSelf();
				$notificacion->enviar();
			}
			else{
				Core_App::getInstance()->addErrorMessage("No se pudo enviar notificación a veterinaria", true);
				foreach($notificacion->getTranslatedErrors() as $error){
					Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription(), true);
				}
			}
		}
	}
	public static function asignarCastracion($id_mascota, $fecha_asignacion, $veterinaria_nombre, $veterinaria_id, $descripcion){
		$fecha_asignacion = strtotime(Mysql_Helper::filterDateInput($fecha_asignacion));
//		var_dump($id_mascota, $fecha_asignacion, $veterinaria_nombre, $veterinaria_id);
//		die(__FILE__.__LINE__);
		if(!$id_mascota){
			Core_App::getInstance()->addErrorMessage(self::getInstance()->__t('Error en la acción, dirección inválida'), true);
			return false;
		}
		//Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Domicilio mascota guardada en sesión'), true);
		$mascota = new Frontend_Model_Mascota();
		$mascota->setId($id_mascota);
		if(!$mascota->load()){
			Core_App::getInstance()->addErrorMessage(self::getInstance()->__t('La mascota no existe'), true);
			return false;
		}
		$castracion = $mascota->getCastracionActual();
		$castracion
			->setFechaAsignacion($fecha_asignacion)
			->setVeterinario($veterinaria_nombre)
			->setDescripcion($descripcion)
		;

		if($veterinaria_id)
			$castracion->setIdVeterinario($veterinaria_id);
//		var_dump($veterinaria_id, $castracion->getVeterinario());
//		die(__FILE__.__LINE__);
		if(!$castracion->update()){
			Core_App::getInstance()->addErrorMessage(self::getInstance()->__t('Error en la acción, no se pudo asignar castración'), true);
			return false;
		}
		$mascota->setEstadoCastracionAsignada();
		if(!$mascota->update()){
			Core_App::getInstance()->addErrorMessage(self::getInstance()->__t('Error en la acción, no se pudo guardar'), true);
			return false;
		}
		$nombre = $mascota->getNombre();
		Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Se ha asignado fecha de castración para <em>'.$nombre.'</em>'), true);
		self::enviarNotificacionCastracionAsignada($castracion);
		return true;
	}
	public static function enviarNotificacionCastracionAsignada($castracion){
		$mascota = $castracion->getMascota();
		$usuario = $mascota->getDueno();
		$veterinario = $castracion->getVeterinario();
		$descripcion = $castracion->getDescripcion();
//		var_dump($veterinario );
//		die(__FILE__.__LINE__);
		$dia = date('d/m/Y', $castracion->getFechaAsignacion());
		if($usuario){
			$asunto = 'Castración Asignada';
			$asunto_type = 'notificacion_castracion_asignada_usuario';
				$mensaje = <<<asunto
La sociedad protectora de animales te ha asignado la fecha de castración en $dia.<br />
<br />
asunto;
			if($veterinario){
				$nombre = $veterinario->getNombre();
				$domicilio = $veterinario->getDomicilio();
				if($domicilio){
					$barrio = $domicilio->getBarrio();
					$localidad = $barrio->getLocalidad();
					$domicilio = $domicilio->getCalleNumero().', '.$barrio->getNombre().', '.$localidad->getNombre();
				$mensaje .= <<<asunto
El responable de castración es la veterinaria "$nombre" ubicada en $domicilio.<br />
<br />
asunto;
				}
				else{
				$mensaje .= <<<asunto
El responable de castración es la veterinaria "$nombre".<br />
<br />
asunto;
				}
			}
			else{
				$mensaje .= <<<asunto
La castración no había sido asignada todavía.<br />
<br />
asunto;
			}
			if($descripcion)
			$mensaje .= <<<asunto
Detalles:$descripcion.<br />
<br />
asunto;
			$id_usuario = $usuario->getId();
			$notificacion = new Saludmascotas_Model_Notificacion();
			$notificacion
				->setIdUsuarioTo($id_usuario)
	//			->setIdAdopcionSolicitud($id_adopcion_solicitud)
	//			->setIdAdopcionOferta($id_adopcion_oferta)
	//			->setIdAdopcionConciliacion($id_adopcion_conciliacion)
	//			->setIdMascota($id_mascota)
				->setHora(time())
				->setMensaje($mensaje)
				->setAsunto($asunto)
				->setAsuntoType($asunto_type)
			;
			if($notificacion->insert()){
				Core_App::getInstance()->addSuccessMessage("Notificacion enviada a usuario", true);
				$notificacion->fromSelf();
				$notificacion->enviar();
				//return true;
			}
			else{
				Core_App::getInstance()->addErrorMessage("No se pudo enviar notificación a usuario", true);
				foreach($notificacion->getTranslatedErrors() as $error){
					Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription(), true);
				}
			}
		}
		if($veterinario){
			$asunto = 'Castración Asginada';
			$asunto_type = 'notificacion_castracion_asginada_veterinario';
			$nombre = $veterinario->getNombre();
			$dia = date('d/m/Y', $castracion->getFechaAsignacion());
				$mensaje = <<<asunto
La sociedad protectora de animales te ha asignado la fecha de castración en $dia.<br />
<br />
asunto;
			if($descripcion)
			$mensaje .= <<<asunto
Detalles:$descripcion.<br />
<br />
asunto;
			$usuario = $veterinario;//$castracion->getUsuario();
			$id_usuario = $usuario->getId();
			$notificacion = new Saludmascotas_Model_Notificacion();
			$notificacion
				->setIdUsuarioTo($id_usuario)
	//			->setIdAdopcionSolicitud($id_adopcion_solicitud)
	//			->setIdAdopcionOferta($id_adopcion_oferta)
	//			->setIdAdopcionConciliacion($id_adopcion_conciliacion)
	//			->setIdMascota($id_mascota)
				->setHora(time())
				->setMensaje($mensaje)
				->setAsunto($asunto)
				->setAsuntoType($asunto_type)
			;
			if($notificacion->insert()){
				Core_App::getInstance()->addSuccessMessage("Notificacion enviada a veterinaria", true);
				$notificacion->fromSelf();
				$notificacion->enviar();
				//return true;
			}
			else{
				Core_App::getInstance()->addErrorMessage("No se pudo enviar notificación a veterinaria", true);
				foreach($notificacion->getTranslatedErrors() as $error){
					Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription(), true);
				}
			}
		}
	}
}
?>