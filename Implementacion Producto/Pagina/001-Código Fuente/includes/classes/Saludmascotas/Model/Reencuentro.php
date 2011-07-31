<?//es útf8
/**
 *@referencia Perdida(id_perdida) Saludmascotas_Model_Perdida(id)
 *@referencia Usuario(id_usuario) Saludmascotas_Model_User(id)
 *@referencia Encuentro(id_encuentro) Saludmascotas_Model_Encuentro(id)
*/
//*@listar Localidad Saludmascotas_Model_Localidad
class Saludmascotas_Model_Reencuentro extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$this->setTableColumn(
			'id'
			,'hora_reencuentro'
			,'descripcion'
			,'confirmado'
			,'iniciado_por'
			,'email'
			,'nombre'
			,'id_perdida'
			,'id_usuario'
			,'id_encuentro'
		);
		$this->addAutofilterFieldInput('hora_reencuentro', array('Mysql_Helper','filterTimestampInput'));
		$this->addAutofilterFieldOutput('hora_reencuentro', array('Mysql_Helper','filterTimestampOutput'));
	}
	public function setConfirmado($set=true){
		$this->setData('confirmado', $set?'si':'no');
		return $this;
	}
	public function esConfirmado(){
		return $this->getConfirmado()=='si';
	}
	public function getDbTableName() 
	{
		return 'sm_reencuentro';
	}
}
?>