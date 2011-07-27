<?
class Admin_TipoAspecto_Helper extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public static function actionAgregarEditarTipoAspecto($tipo_aspecto){
		if(!is_a($tipo_aspecto,'Inta_Model_TipoAspecto')){
			$tipo_aspecto = new Inta_Model_TipoAspecto($tipo_aspecto->getData());
		}
		if(!$tipo_aspecto->hasId()){/** aca hay que agregar a la base de datos*/
			$resultado = $tipo_aspecto->replace()?true:false;
			//$insertada = true;// insertarEnLaBase()
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('TipoAspecto a�adida correctamente'));
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo agregar la TipoAspecto, error en la operaci�n");
			}
		}
		else{/** aca hay que actualizar el registro*/
			//$actualizada = true;// actualizarEnLaBase()
			$resultado = $tipo_aspecto->update(null)?true:false;
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('TipoAspecto actualizada correctamente'));
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo actualizar la TipoAspecto, error en la operaci�n");
			}
		}
		return($resultado);
	}
	public static function actionEliminarTipoAspecto($id_tipo_aspecto){
		if(self::eliminarTipoAspecto($id_tipo_aspecto)){
			Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('TipoAspecto Eliminada Correctamente'));
		}
		else{
			Admin_App::getInstance()->addErrorMessage(self::getInstance()->__t('No se pudo eliminar la TipoAspecto'));
		}
	}
	public static function eliminarTipoAspecto($id_tipo_aspecto){
		$tipo_aspecto = new Inta_Model_TipoAspecto();
//		return($tipo_aspecto->setId($id_tipo_aspecto)->delete());
		$ret = $tipo_aspecto->setId($id_tipo_aspecto)->delete();
		foreach($tipo_aspecto->getTranslatedErrors() as $error)
			Admin_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
		return($ret);
	}
}
?>