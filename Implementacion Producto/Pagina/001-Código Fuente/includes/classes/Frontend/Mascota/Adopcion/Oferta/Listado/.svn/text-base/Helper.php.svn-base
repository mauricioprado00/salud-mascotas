<?php //es útf8
class Frontend_Mascota_Adopcion_Oferta_Listado_Helper extends Frontend_Helper{
	public function getInstance(){
		return self::getInstanceOf(__CLASS__);
	}
	public static function getUrl($pagina=0){
		return 'mascotas/adopcion_ofertas' . ($pagina?'/'.intval($pagina):'');
	}
	public static function getUrlMascota($id_mascota){
		return 'mascotas/adopcion_ofertas/mascota/'.$id_mascota;
	}
	public static function getUpdatableFields(){
		return array(
			'nombre'
			,'email'
			,'descripcion'
		);
	}
	public static function getIdsDomiciliosAdopcionOfertas($restart=false){
		if($restart||self::getSession()->getVar('mascotas_adopcion_ofertas_listado_ids_domicilios')==null){
			self::getIdsMascotasAdopcionOfertas($restart);
		}
		return self::getSession()->getVar('mascotas_adopcion_ofertas_listado_ids_domicilios');
	}
	private static function getDomiciliosAdopcionOfertas($restart=false){
		if($restart||self::getSession()->getVar('mascotas_adopcion_ofertas_listado_domicilios')==null){
			self::getIdsMascotasAdopcionOfertas($restart);
		}
		return self::getSession()->getVar('mascotas_adopcion_ofertas_listado_domicilios');
	}
	public static function prepareDomiciliosAdopcionOfertas($current=null){
		$domicilios = self::getDomiciliosAdopcionOfertas();
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
	public static function getIdsMascotasAdopcionOfertas($restart=false, $id_mascota=null){
		if($restart||self::getSession()->getVar('mascotas_adopcion_ofertas_listado_ids_mascotas')==null){
			$ids_mascotas_adopcion_ofertas = array();
			$ids_domicilios = array();
			$domicilios = array();
			$mascota_adopcion_oferta = new Saludmascotas_Model_View_MascotaAdopcionOferta();
			$where = array();
			$where[] = Db_Helper::equal('pe_activo','si');
			$where[] = Db_Helper::equal('ma_activa','si');
			if(isset($id_mascota)){
				$where[] = Db_Helper::equal('ma_id', $id_mascota, false);
				$mascota = new Frontend_Model_Mascota();
				$mascota->setId($id_mascota);
				if($mascota->load()){
					if($mascota->esEstadoAdopcionOferta()||$mascota->esEstadoConDueno()){
						if($mascota->esEstadoConDueno()){
							Core_App::getInstance()->addErrorMessage('La mascota ya fué encontrada');
							if($domicilio = $mascota->getDomicilio()){
								$ids_mascotas_adopcion_ofertas[] = $id_mascota;
								$ids_domicilios[] = $domicilio;
								$domicilios[] = $domicilio->getData();
							}
						}
						elseif(($adopcion_oferta = $mascota->getAdopcionOfertaActual()) && ($domicilio = $adopcion_oferta->getDomicilio())){
							$ids_mascotas_adopcion_ofertas[] = $id_mascota;
							$ids_domicilios[] = $domicilio;
							$domicilios[] = $domicilio->getData();
						}
					}
					else{
						Core_App::getInstance()->addErrorMessage('La mascota no se encuentra adopcion_oferta');
					}
				}
			}
			$mascota_adopcion_oferta->setWhereByArray($where);
			$mascotas_adopcion_ofertas = $mascota_adopcion_oferta->search('rand()', 'ASC', null, 0, get_class($mascota_adopcion_oferta));
			foreach($mascotas_adopcion_ofertas as $mascota_adopcion_oferta){
				$ids_mascotas_adopcion_ofertas[] = $mascota_adopcion_oferta->getMaId();
				$ids_domicilios[] = $mascota_adopcion_oferta->getDoId();
				$domicilios[] = $mascota_adopcion_oferta->getDomicilio()->getData();
			}
			self::getSession()->setVar('mascotas_adopcion_ofertas_listado_ids_mascotas', $ids_mascotas_adopcion_ofertas);
			self::getSession()->setVar('mascotas_adopcion_ofertas_listado_ids_domicilios', $ids_domicilios);
			self::getSession()->setVar('mascotas_adopcion_ofertas_listado_domicilios', $domicilios);
		}
		return self::getSession()->getVar('mascotas_adopcion_ofertas_listado_ids_mascotas');
	}
	public static function getPaginasVistas(){
		if(!self::getSession()->getVar('mascotas_adopcion_ofertas_listado_paginas_vistas')){
			return array();
		}
		return self::getSession()->getVar('mascotas_adopcion_ofertas_listado_paginas_vistas');
	}
	public static function addPaginaVista($numero_pag){
		$paginas_vistas = self::getPaginasVistas();
		if(!in_array($numero_pag, $paginas_vistas))
			$paginas_vistas[] = $numero_pag;
		self::getSession()->setVar('mascotas_adopcion_ofertas_listado_paginas_vistas', $paginas_vistas);
		return $this;
	}
	
	public static function actionCrearAdopcionConciliacion($adopcion_conciliacion, $id_mascota){
		if(!is_a($adopcion_conciliacion,'Frontend_Model_AdopcionConciliacion')){
			$array = $adopcion_conciliacion->getData();
			$adopcion_conciliacion = new Frontend_Model_AdopcionConciliacion();
			$adopcion_conciliacion->loadFromArray($array);
		}
		$errors = array();
		$mascota = new Frontend_Model_Mascota();
		$mascota->setId($id_mascota);
		$id_adopcion_oferta = null;
		if($mascota->load()){
			if($mascota->esEstadoAdopcionOferta()){
				$adopcion_oferta = $mascota->getAdopcionOfertaActual();
				if($adopcion_oferta){
					$id_adopcion_oferta = $adopcion_oferta->getId();
				}
				else $errors[] = 'No se pudo completar el registro, hay un error en el sistema';
			}
			else $errors[] = 'La mascota no se encuentra adopcion_oferta';
		}
		else $errors[] = 'La mascota no existe';
		$usuario = self::getLogedUser();
		
		if(!$usuario){
			$adopcion_conciliacion->addExtraValidators();
		}
		else $adopcion_conciliacion->setIdUsuario($usuario->getId());		
		$adopcion_conciliacion
			->setIdAdopcionSolicitud(null)
			->setIdAdopcionOferta($id_adopcion_oferta)
			->setConfirmado(false)
			->setHoraAdopcionConciliacion(time())
			->setIniciadoPor('adopcion_solicitud')
		;
		
		if(!$adopcion_conciliacion->validateFields() || $errors){
			Core_App::getInstance()->addErrorMessage(self::getInstance()->__t("No se pudo registrar el adopcion_conciliacion"));
			Core_Helper::LoadValidationTranslation();
			if($adopcion_conciliacion->getValidationMessages())
				foreach($adopcion_conciliacion->getValidationMessages() as $key=>$messages){
					foreach($messages as $message){
						Core_App::getInstance()->addErrorMessage($message);
					}
				}
			foreach($errors as $message){
				Core_App::getInstance()->addErrorMessage($message);
			}
			return false;
		}
		
		//if(!$adopcion_conciliacion->hasId()){/** aca hay que agregar a la base de datos*/
			$resultado = $adopcion_conciliacion->insert()?true:false;
			if($resultado){
				Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('AdopcionConciliacion registrada correctamente'), true);
				$res = self::enviarNotificacionAdopcionConciliacion($adopcion_conciliacion, $id_mascota);
			}
			else{
				Core_App::getInstance()->addErrorMessage("No se pudo registrar la adopcion_conciliacion");
				foreach($adopcion_conciliacion->getTranslatedErrors() as $error){
					Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
				}
			}
		//}
//		else{/** aca hay que actualizar el registro*/
//			$resultado = $adopcion_conciliacion->updateFromUserInput(null)?true:false;
////			header('content-type:text/plain');
////			var_dump($adopcion_conciliacion);
////			die(__FILE__.__LINE__);
////			
//			if($resultado){
//				Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Sus datos han sido actualizados'));
//			}
//			else{
//				Core_App::getInstance()->addErrorMessage("No se pudo actualizar sus datos");
//				foreach($adopcion_conciliacion->getTranslatedErrors() as $error){
//					Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
//				}
//			}
// 		}
		return($resultado);
	}
	public static function enviarNotificacionAdopcionConciliacion($adopcion_conciliacion, $id_mascota=null){
		$usuario = self::getLogedUser();
		if(!$usuario){
			$nombre = $adopcion_conciliacion->getNombre();
			$email = $adopcion_conciliacion->getEmail();
		}
		else{
			$nombre = $usuario->getNombre() . ' ' . $usuario->getApellido();
			$email = $usuario->getEmail();
		}
		$adopcion_oferta = $adopcion_conciliacion->getAdopcionOferta();
		$id_mascota_adopcion_oferta = $id_mascota;//$adopcion_oferta->getIdMascota();
		$url_vista_confirmaciones_pendientes = Frontend_Mascota_Adopcion_Conciliacion_Helper::getUrlConfirmacionesPendientes($id_mascota_adopcion_oferta);
		$url_confirmar = Frontend_Mascota_Adopcion_Conciliacion_Helper::getUrlConfirmar($id_mascota_adopcion_oferta, $adopcion_conciliacion->getId());
		$url_vista_confirmaciones_pendientes = Core_App::getUrlModel()->getUrl($url_vista_confirmaciones_pendientes);
		$url_confirmar = Core_App::getUrlModel()->getUrl($url_confirmar);
		$usuario = $adopcion_oferta->getUsuario();
		$id_usuario = $usuario->getId();
		$id_adopcion_solicitud = $adopcion_conciliacion->getIdAdopcionSolicitud();
		$id_adopcion_oferta = $adopcion_conciliacion->getIdAdopcionOferta();
		$id_adopcion_conciliacion = $adopcion_conciliacion->getId();
//		if(!isset($id_mascota)){
//			if($id_adopcion_solicitud){
//				$adopcion_solicitud = new Saludmascotas_Model_AdopcionSolicitud();
//				$adopcion_solicitud->setId($id_adopcion_solicitud);
//				if($adopcion_solicitud->load()){
//					$id_mascota = $adopcion_solicitud->getIdMascota();
//				}
//			}
//		}
//		if(!isset($id_mascota)){
//			if($id_adopcion_oferta){
//				$adopcion_oferta = new Saludmascotas_Model_AdopcionOferta();
//				$adopcion_oferta->setId($id_adopcion_oferta);
//				if($adopcion_oferta->load()){
//					$id_mascota = $adopcion_oferta->getIdMascota();
//				}
//			}
//		}
		$comentario = $adopcion_conciliacion->getDescripcion();
		$comentario = htmlentities(utf8_decode($comentario));
		$asunto = 'Comentario en anuncio de AdopcionOferta';
		$mensaje = <<<asunto
Alguien ha comentado sobre el tu anuncio de mascota adopcion_oferta.<br />
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
		$asunto_type = 'notificacion_adopcion_conciliacion_comentario';
		
		$notificacion = new Saludmascotas_Model_Notificacion();
		$notificacion
			->setIdUsuarioTo($id_usuario)
			->setIdAdopcionSolicitud(null)
			->setIdAdopcionOferta($id_adopcion_oferta)
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