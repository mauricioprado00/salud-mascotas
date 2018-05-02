<?
/**
 *@referencia Spa(id_spa) Saludmascotas_Model_User(id)
 *@listar VisitasBarrios Saludmascotas_Model_VisitaBarrio
*/
class Saludmascotas_Model_Patrullaje extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$this->setId(null)
			->setFecha(null)
			->setIdSpa(null)
			->setComentario(null)
		;
		$this->addAutofilterFieldInput('fecha', array('Mysql_Helper','filterDateInput'));
		$this->addAutofilterFieldOutput('fecha', array('Mysql_Helper','filterDateOutput'));
	}
	public static function getConfiguraciones(){
		$configuraciones = Saludmascotas_Model_ConfiguracionUsuario::findConfigValues(array(
			'patrullaje/prioridad_perdidas'=>'5',
			'patrullaje/prioridad_perdidas_tiempo'=>'6',//priorizar por nuevos
			'patrullaje/prioridad_perdidas_max_dias'=>'90',
			
			'patrullaje/prioridad_encuentros_vistas'=>'5',
			'patrullaje/prioridad_encuentros_vistas_tiempo'=>'4',//priorizar por antiguedad
			'patrullaje/prioridad_encuentros_vistas_max_dias'=>'90',

			'patrullaje/prioridad_encuentros_en_guarda'=>'5',
			'patrullaje/prioridad_encuentros_en_guarda_tiempo'=>'4',//priorizar por antiguedad
			'patrullaje/prioridad_encuentros_en_guarda_max_dias'=>'90',


			'patrullaje/prioridad_distancia'=>'2',
			'patrullaje/prioridad_distancia_maxima'=>'40',//kilometros
			//'patrullaje/prioridad_encuentros_en_guarda_max_dias'=>'90',

			'patrullaje/prioridad_tiempo_max'=>'15',//15 dias
			'patrullaje/prioridad_tiempo'=>'2',//
			
			//metodo 2
			'patrullaje/met2/max_dias'=>90,
			'patrullaje/met2/peso/dias_visita'=>6,
			'patrullaje/met2/peso/cantidad_avistamientos'=>4,
			'patrullaje/met2/peso/cantidad_perdidas'=>1,
			'patrullaje/met2/peso/cantidad_en_guarda'=>3,

		));
		return $configuraciones;
	}
	public function getDbTableName() 
	{
		return 'sm_patrullaje';
	}
}
?>