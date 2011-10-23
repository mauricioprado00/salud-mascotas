<?php
abstract class Frontend_Router_Abstract extends Core_Router_Abstract{
	private $helper;
	protected function getSession(){
		return Frontend_Session::getInstance();
	}
	protected function getHelper(){
		if(!isset($this->helper)){
			$this->helper = $this->createHelper();
		}
		return $this->helper;
	}
	protected function createHelper(){
		$class = get_class($this);
		do{
			$class = explode('_', $class);
			array_pop($class);
			array_push($class,'Helper');
			$class = implode('_', $class);
			if(class_exists($class)){
				//die(__FILE__.__LINE__);
				return new $class();
			}
			$class = get_parent_class($class);
		}while($class);
		return null;
	}
	protected function initialize(){
		
	}
	public function setPageReference($title, $description=''){
		Core_App::getInstance()
			->setPageReferenceTitle($title)
			->setPageReferenceDescription($description);
		;
		return $this;
	}
	public function showLeftMenu($menu_name, $active_menu=''){
		Core_App::getLayout()
			->addActions('show_left_menu', 'left_menu_'.$menu_name)
		;
		if($active_menu){
			$this->setActiveLeftMenu($active_menu);
		}
		return $this;
	}
	public function setActiveLeftMenu($active_menu){
		Core_App::getLoadedLayout()
			->getBlock('left_menu')
			->setActive($active_menu)
		;
	}
	protected function RedirectIfLoged(){
		$logeado = Frontend_Usuario_Model_User::getLogedUser();
		if($logeado){
			Core_Http_Header::Redirect(Frontend_Usuario_Helper::getUrl(), true);
			return true;
		}
		return false;
	}
	protected function RedirectIfNotLoged(){
		Core_App::getSession()->setVar('return_to', Core_App::getInstance()->getCurrentUrl(), 'unlogged_data');
		$logeado = Frontend_Usuario_Model_User::getLogedUser();
		if(!$logeado){
			Core_Http_Header::Redirect(Frontend_Usuario_Helper::getUrlLogin(), true);
			return true;
		}
		return false;
	}
	protected function RedirectIfNotSpa(){
		if($this->RedirectIfNotLoged())
			return true;
		$logeado = Frontend_Usuario_Model_User::getLogedUser();
		if(!$logeado->esTipoSpa()){
			Core_Http_Header::Redirect('/', true);
			return true;
		}
		return false;
	}
	protected function getLogedUser(){
		return Frontend_Usuario_Model_User::getLogedUser();
	}
	private static $initialized_onthrought = false;
	public function onThrought($force=false){
		if(self::$initialized_onthrought)
			return;
		$this->initialize_onthrought();
		self::$initialized_onthrought = true;
	}
	public function initialize_onthrought($force=false){
		$user = Frontend_Usuario_Model_User::getLogedUser();
		if(!$user)
			return;
		$tipo = $user->getTipo();
		if(!$tipo)
			return;
		Core_App::getLayout()
			->addActions('tipo_usuario_'.$tipo);
//			var_dump('tipo_usuario_'.$tipo);
//			die(__FILE__.__LINE__);
		return;
//		static $called;if($called&&!$force)return($this);$called=true;
//		if($user = Admin_User_Model_User::getLogedUser()){
//			if($user->esSuperadmin()){
//				Core_App::getLayout()
//					->addActions('superadmin');
//			}	
//		}
//		if(Admin_App::getInstance()->getModoAjax()){
//			Core_App::getLayout()
//				->addActions('modo_ajax');
//		}
//		else{
//			Core_App::getLayout()
//				->removeActions('modo_ajax');
//		}
//		Core_App::getLayout()
//			->setModo('admin')
////			->addDesignPaths('admin', 'admin/default/')
////			->addDesignPaths('admin', 'visualadmin/default/')
//		;
	}
}
?>