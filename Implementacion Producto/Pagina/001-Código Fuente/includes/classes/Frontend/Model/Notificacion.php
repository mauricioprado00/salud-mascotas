<?php //es útf8
/**
 *@referencia Mascota(id_mascota) Frontend_Model_Mascota(id)
 *@referencia Perdida(id_perdida) Frontend_Model_Perdida(id)
 *@referencia Encuentro(id_encuentro) Frontend_Model_Encuentro(id)
 *@referencia Reencuentro(id_reencuentro) Saludmascotas_Model_Reencuentro(id)
 *@referencia UsuarioFrom(id_usuario_from) Frontend_Usuario_Model_User(id)
 *@referencia UsuarioTo(id_usuario_to) Frontend_Usuario_Model_User(id)
*/
class Frontend_Model_Notificacion extends Saludmascotas_Model_Notificacion{
	protected static $class = __CLASS__; 
	public function _construct(){
		parent::_construct();
	}
	public function getAsuntoFormateado($max_asunto=70){
		$asunto = $this->getAsunto();
		if(!$max_asunto)
			return $asunto;
		return substr($asunto, 0, $max_asunto).'...';
	} 
	public function getMensajeFormateado($max_mensaje=70){
		$mensaje = $this->getMensaje();
		if(!$max_mensaje)
			return $mensaje;
		return substr($mensaje, 0, $max_mensaje).'...';
	}
	public function getFromFormateado(){
		$from = $this->getNombreFrom();
		if(!$from){
			$usuario_from = $this->getUsuarioFrom();
			if(!$usuario_from){
				return 'SaludMascotas';
			}
			$from = $usuario_from->getNombre() . ' '. $usuario_from->getApellido();
		}
		return $from;
	}
	public function getFechaFormateada($short=true){
		$hora = $this->getHora();
		if($short){
			if(strtotime(date('Y-m-d'))<=$hora){
				return date('H:i:s', $hora);
			}
			return date('d/m/Y', $hora);
		}
		return date('d/m/Y H:i:s', $hora);
	}
	public function getUrlView(){
		return Frontend_Notificacion_Helper::getUrlView($this->getId());
	}
}

?>