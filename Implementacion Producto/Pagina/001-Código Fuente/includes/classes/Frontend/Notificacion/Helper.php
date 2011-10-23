<?php //es útf8
class Frontend_Notificacion_Helper extends Frontend_Mascota_Helper{
	public function getInstance(){
		return self::getInstanceOf(__CLASS__);
	}
	public static function getUrlListado($pagina=0){
		return 'user/notificaciones/listado' . ($pagina?'/'.$pagina:'');
	}
	public static function getUrlView($id_notificacion){
		return 'user/notificaciones/view/' . $id_notificacion;
	}
	public static function getUrlEliminar($id_notificacion, $confirmar=false){
		return 'user/notificaciones/eliminar/'.$id_notificacion.($confirmar?'/1':'');
	}
	public static function actionNotificacionLeida($notificacion){
		$ret = $notificacion->update(array('leida'=>'si'));
		if(!$ret){
			
			foreach($notificacion->getTranslatedErrors() as $error){
				Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
			}
		}
	}
	public static function actionEliminarNotificacion($notificacion){
		$result = $notificacion->delete();
		if($result){
			Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t("La notificación fué eliminada correctamente"), true);
		}
		else{
			Core_App::getInstance()->addErrorMessage(self::getInstance()->__t("No se pudo registrar la notificación"), true);
		}
		return $result;
	}
}
?>