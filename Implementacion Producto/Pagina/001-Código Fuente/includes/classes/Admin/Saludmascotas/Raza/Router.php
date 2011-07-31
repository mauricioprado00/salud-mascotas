<?
class Admin_Saludmascotas_Raza_Router extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addRouter('admin','Router.Admin');
		$this->addActions(
			'cerrar_sesion',
			'addEdit','delete','listar','datalist',
			'ordenar','setorden'
		);
	}
	protected function onThrought(){
		Core_App::getLayout()->addActions('modulo','modulo_admin_raza');
	}
	private function cambiarUrlAjax($link_url){
		$helper_url_ajax = Core_App::getLoadedLayout()
				->getBlock('helper_url_ajax');
		if($helper_url_ajax){
			$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl($link_url));
		}
	}
	protected function delete($id_raza=null){
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Saludmascotas_Model_Raza()), 'd');
		Core_App::getInstance()->clearLastErrorMessages();
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido borrar Raza.');
		}
		else{
			if(isset($id_raza)){
				Admin_Saludmascotas_Raza_Helper::actionEliminarRaza($id_raza);
			}
		}
		$this->listar();
	}
	protected function addEdit($id_raza=null){
		Core_App::getInstance()->clearLastErrorMessages();
		$guardado = false;
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Saludmascotas_Model_Raza()), 'w');
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido editar Raza.');
			//$mensajes[] = 'No tiene permitido editar razaes.';
			$this->listar();
			//return;
		}
		else{
			$post = Core_Http_Post::hasParameters()?Core_Http_Post::getParameters('Core_Object'):null;
			$post_raza = $post&&$post->hasRaza()?$post->GetRaza(true):null;
			$raza = new Saludmascotas_Model_Raza();
			if(isset($post_raza)){
				$raza->loadFromArray($post_raza->getData());
				//echo Core_Helper::DebugVars($raza->getData());
				$guardado = 
					Admin_Saludmascotas_Raza_Helper::actionAgregarEditarRaza($raza)?true:false;
			}
			else{
				if(isset($id_raza)){
					$raza->setId($id_raza);
					$raza->load();
				}
			}
			//Admin_App::getInstance()->addShieldMessage(date('His').(isset($post_raza)?'seteado':'no seteado'));
			if($guardado){
				Core_App::getLayout()->addActions('entity_addedit_action', 'addedit_admin_raza_action');
				$this->listar();
			}
			else{
				Core_App::getLayout()->addActions('entity_addedit', 'addedit_admin_raza');
				$layout = Core_App::getLoadedLayout();
				if($raza->getId()&&!$id_raza){
					$this->cambiarUrlAjax('administrator/raza/addEdit/'.$raza->getId());
				}

				$raza->addAutofilterOutput('utf8_decode');
				
				foreach($layout->getBlocks('raza_add_edit_form') as $block){
					$block->setIdToEdit($raza->getId());
					$block->setObjectToEdit($raza);
				}
			}
		}
	}
	protected function listar(){
		Core_App::getLayout()->addActions('entity_list', 'list_admin_raza');
		$this->cambiarUrlAjax('administrator/raza/listar');
	}
	protected function datalist(){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_raza');
	}
	protected function dispatchNode(){
		return;
	}
	protected function ordenar($id_raza=null){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('ordenar_raza');
		$layout = Core_App::getLoadedLayout();
		$ordenar_raza = $layout->getBlock('ordenar_raza');
		$ordenar_raza->setIdRaza($id_raza);
		$raza = new Saludmascotas_Model_Raza();
		$razaes = $raza->search('orden');
		$ordenar_raza->setRazaes($razaes);
	}
}
?>