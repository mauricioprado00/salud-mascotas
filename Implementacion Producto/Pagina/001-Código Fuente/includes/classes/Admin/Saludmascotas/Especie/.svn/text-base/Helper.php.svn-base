<?
class Admin_Saludmascotas_Especie_Helper extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public static function actionAgregarEditarEspecie($especie){
		if(!is_a($especie,'Saludmascotas_Model_Especie')){
			$especie = new Saludmascotas_Model_Especie($especie->getData());
		}
		if(!$especie->hasId()){/** aca hay que agregar a la base de datos*/
			$resultado = $especie->replace()?true:false;
			//$insertada = true;// insertarEnLaBase()
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Especie a�adida correctamente'));
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo agregar la Especie, error en la operaci�n");
			}
		}
		else{/** aca hay que actualizar el registro*/
			//$actualizada = true;// actualizarEnLaBase()
			$resultado = $especie->update(null)?true:false;
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Especie actualizada correctamente'));
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo actualizar la Especie, error en la operaci�n");
			}
		}
		return($resultado);
	}
	public static function actionEliminarEspecie($id_especie){
		if(self::eliminarEspecie($id_especie)){
			Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Especie Eliminada Correctamente'));
		}
		else{
			Admin_App::getInstance()->addErrorMessage(self::getInstance()->__t('No se pudo eliminar la Especie'));
		}
	}
	public static function eliminarEspecie($id_especie){
		$especie = new Saludmascotas_Model_Especie();
//		return($especie->setId($id_especie)->delete());
		$ret = $especie->setId($id_especie)->delete();
		foreach($especie->getTranslatedErrors() as $error)
			Admin_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
		return($ret);
	}
}
?>