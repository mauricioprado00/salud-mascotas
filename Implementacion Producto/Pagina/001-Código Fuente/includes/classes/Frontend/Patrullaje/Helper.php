<?php //es útf8
class Frontend_Patrullaje_Helper extends Frontend_Helper{
	public function getInstance(){
		return self::getInstanceOf(__CLASS__);
	}
	public static function getUrlPrioridadesPatrullaje($pagina=null){
		return 'patrullaje/prioridades'.($pagina?'/'.$pagina:'');
	}
	public static function getUrlRegenerarPrioridadesPatrullaje(){
		return 'patrullaje/regenerar_prioridades';
	}
	public static function getUrlRegistrarVisita(){
		return 'patrullaje/registrar_visita';
	}
	public static function getUrlConfigurarPrioridades(){
		return 'patrullaje/configurar_prioridades';
	}
	public static function regenerarPrioridades(){
		$barrio = new Saludmascotas_Model_Barrio();
		//var_dump(self::getUserSessionVar('ids_prioridades_barrios', array(__CLASS__)));
		$ids_prioridades_barrios = $barrio->listarIdsPorPrioridadPatrullaje('rand()');
//		var_dump($ids_prioridades_barrios);
//		die(__FILE__.__LINE__);
		self::setUserSessionVar('ids_prioridades_barrios', $ids_prioridades_barrios, array(__CLASS__));
	}
	public static function getIdsPrioridadesBarrios(){
		return self::getUserSessionVar('ids_prioridades_barrios', array(__CLASS__));
	}
	public static function getUpdatableFields(){
		return array(
			'fecha'
			,'selector_barrio'
			,'comentario'
		);
	}
	public static function actionGuardarConfiguraciones($configuraciones, $post){
		//var_dump($configuraciones, $post);
		$new_data = $post->getData();
		foreach($configuraciones as $key=>$value){
			if(!isset($new_data[$key])){
				//echo "no config para $key\n";
				continue;
			}
			$new_value = $new_data[$key];
			$config = Saludmascotas_Model_ConfiguracionUsuario::findConfig($key);
			$config->setValor($new_value);
			if(!$config->update()){
				foreach($config->getTranslatedErrors() as $error){
					//var_dump($error->getTranslatedDescription());
					Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
				}

			}
			//var_dump(get_class($config),$config->getData());
		}
		//die();

		Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t("Se han guardado las configuraciones"), true);
		return true;
	}
	public static function actionAgregarPatrullaje($patrullaje, $ids_barrios){
		$resultado = true;
		if(!$patrullaje->validateFields()){
			Core_App::getInstance()->addErrorMessage(self::getInstance()->__t("No se pudo registrar el patrullaje"));
			Core_Helper::LoadValidationTranslation();
			//var_dump($mascota->getValidationMessages());
			if($patrullaje->getValidationMessages())
				foreach($patrullaje->getValidationMessages() as $key=>$messages){
					foreach($messages as $message){
						Core_App::getInstance()->addErrorMessage($message);
					}
				}
			$resultado = false;
		}
		if(!count($ids_barrios)){
			Core_App::getInstance()->addErrorMessage("Debe seleccionar al menos un barrio");
			$resultado = false;
		}
		if(!$resultado)
			return false;

		$usuario = self::getLogedUser();
		$patrullaje
			->setIdSpa($usuario->getId())
		;
		$resultado = $patrullaje->insertFromUserInput()?true:false;
		//echo Core_Helper::DebugVars(get_class($vacunacion),$vacunacion->getData());
		if($resultado){
			Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Patrullaje registrado correctamente'), true);
		}
		else{
			Core_App::getInstance()->addErrorMessage("El patrullaje no pudo ser registrado");
			foreach($vacunacion->getTranslatedErrors() as $error){
				Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
			}
		}
		if($resultado){
			foreach($ids_barrios as $id_barrio){
				$visita_barrio = new Frontend_Model_VisitaBarrio();
				$visita_barrio
					->setIdPatrullaje($patrullaje->getId())
					->setIdBarrio($id_barrio)
				;
				$resultado = $visita_barrio->insertFromUserInput()?true:false;
				//echo Core_Helper::DebugVars(get_class($domicilio),$domicilio->getData());
				if($resultado){
					
				}
				else{
					Core_App::getInstance()->addErrorMessage("No se pudo registrar Visita a barrio");
					foreach($visita_barrio->getTranslatedErrors() as $error){
						Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
					}
				}
			}
		}
		return $resultado;
	}
}
?>