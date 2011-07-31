<?php /*es útf8*/
class Frontend_Router extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addActions('algo');
	}
	protected function localDispatch(){
		//esta es la home
		Core_App::getLayout()
			->setModo('saludmascotas_legacy')
			->addAction('home')
		;
		//die(__FILE__.__LINE__);
		return true;
	}
	protected function dispatchNode(){//esto es cuando hay algo despues de la url.
		//var_dump(func_get_args());
		die(__FILE__.__LINE__);
		return true;
	}
}
?>