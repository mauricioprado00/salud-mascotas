<?php //es útf8
class Frontend_Vacunacion_Helper extends Frontend_Helper{
	public function getInstance(){
		return self::getInstanceOf(__CLASS__);
	}
	public static function getUrlConsultaVacunacion(){
		return 'vacunacion/consulta';
	}
	public static function getUrlCronogramaVacunacion(){
		return 'vacunacion/cronograma';
	}
	public static function getUrlAgregarVacunacion($fecha_inicio,$fecha_fin){
		return 'vacunacion/agregar'.($fecha_inicio?'/'.$fecha_inicio.($fecha_fin?'/'.$fecha_fin:''):'');
	}
	public static function getUrlEditarVacunacion($id_vacunacion){
		return 'vacunacion/editar/'.$id_vacunacion;
	}
	public static function getUrlEliminarVacunacion($id_vacunacion){
		return 'vacunacion/eliminar/'.$id_vacunacion;
	}
	public static function getUrlConsultarVacunacion($id_vacunacion){
		return 'vacunacion/eliminar'.($id_vacunacion?'/'.$id_vacunacion:'');
	}
	public static function getUpdatableFields(){
		return array(
			'id_pais',
			'provincia',
			'localidad',
			'barrio',
			'calle_numero',
			'lat',
			'lng',
			'fecha_inicio',
			'fecha_fin',
			'texto',
		);
	}
	public static function actionEliminarVacunacion($vacunacion){
		//$actualizada = true;// actualizarEnLaBase()
		$vacunacion->setActivo(false);
		$resultado = $vacunacion->updateFromUserInput(null)?true:false;
		//echo Core_Helper::DebugVars(Inta_Db::getInstance()->getLastQuery());
		if($resultado){
			Core_App::getInstance()->addErrorMessage("La vacunacion se ha eliminado del cronograma", true);
		}
		else{
			Core_App::getInstance()->addErrorMessage("La vacunacion no pudo ser eliminada", true);
			foreach($vacunacion->getTranslatedErrors() as $error){
				Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
			}
		}
		return $resultado;
	}
	public static function actionAgregarEditarVacunacion($vacunacion, $domicilio){
		$resultado = true;
		if(!$vacunacion->validateFields()){
			Core_App::getInstance()->addErrorMessage(self::getInstance()->__t("No se pudo registrar la vacunacion"));
			Core_Helper::LoadValidationTranslation();
			//var_dump($mascota->getValidationMessages());
			if($vacunacion->getValidationMessages())
				foreach($vacunacion->getValidationMessages() as $key=>$messages){
					foreach($messages as $message){
						Core_App::getInstance()->addErrorMessage($message);
					}
				}
			$resultado = false;
		}
		$errors = array();
		if(((float)$domicilio->getLng())==0 || ((float)$domicilio->getLat())==0 ){
			$errors[] = 'Debe seleccionar un punto en el mapa';
		}
		if(!$domicilio->validateFields()||$errors){
			Core_App::getInstance()->addErrorMessage("No se pudo actualizar el domicilio");
			Core_Helper::LoadValidationTranslation();
			foreach($errors as $message){
				Core_App::getInstance()->addErrorMessage(self::getInstance()->__t($message));
			}
			if($domicilio->getValidationMessages())
			foreach($domicilio->getValidationMessages() as $key=>$messages){
				foreach($messages as $message){
					Core_App::getInstance()->addErrorMessage($message);
				}
			}
			$resultado = false;
		}
		if(!$resultado)
			return false;
		
		
		if(!$domicilio->hasId()){/** aca hay que agregar a la base de datos*/
			$resultado = $domicilio->insertFromUserInput()?true:false;
			//echo Core_Helper::DebugVars(get_class($domicilio),$domicilio->getData());
			if($resultado){
				
			}
			else{
				Core_App::getInstance()->addErrorMessage("El domicilio no pudo ser registrado");
				foreach($domicilio->getTranslatedErrors() as $error){
					Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
				}
			}
		}
		else{/** aca hay que actualizar el registro*/
			//$actualizada = true;// actualizarEnLaBase()
			$resultado = $domicilio->updateFromUserInput(null)?true:false;
			//echo Core_Helper::DebugVars(Inta_Db::getInstance()->getLastQuery());
			if($resultado){
			}
			else{
				Core_App::getInstance()->addErrorMessage("El domicilio no pudo ser actualizado");
				foreach($domicilio->getTranslatedErrors() as $error){
					Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
				}
			}
 		}
 		if($resultado){
			$usuario = self::getLogedUser();
			$vacunacion
				->setIdDomicilio($domicilio->getId())
				->setIdSpa($usuario->getId())
			;
			if(!$vacunacion->hasId()){/** aca hay que agregar a la base de datos*/
				$resultado = $vacunacion->insertFromUserInput()?true:false;
				//echo Core_Helper::DebugVars(get_class($vacunacion),$vacunacion->getData());
				if($resultado){
					Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Vacunación registrada correctamente'), true);
				}
				else{
					Core_App::getInstance()->addErrorMessage("La vacunación no pudo ser registrada");
					foreach($vacunacion->getTranslatedErrors() as $error){
						Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
					}
				}
			}
			else{/** aca hay que actualizar el registro*/
				//$actualizada = true;// actualizarEnLaBase()
				$resultado = $vacunacion->updateFromUserInput(null)?true:false;
				//echo Core_Helper::DebugVars(Inta_Db::getInstance()->getLastQuery());
				if($resultado){
					Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Vacunación actualizada correctamente'), true);
				}
				else{
					Core_App::getInstance()->addErrorMessage("La vacunación no pudo ser actualizada");
					foreach($vacunacion->getTranslatedErrors() as $error){
						Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
					}
				}
	 		}
		}
		return $resultado;
	}
}
?>