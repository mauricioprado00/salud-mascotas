<?php /*es útf8*/
class Frontend_Router extends Frontend_Router_Abstract{
	protected function initialize(){
		$this->addActions('dashboard');
	}
	protected function localDispatch(){
		Core_App::getSession()->setVar('return_to', Frontend_Helper::getUrlDashboard(), 'unlogged_data');
		//esta es la home
		Core_App::getLayout()
			->setModo('saludmascotas_legacy')
			->addAction('home')
		;
		//die(__FILE__.__LINE__);
		return true;
	}
	protected function dashboard(){
		$this->setPageReference('Actualidad', 'en la comunidad');
		Core_App::getLayout()
			->setModo('saludmascotas')
			->addAction('dashboard')
		;
		$this->showLeftMenu('dashboard');
//		var_dump(Core_App::getLayout()->getActions());
//		die(__FILE__.__LINE__);
		
		//loaded layout
//		Core_App::getLoadedLayout()
//			->getBlock('form_edit')//$x = $this->getObjectToEdit();
//			->setObjectToEdit($object_to_edit)
//		;
		$this->setActiveLeftMenu('dashboard_actualidad');
	}
	protected function dispatchNode(){//esto es cuando hay algo despues de la url.
		//var_dump(func_get_args());
		die(__FILE__.__LINE__);
		return true;
	}
}
?>