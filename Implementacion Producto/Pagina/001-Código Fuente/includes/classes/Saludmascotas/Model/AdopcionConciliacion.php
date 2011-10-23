<?//es útf8
/**
 *@referencia AdopcionOferta(id_adopcion_oferta) Saludmascotas_Model_AdopcionOferta(id)
 *@referencia Usuario(id_usuario) Saludmascotas_Model_User(id)
 *@referencia AdopcionSolicitud(id_adopcion_solicitud) Saludmascotas_Model_AdopcionSolicitud(id)
*/
//*@listar Localidad Saludmascotas_Model_Localidad
class Saludmascotas_Model_AdopcionConciliacion extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$this->setTableColumn(
			'id'
			,'activo'
			,'hora_adopcion_conciliacion'
			,'descripcion'
			,'confirmado'
			,'iniciado_por'
			,'email'
			,'nombre'
			,'id_adopcion_oferta'
			,'id_usuario'
			,'id_adopcion_solicitud'
		);
		$this->addAutofilterFieldInput('hora_adopcion_conciliacion', array('Mysql_Helper','filterTimestampInput'));
		$this->addAutofilterFieldOutput('hora_adopcion_conciliacion', array('Mysql_Helper','filterTimestampOutput'));
	}
	public function setConfirmado($set=true){
		$this->setData('confirmado', $set?'si':'no');
		return $this;
	}
	public function esConfirmado(){
		return $this->getConfirmado()=='si';
	}
	public function setActivo($set=true){
		$this->setData('activo', $set?'si':'no');
		return $this;
	}
	public function esActivo(){
		return $this->getActivo()=='si';
	}
	public function getNombreCompleto(){
		if($this->hasIdUsuario()){
			$usuario = $this->getUsuario();
			return $usuario->getNombre(). ' '. $usuario->getApellido();
		}
		return $this->getNombre();
	}
	public function getTelefono(){
		if($this->hasIdAdopcionSolicitud() && $adopcion_solicitud = $this->getAdopcionSolicitud()){
			if($adopcion_solicitud->getMostrarTelefono() && $usuario = $this->getUsuario()){
				return trim($usuario->getTelefono());
			}
		}
	}
	public function getDbTableName() 
	{
		return 'sm_adopcion_conciliacion';
	}
}
?>