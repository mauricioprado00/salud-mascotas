<?
class Admin_Saludmascotas_Especie_Router extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addRouter('admin','Router.Admin');
		$this->addActions(
			'cerrar_sesion',
			'addEdit','delete','listar','datalist',
			'ordenar','setorden'
		);
	}
	protected function onThrought(){
		Core_App::getLayout()->addActions('modulo','modulo_admin_especie');
	}
	private function cambiarUrlAjax($link_url){
		$helper_url_ajax = Core_App::getLoadedLayout()
				->getBlock('helper_url_ajax');
		if($helper_url_ajax){
			$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl($link_url));
		}
	}
	protected function delete($id_especie=null){
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Saludmascotas_Model_Especie()), 'd');
		Core_App::getInstance()->clearLastErrorMessages();
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido borrar Especie.');
		}
		else{
			if(isset($id_especie)){
				Admin_Saludmascotas_Especie_Helper::actionEliminarEspecie($id_especie);
			}
		}
		$this->listar();
	}
	protected function addEdit($id_especie=null){
		Core_App::getInstance()->clearLastErrorMessages();
		$guardado = false;
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Saludmascotas_Model_Especie()), 'w');
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido editar Especie.');
			//$mensajes[] = 'No tiene permitido editar especiees.';
			$this->listar();
			//return;
		}
		else{
			$post = Core_Http_Post::hasParameters()?Core_Http_Post::getParameters('Core_Object'):null;
			$post_especie = $post&&$post->hasEspecie()?$post->GetEspecie(true):null;
			$especie = new Saludmascotas_Model_Especie();
			if(isset($post_especie)){
				$especie->loadFromArray($post_especie->getData());
				//echo Core_Helper::DebugVars($especie->getData());
				$guardado = 
					Admin_Saludmascotas_Especie_Helper::actionAgregarEditarEspecie($especie)?true:false;
			}
			else{
				if(isset($id_especie)){
					$especie->setId($id_especie);
					$especie->load();
				}
			}
			//Admin_App::getInstance()->addShieldMessage(date('His').(isset($post_especie)?'seteado':'no seteado'));
			if($guardado){
				Core_App::getLayout()->addActions('entity_addedit_action', 'addedit_admin_especie_action');
				$this->listar();
			}
			else{
				Core_App::getLayout()->addActions('entity_addedit', 'addedit_admin_especie');
				$layout = Core_App::getLoadedLayout();
				if($especie->getId()&&!$id_especie){
					$this->cambiarUrlAjax('administrator/especie/addEdit/'.$especie->getId());
				}

				$especie->addAutofilterOutput('utf8_decode');
				
				foreach($layout->getBlocks('especie_add_edit_form') as $block){
					$block->setIdToEdit($especie->getId());
					$block->setObjectToEdit($especie);
				}
			}
		}
	}
	protected function listar(){
		Core_App::getLayout()->addActions('entity_list', 'list_admin_especie');
		$this->cambiarUrlAjax('administrator/especie/listar');
	}
	protected function datalist(){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_especie');
	}
	protected function dispatchNode(){
		return;
	}
	protected function ordenar($id_especie=null){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('ordenar_especie');
		$layout = Core_App::getLoadedLayout();
		$ordenar_especie = $layout->getBlock('ordenar_especie');
		$ordenar_especie->setIdEspecie($id_especie);
		$especie = new Saludmascotas_Model_Especie();
		$especiees = $especie->search('orden');
		$ordenar_especie->setEspeciees($especiees);
	}
}
?>