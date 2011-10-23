<?php /*es útf8*/
class Frontend_Patrullaje_Router extends Frontend_Router_Abstract{
	protected function initialize(){
		parent::initialize();
		$this->addActions(
			'regenerar_prioridades'
			,'prioridades'
			,'registrar_visita'
			,'configurar_prioridades'
		);
	}
	protected function regenerar_prioridades(){
		$this->getHelper()->regenerarPrioridades();
		$this->redirect($this->getHelper()->getUrlPrioridadesPatrullaje(), true);
		return true;
	}
	protected function prioridades($numero_pag=0){
		if($this->RedirectIfNotSpa())
			return true;
		Core_App::getInstance()->setPagina($numero_pag);
//		if(Core_Http_Post::hasParameters()){
//			$post = Core_Http_Post::getParameters('Core_Object');
//			return $this->redirect($this->getHelper()->getUrlAgregarVacunacion($post->getFechaInicio(), $post->getFechaFin()));
////			var_dump($post->getData());
////			die(__FILE__.__LINE__);
////			$r = $this->asignar_handle_post($numero_pag);
////			if($r)
////				return;
//		}
		Core_App::getInstance()->setIdsPrioridadesBarrios($this->getHelper()->getIdsPrioridadesBarrios());
//		Core_App::getInstance()->setPagina();
		$this->setPageReference('Patrullaje', 'prioridades');
		Core_App::getLayout()
			->setModo('saludmascotas')
			->addAction('patrullaje_prioridades')
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
			->getBlock('patrullaje_prioridades_list')//$x = $this->getObjectToEdit();
			->setIdsBarrios(Core_App::getInstance()->getIdsPrioridadesBarrios())
			->setUrlAction($this->getHelper()->getUrlRegistrarVisita())
		;
		$this->setActiveLeftMenu('patrullaje_prioridades');
	}
	public function registrar_visita(){
		if($this->RedirectIfNotSpa())
			return true;
		if(!Core_Http_Post::hasParameters()){
			return $this->redirect($this->getHelper()->getUrlPrioridadesPatrullaje());
		}
			
		$post = Core_Http_Post::getParameters('Core_Object');
		if(!count($post->getSelectorBarrio())){
			return $this->redirect($this->getHelper()->getUrlPrioridadesPatrullaje());
		}
		$ids_barrios = $post->getSelectorBarrio();
//		var_dump($post->getSelectorBarrio());
//		die(__FILE__.__LINE__);
		if($post->hasFecha()){
			$post = Core_Http_Post::getParameters('Core_Object');
			if($this->registrar_visita_handle_post())
				return true;
//			var_dump($post->getData());
//			die(__FILE__.__LINE__);
//			$r = $this->asignar_handle_post($numero_pag);
//			if($r)
//				return;
		}
//		Core_App::getInstance()->setIdsPrioridadesBarrios($this->getHelper()->getIdsPrioridadesBarrios());
//		Core_App::getInstance()->setPagina();
		$this->setPageReference('Patrullaje', 'recorrido');
		Core_App::getLayout()
			->setModo('saludmascotas')
			->addAction('patrullaje_recorrido')
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
			->getBlock('patrullaje_prioridades_list')//$x = $this->getObjectToEdit();
			->setUrlAction($this->getHelper()->getUrlRegistrarVisita())
			->setIdsBarrios($ids_barrios)
		;
		$this->setActiveLeftMenu('patrullaje_prioridades');
	}
	protected function registrar_visita_handle_post(){
		//header('content-type:text/plain');
		$post = Core_Http_Post::getParameters('Core_Object', $this->getHelper()->getUpdatableFields());
		$patrullaje = new Frontend_Model_Patrullaje();
		$patrullaje->loadFromArray($post->getData(), true, true);
		$ids_barrios = $post->getSelectorBarrio();
//		var_dump($post->getData(), $ids_barrios);
//		die(__FILE__.__LINE__);
		//var_dump($post->getData(),$vacunacion->getFechaInicio(), $vacunacion->getData(), $domicilio->getData());die(__FILE__.__LINE__);
		$guardado = $this->getHelper()->actionAgregarPatrullaje($patrullaje, $ids_barrios)?true:false;
		if($guardado)
			$this->redirect($this->getHelper()->getUrlRegenerarPrioridadesPatrullaje());
		return $guardado;
		//var_dump($vacunacion->getData(), $domicilio->getData());die(__FILE__.__LINE__);
	}
	protected function configurar_prioridades(){
		if($this->RedirectIfNotSpa())
			return true;
		$configuraciones = Saludmascotas_Model_Patrullaje::getConfiguraciones();
//		var_dump($configuraciones);
//		die(__FILE__.__LINE__);
		if(Core_Http_Post::hasParameters()){
//			$post = Core_Http_Post::getParameters('Core_Object');
//			return $this->redirect($this->getHelper()->getUrlAgregarVacunacion($post->getFechaInicio(), $post->getFechaFin()));
//			var_dump($post->getData());
//			die(__FILE__.__LINE__);
			$r = $this->configurar_prioridades_post($configuraciones);
			if($r)
				return $this->redirect($this->getHelper()->getUrlRegenerarPrioridadesPatrullaje());
		}
		Core_App::getInstance()->setIdsPrioridadesBarrios($this->getHelper()->getIdsPrioridadesBarrios());
//		Core_App::getInstance()->setPagina();
		$this->setPageReference('Patrullaje', 'configurar');
		Core_App::getLayout()
			->setModo('saludmascotas')
			->addAction('patrullaje_configurar_prioridades')
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
			->getBlock('patrullaje_configurar')//$x = $this->getObjectToEdit();
			->setConfiguraciones($configuraciones)
//			->setIdsBarrios(Core_App::getInstance()->getIdsPrioridadesBarrios())
//			->setUrlAction($this->getHelper()->getUrlRegistrarVisita())
		;
		$this->setActiveLeftMenu('patrullaje_prioridades');
	}
	private function configurar_prioridades_post($configuraciones){
		$post = Core_Http_Post::getParameters('Core_Object');
//		var_dump($post->getData());
//		die(__FILE__.__LINE__);
		return $this->getHelper()->actionGuardarConfiguraciones($configuraciones, $post);
	}
}
?>