<?php
abstract class Frontend_Router_Abstract extends Core_Router_Abstract{
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