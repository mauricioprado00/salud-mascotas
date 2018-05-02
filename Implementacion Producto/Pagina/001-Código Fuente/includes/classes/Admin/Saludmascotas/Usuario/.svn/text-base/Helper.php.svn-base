<?
class Admin_Saludmascotas_Usuario_Helper extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public static function actionAgregarEditarUsuario($usuario){
		if(!is_a($usuario,'Saludmascotas_Model_User')){
			$usuario = new Saludmascotas_Model_User($usuario->getData());
		}
		if(!$usuario->hasId()){/** aca hay que agregar a la base de datos*/
			$resultado = $usuario->replace()?true:false;
			//$insertada = true;// insertarEnLaBase()
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Usuario a�adida correctamente'));
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo agregar la Usuario, error en la operaci�n");
			}
		}
		else{/** aca hay que actualizar el registro*/
			//$actualizada = true;// actualizarEnLaBase()
			$resultado = $usuario->update(null)?true:false;
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Usuario actualizada correctamente'));
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo actualizar la Usuario, error en la operaci�n");
			}
		}
		return($resultado);
	}
	public static function actionEliminarUsuario($id_usuario){
		if(self::eliminarUsuario($id_usuario)){
			Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Usuario Eliminada Correctamente'));
		}
		else{
			Admin_App::getInstance()->addErrorMessage(self::getInstance()->__t('No se pudo eliminar la Usuario'));
		}
	}
	public static function eliminarUsuario($id_usuario){
		$usuario = new Saludmascotas_Model_User();
//		return($usuario->setId($id_usuario)->delete());
		$ret = $usuario->setId($id_usuario)->delete();
		foreach($usuario->getTranslatedErrors() as $error)
			Admin_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
		return($ret);
	}
}
?>