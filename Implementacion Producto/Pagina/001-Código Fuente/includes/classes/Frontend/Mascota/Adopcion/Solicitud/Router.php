<?php /*es útf8*/
class Frontend_Mascota_Adopcion_Solicitud_Router extends Frontend_Mascota_Router{
	protected function initialize(){
		parent::initialize();
		$this->addActions(
			//'usuario'
			//,
			'agregar'
			,'editar'
			,'kradkk'
			,'kradkk_embeeder'
		);
	}
	protected function kradkk(){
		Core_App::getLayout()
			->setModo('saludmascotas')
			->setActions('simple_layout','kradkk')
		;
		$this->setPageReference('Prueba de mapa', '');
	}
	protected function kradkk_embeeder(){
		Core_App::getLayout()
			->setModo('saludmascotas')
			->addAction('kradkk_embeeder')
		;
	}
	protected function _allowStep($paso){
		return !($paso>4 || $paso<1);
	}
	protected function _pre_editar_init($paso=1, $id_mascota=null, $preserve_mascota_edicion=false){
		$return = parent::_pre_editar_init($paso, $id_mascota, $preserve_mascota_edicion);
		$object_to_edit = $this->getObjectToEdit();
		$object_to_edit->removeValidators('raza','id_manto','id_longitud_pelaje','tamano');
		$adopcion_solicitud = null;
		if(!$preserve_mascota_edicion || !($adopcion_solicitud = Frontend_Mascota_Adopcion_Solicitud_Helper::getAdopcionSolicitudEdicionFromSession($id_mascota))){
			if(in_array($paso, array(2,4))||!$preserve_mascota_edicion)
				$adopcion_solicitud = Frontend_Mascota_Adopcion_Solicitud_Helper::getAdopcionSolicitudEdicion($object_to_edit);
			//$adopcion_solicitud = $object_to_edit->getAdopcionSolicitud();
		}
		if(!$adopcion_solicitud)
			$adopcion_solicitud = new Frontend_Model_AdopcionSolicitud();

		if($preserve_mascota_edicion){
			$coincidencias_seleccionadas = Frontend_Mascota_Adopcion_Solicitud_Helper::getCoincidenciasSeleccionadasFromSession($id_mascota);
			if(!isset($coincidencias_seleccionadas)){
				$coincidencias_seleccionadas = $adopcion_solicitud->getIdsCoincidenciasSeleccionadas();
			}
		}
		else{
			$coincidencias_seleccionadas = $adopcion_solicitud->getIdsCoincidenciasSeleccionadas();
		}
		if(!isset($coincidencias_seleccionadas)){
			$coincidencias_seleccionadas = array();
		}
//		echo Core_Helper::DebugVars($adopcion_solicitud->getData());
//		die(__FILE__.__LINE__);

		switch($paso){
			case 1:{
				$this->getObjectToEdit()
					->setEstadoAdopcionSolicitud()
					->setEstadoNoAplica()//todo:aca va el estado en guarda o en vista default, aunque se deberia cambiar con los checks
				;
				if(!$object_to_edit->getNombre()){
					$object_to_edit->setNombre('sin nombre');
				}
				break;
			}
			case 3:{
				Core_App::getInstance()->setMascotaParam($object_to_edit);
				$adopcion_solicitud->commitNonTableColumn();
				//Frontend_Model_AdopcionSolicitud
				$coincidencias = $adopcion_solicitud->getCoincidencias();
				$this->setCoincidencias($coincidencias);
				break;
			}
			case 4:{
				//incluyo adopcion_ofertas seleccionadas actualmente y en otros registros
				$ids_coincidencias = $adopcion_solicitud->getIdsCoincidenciasSeleccionadas();
				if($ids_coincidencias)
					$ids_coincidencias = array_merge(array_diff($ids_coincidencias, $coincidencias_seleccionadas), $coincidencias_seleccionadas);
				else $ids_coincidencias = $coincidencias_seleccionadas;
				if($ids_coincidencias)
					$coincidencias = $adopcion_solicitud->getCoincidencias($ids_coincidencias);
				else $coincidencias = null;
//				header('content-type:text/plain');
//				var_dump($ids_coincidencias);
//				var_dump($coincidencias);
				$this->setCoincidencias($coincidencias);
//				die(__FILE__.__LINE__);
				break;
			}
		}
		
		$this->setAdopcionSolicitud($adopcion_solicitud);
		$this->setCoincidenciasSeleccionadas($coincidencias_seleccionadas);
		return $return;
	}
	protected function _editar_step_ok($paso=1, $id_mascota=null, $preserve_mascota_edicion=false){
		switch($paso){
			case 1:{//si el paso está bien cargado redirijo al paso siguiente
				$object_to_edit = $this->getObjectToEdit();
				Core_Http_Header::Redirect(Frontend_Mascota_Adopcion_Solicitud_Helper::getUrlEditar($object_to_edit->getId(), 1, 2), true);
				return true;
				break;
			}
			case 2:{//si el paso esta bien cargado (domicilio) checkeo la carga de fecha y hora de adopcion_solicitud y redirijo al paso siguiente
				Core_Http_Header::Redirect(Frontend_Mascota_Adopcion_Solicitud_Helper::getUrlEditar($id_mascota, 1, 3), true);
				return true;
				break;			
			}
			case 3:{//si las selecciones de mascotas coincidentes en la búsqueda estan correctas redirijo al paso siguiente
				$object_to_edit = $this->getObjectToEdit();
				Core_Http_Header::Redirect(Frontend_Mascota_Adopcion_Solicitud_Helper::getUrlEditar($object_to_edit->getId(), 1, 4), true);
				break;
			}
			case 4:{//si la carga de opciones y verificación final es correcta, guardo domicilio, mascota, adopcion_conciliacions y anuncio
				//Core_App::getInstance()->addErrorMessage('guardo domicilio, mascota, adopcion_conciliacions y anuncio '.__FILE__.__LINE__, true);
				
				$adopcion_solicitud = $this->getAdopcionSolicitud();
				$object_to_edit = $this->getObjectToEdit();
				//$id_mascota = $object_to_edit->getId();
				$object_to_edit->removeValidators('raza','id_manto','id_longitud_pelaje','tamano');
				$domicilio_mascota = $this->getDomicilioMascota();
				$domicilio_mascota_guardada = $this->getHelper()->actionAgregarEditarDomicilio($domicilio_mascota, false)?true:false;
				if($domicilio_mascota_guardada){
					$mascota_guardada = $this->getHelper()->actionAgregarEditarMascota($object_to_edit, false, $domicilio_mascota, false)?true:false;
					if($mascota_guardada){
						$id_mascota = $object_to_edit->getId();
						$guardado = Frontend_Mascota_Adopcion_Solicitud_Helper::actionAgregarEditarAdopcionSolicitud($adopcion_solicitud, false, $id_mascota, $domicilio_mascota)?true:false;
						if($guardado){//pasa validaciones
							$guardadas = Frontend_Mascota_Adopcion_Solicitud_Helper::crearAdopcionConciliacions($adopcion_solicitud, $this->getCoincidenciasSeleccionadas())?true:false;
							if($guardadas){
								$this->getHelper()->clearSessionVars();
								if($object_to_edit->hasAdopcionConciliacion())
									Core_Http_Header::Redirect($object_to_edit->getUrlAdopcionConciliacionesPendientes(), true);
								else
									Core_Http_Header::Redirect(Frontend_Mascota_Adopcion_Solicitud_Helper::getUrlQuieroAdoptar(), true);
								return true;
							}
						}
					}
				}
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
			case 1:{
				$object_to_edit = $this->getObjectToEdit();
				$post = Core_Http_Post::getParameters('Core_Object', $this->getHelper()->getUpdatableFields());
				$object_to_edit->loadFromArray($post->getData(), true, true);
				if($object_to_edit->getRaza(false)==''){
					$object_to_edit->setRaza('sin raza');
				}
				$guardado_en_sesion = $this->getHelper()->actionAgregarEditarMascota($object_to_edit, true, null, false)?true:false;
				if($guardado_en_sesion){
					return true;
				}
				break;
			}
			case 2:{
				$adopcion_solicitud = $this->getAdopcionSolicitud();
				$post = Core_Http_Post::getParameters('Core_Object', Frontend_Mascota_Adopcion_Solicitud_Helper::getUpdatableAdopcionSolicitudFields());
				$adopcion_solicitud->loadFromArray($post->getData(), false, true);
//				header('content-type:text/plain');
//				var_dump($adopcion_solicitud->getData());
//				die(__FILE__.__LINE__);
				$object_to_edit = $this->getObjectToEdit();
				$id_mascota = $object_to_edit->getId();
				$guardado_en_sesion = Frontend_Mascota_Adopcion_Solicitud_Helper::actionAgregarEditarAdopcionSolicitud($adopcion_solicitud, true, $id_mascota)?true:false;
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
				$guardado = Frontend_Mascota_Adopcion_Solicitud_Helper::setCoincidenciasSeleccionadasInSession($coincidencias_seleccionadas);
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
				$adopcion_solicitud = $this->getAdopcionSolicitud();
				$post = Core_Http_Post::getParameters('Core_Object', Frontend_Mascota_Adopcion_Solicitud_Helper::getUpdatableAdopcionSolicitudFields());
				$adopcion_solicitud->loadFromArray($post->getData(), false);
//				header('content-type:text/plain');
//				var_dump($adopcion_solicitud->getData());
//				die(__FILE__.__LINE__);
				$object_to_edit = $this->getObjectToEdit();
				$id_mascota = $object_to_edit->getId();
				$guardado_en_sesion = Frontend_Mascota_Adopcion_Solicitud_Helper::actionAgregarEditarAdopcionSolicitud($adopcion_solicitud, true, $id_mascota)?true:false;
				if($guardado_en_sesion){//pasa validaciones
//					if($post->getData('tiene_mascota')=='si')
//						$object_to_edit->setEstadoEnGuarda();
//					else
//						$object_to_edit->setEstadoVista();
					return true;
				}
			}
		}
		return $return;
	}
	protected function _pre_editar($paso=1, &$id_mascota=null, $preserve_mascota_edicion=false){
		return parent::_pre_editar($paso, $id_mascota, $preserve_mascota_edicion);
	}
	protected function _editar_handle_load_layout($paso=1, $id_mascota=null, $preserve_mascota_edicion=false){
		$return = parent::_editar_handle_load_layout($paso, $id_mascota, $preserve_mascota_edicion);
		if($paso==1){
			$loaded_layout = Core_App::getLoadedLayout();
			$loaded_layout->getBlock('main_form_edit')
				->setLabelNombre('Ingresa un nombre temporal o déjalo vacío')
				->setLabelEdad('Ingresa su edad o fecha de nacimiento estimada')
				->setShowOptionalFields(false)
				->setDontKnowOptions(true)
			;
		}
		if($paso==2){
			$loaded_layout = Core_App::getLoadedLayout();
			$adopcion_solicitud = $this->getAdopcionSolicitud();
//			$loaded_layout
//				->getBlock('addedit_fecha_adopcion_solicitud')
//				->setAdopcionSolicitud($adopcion_solicitud)
//				->setDontKnowOptions(true)
//			;
		}
		if($paso==3){
			$loaded_layout = Core_App::getLoadedLayout();
			$show_select_coincidencias = $loaded_layout->getBlock('show_select_coincidencias');
//			$coincidencias = $this->getCoincidencias();
//			foreach($coincidencias as $coincidencia){
//				var_dump(get_class($coincidencia));
//				var_dump($coincidencia->getData());
//			}
//			die(__FILE__.__LINE__);
			$show_select_coincidencias
				->setCoincidencias($this->getCoincidencias())
				->setCoincidenciasSeleccionadas($this->getCoincidenciasSeleccionadas())
			;
		}
		if($paso==4){
			$loaded_layout = Core_App::getLoadedLayout();
			$object_to_edit = $this->getObjectToEdit();
			$fotos_mascotas = $object_to_edit->getListFoto(true);//el true es para que permita pk nula
			$adopcion_solicitud = $this->getAdopcionSolicitud();
			$loaded_layout->getBlock('form_edit')->setObjectToEdit($object_to_edit);
			$view_datos_mascota = $loaded_layout
				->getBlock('view_datos_mascota')
				->setMascota($object_to_edit)
				->setPhotoList($fotos_mascotas)
				->addExtraData('Fecha AdopcionSolicitud', $adopcion_solicitud->getAdopcionSolicitudFecha())
			;
			$view_ubicacion = $loaded_layout
				->getBlock('view_ubicacion')
				->setDomicilio($this->getDomicilioMascota())
			;
			$view_posibles_adopcion_conciliacions = $loaded_layout
				->getBlock('view_posibles_adopcion_conciliacions')
				->setCoincidencias($this->getCoincidencias())
				->setCoincidenciasSeleccionadas($this->getCoincidenciasSeleccionadas())
				//->setAdopcionSolicitud(NULL)
			;
			$form_edit_publicacion = $loaded_layout
				->getBlock('form_edit_publicacion')
				->setAdopcionSolicitud($adopcion_solicitud)
			;
		}
		return $return;
	}
	protected function _editar_handle_setActiveLeftMenu(){
		$this->setActiveLeftMenu('mascotas_usuario_quiero_adoptar');
	}
	protected function _editar_handle_init_layout($paso=1, $id_mascota=null, $preserve_mascota_edicion=false){
		$this->setPageReference('Adoptar', 'Una mascota');
		$handle_type = !isset($id_mascota)||$id_mascota=='new'||!$id_mascota?'nueva':'existente';
		$handle = array(1=>'datos',2=>'domicilio',3=>'busqueda',4=>'publicacion');
		$handle = $handle[$paso];
		Core_App::getLayout()
			->setModo('saludmascotas')
			->addAction('mascota_adopcion_solicitud_addedit')
			->addAction('mascota_adopcion_solicitud_addedit_step_'.$paso)
			->addAction('mascota_adopcion_solicitud_addedit_' . $handle);
		;
		$this->showLeftMenu('usuario');	
	}
}
?>