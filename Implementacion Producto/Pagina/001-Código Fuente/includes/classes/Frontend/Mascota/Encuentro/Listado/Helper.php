<?php //es útf8
class Frontend_Mascota_Encuentro_Listado_Helper extends Frontend_Helper{
	public static function getUrl($pagina=0){
		return 'mascotas/encuentros' . ($pagina?'/pagina/'.intval($pagina):'');
	}
}
?>