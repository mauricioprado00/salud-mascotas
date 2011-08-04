<?php //es útf8
class Frontend_Mascota_Perdida_Listado_Helper extends Frontend_Helper{
	public static function getUrl($pagina=0){
		return 'mascotas/perdidas' . ($pagina?'/pagina/'.intval($pagina):'');
	}
}
?>