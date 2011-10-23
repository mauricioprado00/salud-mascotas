<?php /*es útf8*/
class Frontend_Mascota_Perdida_Router extends Frontend_Mascota_Router{
	protected function initialize(){
		parent::initialize();
		$this->addActions(
			//'usuario'
			//,
			'agregar'
			,'editar'
		);
	}
//	protected function localDispatch(){
//		echo 'home mascotas';
//		die(__FILE__.__LINE__);
//		//esta es la home
////		Core_App::getLayout()
////			->setModo('saludmascotas_legacy')
////			->addAction('home')
////		;
//		//var_dump(c(new Frontend_Mascota_Model_User())->isLoged(), Frontend_Mascota_Model_User::getLogedUser()?true:false);
//		if(Frontend_Mascota_Model_User::getLogedUser()){
//			return $this->update();
//		}
//		return $this->login();
////		return true;
//	}
//	protected function usuario($numero_pag=0){
//		if($this->RedirectIfNotLoged())
//			return true;
//		Core_App::getInstance()->setPagina($numero_pag);
//		$this->setPageReference('Mis mascotas', 'Revistar mascotas');
//		Core_App::getLayout()
//			->setModo('saludmascotas')
//			->addAction('mascota_usuario_listado')
//		;
//		$this->showLeftMenu('usuario');
//		
//		//loaded layout
////		Core_App::getLoadedLayout()
////			->getBlock('form_edit')//$x = $this->getObjectToEdit();
////			->setObjectToEdit($object_to_edit)
////		;
//		$this->setActiveLeftMenu('mascotas_usuario');
//	}
//	protected function agregar($paso=1, $preserve_mascota_edicion=false){
//		return $this->editar($paso, 'new', $preserve_mascota_edicion);
//	}
	protected function _allowStep($paso){
		return !($paso>4 || $paso<1);
	}
	protected function _pre_editar_init($paso=1, $id_mascota=null, $preserve_mascota_edicion=false){
		$return = parent::_pre_editar_init($paso, $id_mascota, $preserve_mascota_edicion);
		$object_to_edit = $this->getObjectToEdit();
		
		$perdida = null;
		if(!$preserve_mascota_edicion || !($perdida = Frontend_Mascota_Perdida_Helper::getPerdidaEdicionFromSession($id_mascota))){
			if(in_array($paso, array(2,4))||!$preserve_mascota_edicion)
				$perdida = Frontend_Mascota_Perdida_Helper::getPerdidaEdicion($object_to_edit);
			//$perdida = $object_to_edit->getPerdida();
		}
		if(!$perdida)
			$perdida = new Frontend_Model_Perdida();
		
		if($preserve_mascota_edicion){
			$coincidencias_seleccionadas = Frontend_Mascota_Perdida_Helper::getCoincidenciasSeleccionadasFromSession($id_mascota);
			if(!isset($coincidencias_seleccionadas)){
				$coincidencias_seleccionadas = $perdida->getIdsCoincidenciasSeleccionadas();
			}
		}
		else{
			$coincidencias_seleccionadas = $perdida->getIdsCoincidenciasSeleccionadas();
		}
		if(!isset($coincidencias_seleccionadas)){
			$coincidencias_seleccionadas = array();
		}
//		echo Core_Helper::DebugVars($perdida->getData());
//		die(__FILE__.__LINE__);

		switch($paso){
			case 1:{
				$this->getObjectToEdit()->setEstadoPerdida();
				break;
			}
			case 3:{
				Core_App::getInstance()->setMascotaParam($object_to_edit);
				$perdida->commitNonTableColumn();
				$coincidencias = $perdida->getCoincidencias();
				$this->setCoincidencias($coincidencias);
				//Core_App::getInstance()->addErrorMessage('inicializar mascotas coincidentes elegidas '.__FILE__.__LINE__);
				break;
			}
			case 4:{
				//incluyo encuentros seleccionados actualmente y en otros registros
				$ids_coincidencias = $perdida->getIdsCoincidenciasSeleccionadas();
				if($ids_coincidencias)
					$ids_coincidencias = array_merge(array_diff($ids_coincidencias, $coincidencias_seleccionadas), $coincidencias_seleccionadas);
				else $ids_coincidencias = $coincidencias_seleccionadas;
//				var_dump($ids_coincidencias, $coincidencias_seleccionadas);
//				die(__FILE__.__LINE__);
				if($ids_coincidencias)
					$coincidencias = $perdida->getCoincidencias($ids_coincidencias);
				else $coincidencias = null;
				$this->setCoincidencias($coincidencias);
				//Core_App::getInstance()->addErrorMessage('inicializar mascotas coincidentes elegidas '.__FILE__.__LINE__);
				break;
			}
		}
		
		$this->setPerdida($perdida);
		$this->setCoincidenciasSeleccionadas($coincidencias_seleccionadas);

		return $return;
//		$object_to_edit = null;
//		if(!$preserve_mascota_edicion || !($object_to_edit = Frontend_Mascota_Helper::getMascotaEdicionFromSession($id_mascota))){
//			if(isset($id_mascota)){
//				$object_to_edit = Frontend_Mascota_Helper::getMascotaEdicion($id_mascota);
//				if(!$object_to_edit){
//					Core_Http_Header::Redirect(Frontend_Mascota_Helper::getUrlUsuario(), true);
//					return false;
//				}
//			}
//			else{
//				$object_to_edit = new Frontend_Model_Mascota();
//			}
//		}
//		
//		$domicilio_mascota = null;
//		if($paso=='2'){
//			if(!$preserve_mascota_edicion || !($domicilio_mascota = Frontend_Mascota_Helper::getDomicilioMascotaEdicionFromSession($id_mascota))){
//				$domicilio_mascota = Frontend_Mascota_Helper::getDomicilioEdicion($object_to_edit);
//				//$domicilio_mascota = $object_to_edit->getDomicilio();
//			}
//			if(!$domicilio_mascota)
//				$domicilio_mascota = new Frontend_Model_Domicilio();
////			echo Core_Helper::DebugVars($object_to_edit->getData(),$domicilio_mascota->getData());
////			die(__FILE__.__LINE__);
//		}
//		$this->setObjectToEdit($object_to_edit);
//		$this->setDomicilioMascota($domicilio_mascota);
	}
	protected function _editar_step_ok($paso=1, $id_mascota=null, $preserve_mascota_edicion=false){
		switch($paso){
			case 1:{//si el paso está bien cargado redirijo al paso siguiente
				$object_to_edit = $this->getObjectToEdit();
				Core_Http_Header::Redirect(Frontend_Mascota_Perdida_Helper::getUrlEditar($object_to_edit->getId(), 1, 2), true);
				return true;
				break;
			}
			case 2:{//si el paso esta bien cargado (domicilio) checkeo la carga de fecha y hora de extravio y redirijo al paso siguiente
				Core_Http_Header::Redirect(Frontend_Mascota_Perdida_Helper::getUrlEditar($id_mascota, 1, 3), true);
				return true;
//				$perdida = $this->getPerdida();
//				$post = Core_Http_Post::getParameters('Core_Object', Frontend_Mascota_Perdida_Helper::getUpdatablePerdidaFields());
//				$perdida->loadFromArray($post->getData(), false);
////				header('content-type:text/plain');
////				var_dump($perdida->getData());
////				die(__FILE__.__LINE__);
//				$object_to_edit = $this->getObjectToEdit();
//				$id_mascota = $object_to_edit->getId();
//				$guardado_en_sesion = Frontend_Mascota_Perdida_Helper::actionAgregarEditarPerdida($perdida, true, $id_mascota)?true:false;
//				if($guardado_en_sesion){//pasa validaciones
//					Core_Http_Header::Redirect(Frontend_Mascota_Perdida_Helper::getUrlEditar($id_mascota, 1, 3), true);
//					return true;
//				}
				break;			
			}
			case 3:{//si las selecciones de mascotas coincidentes en la búsqueda estan correctas redirijo al paso siguiente
				$object_to_edit = $this->getObjectToEdit();
				Core_Http_Header::Redirect(Frontend_Mascota_Perdida_Helper::getUrlEditar($object_to_edit->getId(), 1, 4), true);
				break;
			}
			case 4:{//si la carga de opciones y verificación final es correcta, guardo domicilio, mascota, reencuentros y anuncio
				//Core_App::getInstance()->addErrorMessage('guardo domicilio, mascota, reencuentros y anuncio '.__FILE__.__LINE__, true);
				
				$perdida = $this->getPerdida();
				$object_to_edit = $this->getObjectToEdit();
				//$id_mascota = $object_to_edit->getId();
				
				$domicilio_mascota = $this->getDomicilioMascota();
				$domicilio_mascota_guardada = $this->getHelper()->actionAgregarEditarDomicilio($domicilio_mascota, false)?true:false;
				if($domicilio_mascota_guardada){
					$mascota_guardada = $this->getHelper()->actionAgregarEditarMascota($object_to_edit, false, null/* no vamos a modificar el domicilio de la mascota $domicilio_mascota*/)?true:false;
					if($mascota_guardada){
//var_dump($id_mascota);die(__FILE__.__LINE__);
						$id_mascota = $object_to_edit->getId();
						$guardado = Frontend_Mascota_Perdida_Helper::actionAgregarEditarPerdida($perdida, false, $id_mascota, $domicilio_mascota)?true:false;
						if($guardado){//pasa validaciones
							$guardadas = Frontend_Mascota_Perdida_Helper::crearReencuentros($perdida, $this->getCoincidenciasSeleccionadas())?true:false;
							if($guardadas){
								$this->getHelper()->clearSessionVars();
								if($object_to_edit->hasReencuentros())
									Core_Http_Header::Redirect($object_to_edit->getUrlConfirmacionesPendientes(), true);
								else
									Core_Http_Header::Redirect(Frontend_Mascota_Encuentro_Helper::getUrlUsuario(), true);
								return true;
							}
						}
//						$this->getHelper()->clearSessionVars();
//						Core_Http_Header::Redirect($this->getHelper()->getUrlUsuario(), true);
//						return true;
					}
				}

//				$perdida = $this->getPerdida();
//				$object_to_edit = $this->getObjectToEdit();
//				$id_mascota = $object_to_edit->getId();
//				$domicilio_mascota = $this->getDomicilioMascota();
//				$guardado_en_sesion = Frontend_Mascota_Perdida_Helper::actionAgregarEditarPerdida($perdida, false, $id_mascota, $domicilio_mascota)?true:false;
//				if($guardado_en_sesion){//pasa validaciones
//					$object_to_edit = $this->getObjectToEdit();
//					Core_Http_Header::Redirect(Frontend_Mascota_Perdida_Helper::getUrlUsuario(), true);
//					return true;
//				}
//				die('guardo domicilio, mascota, reencuentros y anuncio '.__FILE__.__LINE__);
//				Core_App::getInstance()->addErrorMessage('guardo domicilio, mascota, reencuentros y anuncio '.__FILE__.__LINE__);
				break;
			}
		}
	}
  /**
   * Frontend_Mascota_Router::_pre_editar_handle_post()
   *
   * @param integer $paso
   * @param mixed $id_mascota
   * @param bool $preserve_mascota_edicion
   * @return true si maneja el post y realiza las acciones correctamente, false si maneja el post y no realiza las acciones correctamente, null si no maneja el post
   */
	protected function _pre_editar_handle_post($paso=1, $id_mascota=null, $preserve_mascota_edicion=false){
		$return = parent::_pre_editar_handle_post($paso, $id_mascota, $preserve_mascota_edicion);
		switch($paso){
			case 2:{
				$perdida = $this->getPerdida();
				$post = Core_Http_Post::getParameters('Core_Object', Frontend_Mascota_Perdida_Helper::getUpdatablePerdidaFields());
				$perdida->loadFromArray($post->getData(), false, true);
//				header('content-type:text/plain');
//				var_dump($perdida->getData());
//				die(__FILE__.__LINE__);
				$object_to_edit = $this->getObjectToEdit();
				$id_mascota = $object_to_edit->getId();
				$guardado_en_sesion = Frontend_Mascota_Perdida_Helper::actionAgregarEditarPerdida($perdida, true, $id_mascota)?true:false;
				$return &= $guardado_en_sesion;
//				if($guardado_en_sesion){//pasa validaciones
//					
//				}
//				var_dump($return);
//				die(__FILE__.__LINE__);
				break;
			}
			case 3:{//verificar que las selecciones sean correcta y guardar en sesión
				//Core_App::getInstance()->addErrorMessage('verificar que las selecciones sean correcta y guardar en sesión '.__FILE__.__LINE__);
				$post = Core_Http_Post::getParameters('Core_Object', array('coincidencias_seleccionadas'));
				$coincidencias_seleccionadas = $post->getCoincidenciasSeleccionadas();
				$guardado = Frontend_Mascota_Perdida_Helper::setCoincidenciasSeleccionadasInSession($coincidencias_seleccionadas);
				if($guardado){
					return true;
//					$return = $this->_editar_step_ok($paso, $id_mascota, $preserve_mascota_edicion);
//					if(isset($return))
//						return $return;
				}
				break;
			}
			case 4:{//verificar la carga de opciones de verificación final
//				die('verificar la carga de opciones de verificación final'.__FILE__.__LINE__);
				$post = Core_Http_Post::getParameters('Core_Object', array('coincidencias_seleccionadas'));
				$coincidencias_seleccionadas = $post->getCoincidenciasSeleccionadas();
				$guardado = Frontend_Mascota_Perdida_Helper::setCoincidenciasSeleccionadasInSession($coincidencias_seleccionadas);
				$this->setCoincidenciasSeleccionadas($coincidencias_seleccionadas);
				$perdida = $this->getPerdida();
				$post = Core_Http_Post::getParameters('Core_Object', Frontend_Mascota_Perdida_Helper::getUpdatablePerdidaFields());
				$perdida->loadFromArray($post->getData(), false);
//				header('content-type:text/plain');
//				var_dump($perdida->getData());
//				die(__FILE__.__LINE__);
				$object_to_edit = $this->getObjectToEdit();
				$id_mascota = $object_to_edit->getId();
				$guardado_en_sesion = Frontend_Mascota_Perdida_Helper::actionAgregarEditarPerdida($perdida, true, $id_mascota)?true:false;
				if($guardado_en_sesion){//pasa validaciones
					return true;
//					$return = $this->_editar_step_ok($paso, $id_mascota, $preserve_mascota_edicion);
//					if(isset($return))
//						return $return;
				}
			}
		}
		return $return;
//		$object_to_edit = $this->getObjectToEdit();
//		$domicilio_mascota = $this->getDomicilioMascota();
//		if($paso==1){
//			$post = Core_Http_Post::getParameters('Core_Object', Frontend_Mascota_Helper::getUpdatableFields());
//			$object_to_edit->loadFromArray($post->getData(), true, true);
////			header('content-type:text/plain');
////			var_dump($object_to_edit->getData(), $post->getData());
////			die(__FILE__.__LINE__);				//$object_to_edit->setPassword2($post->getPassword2());
//			$guardado_en_sesion = Frontend_Mascota_Helper::actionAgregarEditarMascota($object_to_edit, true)?true:false;
//			if($guardado_en_sesion){
////				echo Core_Helper::DebugVars($object_to_edit->getId(), 1, 2);
////				var_dump(Frontend_Mascota_Helper::getUrlEditar($object_to_edit->getId(), 1, 2));
////				die(__FILE__.__LINE__);
//				Core_Http_Header::Redirect(Frontend_Mascota_Helper::getUrlEditar($object_to_edit->getId(), 1, 2), true);
//				return true;
////				echo "mascota guardada en sesión";
////				die(__FILE__.__LINE__);
////				//Core_Http_Header::Redirect(Frontend_Usuario_Helper::getUrlLogin($object_to_edit), true);
////				return true;
//			}
//		}
//		elseif($paso==2){
//			$post = Core_Http_Post::getParameters('Core_Object', Frontend_Mascota_Helper::getUpdatableDomicilioFields());
//			$domicilio_mascota->loadFromArray($post->getData(), false);
////			header('content-type:text/plain');
////			var_dump($domicilio_mascota->getData());
////			die(__FILE__.__LINE__);
//			$guardado_en_sesion = Frontend_Mascota_Helper::actionAgregarEditarDomicilio($domicilio_mascota, true)?true:false;
//			if($guardado_en_sesion){//pasa validaciones
//				$domicilio_mascota_guardada = Frontend_Mascota_Helper::actionAgregarEditarDomicilio($domicilio_mascota, false)?true:false;
//				if($domicilio_mascota_guardada){
//					$mascota_guardada = Frontend_Mascota_Helper::actionAgregarEditarMascota($object_to_edit, false, $domicilio_mascota)?true:false;
//					if($mascota_guardada){
//						Frontend_Mascota_Helper::clearSessionVars();
//						Core_Http_Header::Redirect(Frontend_Mascota_Helper::getUrlUsuario(), true);
//						return true;
//					}
//				}
//			}
//		}
////		$object_to_edit = $this->getObjectToEdit();
////		$domicilio_mascota = $this->getDomicilioMascota();
	}
	protected function _pre_editar($paso=1, &$id_mascota=null, $preserve_mascota_edicion=false){
		return parent::_pre_editar($paso, $id_mascota, $preserve_mascota_edicion);
//		if($this->RedirectIfNotLoged())
//			return true;
//		if($this->_allowStep($paso)){
//			Core_Http_Header::Redirect(Frontend_Mascota_Helper::getUrlEditar($id_mascota, 1, 1), true);
//			return true;
//		}
//		$id_mascota = $id_mascota=='new'?null:$id_mascota;
//		
//		$return = $this->_pre_editar_init($paso, $id_mascota, $preserve_mascota_edicion);
//		if(isset($return)){
//			return $return;
//		}
//		if(Core_Http_Post::hasParameters()){
//			$return = $this->_pre_editar_handle_post($paso, $id_mascota, $preserve_mascota_edicion);
//			if(isset($return)){
//				return $return;
//			}
//		}
	}
	protected function _editar_handle_load_layout($paso=1, $id_mascota=null, $preserve_mascota_edicion=false){
		$return = parent::_editar_handle_load_layout($paso, $id_mascota, $preserve_mascota_edicion);
		if($paso==1){
			$loaded_layout = Core_App::getLoadedLayout();
			$loaded_layout->getBlock('main_form_edit')
				->setShowOptionalFields(false)
			;
		}
		if($paso==2){
			$loaded_layout = Core_App::getLoadedLayout();
			$perdida = $this->getPerdida();
			$loaded_layout
				->getBlock('addedit_fecha_extravio')
				->setPerdida($perdida);
			;
		}
		if($paso==3){
			$loaded_layout = Core_App::getLoadedLayout();
			$show_select_coincidencias = $loaded_layout->getBlock('show_select_coincidencias');
			$show_select_coincidencias
				->setCoincidencias($this->getCoincidencias())
				->setCoincidenciasSeleccionadas($this->getCoincidenciasSeleccionadas())
			;
		}
		if($paso==4){
			$loaded_layout = Core_App::getLoadedLayout();
			$object_to_edit = $this->getObjectToEdit();
			$fotos_mascotas = $object_to_edit->getListFoto(true);//el true es para que permita pk nula
			$perdida = $this->getPerdida();
			$loaded_layout->getBlock('form_edit')->setObjectToEdit($object_to_edit);
			$view_datos_mascota = $loaded_layout
				->getBlock('view_datos_mascota')
				->setMascota($object_to_edit)
				->setPhotoList($fotos_mascotas)
				->addExtraData('Fecha Extravío', $perdida->getExtravioFecha())
			;
			$view_ubicacion = $loaded_layout
				->getBlock('view_ubicacion')
				->setDomicilio($this->getDomicilioMascota())
			;

			$view_posibles_reencuentros = $loaded_layout
				->getBlock('view_posibles_reencuentros')
				->setCoincidencias($this->getCoincidencias())
				->setCoincidenciasSeleccionadas($this->getCoincidenciasSeleccionadas())
				//->setPerdida(NULL)
			;
			$form_edit_publicacion = $loaded_layout
				->getBlock('form_edit_publicacion')
				->setPerdida($perdida)
			;
		}
		return $return;
//		$object_to_edit = $this->getObjectToEdit();
//		$domicilio_mascota = $this->getDomicilioMascota();
//		
//		$loaded_layout = Core_App::getLoadedLayout();
//		if($paso==1){
//			$form_edit = $loaded_layout->getBlock('form_edit');
//			$form_edit
//				->setObjectToEdit($object_to_edit)
//			;
//			$fotos_addedit = $loaded_layout->getBlock('fotos_addedit');
//			$usuario = $this->getLogedUser();
//			$foto_mascota = new Saludmascotas_Model_FotoMascota();
//			$foto_mascota->setWhere(
//				Db_Helper::equal('id_usuario'), 
//				Db_Helper::equal('id_mascota')
//			);
//			$foto_mascota
//				->setIdUsuario($usuario->getId())
//				->setIdMascota($object_to_edit->getId())
//			;
//			$fotos_mascotas = $foto_mascota->search(null, 'ASC', null, 0, get_class($foto_mascota));
////			var_dump(count($fotos_mascotas));
////			die(__FILE__.__LINE__);
//			$fotos_addedit
//				->setUrlPhoto(Frontend_Mascota_Helper::getUrlPhoto())
//				->setIdMascota($object_to_edit->getId())
//				->setPhotoList($fotos_mascotas)
//			;
//		}
//		elseif($paso==2){
//			$domicilio_usuario = Frontend_Mascota_Helper::getDomicilioUsuario(true);
////			header('content-type:text/plain');
////			var_dump($domicilio_usuario->getData());
////			die(__FILE__.__LINE__);
//			foreach($loaded_layout->getBlocks('form_edit') as $form_edit){
//				$form_edit
//					->setObjectToEdit($domicilio_mascota)
//					->setDomicilioUsuario($domicilio_usuario)
//				;
//			}
//			$location_selector = $loaded_layout
//				->getBlock('location_selector')
//				->setObjectToEdit($domicilio_mascota)
//			;
//		}
//		$this->_editar_handle_setActiveLeftMenu($paso, $id_mascota, $preserve_mascota_edicion);
	}
	protected function _editar_handle_setActiveLeftMenu(){
		$this->setActiveLeftMenu('mascotas_usuario_perdi_mi_mascota');
	}
	protected function _editar_handle_init_layout($paso=1, $id_mascota=null, $preserve_mascota_edicion=false){
		$this->setPageReference('Ofertar mascota', 'Publicar anuncio');
		$handle_type = !isset($id_mascota)||$id_mascota=='new'||!$id_mascota?'nueva':'existente';
		$handle = array(1=>'datos',2=>'domicilio',3=>'busqueda',4=>'publicacion');
		$handle = $handle[$paso];
		Core_App::getLayout()
			->setModo('saludmascotas')
			->addAction('mascota_perdida_addedit')
			->addAction('mascota_perdida_addedit_step_'.$paso)
			->addAction('mascota_perdida_addedit_' . $handle);
		;
		$this->showLeftMenu('usuario');	
	}
//	protected function editar($paso=1, $id_mascota=null, $preserve_mascota_edicion=false){
//		$return = $this->_pre_editar($paso, $id_mascota, $preserve_mascota_edicion);
//		if(isset($return))
//			return $return;
//		
//		$return = $this->_editar_handle_init_layout($paso, $id_mascota, $preserve_mascota_edicion);
//		if(isset($return))
//			return $return;
//		
//		//loaded layout
//		$return = $this->_editar_handle_load_layout($paso, $id_mascota, $preserve_mascota_edicion);
//		if(isset($return))
//			return $return;
//	}
}
?>
