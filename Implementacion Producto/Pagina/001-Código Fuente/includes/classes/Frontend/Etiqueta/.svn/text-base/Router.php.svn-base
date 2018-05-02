<?php /*es útf8*/
class Frontend_Etiqueta_Router extends Frontend_Router_Abstract{
	protected function initialize(){
		parent::initialize();
		$this->addActions(
			'change_etiqueta_mascota'
			,'agregar_etiqueta_mascota'
			,'eliminar_etiqueta'
		);
	}
	protected function eliminar_etiqueta($id_etiqueta){
		$etiqueta = new Frontend_Model_Etiqueta();
		$etiqueta->setId($id_etiqueta);
		$usuario = $this->getLogedUser();
		$return_to = Core_Http_Header::getRequest('referer');
		if($etiqueta->load()&&$usuario->getId()==$etiqueta->getIdUsuario()&&$etiqueta->delete()){
			
		}
		else{
			Core_App::getInstance()->addErrorMessage(self::getInstance()->__t("No se pudo eliminar la etiqueta"));
		}
		return Core_Http_Header::Redirect($return_to);
		
	}
	protected function change_etiqueta_mascota(){
		if(!Core_Http_Post::hasParameters()){
			die(__FILE__.__LINE__);
		}
		$post = Core_Http_Post::getParameters('Core_Object');
		$id_mascota = $post->getIdMascota();
		$id_etiqueta = $post->getIdEtiqueta();
		if(!$id_mascota||!$id_etiqueta){
			die(__FILE__.__LINE__);
		}
		$id_mascota = $post->getIdMascota();
		$id_etiqueta = $post->getIdEtiqueta();
		$res = $this->getHelper()->actionChangeEtiquetaMascota($id_etiqueta, $id_mascota);
		var_dump($res);
		die();
	}
	protected function agregar_etiqueta_mascota($id_mascota){
		if($this->RedirectIfNotLoged())
			return true;
		$mascota = new Frontend_Model_Mascota();
		$mascota->setId($id_mascota);
		$usuario = $this->getLogedUser();
//		var_dump($id_mascota);
//		die(__FILE__.__LINE__);
		$return_to = Core_Http_Header::getRequest('referer');
		if(!$mascota->load()||$mascota->getIdDueno()!=$usuario->getId()){
			return Core_Http_Header::Redirect($return_to);
		}
		if(Core_Http_Post::hasParameters()){
			$post = Core_Http_Post::getParameters('Core_Object');
			$return_to = $post->getReturnTo();
//			return $this->redirect($this->getHelper()->getUrlAgregarVacunacion($post->getFechaInicio(), $post->getFechaFin()));
////			var_dump($post->getData());
////			die(__FILE__.__LINE__);
			$r = $this->agregar_etiqueta_mascota_handle_post($mascota);
			if($r)
				return Core_Http_Header::Redirect($return_to);
		}
		Core_App::getInstance()->setIdsPrioridadesBarrios($this->getHelper()->getIdsPrioridadesBarrios());
//		Core_App::getInstance()->setPagina();
		$this->setPageReference('Etiqueta', 'agregar');
		Core_App::getLayout()
			->setModo('saludmascotas')
			->addAction('etiqueta_mascota_agregar')
		;
		$this->showLeftMenu('usuario');
		
//		$vacunacion = new Frontend_Model_Vacunacion();
//		$vacunacion->setWhere(	
//			Db_Helper::equal('id_spa', $this->getLogedUser()->getId()),
//			Db_Helper::equal('activo', 'si')
//		);
//		$vacunaciones = $vacunacion->search(null,'asc', null, 0, 'Frontend_Model_Vacunacion');
		
		//loaded layout
		Core_App::getLoadedLayout()
			->getBlock('etiqueta_mascota_agregar')//$x = $this->getObjectToEdit();
			->setReturnTo($return_to)
		;
		Core_App::getLoadedLayout()
			->getBlock('view_datos_mascota')//$x = $this->getObjectToEdit();
			->setMascota($mascota)
//			->setIdsBarrios(Core_App::getInstance()->getIdsPrioridadesBarrios())
//			->setUrlAction($this->getHelper()->getUrlRegistrarVisita())
		;
		$this->setActiveLeftMenu('mascotas_usuario_mis_mascotas');
	}
	private function agregar_etiqueta_mascota_handle_post($mascota){
		$post = Core_Http_Post::getParameters('Core_Object');
		return $this->getHelper()->actionAgregarEtiquetaMascota($mascota, $post->getNombre(), $post->getIdParent());
	}
}
?>