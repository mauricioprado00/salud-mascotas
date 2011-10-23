<?php /*es útf8*/
class Frontend_Notificacion_Router extends Frontend_Router_Abstract{
	protected function initialize(){
		parent::initialize();
		$this->addActions(
			//'usuario'
			//,
			'listado'
			,'view'
			,'eliminar'
		);
	}
	protected function localDispatch(){
		return $this->listado();
		
		return true;
	}
	protected function listado($pagina=null){
		if($this->RedirectIfNotLoged())
			return true;
		Core_App::getInstance()->setPagina($pagina);
		$this->setPageReference('Notificaciones', 'Mantente informado');
		Core_App::getLayout()
			->setModo('saludmascotas')
			->addAction('usuario_notificaciones_listado')
		;
		
		$this->showLeftMenu('usuario');
		
		
		//loaded layout
		
		$this->setActiveLeftMenu('user_notificaciones');
	}
	protected function view($id_notificacion){
		if($this->RedirectIfNotLoged())
			return true;
		$notificacion = new Frontend_Model_Notificacion();
		$notificacion->setId($id_notificacion);
		$usuario = $this->getLogedUser();
		if(!$notificacion->load() || $notificacion->getIdUsuarioTo()!=$usuario->getId()){
			Core_Http_Header::Redirect($this->getHelper()->getUrlListado(), true);
			return true;
		}
		$this->getHelper()->actionNotificacionLeida($notificacion);
//		$ret = $notificacion->update(array('leida','si'));
//		var_dump($ret);
//		die(__FILE__.__LINE__);
		Core_App::getInstance()->setPagina($pagina);
		$this->setPageReference('Notificaciones', 'Mantente informado');
		Core_App::getLayout()
			->setModo('saludmascotas')
			->addAction('usuario_notificaciones_view')
		;
		
		$this->showLeftMenu('usuario');
		
		
		//loaded layout
		$loaded_layout = Core_App::getLoadedLayout();
		$notificacion_view = $loaded_layout->getBlock('notificacion_view');
		$notificacion_view->setNotificacion($notificacion);
		$this->setActiveLeftMenu('user_notificaciones');
	}
	protected function eliminar($id_notificacion, $confirmar=false){
		if($this->RedirectIfNotLoged())
			return true;
		$notificacion = new Frontend_Model_Notificacion();
		$notificacion->setId($id_notificacion);
		$usuario = $this->getLogedUser();
		$return_to = Core_Http_Header::getRequest('referer');
		if(!$notificacion->load() || $notificacion->getIdUsuarioTo()!=$usuario->getId()){
			Core_Http_Header::Redirect($this->getHelper()->getUrlListado(), true);
			return true;
		}
		if(Core_Http_Post::hasParameters()&&$confirmar){
			//die(__FILE__.__LINE__);
			$post = Core_Http_Post::getParameters('Core_Object');
			$return_to = $post->getReturnTo();
			$r = $this->eliminar_handle_post($notificacion, $post);
			if($r)
				return Core_Http_Header::Redirect($return_to);
		}
		Core_App::getInstance()->setPagina($pagina);
		$this->setPageReference('Notificaciones', 'Eliminar');
		Core_App::getLayout()
			->setModo('saludmascotas')
			->addAction('usuario_notificaciones_view')
			->addAction('usuario_notificaciones_eliminar')
		;
		
		$this->showLeftMenu('usuario');
		
		
		//loaded layout
		$loaded_layout = Core_App::getLoadedLayout();
		$notificacion_view = $loaded_layout->getBlock('notificacion_view');
		$notificacion_view->setNotificacion($notificacion);
		$loaded_layout->getBlock('form_eliminar_notificacion')
			->setReturnTo($return_to)
		;
		$this->setActiveLeftMenu('user_notificaciones');
	}
	private function eliminar_handle_post($notificacion, $post){
		return $this->getHelper()->actionEliminarNotificacion($notificacion);
	}
}
?>