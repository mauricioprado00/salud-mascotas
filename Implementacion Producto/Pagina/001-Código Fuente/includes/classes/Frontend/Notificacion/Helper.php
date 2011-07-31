<?php //es útf8
class Frontend_Notificacion_Helper extends Frontend_Mascota_Helper{
	public static function getUrlListado($pagina=0){
		return 'user/notificaciones/listado' . ($pagina?'/'.$pagina:'');
	}
	public static function getUrlView($id_notificacion){
		return 'user/notificaciones/view/' . $id_notificacion;
	}
}
?>