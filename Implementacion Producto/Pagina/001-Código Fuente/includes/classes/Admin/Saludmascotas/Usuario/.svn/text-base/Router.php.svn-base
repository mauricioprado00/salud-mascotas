<?
class Admin_Saludmascotas_Usuario_Router extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addRouter('admin','Router.Admin');
		$this->addActions(
			'cerrar_sesion',
			'addEdit','delete','listar','datalist',
			'ordenar','setorden'
		);
	}
	protected function onThrought(){
		Core_App::getLayout()->addActions('modulo','modulo_admin_usuario');
	}
	private function cambiarUrlAjax($link_url){
		$helper_url_ajax = Core_App::getLoadedLayout()
				->getBlock('helper_url_ajax');
		if($helper_url_ajax){
			$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl($link_url));
		}
	}
	protected function delete($id_usuario=null){
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Saludmascotas_Model_User()), 'd');
		Core_App::getInstance()->clearLastErrorMessages();
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido borrar Usuario.');
		}
		else{
			if(isset($id_usuario)){
				Admin_Saludmascotas_Usuario_Helper::actionEliminarUsuario($id_usuario);
			}
		}
		$this->listar();
	}
	protected function addEdit($id_usuario=null){
		Core_App::getInstance()->clearLastErrorMessages();
		$guardado = false;
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Saludmascotas_Model_User()), 'w');
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido editar Usuario.');
			//$mensajes[] = 'No tiene permitido editar usuarioes.';
			$this->listar();
			//return;
		}
		else{
			$post = Core_Http_Post::hasParameters()?Core_Http_Post::getParameters('Core_Object'):null;
			$post_usuario = $post&&$post->hasUsuario()?$post->GetUsuario(true):null;
			$usuario = new Saludmascotas_Model_User();
			if(isset($post_usuario)){
				$usuario->loadFromArray($post_usuario->getData());
				//echo Core_Helper::DebugVars($usuario->getData());
				$guardado = 
					Admin_Saludmascotas_Usuario_Helper::actionAgregarEditarUsuario($usuario)?true:false;
			}
			else{
				if(isset($id_usuario)){
					$usuario->setId($id_usuario);
					$usuario->load();
				}
			}
			//Admin_App::getInstance()->addShieldMessage(date('His').(isset($post_usuario)?'seteado':'no seteado'));
			if($guardado){
				Core_App::getLayout()->addActions('entity_addedit_action', 'addedit_admin_usuario_action');
				$this->listar();
			}
			else{
				Core_App::getLayout()->addActions('entity_addedit', 'addedit_admin_usuario');
				$layout = Core_App::getLoadedLayout();
				if($usuario->getId()&&!$id_usuario){
					$this->cambiarUrlAjax('administrator/usuario/addEdit/'.$usuario->getId());
				}

				$usuario->addAutofilterOutput('utf8_decode');
				
				foreach($layout->getBlocks('usuario_add_edit_form') as $block){
					$block->setIdToEdit($usuario->getId());
					$block->setObjectToEdit($usuario);
				}
			}
		}
	}
	protected function listar(){
		Core_App::getLayout()->addActions('entity_list', 'list_admin_usuario');
		$this->cambiarUrlAjax('administrator/usuario/listar');
	}
	protected function datalist(){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_usuario');
	}
	protected function dispatchNode(){
		return;
	}
	protected function ordenar($id_usuario=null){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('ordenar_usuario');
		$layout = Core_App::getLoadedLayout();
		$ordenar_usuario = $layout->getBlock('ordenar_usuario');
		$ordenar_usuario->setIdUsuario($id_usuario);
		$usuario = new Saludmascotas_Model_User();
		$usuarioes = $usuario->search('orden');
		$ordenar_usuario->setUsuarioes($usuarioes);
	}
}
?>