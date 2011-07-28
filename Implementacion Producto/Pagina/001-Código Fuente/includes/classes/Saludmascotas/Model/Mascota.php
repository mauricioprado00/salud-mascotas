<?//es útf8
/**
 *@referencia Domicilio(id_domicilio) Saludmascotas_Model_Domicilio(id)
 *@referencia Dueno(id_dueno) Saludmascotas_Model_User(id)
 *@referencia EstadoMascota(id_estadomascota) Saludmascotas_Model_EstadoMascota(id)
 *@referencia LongitudPelaje(id_longitud_pelaje) Saludmascotas_Model_LongitudPelaje(id)
 *@referencia Manto(id_manto) Saludmascotas_Model_Manto(id)
 *@referencia Raza(id_raza) Saludmascotas_Model_Raza(id)
 *@listar Foto Saludmascotas_Model_FotoMascota
 *@listar Color Saludmascotas_Model_ColorMascota
 *@listar Perdida Saludmascotas_Model_Perdida
 *@listar Encuentro Saludmascotas_Model_Encuentro
*/
//*@listar Localidad Saludmascotas_Model_Localidad
class Saludmascotas_Model_Mascota extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$this->setTableColumn(
			'id'
			,'activa'
			,'castrado'
			,'descripcion'
			,'destacada'
			,'entrenada'
			,'fecha_nacimiento'
			,'nombre'
			,'para_adoptar'
			,'para_cruza'
			,'para_venta'
			,'pedigree'
			,'quiere_destacar'
			,'sexo'
			,'tamano'
			,'id_domicilio'
			,'id_dueno'
			,'id_estadomascota'
			,'id_longitud_pelaje'
			,'id_manto'
			,'id_raza'
		);
		$this->addAutofilterFieldInput('fecha_nacimiento', array('Mysql_Helper','filterDateInput'));
		$this->addAutofilterFieldOutput('fecha_nacimiento', array('Mysql_Helper','filterDateOutput'));
	}
	public function setEstadoByName($nombre_estado_mascota){
		if(!($estado_mascota = Saludmascotas_Model_EstadoMascota::getEstadoByName($nombre_estado_mascota)))
			return false;
		$this->setIdEstadomascota($estado_mascota->getId());
		return true;
	}
	protected function esEstadoByName($nombre_estado_mascota){
		if(!($estado_mascota = Saludmascotas_Model_EstadoMascota::getEstadoByName($nombre_estado_mascota)))
			return false;
		return $this->getIdEstadomascota()==$estado_mascota->getId();
	}
	public function setEstadoConDueno(){
		return $this->setEstadoByName('con dueno');
	}
	public function setEstadoPerdida(){
		return $this->setEstadoByName('perdida');
	}
	public function setEstadoEliminada(){
		return $this->setEstadoByName('eliminada');
	}
	public function setEstadoEnGuarda(){
		return $this->setEstadoByName('en guarda');
	}
	public function setEstadoVista(){
		return $this->setEstadoByName('vista');
	}
	public function esEstadoConDueno(){
		return $this->esEstadoByName('con dueno');
	}
	public function esEstadoPerdida(){
		return $this->esEstadoByName('perdida');
	}
	public function esEstadoEliminada(){
		return $this->esEstadoByName('eliminada');
	}
	public function esEstadoEnGuarda(){
		return $this->esEstadoByName('en guarda');
	}
	public function esEstadoVista(){
		return $this->esEstadoByName('vista');
	}
	protected function setRazaByName($nombre_raza, $id_especie, $create_if_needed=false){
		$raza = new Saludmascotas_Model_Raza();
		$raza->setNombre($nombre_raza);
		if(!$raza->load()){
			if($create_if_needed){
				$raza = new Saludmascotas_Model_Raza();
				$raza->setNombre($nombre_raza);
				$raza->setIdEspecie($id_especie);
				$raza->setDescripcion('');
				if(!$raza->insert()){
					return false;
				}
			}
			else
				return false;
		}
		$this->setIdRaza($raza->getId());
		return true;
	}
	public function getColoresAgregadosAsCollection(){
		$colores = $this->getListColor();
		if(!$colores)
			return null;
		$collection = new Core_Collection();
		foreach($colores as $color_mascota){
			$color = $color_mascota->getColor();
			$collection->addItem($color);
		}
		return $collection;
	}
	public function calcularEdad(){
		$fecha_nacimiento = $this->getFechaNacimiento();
		if(!$fecha_nacimiento)
			return null;
		list($dia_nacimiento, $mes_nacimiento, $anio_nacimiento) = explode('/', $fecha_nacimiento);
		$fecha_actual = date('d/m/Y');
		list($dia_actual, $mes_actual, $anio_actual) = explode('/', $fecha_actual);
		
		return Core_Helper::calculateAgeFormatted($dia_nacimiento, $mes_nacimiento, $anio_nacimiento, $dia_actual, $mes_actual, $anio_actual);
	}
	public function getDbTableName() 
	{
		return 'sm_mascota';
	}
}
?>