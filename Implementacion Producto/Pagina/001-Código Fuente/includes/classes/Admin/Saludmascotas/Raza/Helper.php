<?
class Admin_Saludmascotas_Raza_Helper extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public static function actionAgregarEditarRaza($raza){
		if(!is_a($raza,'Saludmascotas_Model_Raza')){
			$raza = new Saludmascotas_Model_Raza($raza->getData());
		}
		if(!$raza->hasId()){/** aca hay que agregar a la base de datos*/
			$resultado = $raza->replace()?true:false;
			//$insertada = true;// insertarEnLaBase()
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Raza a�adida correctamente'));
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo agregar la Raza, error en la operaci�n");
			}
		}
		else{/** aca hay que actualizar el registro*/
			//$actualizada = true;// actualizarEnLaBase()
			$resultado = $raza->update(null)?true:false;
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Raza actualizada correctamente'));
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo actualizar la Raza, error en la operaci�n");
			}
		}
		return($resultado);
	}
	public static function actionEliminarRaza($id_raza){
		if(self::eliminarRaza($id_raza)){
			Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Raza Eliminada Correctamente'));
		}
		else{
			Admin_App::getInstance()->addErrorMessage(self::getInstance()->__t('No se pudo eliminar la Raza'));
		}
	}
	public static function eliminarRaza($id_raza){
		$raza = new Saludmascotas_Model_Raza();
//		return($raza->setId($id_raza)->delete());
		$ret = $raza->setId($id_raza)->delete();
		foreach($raza->getTranslatedErrors() as $error)
			Admin_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
		return($ret);
	}
}
?>