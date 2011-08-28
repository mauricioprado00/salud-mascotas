<?php //es útf8
class Frontend_Mascota_Perdida_Listado_Helper extends Frontend_Helper{
	public function getInstance(){
		return self::getInstanceOf(__CLASS__);
	}
	public static function getUrl($pagina=0){
		return 'mascotas/perdidas' . ($pagina?'/'.intval($pagina):'');
	}
	public static function getUrlMascota($id_mascota){
		return 'mascotas/perdidas/mascota/'.$id_mascota;
	}
	public static function getUpdatableFields(){
		return array(
			'nombre'
			,'email'
			,'descripcion'
		);
	}
	public static function getIdsDomiciliosPerdidas($restart=false){
		if($restart||self::getSession()->getVar('mascotas_perdidas_listado_ids_domicilios')==null){
			self::getIdsMascotasPerdidas($restart);
		}
		return self::getSession()->getVar('mascotas_perdidas_listado_ids_domicilios');
	}
	private static function getDomiciliosPerdidas($restart=false){
		if($restart||self::getSession()->getVar('mascotas_perdidas_listado_domicilios')==null){
			self::getIdsMascotasPerdidas($restart);
		}
		return self::getSession()->getVar('mascotas_perdidas_listado_domicilios');
	}
	public static function prepareDomiciliosPerdidas($current=null){
		$domicilios = self::getDomiciliosPerdidas();
		if(!$domicilios)
			return array();
//		var_dump($domicilios);
//		die(__FILE__.__LINE__);
		$return = array();
		foreach($domicilios as $idx=>$domicilio){
			$dom = array(
				'id'=>$domicilio['id'],
				'lat'=>$domicilio['lat']/60,
				'lng'=>$domicilio['lng']/60,
			);
			if(isset($current)&&$idx==$current){
				$dom['current'] = true;
			}
			$return[] = $dom;
		}
		//die(__FILE__.__LINE__);
		return $return;
	}
	public static function getIdsMascotasPerdidas($restart=false, $id_mascota=null){
		if($restart||self::getSession()->getVar('mascotas_perdidas_listado_ids_mascotas')==null){
			$ids_mascotas_perdidas = array();
			$ids_domicilios = array();
			$domicilios = array();
			$mascota_perdida = new Saludmascotas_Model_View_MascotaPerdida();
			$where = array();
			$where[] = Db_Helper::equal('pe_activo','si');
			$where[] = Db_Helper::equal('ma_activa','si');
			if(isset($id_mascota)){
				$where[] = Db_Helper::equal('ma_id', $id_mascota, false);
				$mascota = new Frontend_Model_Mascota();
				$mascota->setId($id_mascota);
				if($mascota->load()){
					if($mascota->esEstadoPerdida()||$mascota->esEstadoConDueno()){
						if($mascota->esEstadoConDueno()){
							Core_App::getInstance()->addErrorMessage('La mascota ya fué encontrada');
							if($domicilio = $mascota->getDomicilio()){
								$ids_mascotas_perdidas[] = $id_mascota;
								$ids_domicilios[] = $domicilio;
								$domicilios[] = $domicilio->getData();
							}
						}
						elseif(($perdida = $mascota->getPerdidaActual()) && ($domicilio = $perdida->getDomicilio())){
							$ids_mascotas_perdidas[] = $id_mascota;
							$ids_domicilios[] = $domicilio;
							$domicilios[] = $domicilio->getData();
						}
					}
					else{
						Core_App::getInstance()->addErrorMessage('La mascota no se encuentra perdida');
					}
				}
			}
			$mascota_perdida->setWhereByArray($where);
			$mascotas_perdidas = $mascota_perdida->search('rand()', 'ASC', null, 0, get_class($mascota_perdida));
			foreach($mascotas_perdidas as $mascota_perdida){
				$ids_mascotas_perdidas[] = $mascota_perdida->getMaId();
				$ids_domicilios[] = $mascota_perdida->getDoId();
				$domicilios[] = $mascota_perdida->getDomicilio()->getData();
			}
			self::getSession()->setVar('mascotas_perdidas_listado_ids_mascotas', $ids_mascotas_perdidas);
			self::getSession()->setVar('mascotas_perdidas_listado_ids_domicilios', $ids_domicilios);
			self::getSession()->setVar('mascotas_perdidas_listado_domicilios', $domicilios);
		}
		return self::getSession()->getVar('mascotas_perdidas_listado_ids_mascotas');
	}
	public static function getPaginasVistas(){
		if(!self::getSession()->getVar('mascotas_perdidas_listado_paginas_vistas')){
			return array();
		}
		return self::getSession()->getVar('mascotas_perdidas_listado_paginas_vistas');
	}
	public static function addPaginaVista($numero_pag){
		$paginas_vistas = self::getPaginasVistas();
		if(!in_array($numero_pag, $paginas_vistas))
			$paginas_vistas[] = $numero_pag;
		self::getSession()->setVar('mascotas_perdidas_listado_paginas_vistas', $paginas_vistas);
		return $this;
	}
	
