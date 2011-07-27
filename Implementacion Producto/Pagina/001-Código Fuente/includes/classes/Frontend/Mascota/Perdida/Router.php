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
		return parent::_pre_editar_init($paso, $id_mascota, $preserve_mascota_edicion);
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
	protected function _pre_editar_handle_post($paso=1, $id_mascota=null, $preserve_mascota_edicion=false){
		return parent::_pre_editar_handle_post($paso, $id_mascota, $preserve_mascota_edicion);
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
		return parent::_editar_handle_load_layout($paso, $id_mascota, $preserve_mascota_edicion);
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
		$this->setPageReference('Perdí mi mascota', 'Publicar anuncio');
		$handle_type = !isset($id_mascota)||$id_mascota=='new'||!$id_mascota?'nueva':'existente';
		$handle = array(1=>'datos',2=>'domicilio',3=>'busqueda',4=>'publicacion');
		$handle = $handle[$paso];
		Core_App::getLayout()
			->setModo('saludmascotas')
			->addAction('mascota_addedit')
			->addAction('mascota_addedit_' . $handle);
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