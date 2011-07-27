<?
class Admin_Documento_Helper extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public static function actionAgregarEditarDocumento($documento){
		if(!is_a($documento,'Inta_Model_Documento')){
			$documento = new Inta_Model_Documento($documento->getData());
		}
		if(!$documento->hasId()){/** aca hay que agregar a la base de datos*/
			$resultado = $documento->replace()?true:false;
			//$insertada = true;// insertarEnLaBase()
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Documento a�adida correctamente'));
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo agregar la Documento, error en la operaci�n");
			}
		}
		else{/** aca hay que actualizar el registro*/
			//$actualizada = true;// actualizarEnLaBase()
			$resultado = $documento->update(null)?true:false;
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Documento actualizada correctamente'));
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo actualizar la Documento, error en la operaci�n");
			}
		}
		return($resultado);
	}
	public static function actionEliminarDocumento($id_documento){
		if(self::eliminarDocumento($id_documento)){
			Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Documento Eliminada Correctamente'));
		}
		else{
			Admin_App::getInstance()->addErrorMessage(self::getInstance()->__t('No se pudo eliminar la Documento'));
		}
	}
	public static function eliminarDocumento($id_documento){
		$documento = new Inta_Model_Documento();
//		return($documento->setId($id_documento)->delete());
		$ret = $documento->setId($id_documento)->delete();
		foreach($documento->getTranslatedErrors() as $error)
			Admin_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
		return($ret);
	}
}
?>