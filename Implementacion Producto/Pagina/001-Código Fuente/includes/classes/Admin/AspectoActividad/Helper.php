<?
class Admin_AspectoActividad_Helper extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public static function actionAgregarEditarAspectoActividad($aspecto_actividad){
		if(!is_a($aspecto_actividad,'Inta_Model_AspectoActividad')){
			$aspecto_actividad = new Inta_Model_AspectoActividad($aspecto_actividad->getData());
		}
		if(!$aspecto_actividad->hasId()){/** aca hay que agregar a la base de datos*/
			$resultado = $aspecto_actividad->replace()?true:false;
			//$insertada = true;// insertarEnLaBase()
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('AspectoActividad a�adida correctamente'));
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo agregar la AspectoActividad, error en la operaci�n");
				echo Core_Helper::DebugVars(Inta_Db::getInstance()->getLastQuery());
				
			}
		}
		else{/** aca hay que actualizar el registro*/
			//$actualizada = true;// actualizarEnLaBase()
			$resultado = $aspecto_actividad->update(null)?true:false;
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('AspectoActividad actualizada correctamente'));
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo actualizar la AspectoActividad, error en la operaci�n");
			}
		}
		return($resultado);
	}
	public static function actionEliminarAspectoActividad($id_aspecto_actividad){
		if(self::eliminarAspectoActividad($id_aspecto_actividad)){
			Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('AspectoActividad Eliminada Correctamente'));
		}
		else{
			Admin_App::getInstance()->addErrorMessage(self::getInstance()->__t('No se pudo eliminar la AspectoActividad'));
		}
	}
	public static function eliminarAspectoActividad($id_aspecto_actividad){
		$aspecto_actividad = new Inta_Model_AspectoActividad();
//		return($aspecto_actividad->setId($id_aspecto_actividad)->delete());
		$ret = $aspecto_actividad->setId($id_aspecto_actividad)->delete();
		foreach($aspecto_actividad->getTranslatedErrors() as $error)
			Admin_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
		return($ret);
	}
}
?>