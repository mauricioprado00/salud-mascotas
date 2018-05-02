<?
class Core_Router_Main extends Core_Router_Abstract{
//	public function onThrought(){
////		Core_App::getLayout()
////			->setModo('frontend')
////			->addDesignPaths('frontend', 'frontendv3/default/');
////		if(Granguia_User_Model_User::getLogedUser())
////			Core_App::getLayout()->addActions('loged_user');
//	}
	protected function onThrought(){
		$usuario = new Frontend_Usuario_Model_User();
		if($usuario->isLoged()){
			Core_App::getLayout()->addActions('usuario_logeado');
		}
		else{
			Core_App::getLayout()->addActions('usuario_no_logeado');
		}
		
		return(null);
	}
	protected function initialize(){
//		Core_App::getLayout()
//			->setModo(Core_Http_Header::isAjaxRequest()&&!Core_Http_Header::getRequest('EMULATENOAJAX')?'ajax_mode':'granguia')
//			->addDesignPaths('granguia', 'granguia/default/')
//			->addDesignPaths('simple', 'granguia/default/')
//			->addDesignPaths('ajax_mode', 'granguia/default/');
			
		//var_dump();
		//$route_data = Core_App::getInstance()->getConfig()->getRouteData();
		//$this->setRouteData($route_data);
		//$this->addActions('skin','contacto');
		//$this->addRouter('admin','Router.Admin');
	}
//	protected function contacto(){
//		Granguia_Helper::contactoCliente();
//	}
	protected function localDispatch(){//esto es la HOME
		$controller = new Frontend_Router();
		return $controller->route($this->request_path);//redirecciono a frontend_router()
	}
	protected function dispatchNode(){
		$controller = new Frontend_Router();
		return $controller->route($this->request_path);//redirecciono a frontend_router()
		
		/*<estadisticas>*/
//		Granguia_Model_Contador::ContarAccesoSesion(Core_Server::getRemoteAddr(), $this->request_path);
//		if(Core_Session::getInstance()->getVarMulticontext('session_contada',array('contador_estadisticas'))==null){
//			Core_Session::getInstance()->setVarMulticontext('session_contada','1',array('contador_estadisticas'));
//			Granguia_Model_Contador::ContarInicioSesion(Core_Server::getRemoteAddr());	
//		}
//		if(in_array('barrio',Core_App::getLayout()->getActions())){
//			$id_barrio = null;
//			if($barrio = Core_App::getInstance()->getBarrio())
//				$id_barrio = $barrio->getId();
//			$id_categoria = null;
//			if($categoria = Core_App::getInstance()->getCategoria())
//				$id_categoria = $categoria->getId();
//			Granguia_Model_Contador::ContarAccesoCategoria($id_categoria, $id_barrio);
//		}
		/*</estadisticas>*/
		
//		$nodo = Core_App::getInstance()->getNodo();//el nodo puede cambiar de generico a específico en el router
//		$head = Core_App::getLoadedLayout()->getBlock('html_head');
//		if($head){
//			$titulo = $nodo->getMetaTitle($head->getTitle());
//			$favicon = Granguia_Model_Config::findConfigValue('pagina/file_favicon');
//			if(isset($favicon)){
//				$head->setFavicon($favicon);
//			}
//			if($categoria = Core_App::getInstance()->getCategoria()){
//				$titulo = $categoria->getNodo()->getMetaTitle($titulo);
//			}
//			$head->setTitle($titulo);
//			$meta_description = Core_App::getLayout()->appendBlock(utf8_decode('<meta html_name="Description" before="" />'),$head);
//			$meta_description
//				->setHtmlContent($nodo->getMetaDescription('Guía de comercios de la ciudad de Córdoba'))
//			;
//			$meta_description = Core_App::getLayout()->appendBlock(utf8_decode('<meta html_name="keywords" before="" />'),$head);
//			if($categoria){
//				$meta_description
//					->setHtmlContent($categoria->getNodo()->getMetaKeywords('ubicaciones de comercios, mapa comercios, comercios córdoba, listado comercios'))
//				;
//			}
//			else{
//				$meta_description
//					->setHtmlContent($nodo->getMetaKeywords(''))
//				;
//			}
//		}
		return true;
	}
}
?>