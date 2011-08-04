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
	protected function getLogedUser(){
		return Frontend_Usuario_Model_User::getLogedUser();
	}
}
?>