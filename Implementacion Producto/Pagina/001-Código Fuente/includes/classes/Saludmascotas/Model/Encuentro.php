<?
/**
 *@referencia Domicilio(id_domicilio) Saludmascotas_Model_Domicilio(id)
 *@referencia Mascota(id_mascota) Saludmascotas_Model_Mascota(id)
 *@referencia Usuario(id_usuario) Saludmascotas_Model_User(id)
*/
// *@listar Foto Saludmascotas_Model_FotoMascota
// *@listar Color Saludmascotas_Model_ColorMascota
// *@listar Localidad Saludmascotas_Model_Localidad
class Saludmascotas_Model_Encuentro extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$this->setTableColumn(
			'id'
			,'activo'
//			,'destacado'
			,'hora_encuentro'
			,'descripcion'
			,'notificacion_email'
			,'republicar_automaticamente'
//			,'quiere_destacar'
			,'mostrar_telefono'
			,'tiene_mascota'
			,'estado_mascota'
			
			,'fecha_publicacion'
			,'fecha_expiracion'

			,'id_domicilio'
			,'id_mascota'
			,'id_usuario'
		);
		$this->addAutofilterFieldInput('hora_encuentro', array('Mysql_Helper','filterTimestampInput'));
		$this->addAutofilterFieldOutput('hora_encuentro', array('Mysql_Helper','filterTimestampOutput'));
		$this->addAutofilterFieldInput('fecha_publicacion', array('Mysql_Helper','filterTimestampInput'));
		$this->addAutofilterFieldOutput('fecha_publicacion', array('Mysql_Helper','filterTimestampOutput'));
		$this->addAutofilterFieldInput('fecha_expiracion', array('Mysql_Helper','filterTimestampInput'));
		$this->addAutofilterFieldOutput('fecha_expiracion', array('Mysql_Helper','filterTimestampOutput'));
	}
	public function getDbTableName() 
	{
		return 'sm_encuentro';
	}
}
?>