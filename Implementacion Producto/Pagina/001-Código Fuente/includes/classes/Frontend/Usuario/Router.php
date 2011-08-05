<?php /*es útf8*/
class Frontend_Usuario_Router extends Frontend_Router_Abstract{
	protected function initialize(){
		$this->addActions(
			'login','recover','register',//no registrado
			'activate',//no activado
			'logout','update','update_address','update_password'//registrado
		);
	}
//	protected function RedirectIfLoged(){
//		$logeado = Frontend_Usuario_Model_User::getLogedUser();
//		if($logeado){
//			Core_Http_Header::Redirect(Frontend_Usuario_Helper::getUrl(), true);
//			return true;
//		}
//		return false;
//	}
//	protected function RedirectIfNotLoged(){
//		$logeado = Frontend_Usuario_Model_User::getLogedUser();
//		if(!$logeado){
//			Core_Http_Header::Redirect(Frontend_Usuario_Helper::getUrlLogin(), true);
//			return true;
//		}
//		return false;
//	}
	protected function localDispatch(){
		//esta es la home
//		Core_App::getLayout()
//			->setModo('saludmascotas_legacy')
//			->addAction('home')
//		;
		//var_dump(c(new Frontend_Usuario_Model_User())->isLoged(), Frontend_Usuario_Model_User::getLogedUser()?true:false);
		if(Frontend_Usuario_Model_User::getLogedUser()){
			return $this->update();
		}
		return $this->login();
//		echo 'si esta logeado ir a cuenta, sino a login<br />';
//		die(__FILE__.__LINE__);
//		return true;
	}
	protected function login($email_or_user=''){
		if($this->RedirectIfLoged())
			return true;
		$this->setPageReference('Inicia Sesión', 'Y continua disfrutando de la comunidad');
		Core_App::getLayout()
			->setModo('saludmascotas')
			->addAction('usuario_login')
		;
		
		$this->showLeftMenu('usuario_logged_out');
		
		if(Core_Http_Post::hasParameters()){
			$data = Core_Http_Post::getParameters('Core_Object');
			
			$logged = Frontend_Usuario_Helper::tryLogin($data->getUsername(), $data->getPassword());
			if(!$logged){
				$email_or_user = $data->getUsername();
			}
			else{
				$return_to = Core_App::getSession()->getVar('return_to', 'unlogged_data');
				if(!$return_to)
					$return_to = Frontend_Usuario_Helper::getUrl();
				$this->Redirect($return_to);
				return true;
			}
		}
		Core_App::getLayout()->addActions('resultado_login_usuario');

		
		//loaded layout
		Core_App::getLoadedLayout()
			->getBlock('form_login')
			->setEmailOrUser($email_or_user)
		;
		$this->setActiveLeftMenu('user_login');
	}
	public function logout(){
		Frontend_Usuario_Helper::logout();
		$this->redirect(Frontend_Helper::getUrlDashboard());
//		Core_Http_Header::Redirect(Frontend_Usuario_Helper::getUrlLogin(), true);
		return true;
	}
	protected function register(){
		if($this->RedirectIfLoged())
			return true;
		$object_to_edit = new Frontend_Usuario_Model_User();
		if(Core_Http_Post::hasParameters()){
//			$x = new Zend_Validate_NotEmpty();
//			$x->isValid('');
//			var_dump($x->getMessages());
//			$validator = new Zend_Validate_Alnum();
//			var_dump($validator->isValid('Abcd1ñ2'));
//			var_dump($validator->isValid('Abcd1ñ2·'));
//			var_dump($validator->getMessages());
//			die();
			$post = Core_Http_Post::getParameters('Core_Object', array('nombre','apellido','telefono','email','username','password','password2'));
			$object_to_edit->loadFromArray($post->getData());
			$object_to_edit->setPassword2($post->getPassword2());
			$guardado = Frontend_Usuario_Helper::actionAgregarEditarUsuario($object_to_edit)?true:false;
			if($guardado){
				Core_Http_Header::Redirect(Frontend_Usuario_Helper::getUrlLogin($object_to_edit), true);
				return true;
			}
		}
		$this->setPageReference('Registrate y', 'Únete a la comunidad');
		Core_App::getLayout()
			->setModo('saludmascotas')
			->addAction('usuario_register')
		;
		$this->showLeftMenu('usuario_logged_out');
		
		
		//loaded layout
		Core_App::getLoadedLayout()
			->getBlock('form_edit')//$x = $this->getObjectToEdit();
			->setObjectToEdit($object_to_edit)
		;
		$this->setActiveLeftMenu('user_register');
	}
	protected function recover($email_or_user=''){
		if($this->RedirectIfLoged())
			return true;
		if(Core_Http_Post::hasParameters()){
			$post = Core_Http_Post::getParameters('Core_Object');
			$enviado = Frontend_Usuario_Helper::solicitudRecuperacion($post->getRecover());
			if($enviado){
				Core_Http_Header::Redirect(Frontend_Usuario_Helper::getUrlLogin($object_to_edit), true);
				return true;
			}
		}
		$this->setPageReference('Recupera', 'Tu usuario y vuelve a la comunidad');
		Core_App::getLayout()
			->setModo('saludmascotas')
			->addAction('usuario_recover')
		;
		$this->showLeftMenu('usuario_logged_out');
		
		//loaded layout
		Core_App::getLoadedLayout()
			->getBlock('form_recover')
			->setEmailOrUser($email_or_user)
		;
		$this->setActiveLeftMenu('user_recover');
	}
	protected function activate($username, $max_time, $hash){
		if(Frontend_Usuario_Helper::activateUsuario($username, $max_time, $hash)){
			Core_Http_Header::Redirect(Frontend_Usuario_Helper::getUrl(), true);
		}
		else{
			
			Core_Http_Header::Redirect(Frontend_Usuario_Helper::getUrlLogin($username), true);
		}
		return true;
	}
	protected function update(){
		if($this->RedirectIfNotLoged())
			return true;
		$object_to_edit = Frontend_Usuario_Model_User::getLogedUser();
		
		if(Core_Http_Post::hasParameters()){
			$post = Core_Http_Post::getParameters('Core_Object', array('nombre','apellido','telefono','email'));
			$object_to_edit->loadFromArray($post->getData());
			$guardado = Frontend_Usuario_Helper::actionAgregarEditarUsuario($object_to_edit)?true:false;
			if($guardado){
				//Core_Http_Header::Redirect(Frontend_Usuario_Helper::getUrlUpdate(), true);
				//return true;
			}
		}
		
		$this->setPageReference('Actualiza', 'Tus datos');
		Core_App::getLayout()
			->setModo('saludmascotas')
			->addAction('usuario_update')
		;

		$this->showLeftMenu('usuario');

		//loaded layout
		Core_App::getLoadedLayout()
			->getBlock('form_edit')//$x = $this->getObjectToEdit();
			->setObjectToEdit($object_to_edit)
		;
		$this->setActiveLeftMenu('user_update');
	}
	protected function update_address(){
		
		if($this->RedirectIfNotLoged())
			return true;
		$usuario = Frontend_Usuario_Model_User::getLogedUser();
		$object_to_edit = $usuario->getDomicilio();
		if(!$object_to_edit)
			$object_to_edit = new Frontend_Model_Domicilio();// Frontend_Usuario_Model_User::getLogedUser();
		else $object_to_edit->restorePrivateData();
//		$usuario = Frontend_Usuario_Model_User::getLogedUser();
//		if(){
//			
//		}
		if(Core_Http_Post::hasParameters()){
			$post = Core_Http_Post::getParameters('Core_Object', array('id_pais','provincia','localidad','barrio','calle_numero','lat','lng'));
			$object_to_edit->loadFromArray($post->getData(), false);
//			header('content-type:text/plain');
//			var_dump($object_to_edit->getData());
//			die(__FILE__.__LINE__);
			$guardado = Frontend_Usuario_Helper::actionAgregarEditarDomicilio($object_to_edit)?true:false;
			if($guardado){
				//Core_Http_Header::Redirect(Frontend_Usuario_Helper::getUrlUpdate(), true);
				//return true;
			}
		}
		
		$this->setPageReference('Actualiza', 'Tu domicilio');
		Core_App::getLayout()
			->setModo('saludmascotas')
			->addAction('usuario_update_address')
		;

		$this->showLeftMenu('usuario');

		//loaded layout
		c($loaded_layout = Core_App::getLoadedLayout())
			->getBlock('form_edit')//$x = $this->getObjectToEdit();
			->setObjectToEdit($object_to_edit)
		;
		$location_selector = $loaded_layout
			->getBlock('location_selector')
			->setObjectToEdit($object_to_edit)
		;

		$this->setActiveLeftMenu('user_update_address');
	}
	protected function update_password(){
		if($this->RedirectIfNotLoged())
			return true;
		$object_to_edit = Frontend_Usuario_Model_User::getLogedUser();
		
		if(Core_Http_Post::hasParameters()){
			
			$post = Core_Http_Post::getParameters('Core_Object', array('current_password','new_password','new_password2'));
			//$object_to_edit->loadFromArray($post->getData());
			$object_to_edit->setCurrentPassword($post->getCurrentPassword());
			$object_to_edit->setNewPassword($post->getNewPassword());
			$object_to_edit->setNewPassword2($post->getNewPassword2());
			
			$guardado = Frontend_Usuario_Helper::actionActualizarPassword($object_to_edit)?true:false;
			if($guardado){
				Core_Http_Header::Redirect(Frontend_Usuario_Helper::getUrl(), true);
				return true;
			}
		}
		
		$this->setPageReference('Actualiza', 'Tu contraseña');
		Core_App::getLayout()
			->setModo('saludmascotas')
			->addAction('usuario_update_password')
		;
		
		$this->showLeftMenu('usuario');

		//loaded layout
		Core_App::getLoadedLayout()
			->getBlock('form_edit')//$x = $this->getObjectToEdit();
			->setObjectToEdit($object_to_edit)
		;
		$this->setActiveLeftMenu('user_update');
	}
	protected function dispatchNode($nodo){//esto es cuando hay algo despues de la url.
		$nodo = trim($nodo);
		$nodo = strtolower($nodo);
		switch($nodo){
			case 'privacy-policy':{
				$this->setPageReference('Políticas de Privacidad', 'Informate sobre las precauciones a tu privacidad');
				Core_App::getLayout()
					->setModo('saludmascotas')
					->addActions('privacy_policy')
				;
				break;
			}
			case 'service-conditions':{
				$this->setPageReference('Condiciones del Servicio', 'Informate sobre nuestro servicio');
				Core_App::getLayout()
					->setModo('saludmascotas')
					->addActions('service_conditions')
				;
				break;
			}
			default:{
				return false;
				break;
			}
			case '':{
				break;
			}
		}
		return true;
	}
}
?>