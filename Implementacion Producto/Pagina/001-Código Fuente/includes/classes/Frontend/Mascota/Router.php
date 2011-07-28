<?php /*es útf8*/
class Frontend_Mascota_Router extends Frontend_Router_Abstract{
	protected function initialize(){
		parent::initialize();
		$this->addActions(
			'usuario'
			,'agregar'
			,'editar'
			,'upload_foto'
			,'upload_photo'
			,'ajax_upload_photo'
		);
	}
	protected function localDispatch(){
		echo 'home mascotas';
		die(__FILE__.__LINE__);
		//esta es la home
//		Core_App::getLayout()
//			->setModo('saludmascotas_legacy')
//			->addAction('home')
//		;
		//var_dump(c(new Frontend_Mascota_Model_User())->isLoged(), Frontend_Mascota_Model_User::getLogedUser()?true:false);
		if(Frontend_Mascota_Model_User::getLogedUser()){
			return $this->update();
		}
		return $this->login();
//		return true;
	}
	protected function usuario($numero_pag=0){
		if($this->RedirectIfNotLoged())
			return true;
		Core_App::getInstance()->setPagina($numero_pag);
		$this->setPageReference('Mis mascotas', 'Revistar mascotas');
		Core_App::getLayout()
			->setModo('saludmascotas')
			->addAction('mascota_usuario_listado')
		;
		$this->showLeftMenu('usuario');
		
		//loaded layout
//		Core_App::getLoadedLayout()
//			->getBlock('form_edit')//$x = $this->getObjectToEdit();
//			->setObjectToEdit($object_to_edit)
//		;
		$this->setActiveLeftMenu('mascotas_usuario');
	}
	protected function agregar($paso=1, $preserve_mascota_edicion=false){
		return $this->editar($paso, 'new', $preserve_mascota_edicion);
	}
	protected function _allowStep($paso){
		return !($paso>2 || $paso<1);
	}
	protected function _pre_editar_init($paso=1, $id_mascota=null, $preserve_mascota_edicion=false){
		$object_to_edit = null;
		if(!$preserve_mascota_edicion){
			$this->getHelper()->clearSessionVars();
		}
		if(!$preserve_mascota_edicion || !($object_to_edit = $this->getHelper()->getMascotaEdicionFromSession($id_mascota))){
			if(isset($id_mascota)){
				$object_to_edit = $this->getHelper()->getMascotaEdicion($id_mascota);
				if(!$object_to_edit){
					Core_Http_Header::Redirect($this->getHelper()->getUrlUsuario(), true);
					return false;
				}
			}
			else{
				$object_to_edit = new Frontend_Model_Mascota();
			}
		}
		
		$domicilio_mascota = null;
		if(!$preserve_mascota_edicion || !($domicilio_mascota = $this->getHelper()->getDomicilioMascotaEdicionFromSession($id_mascota))){
			if($paso==2)
				$domicilio_mascota = $this->getHelper()->getDomicilioEdicion($object_to_edit);
			//$domicilio_mascota = $object_to_edit->getDomicilio();
		}
		if(!$domicilio_mascota)
			$domicilio_mascota = new Frontend_Model_Domicilio();
//		echo Core_Helper::DebugVars($object_to_edit->getData(),$domicilio_mascota->getData());
//		die(__FILE__.__LINE__);
		switch($paso){
			case 1:{
				$object_to_edit->setEstadoConDueno();
				break;
			}
		}
		$this->setObjectToEdit($object_to_edit);
		$this->setDomicilioMascota($domicilio_mascota);
	}
	protected function _editar_step_ok($paso=1, $id_mascota=null, $preserve_mascota_edicion=false){
		switch($paso){
			case 1:{//si el paso está bien cargado redirijo al paso siguiente
				$object_to_edit = $this->getObjectToEdit();
				Core_Http_Header::Redirect($this->getHelper()->getUrlEditar($object_to_edit->getId(), 1, 2), true);
				return true;
				break;
			}
			case 2:{//si el paso esta bien cargado, guardo domicilio y mascota
				$object_to_edit = $this->getObjectToEdit();
				$domicilio_mascota = $this->getDomicilioMascota();
				$domicilio_mascota_guardada = $this->getHelper()->actionAgregarEditarDomicilio($domicilio_mascota, false)?true:false;
				if($domicilio_mascota_guardada){
					$mascota_guardada = $this->getHelper()->actionAgregarEditarMascota($object_to_edit, false, $domicilio_mascota)?true:false;
					if($mascota_guardada){
						$this->getHelper()->clearSessionVars();
						Core_Http_Header::Redirect($this->getHelper()->getUrlUsuario(), true);
						return true;
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
		$object_to_edit = $this->getObjectToEdit();
		$domicilio_mascota = $this->getDomicilioMascota();
		if($paso==1){
			$post = Core_Http_Post::getParameters('Core_Object', $this->getHelper()->getUpdatableFields());
			$object_to_edit->loadFromArray($post->getData(), true, true);
//			header('content-type:text/plain');
//			var_dump($object_to_edit->getData(), $post->getData());
//			die(__FILE__.__LINE__);				//$object_to_edit->setPassword2($post->getPassword2());
			$guardado_en_sesion = $this->getHelper()->actionAgregarEditarMascota($object_to_edit, true)?true:false;
			if($guardado_en_sesion){
//				echo Core_Helper::DebugVars($object_to_edit->getId(), 1, 2);
//				var_dump($this->getHelper()->getUrlEditar($object_to_edit->getId(), 1, 2));
//				die(__FILE__.__LINE__);
				return true;
//				$return = $this->_editar_step_ok($paso, $id_mascota, $preserve_mascota_edicion);
//				if(isset($return))
//					return $return;
				//Core_Http_Header::Redirect($this->getHelper()->getUrlEditar($object_to_edit->getId(), 1, 2), true);
				//return true;
//				echo "mascota guardada en sesión";
//				die(__FILE__.__LINE__);
//				//Core_Http_Header::Redirect(Frontend_Usuario_Helper::getUrlLogin($object_to_edit), true);
//				return true;
			}
		}
		elseif($paso==2){
			$post = Core_Http_Post::getParameters('Core_Object', $this->getHelper()->getUpdatableDomicilioFields());
			$domicilio_mascota->loadFromArray($post->getData(), false);
//			header('content-type:text/plain');
//			var_dump($domicilio_mascota->getData());
//			die(__FILE__.__LINE__);
			$guardado_en_sesion = $this->getHelper()->actionAgregarEditarDomicilio($domicilio_mascota, true)?true:false;
			if($guardado_en_sesion){//pasa validaciones
				return true;
//				$return = $this->_editar_step_ok($paso, $id_mascota, $preserve_mascota_edicion);
//				if(isset($return))
//					return $return;
			}
		}
//		$object_to_edit = $this->getObjectToEdit();
//		$domicilio_mascota = $this->getDomicilioMascota();
	}
	protected function _pre_editar($paso=1, &$id_mascota=null, $preserve_mascota_edicion=false){
		if($this->RedirectIfNotLoged())
			return true;
		if(!$this->_allowStep($paso)){
			Core_Http_Header::Redirect($this->getHelper()->getUrlEditar($id_mascota, 1, 1), true);
			return true;
		}
		$id_mascota = $id_mascota=='new'?null:$id_mascota;
		
		$return = $this->_pre_editar_init($paso, $id_mascota, $preserve_mascota_edicion);
		if(isset($return)){
			return $return;
		}
		if(Core_Http_Post::hasParameters()){
			$handled = $this->_pre_editar_handle_post($paso, $id_mascota, $preserve_mascota_edicion);
//			var_dump($handled);
//			die(__FILE__.__LINE__);
			if(isset($handled)&&$handled){
				$return = $this->_editar_step_ok($paso, $id_mascota, $preserve_mascota_edicion);
				if(isset($return))
					return $return;
				//return $return;
			}
		}
	}
	protected function _editar_handle_load_layout($paso=1, $id_mascota=null, $preserve_mascota_edicion=false){
		$object_to_edit = $this->getObjectToEdit();
		$domicilio_mascota = $this->getDomicilioMascota();
		
		$loaded_layout = Core_App::getLoadedLayout();
		if($paso==1){
			$form_edit = $loaded_layout->getBlock('form_edit');
			$form_edit
				->setObjectToEdit($object_to_edit)
			;
			$fotos_addedit = $loaded_layout->getBlock('fotos_addedit');
			$usuario = $this->getLogedUser();
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
			$fotos_mascotas = $object_to_edit->getListFoto(true);//el true es para que permita pk nula
//			var_dump(count($fotos_mascotas));
//			die(__FILE__.__LINE__);
			$fotos_addedit
				->setUrlPhoto($this->getHelper()->getUrlPhoto())
				->setIdMascota($object_to_edit->getId())
				->setPhotoList($fotos_mascotas)
			;
		}
		elseif($paso==2){
			$domicilio_usuario = $this->getHelper()->getDomicilioUsuario(true);
//			header('content-type:text/plain');
//			var_dump($domicilio_usuario->getData());
//			die(__FILE__.__LINE__);
			foreach($loaded_layout->getBlocks('form_edit') as $form_edit){
				//var_dump(get_class($form_edit), $form_edit->getTemplate());
				$form_edit
					->setObjectToEdit($domicilio_mascota)
					->setDomicilioUsuario($domicilio_usuario)
				;
			}
//			die(__FILE__.__LINE__);
			$location_selector = $loaded_layout
				->getBlock('location_selector')
				->setObjectToEdit($domicilio_mascota)
			;
		}
		$this->_editar_handle_setActiveLeftMenu($paso, $id_mascota, $preserve_mascota_edicion);
	}
	protected function _editar_handle_setActiveLeftMenu(){
		$this->setActiveLeftMenu('mascotas_usuario');
	}
	protected function _editar_handle_init_layout($paso=1, $id_mascota=null, $preserve_mascota_edicion=false){
		$this->setPageReference('Registrar', 'Mi mascota');
		$handle_type = !isset($id_mascota)||$id_mascota=='new'||!$id_mascota?'nueva':'existente';
		$handle = array(1=>'datos',2=>'domicilio');
		$handle = $handle[$paso];
		Core_App::getLayout()
			->setModo('saludmascotas')
			->addAction('mascota_addedit')
			->addAction('mascota_addedit_' . $handle);
		;
		$this->showLeftMenu('usuario');	
	}
	protected function editar($paso=1, $id_mascota=null, $preserve_mascota_edicion=false){
		$return = $this->_pre_editar($paso, $id_mascota, $preserve_mascota_edicion);
		if(isset($return))
			return $return;
		
		$return = $this->_editar_handle_init_layout($paso, $id_mascota, $preserve_mascota_edicion);
		if(isset($return))
			return $return;
		
		//loaded layout
		$return = $this->_editar_handle_load_layout($paso, $id_mascota, $preserve_mascota_edicion);
		if(isset($return))
			return $return;
	}
	protected function dispatchNode($nodo){//esto es cuando hay algo despues de la url.
		echo 'nodo mascotas';
		die(__FILE__.__LINE__);
		$nodo = trim($nodo);
		$nodo = strtolower($nodo);
		switch($nodo){
//			case 'privacy-policy':{
//				$this->setPageReference('Políticas de Privacidad', 'Informate sobre las precauciones a tu privacidad');
//				Core_App::getLayout()
//					->setModo('saludmascotas')
//					->addActions('privacy_policy')
//				;
//				break;
//			}
//			case 'service-conditions':{
//				$this->setPageReference('Condiciones del Servicio', 'Informate sobre nuestro servicio');
//				Core_App::getLayout()
//					->setModo('saludmascotas')
//					->addActions('service_conditions')
//				;
//				break;
//			}
			default:{
				return false;
				break;
			}
			case '':{
				break;
			}
		}
		return true;
	}
}
?>