<?
/**
 *@referencia Domicilio(id_domicilio) Saludmascotas_Model_Domicilio(id)
 *@referencia Mascota(id_mascota) Saludmascotas_Model_Mascota(id)
 *@referencia Usuario(id_usuario) Saludmascotas_Model_User(id)
*/
// *@listar Foto Saludmascotas_Model_FotoMascota
// *@listar Color Saludmascotas_Model_ColorMascota
// *@listar Localidad Saludmascotas_Model_Localidad
class Saludmascotas_Model_Perdida extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$this->setTableColumn(
			'id'
			,'activo'
			,'destacado'
			,'hora_extravio'
			,'descripcion'
			,'notificacion_email'
			,'republicar_automaticamente'
			,'quiere_destacar'
			,'mostrar_telefono'
			
			,'fecha_publicacion'
			,'fecha_expiracion'

			,'id_domicilio'
			,'id_mascota'
			,'id_usuario'
		);
		$this->addAutofilterFieldInput('hora_extravio', array('Mysql_Helper','filterTimestampInput'));
		$this->addAutofilterFieldOutput('hora_extravio', array('Mysql_Helper','filterTimestampOutput'));
		$this->addAutofilterFieldInput('fecha_publicacion', array('Mysql_Helper','filterDateInput'));
		$this->addAutofilterFieldOutput('fecha_publicacion', array('Mysql_Helper','filterDateOutput'));
		$this->addAutofilterFieldInput('fecha_expiracion', array('Mysql_Helper','filterDateInput'));
		$this->addAutofilterFieldOutput('fecha_expiracion', array('Mysql_Helper','filterDateOutput'));
	}
	public function getDbTableName() 
	{
		return 'sm_perdida';
	}
}
?>