<?php /*es útf8*/
class Frontend_Notificacion_Router extends Frontend_Router_Abstract{
	protected function initialize(){
		parent::initialize();
		$this->addActions(
			//'usuario'
			//,
			'listado',
			'view'
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
}
?>