	public static function actionCrearReencuentro($reencuentro, $id_mascota){
		if(!is_a($reencuentro,'Frontend_Model_Reencuentro')){
			$array = $reencuentro->getData();
			$reencuentro = new Frontend_Model_Reencuentro();
			$reencuentro->loadFromArray($array);
		}
		$errors = array();
		$mascota = new Frontend_Model_Mascota();
		$mascota->setId($id_mascota);
		$id_perdida = null;
		if($mascota->load()){
			if($mascota->esEstadoPerdida()){
				$perdida = $mascota->getPerdidaActual();
				if($perdida){
					$id_perdida = $perdida->getId();
				}
				else $errors[] = 'No se pudo completar el registro, hay un error en el sistema';
			}
			else $errors[] = 'La mascota no se encuentra perdida';
		}
		else $errors[] = 'La mascota no existe';
		$usuario = self::getLogedUser();
		
		if(!$usuario){
			$reencuentro->addExtraValidators();
		}
		else $reencuentro->setIdUsuario($usuario->getId());		
		$reencuentro
			->setIdEncuentro(null)
			->setIdPerdida($id_perdida)
			->setConfirmado(false)
			->setHoraReencuentro(time())
			->setIniciadoPor('encuentro')
		;
		
		if(!$reencuentro->validateFields() || $errors){
			Core_App::getInstance()->addErrorMessage(self::getInstance()->__t("No se pudo registrar el reencuentro"));
			Core_Helper::LoadValidationTranslation();
			if($reencuentro->getValidationMessages())
				foreach($reencuentro->getValidationMessages() as $key=>$messages){
					foreach($messages as $message){
						Core_App::getInstance()->addErrorMessage($message);
					}
				}
			foreach($errors as $message){
				Core_App::getInstance()->addErrorMessage($message);
			}
			return false;
		}
		
		//if(!$reencuentro->hasId()){/** aca hay que agregar a la base de datos*/
			$resultado = $reencuentro->insert()?true:false;
			if($resultado){
				Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Reencuentro registrada correctamente'), true);
				$res = self::enviarNotificacionReencuentro($reencuentro, $id_mascota);
			}
			else{
				Core_App::getInstance()->addErrorMessage("No se pudo registrar la reencuentro");
				foreach($reencuentro->getTranslatedErrors() as $error){
					Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
				}
			}
		//}
//		else{/** aca hay que actualizar el registro*/
//			$resultado = $reencuentro->updateFromUserInput(null)?true:false;
////			header('content-type:text/plain');
////			var_dump($reencuentro);
////			die(__FILE__.__LINE__);
////			
//			if($resultado){
//				Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Sus datos han sido actualizados'));
//			}
//			else{
//				Core_App::getInstance()->addErrorMessage("No se pudo actualizar sus datos");
//				foreach($reencuentro->getTranslatedErrors() as $error){
//					Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
//				}
//			}
// 		}
		return($resultado);
	}
	public static function enviarNotificacionReencuentro($reencuentro, $id_mascota=null){
		$usuario = self::getLogedUser();
		if(!$usuario){
			$nombre = $reencuentro->getNombre();
			$email = $reencuentro->getEmail();
		}
		else{
			$nombre = $usuario->getNombre() . ' ' . $usuario->getApellido();
			$email = $usuario->getEmail();
		}
		$perdida = $reencuentro->getPerdida();
		$id_mascota_perdida = $id_mascota;//$perdida->getIdMascota();
		$url_vista_confirmaciones_pendientes = Frontend_Mascota_Reencuentro_Helper::getUrlConfirmacionesPendientes($id_mascota_perdida);
		$url_confirmar = Frontend_Mascota_Reencuentro_Helper::getUrlConfirmar($id_mascota_perdida, $reencuentro->getId());
		$url_vista_confirmaciones_pendientes = Core_App::getUrlModel()->getUrl($url_vista_confirmaciones_pendientes);
		$url_confirmar = Core_App::getUrlModel()->getUrl($url_confirmar);
		$usuario = $perdida->getUsuario();
		$id_usuario = $usuario->getId();
		$id_encuentro = $reencuentro->getIdEncuentro();
		$id_perdida = $reencuentro->getIdPerdida();
		$id_reencuentro = $reencuentro->getId();
//		if(!isset($id_mascota)){
//			if($id_encuentro){
//				$encuentro = new Saludmascotas_Model_Encuentro();
//				$encuentro->setId($id_encuentro);
//				if($encuentro->load()){
//					$id_mascota = $encuentro->getIdMascota();
//				}
//			}
//		}
//		if(!isset($id_mascota)){
//			if($id_perdida){
//				$perdida = new Saludmascotas_Model_Perdida();
//				$perdida->setId($id_perdida);
//				if($perdida->load()){
//					$id_mascota = $perdida->getIdMascota();
//				}
//			}
//		}
		$comentario = $reencuentro->getDescripcion();
		$comentario = htmlentities(utf8_decode($comentario));
		$asunto = 'Comentario en anuncio de Perdida';
		$mensaje = <<<asunto
Alguien ha comentado sobre el tu anuncio de mascota perdida.<br />
comentario:<br />
$comentario
<br />
Puedes responderle al usuario respondiendo este email.
<br />
Puedes ver y confirmar que el usuario está en lo correcto haciendo <a href="$url_confirmar">click aquí</a> o copiando y pegando el siguiente enlace en tu navegador:<br />
$url_confirmar
<br />
o ver todas las demas confirmaciones pendientes para tu publicación haciendo <a href="$url_vista_confirmaciones_pendientes">click aqui</a> o copiando y pegando el siguiente enlace en tu navegador:
$url_vista_confirmaciones_pendientes
<br />

asunto;
		$asunto_type = 'notificacion_reencuentro_comentario';
		
		$notificacion = new Saludmascotas_Model_Notificacion();
		$notificacion
			->setIdUsuarioTo($id_usuario)
			->setIdEncuentro(null)
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
			Core_App::getInstance()->addErrorMessage("Notificacion enviada", true);
			$notificacion->setFrom($email, $nombre);// fromSelf();
			return $notificacion->enviar();
		}
		Core_App::getInstance()->addErrorMessage("No se pudo enviar notificación", true);
		foreach($notificacion->getTranslatedErrors() as $error){
			Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription(), true);
		}
		return false;
	}

}
?>