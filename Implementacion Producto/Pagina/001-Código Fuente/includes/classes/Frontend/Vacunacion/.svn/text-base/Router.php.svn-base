<?php /*es útf8*/
class Frontend_Vacunacion_Router extends Frontend_Router_Abstract{
	protected function initialize(){
		parent::initialize();
		$this->addActions(
			'cronograma'
			,'agregar'
			,'editar'
			,'eliminar'
			,'consultar'
		);
	}

	protected function cronograma(){
		if($this->RedirectIfNotSpa())
			return true;
		if(Core_Http_Post::hasParameters()){
			$post = Core_Http_Post::getParameters('Core_Object');
			return $this->redirect($this->getHelper()->getUrlAgregarVacunacion($post->getFechaInicio(), $post->getFechaFin()));
//			var_dump($post->getData());
//			die(__FILE__.__LINE__);
//			$r = $this->asignar_handle_post($numero_pag);
//			if($r)
//				return;
		}
//		Core_App::getInstance()->setPagina();
		$this->setPageReference('Vacunaciones', 'cronograma');
		Core_App::getLayout()
			->setModo('saludmascotas')
			->addAction('vacunacion_cronograma')
		;
		$this->showLeftMenu('usuario');
		
		$vacunacion = new Frontend_Model_Vacunacion();
		$vacunacion->setWhere(	
			Db_Helper::equal('id_spa', $this->getLogedUser()->getId()),
			Db_Helper::equal('activo', 'si')
		);
		$vacunaciones = $vacunacion->search(null,'asc', null, 0, 'Frontend_Model_Vacunacion');
		
		//loaded layout
		Core_App::getLoadedLayout()
			->getBlock('vacunacion_cronograma_view')//$x = $this->getObjectToEdit();
			->setVacunaciones($vacunaciones)
		;
		$this->setActiveLeftMenu('mascotas_vacunacion_cronograma');
	}
	protected function agregar($fecha_inicio=null, $fecha_fin=null){
		if($this->RedirectIfNotSpa())
			return true;
//		var_dump($fecha_inicio, $fecha_fin);
//		die(__FILE__.__LINE__);
		$vacunacion = new Frontend_Model_Vacunacion();
		if($fecha_inicio){
			$vacunacion
				->setFechaInicio(Mysql_Helper::filterTimestampOutput($fecha_inicio))
				->setFechaFin(Mysql_Helper::filterTimestampOutput($fecha_fin))
			;
		}
		$domicilio = new Frontend_Model_Domicilio();
		
		if(Core_Http_Post::hasParameters()){
			if($this->agregar_handle_post($vacunacion, $domicilio)){
				return;
			}
		}
		
		$this->setPageReference('Vacunaciones', 'agregar');
		Core_App::getLayout()
			->setModo('saludmascotas')
			->addAction('vacunacion_agregar')
		;
		$this->showLeftMenu('usuario');
		
		//loaded layout
		Core_App::getLoadedLayout()
			->getBlock('form_edit_domicilio')//$x = $this->getObjectToEdit();
			->setObjectToEdit($domicilio)
		;
		Core_App::getLoadedLayout()
			->getBlock('location_selector')
			->setObjectToEdit($domicilio)
		;
		Core_App::getLoadedLayout()
			->getBlock('form_edit')//$x = $this->getObjectToEdit();
			->setObjectToEdit($vacunacion)
		;
		$this->setActiveLeftMenu('mascotas_vacunacion_cronograma');
	}
	protected function agregar_handle_post($vacunacion, $domicilio){
		//header('content-type:text/plain');
		$post = Core_Http_Post::getParameters('Core_Object', $this->getHelper()->getUpdatableFields());
		$vacunacion->loadFromArray($post->getData(), true, true);
		$domicilio->loadFromArray($post->getData(), true, true);
		$vacunacion->setFechaInicio(Mysql_Helper::filterDateInput($post->getFechaInicio()), array());
		$vacunacion->setFechaFin(Mysql_Helper::filterDateInput($post->getFechaFin()), array());
		
		//var_dump($post->getData(),$vacunacion->getFechaInicio(), $vacunacion->getData(), $domicilio->getData());die(__FILE__.__LINE__);
		$guardado = $this->getHelper()->actionAgregarEditarVacunacion($vacunacion, $domicilio)?true:false;
		if($guardado)
			$this->redirect($this->getHelper()->getUrlCronogramaVacunacion());
		
		return $guardado;
		//var_dump($vacunacion->getData(), $domicilio->getData());die(__FILE__.__LINE__);
	}
	protected function editar($id_vacunacion){
		if($this->RedirectIfNotSpa())
			return true;
//		var_dump($fecha_inicio, $fecha_fin);
//		die(__FILE__.__LINE__);
		$vacunacion = new Frontend_Model_Vacunacion();
		$vacunacion->setId($id_vacunacion);
		$loaded = $vacunacion->load();
		if(!$loaded){
			return $this->redirect($this->getHelper()->getUrlCronogramaVacunacion());
		}
//		var_dump($id_vacunacion, $vacunacion->getData());
//		die(__FILE__.__LINE__);
		$domicilio = $vacunacion->getDomicilio();
		$domicilio->loadNonTableColumn();
		
		if(Core_Http_Post::hasParameters()){
			if($this->agregar_handle_post($vacunacion, $domicilio)){
				return;
			}
		}
		
		$this->setPageReference('Vacunaciones', 'editar');
		Core_App::getLayout()
			->setModo('saludmascotas')
			->addAction('vacunacion_agregar')
		;
		$this->showLeftMenu('usuario');
		
		//loaded layout
		Core_App::getLoadedLayout()
			->getBlock('form_edit_domicilio')//$x = $this->getObjectToEdit();
			->setObjectToEdit($domicilio)
		;
//		var_dump(get_class(		Core_App::getLoadedLayout()
//			->getBlock('location_selector')), 		Core_App::getLoadedLayout()
//			->getBlock('location_selector')->getTemplate());
//			die();
		Core_App::getLoadedLayout()
			->getBlock('location_selector')
			->setObjectToEdit($domicilio)
		;
		Core_App::getLoadedLayout()
			->getBlock('form_edit')//$x = $this->getObjectToEdit();
			->setObjectToEdit($vacunacion)
			->addLink($vacunacion->getUrlEliminar(), 'Eliminar')
		;
		$this->setActiveLeftMenu('mascotas_vacunacion_cronograma');
	}
	protected function eliminar($id_vacunacion){
		if($this->RedirectIfNotSpa())
			return true;
//		die(__FILE__.__LINE__);
		$vacunacion = new Frontend_Model_Vacunacion();
		$vacunacion->setId($id_vacunacion);
		$loaded = $vacunacion->load();
		if($loaded){
			$this->getHelper()->actionEliminarVacunacion($vacunacion)?true:false;
		}
		return $this->redirect($this->getHelper()->getUrlCronogramaVacunacion());
	}
	protected function consultar(){
//		if($this->RedirectIfNotSpa())
//			return true;
//		if(Core_Http_Post::hasParameters()){
//			$post = Core_Http_Post::getParameters('Core_Object');
//			return $this->redirect($this->getHelper()->getUrlAgregarVacunacion($post->getFechaInicio(), $post->getFechaFin()));
////			var_dump($post->getData());
////			die(__FILE__.__LINE__);
////			$r = $this->asignar_handle_post($numero_pag);
////			if($r)
////				return;
//		}
		//$this->setPageReference('Vacunaciones', 'consulta');
		Core_App::getLayout()
			->setModo('saludmascotas')
			->addAction('vacunacion_consultar')
		;
		$this->showLeftMenu('dashboard');
		
		$vacunacion = new Frontend_Model_Vacunacion();
		$vacunacion->setWhere(	
			Db_Helper::custom('date(fecha_inicio)>=date(now())')
//			Db_Helper::equal('id_spa', $this->getLogedUser()->getId()),
//			Db_Helper::equal('activo', 'si')
		);
		$vacunaciones = $vacunacion->search(null,'asc', null, 0, 'Frontend_Model_Vacunacion');
//		var_dump($vacunaciones);
//		die(__FILE__.__LINE__);
		//loaded layout
		
		Core_App::getLoadedLayout()
			->getBlock('map_swicher')//$x = $this->getObjectToEdit();
			->setVacunaciones($vacunaciones)
		;
		if($vacunaciones){
			$vacunacion = $vacunaciones[0];
			$domicilio = $vacunacion->getDomicilio();
			Core_App::getLoadedLayout()
				->getBlock('view_ubicacion')
				->setDomicilio($domicilio)
			;
		}
		$this->setActiveLeftMenu('dashboard_vacunacion_consulta');
	}
}
?>