<?
/**
 *@referencia Localidad(id_localidad) Saludmascotas_Model_Localidad(id)
*/
class Saludmascotas_Model_Barrio extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$this->setId(null)
			->setIdLocalidad(null)
			->setNombre(null)
		;
	}
	public static function listarIdsPorPrioridadPatrullaje($order_by=null, $order_dir='ASC', $limit=null, $start=0, $as_objects=true){
		$result = array();
		$return = self::_listarPorPrioridadPatrullajeMet2(array('id'), $order_by, $order_dir, $limit, $start, $as_objects);
//		$return = self::_listarPorPrioridadPatrullajeMet1(array('id'), $order_by, $order_dir, $limit, $start, $as_objects);
		foreach($return as $item)
			$result[] = $item->getId();
		return $result;
	}
	public static function listarPorPrioridadPatrullaje($columns=null, $order_by=null, $order_dir='ASC', $limit=null, $start=0, $as_objects=true){
		return self::_listarPorPrioridadPatrullajeMet1($columns, $order_by, $order_dir, $limit, $start, $as_objects);
		return self::_listarPorPrioridadPatrullajeMet2($columns, $order_by, $order_dir, $limit, $start, $as_objects);
	}
	public static function _listarPorPrioridadPatrullajeMet2(){
		$barrio = new self();
		$barrios = new Core_Collection($barrio->search(null, 'ASC', null, 0, get_class($barrio), null));
//		var_dump($barrios);
//		die(__FILE__.__LINE__);
		$config = Saludmascotas_Model_Patrullaje::getConfiguraciones();
		$arr = array();
		$max_dias = $config['patrullaje/met2/max_dias'];
		foreach($barrios as $barrio){
			$arr[$barrio->getId()] = array(
				$barrio->getCantidadDiasUltimaVisita(),
				$barrio->getCantidadAvistamientos($max_dias),
				$barrio->getCantidadPerdidas($max_dias),
				$barrio->getCantidadEnGuarda($max_dias),
			);
			//echo $barrio->getNombre()."\n";
		}
		//echo "\n";
		//var_dump($arr);
		$ord_idx = Saludmascotas_Helper_Dec::getInstance()->simple(
			$arr, array(
				$config['patrullaje/met2/peso/dias_visita'],
				$config['patrullaje/met2/peso/cantidad_avistamientos'],
				$config['patrullaje/met2/peso/cantidad_perdidas'],
				$config['patrullaje/met2/peso/cantidad_en_guarda'],
			),array(
				$max_dias
			)
		);
		$sorted = array();
		foreach($ord_idx as $idx){
			$barrio = $barrios[$idx];
			$sorted[] = $barrio;
			//echo $barrio->getNombre()."\n";
		}
		//var_dump($ord_idx);
//		die(__FILE__.__LINE__);
//		$barrios = $barrios->OrderBy('puntaje', true);
		return $sorted;
	}
	public function getCantidadAvistamientos($max_dias){
		$max_time = time() - $max_dias * 24 * 60 * 60;
		$encuentro = new Saludmascotas_Model_View_MascotaEncuentro();
		$wheres = array(
			Db_Helper::equal('do_id_barrio', $this->getId()), 
			Db_Helper::equal('en_tiene_mascota', 'no'),
			Db_Helper::between('en_hora_encuentro', date('Ymd', $max_time))
		);
		if(!self::$_contar_encuentros_inactivas){
			$wheres[] = Db_Helper::equal('en_activo', 'si');
		}
		$encuentro->setWhereByArray($wheres);
		if(!$encuentro->searchCount())
			return 0;
		return $encuentro->searchCount();
	}
	public function getCantidadEnGuarda($max_dias){
		$max_time = time() - $max_dias * 24 * 60 * 60;
		$encuentro = new Saludmascotas_Model_View_MascotaEncuentro();
		$wheres = array(
			Db_Helper::equal('do_id_barrio', $this->getId()), 
			Db_Helper::equal('en_tiene_mascota', 'si'),
			Db_Helper::between('en_hora_encuentro', date('Ymd', $max_time))
		);
		if(!self::$_contar_encuentros_inactivas){
			$wheres[] = Db_Helper::equal('en_activo', 'si');
		}
		$encuentro->setWhereByArray($wheres);
		if(!$encuentro->searchCount())
			return 0;
		return $encuentro->searchCount();
	}
	public function getCantidadPerdidas($max_dias){
		$max_time = time() - $max_dias * 24 * 60 * 60;
		$perdida = new Saludmascotas_Model_View_MascotaPerdida();
		$wheres = array(
			Db_Helper::equal('do_id_barrio', $this->getId()),
			Db_Helper::between('pe_hora_extravio', date('Ymd', $max_time))
		);
		if(!self::$_contar_perdidas_inactivas){
			$wheres[] = Db_Helper::equal('pe_activo', 'si');
		}
		$perdida->setWhereByArray($wheres);
		if(!$perdida->searchCount())
			return 0;
		return $perdida->searchCount();
	}
	public function getCantidadDiasUltimaVisita(){
		$config = Saludmascotas_Model_Patrullaje::getConfiguraciones();
		$patrullaje = new Saludmascotas_Model_View_PatrullajeVisita();
		$wheres = array(
			Db_Helper::equal('vi_id_barrio', $this->getId())
		);
		$patrullaje->setWhereByArray($wheres);
		if(!$patrullaje->searchCount())
			return null;
		$patrullajes = $patrullaje->search('pa_fecha', 'DESC', null, 0, get_class($patrullaje));
		$patrullaje = $patrullajes[0]->getPatrullaje(new Frontend_Model_Patrullaje);
		$fecha = $patrullaje->getFecha();
		$time = strtotime($patrullaje->getData('fecha', null, array()));
//		var_dump(date('Ymd', $time));
//		var_dump(date('Ymd'));
//		var_dump(time()- $time);
//		var_dump((time()- $time)/(60*60*24));
		$dias_sin_patrullar = ceil((time() - $time) / (60 * 60 * 24));
//		if($dias_sin_patrullar){
//			var_dump($dias_sin_patrullar, $patrullaje->getData('fecha', null, array()));
//			die(__FILE__.__LINE__);
//		}
		return $dias_sin_patrullar;
	}
	public static function _listarPorPrioridadPatrullajeMet1($columns=null, $order_by=null, $order_dir='ASC', $limit=null, $start=0, $as_objects=true){
		$barrio = new self();
		//$barrios = new Core_Collection($barrio->search($order_by, $order_dir, $limit, $start, get_class($barrio), $columns));
		$barrios = new Core_Collection($barrio->search(null, 'ASC', null, 0, get_class($barrio), null));
//		var_dump($barrios);
//		die(__FILE__.__LINE__);
		foreach($barrios as $barrio){
			$puntaje = 0;
			$puntaje += $barrio->calcularPuntajePerdidas();
			$puntaje += $barrio->calcularPuntajeAvistamiento();
			$puntaje += $barrio->calcularPuntajeEnGuarda();
			$puntaje *= $barrio->calcularPuntajeDistancia();
			//var_dump($barrio->calcularPuntajeUltimaVisita());
			$puntaje *= $barrio->calcularPuntajeUltimaVisita();
			$barrio->setPuntaje($puntaje);
			//var_dump($puntaje, $barrio->getNombre());
		}
//		$input = array('b', 'c', 'd', 'a');
//		sort($input);
//		$reverse = array_reverse($input);
//		var_dump($input, $reverse);
		$barrios = $barrios->OrderBy('puntaje', true);
//		foreach($barrios as $barrio){
//			var_dump($barrio->getData());
//		}
		//var_dump($puntaje);
		//die(__FILE__.__LINE__);
		return $barrios;
		//return $barrio->search($order_by, $order_dir, $limit, $start, $as_objects, $columns);
	}
	private static $_contar_perdidas_inactivas = true;
	public function calcularPuntajePerdidas(){
		$perdida = new Saludmascotas_Model_View_MascotaPerdida();
		$wheres = array(
			Db_Helper::equal('do_id_barrio', $this->getId())
		);
		if(!self::$_contar_perdidas_inactivas){
			$wheres[] = Db_Helper::equal('pe_activo', 'si');
		}
		$perdida->setWhereByArray($wheres);
		if(!$perdida->searchCount())
			return 0;
		$perdidas = $perdida->search();
		$puntaje = 0;
		$config = Saludmascotas_Model_Patrullaje::getConfiguraciones();
		$prioridad_perdidas = $config['patrullaje/prioridad_perdidas'];
		$prioridad_perdidas_tiempo = $config['patrullaje/prioridad_perdidas_tiempo'];
		/**
			prioridad_perdidas_tiempo:[1-9] 5 = no hay prioridades, <5 prioriza las perdidas viejas, >5 prioriza las perdidas nuevas
		*/
		$prioridad_perdidas_max_dias = $config['patrullaje/prioridad_perdidas_max_dias'];
		foreach($perdidas as $perdida){
			$dia_extravio = strtotime($perdida->getPeHoraExtravio());
			$tiempo = time() - $dia_extravio;
			$dias_extravio = ceil($tiempo / (60 * 60 * 24));
			if($prioridad_perdidas_max_dias<=$dias_extravio)
				continue;
			//$dias_extravio = 7;
			
			$dias_prop = ($dias_extravio/$prioridad_perdidas_max_dias);
			if($prioridad_perdidas_tiempo>5){//priorizar nuevas
				$dias_prop = 1 - $dias_prop;
				$exp = $prioridad_perdidas_tiempo-4;
			}
			else{//priorizar viejas
				//$dias_prop
				$exp = 6 - $prioridad_perdidas_tiempo; 
			}
//			var_dump($dias_extravio, $prioridad_perdidas_max_dias);
//			var_dump($dias_extravio/$prioridad_perdidas_max_dias);
//			var_dump($dias_prop);
			$puntaje_perdida = pow($dias_prop, $exp) * $prioridad_perdidas;
//			var_dump($puntaje_perdida);
//			echo "\n";
			$puntaje += $puntaje_perdida;
		}
//		var_dump($puntaje);
//		echo $perdida->searchGetSql()."\n";
//		die(__FILE__.__LINE__);
		return $puntaje;
	}
	private static $_contar_encuentros_inactivas = true;
	public function calcularPuntajeAvistamiento(){
		$encuentro = new Saludmascotas_Model_View_MascotaEncuentro();
		$wheres = array(
			Db_Helper::equal('do_id_barrio', $this->getId()), 
			Db_Helper::equal('en_tiene_mascota', 'no')
		);
		if(!self::$_contar_encuentros_inactivas){
			$wheres[] = Db_Helper::equal('en_activo', 'si');
		}
		$encuentro->setWhereByArray($wheres);
		if(!$encuentro->searchCount())
			return 0;
		$encuentros = $encuentro->search();
		$puntaje = 0;
		$config = Saludmascotas_Model_Patrullaje::getConfiguraciones();
		$prioridad_encuentros_vistas = $config['patrullaje/prioridad_encuentros_vistas'];
		$prioridad_encuentros_vistas_tiempo = $config['patrullaje/prioridad_encuentros_vistas_tiempo'];
		/**
			prioridad_encuentros_vistas_tiempo:[1-9] 5 = no hay prioridades, <5 prioriza las encuentros viejas, >5 prioriza las encuentros nuevas
		*/
		$prioridad_encuentros_vistas_max_dias = $config['patrullaje/prioridad_encuentros_vistas_max_dias'];
		foreach($encuentros as $encuentro){
			$dia_extravio = strtotime($encuentro->getEnHoraEncuentro());
			$tiempo = time() - $dia_extravio;
			$dias_extravio = ceil($tiempo / (60 * 60 * 24));
			if($prioridad_encuentros_vistas_max_dias<=$dias_extravio)
				continue;
			//$dias_extravio = 7;
			
			$dias_prop = ($dias_extravio/$prioridad_encuentros_vistas_max_dias);
			if($prioridad_encuentros_vistas_tiempo>5){//priorizar nuevas
				$dias_prop = 1 - $dias_prop;
				$exp = $prioridad_encuentros_vistas_tiempo-4;
			}
			else{//priorizar viejas
				//$dias_prop
				$exp = 6 - $prioridad_encuentros_vistas_tiempo; 
			}
//			var_dump($dias_extravio, $prioridad_encuentros_vistas_max_dias);
//			var_dump($dias_extravio/$prioridad_encuentros_vistas_max_dias);
//			var_dump($dias_prop);
			$puntaje_encuentro = pow($dias_prop, $exp) * $prioridad_encuentros_vistas;
//			var_dump($puntaje_encuentro);
//			echo "\n";
			$puntaje += $puntaje_encuentro;
		}
//		var_dump($puntaje);
//		echo $encuentro->searchGetSql()."\n";
//		die(__FILE__.__LINE__);
		return $puntaje;
	}
	public function calcularPuntajeEnGuarda(){
		$encuentro = new Saludmascotas_Model_View_MascotaEncuentro();
		$wheres = array(
			Db_Helper::equal('do_id_barrio', $this->getId()), 
			Db_Helper::equal('en_tiene_mascota', 'si'),
			//Db_Helper::equal('ma_para_adoptar', 'si')
		);
		if(!self::$_contar_encuentros_inactivas){
			$wheres[] = Db_Helper::equal('en_activo', 'si');
		}
		$encuentro->setWhereByArray($wheres);
		if(!$encuentro->searchCount())
			return 0;
		$encuentros = $encuentro->search();
		$puntaje = 0;
		$config = Saludmascotas_Model_Patrullaje::getConfiguraciones();
		$prioridad_encuentros_en_guarda = $config['patrullaje/prioridad_encuentros_en_guarda'];
		$prioridad_encuentros_en_guarda_tiempo = $config['patrullaje/prioridad_encuentros_en_guarda_tiempo'];
		/**
			prioridad_encuentros_en_guarda_tiempo:[1-9] 5 = no hay prioridades, <5 prioriza las encuentros viejas, >5 prioriza las encuentros nuevas
		*/
		$prioridad_encuentros_en_guarda_max_dias = $config['patrullaje/prioridad_encuentros_en_guarda_max_dias'];
		foreach($encuentros as $encuentro){
			$dia_extravio = strtotime($encuentro->getEnHoraEncuentro());
			$tiempo = time() - $dia_extravio;
			$dias_extravio = ceil($tiempo / (60 * 60 * 24));
			
			if($prioridad_encuentros_en_guarda_max_dias<=$dias_extravio)
				continue;
			//$dias_extravio = 7;
			//var_dump($dias_extravio);
			$dias_prop = ($dias_extravio/$prioridad_encuentros_en_guarda_max_dias);
			if($prioridad_encuentros_en_guarda_tiempo>5){//priorizar nuevas
				$dias_prop = 1 - $dias_prop;
				$exp = $prioridad_encuentros_en_guarda_tiempo-4;
			}
			else{//priorizar viejas
				//$dias_prop
				$exp = 6 - $prioridad_encuentros_en_guarda_tiempo; 
			}
//			var_dump($dias_extravio, $prioridad_encuentros_en_guarda_max_dias);
//			var_dump($dias_extravio/$prioridad_encuentros_en_guarda_max_dias);
//			var_dump($exp, $dias_prop, $prioridad_encuentros_en_guarda);
			$puntaje_encuentro = pow($dias_prop, $exp) * $prioridad_encuentros_en_guarda;
//			var_dump($puntaje_encuentro);
//			echo "\n";
			$puntaje += $puntaje_encuentro;
		}
//		var_dump($puntaje);
//		echo $encuentro->searchGetSql()."\n";
//		die(__FILE__.__LINE__);
		return $puntaje;
	}
	public function calcularPuntajeDistancia(){
		$usuario = Frontend_Usuario_Model_User::getLogedUser();
		$domicilio_usuario = $usuario->getDomicilio();
		if(!$domicilio_usuario)
			return 1;
		$domicilio = new Saludmascotas_Model_Domicilio();
		$wheres = array(
			Db_Helper::equal('id_barrio', $this->getId())
		);
		$domicilio->setWhereByArray($wheres);
		if(!$domicilio->searchCount())
			return 1;
//		echo $domicilio->searchGetSql();
//		die(__FILE__.__LINE__);
		//calcular punto barrio
		$domicilios = $domicilio->search();
		$lat = 0;
		$lng = 0;
		$cant = count($domicilios);
		foreach($domicilios as $domicilio){
			$lat += $domicilio->getLat();
			$lng += $domicilio->getLng();
		}
		$lat = $lat / $cant;
		$lng = $lng / $cant;
//		var_dump($lat, $lng);
//		die(__FILE__.__LINE__);
		$config = Saludmascotas_Model_Patrullaje::getConfiguraciones();
		$prioridad_distancia_maxima = $config['patrullaje/prioridad_distancia_maxima'];
		$prioridad_distancia = $config['patrullaje/prioridad_distancia'];
		$lat_usuario = $domicilio_usuario->getLat();
		$lng_usuario = $domicilio_usuario->getLng();
		$distancia = Saludmascotas_Helper::getInstance()->min_distance_km($lat_usuario, $lng_usuario, $lat, $lng);
//		var_dump($prioridad_distancia_maxima, $distancia, array($lat_usuario, $lng_usuario, $lat, $lng));
//		die(__FILE__.__LINE__);
		if($distancia>$prioridad_distancia_maxima)
			return 0;
			
		$base = ($distancia/$prioridad_distancia_maxima);
		//if($prioridad_encuentros_en_guarda_tiempo>5){//priorizar cercanas
			$base = 1 - $base;
			$exp = $prioridad_distancia;
//		}
//		else{//priorizar viejas
//			//$dias_prop
//			$exp = 6 - $prioridad_encuentros_en_guarda_tiempo; 
//		}
		$puntaje = pow($base, $exp);
//		var_dump($puntaje);
//		die(__FILE__.__LINE__);
		return $puntaje;
	}
	public function calcularPuntajeUltimaVisita(){
		$config = Saludmascotas_Model_Patrullaje::getConfiguraciones();
		$patrullaje = new Saludmascotas_Model_View_PatrullajeVisita();
		$prioridad_tiempo = $config['patrullaje/prioridad_tiempo'];
		$prioridad_tiempo_max = $config['patrullaje/prioridad_tiempo_max'];
		$wheres = array(
			Db_Helper::equal('vi_id_barrio', $this->getId())
		);
		$patrullaje->setWhereByArray($wheres);
		if(!$patrullaje->searchCount())
			return $prioridad_tiempo * 1.5;
		$patrullajes = $patrullaje->search('pa_fecha', 'DESC', null, 0, get_class($patrullaje));
		$patrullaje = $patrullajes[0]->getPatrullaje(new Frontend_Model_Patrullaje);
		$fecha = $patrullaje->getFecha();
		$time = strtotime($patrullaje->getData('fecha', null, array()));
		//var_dump($fecha, $time, ($patrullaje->getData('fecha', null, array())), date('Y/m/d xx', $time));
//		echo $patrullaje->searchGetSql()."\n";
		$dias_sin_patrullar = ceil((time() - $time) / (60 * 60 * 24));
		if($dias_sin_patrullar>$prioridad_tiempo_max){
			if($dias_sin_patrullar>($prioridad_tiempo_max*2)){
//				var_dump($prioridad_tiempo);
//				die(__FILE__.__LINE__);//
				return $prioridad_tiempo * 1.5;
			}
			$base = $prioridad_tiempo_max - (($prioridad_tiempo_max*2) - $dias_sin_patrullar * 0.5);
			$exp = $prioridad_tiempo;
			return $prioridad_tiempo + pow($base , $exp) * $prioridad_tiempo;
		}
		$base = ($prioridad_tiempo_max - $dias_sin_patrullar);
		$exp = $prioridad_tiempo;
		$return = pow($base , $exp) * $prioridad_tiempo * 0.5;
//		var_dump($return);
//		die(__FILE__.__LINE__);//
		return 1;
	}
	public function getDbTableName() 
	{
		return 'sm_barrio';
	}
}
?>