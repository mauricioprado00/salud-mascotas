<?php //es útf8
/**
 *@referencia Domicilio(id_domicilio) Frontend_Model_Domicilio(id)
 *@listar Perdida Frontend_Model_Perdida
 *@listar Encuentro Frontend_Model_Encuentro
*/
class Frontend_Model_Mascota extends Saludmascotas_Model_Mascota{
	protected static $class = __CLASS__; 
	public function _construct(){
		parent::_construct();
		//artificiales
		$this->setNonTableColumn('edad', 'id_especie', 'raza', 'cantidad_colores', 'perdido', 'colores_seleccionados', 'estado');
//		$this->setNonTableColumn('id_pais', 'provincia', 'localidad', 'barrio');
		$this
			->setFieldLabel('nombre','Nombre')
			->addValidator('nombre', c(new Zend_Validate_Alpha(array('allowWhiteSpace' => true))))

			->setFieldLabel('edad','Edad')
			->addValidator('edad', c(new Zend_Validate_NotEmpty(array('allowWhiteSpace' => true))))

			->setFieldLabel('fecha_nacimiento','Fecha de Nacimiento')
			->addValidator('fecha_nacimiento', c(new Zend_Validate_NotEmpty(array('format' => 'dd/mm/yyyy'))))

			->setFieldLabel('id_especie','Especie')
			->addValidator('id_especie', c(new Zend_Validate_NotEmpty(array('allowWhiteSpace' => true))))

			->setFieldLabel('raza','Raza')
			->addValidator('raza', c(new Zend_Validate_Alpha(array('allowWhiteSpace' => true))))

			->setFieldLabel('id_manto','Manto')
			->addValidator('id_manto', c(new Zend_Validate_NotEmpty(array('allowWhiteSpace' => true))))

			->setFieldLabel('id_longitud_pelaje','Longitud Pelaje')
			->addValidator('id_longitud_pelaje', c(new Zend_Validate_NotEmpty(array('allowWhiteSpace' => true))))

			->setFieldLabel('tamano','Tamaño')
			->addValidator('tamano', c(new Zend_Validate_Alpha(array('allowWhiteSpace' => true))))

			->setFieldLabel('sexo','Sexo')
			->addValidator('sexo', c(new Zend_Validate_NotEmpty(array('allowWhiteSpace' => true))))

			->setFieldLabel('castrado','Castrado')
			->addValidator('castrado', c(new Zend_Validate_NotEmpty(array('allowWhiteSpace' => true))))
//
//			->setFieldLabel('entrenada','Entrenamiento')
//			->addValidator('entrenada', c(new Zend_Validate_NotEmpty(array('allowWhiteSpace' => true))))
//

//
//			->setFieldLabel('localidad','Localidad')
//			//->addValidator('nombre', c(new Zend_Validate_NotEmpty()))
//			->addValidator('localidad', c(new Zend_Validate_Alpha(array('allowWhiteSpace' => true))))
//
//			->setFieldLabel('barrio','Barrio')
//			//->addValidator('barrio', c(new Zend_Validate_NotEmpty()))
//			->addValidator('barrio', c(new Zend_Validate_Alpha(array('allowWhiteSpace' => true))))
//
//			->setFieldLabel('calle_numero','Domicilio')
//			//->addValidator('nombre', c(new Zend_Validate_NotEmpty()))
//			->addValidator('calle_numero', c(new Zend_Validate_Alnum(array('allowWhiteSpace' => true))))

//			->setFieldLabel('nombre','Nombre')
//			//->addValidator('nombre', c(new Zend_Validate_NotEmpty()))
//			->addValidator('nombre', c(new Zend_Validate_Alpha(array('allowWhiteSpace' => true))))
//
//			->setFieldLabel('apellido','Apellido')
//			->addValidator('apellido', c(new Zend_Validate_Alpha(array('allowWhiteSpace' => true))))
//
//			->setFieldLabel('telefono','Telefono')
//			//->addValidator('telefono', c(new Zend_Validate_NotEmpty()))
//
//			->setFieldLabel('email','Email')
//			->addValidator('email', c(new Zend_Validate_NotEmpty()))
//			->addValidator('email', c(new Zend_Validate_EmailAddress()))
//
//			->setFieldLabel('username','Nombre de Usuario')
//			//->addValidator('username', c(new Zend_Validate_NotEmpty()))
//			->addValidator('username', c(new Zend_Validate_Regex(array('pattern' => '/^[A-Za-z0-9]+(_[A-Za-z0-9]+)?$/'))))
//
//			->setFieldLabel('password','Contraseña')
//			->addValidator('password', c(new Zend_Validate_NotEmpty()))

		;
	}
	public function loadNonTableColumn(){
		//$this->setNonTableColumn('edad', 'id_especie', 'raza', 'cantidad_colores', 'perdido', 'colores_seleccionados');
		$this->setEdad($this->calcularEdad());
		$raza = $this->getRaza();
		$this->setIdEspecie($raza->getIdEspecie());
		$this->setRaza($raza->getNombre());
		$colores = $this->getListColor();
		if($colores){
			$colores_seleccionados = array();
			foreach($colores as $color_mascota){
				$color = $color_mascota->getColor();
//				echo Core_Helper::DebugVars($color->getData(), $color->getData());
				$colores_seleccionados[] = $color->getColorRgb();
			}
		}
		$this->setColoresSeleccionados($colores_seleccionados);
//		var_dump($colores_seleccionados);
//		die(__FILE__.__LINE__);
		return $this;
	}
//
//	
	public function updateFromUserInput($data=null, $use_null_values=false, $match_fields=array('id')){
		if(!$this->getEntrenada())
			$this->setEntrenada(null);
		if(($raza=$this->getRaza(false)) && ($id_especie=$this->getIdEspecie())){
			if(!$this->setRazaByName($raza, $id_especie, true)){
				return false;
			}
		}
//		var_dump($this->getData());
//		die(__FILE__.__LINE__);
		$updated = parent::update($data,$use_null_values, $match_fields);
//			header('content-type:text/plain');
//			var_dump($this);
//			die(__FILE__.__LINE__);

		if($updated){
			// agrego colores
			//header('content-type:text/plain');
			$colors = Saludmascotas_Model_Color::getColorsAsCollection();
			$colores_agregados = $this->getColoresAgregadosAsCollection();
			
			$colores_seleccionados = $this->getColoresSeleccionados();
			
			//agrego colores que faltan
			foreach($colores_seleccionados as $color_seleccionado){
				if($colores_agregados){
					$color_agregado = $colores_agregados->addFilterEq('color_rgb',$color_seleccionado);
					if($color_agregado && $color_agregado->count()>0){
						$color_agregado->getFirst()->setReagregado(true);
						continue;//el color ya existe, no hay que agregarlo
					}
				}
				$color = $colors->addFilterEq('color_rgb',$color_seleccionado);
				if($color && $color->count()){//el color existe en la paleta
					$color = $color->getFirst();
					$color_mascota = new Saludmascotas_Model_ColorMascota();
					$color_mascota
						->setIdMascota($this->getId())
						->setIdColor($color->getId())
					;
					$color_mascota
						->insert()
					;
					//para evitar 2 colores nuevos iguales
					$color->setReagregado(true);
					if($colores_agregados)
						$colores_agregados->addItem($color);
				}
			}
			//elimino los colores que sobran
			if($colores_agregados){
				foreach($colores_agregados as $color_agregado){
					if(!$color_agregado->getReagregado()){//si no es un color que se acaba de "reagregar"
						$color_mascota = new Saludmascotas_Model_ColorMascota();
						$color_mascota->setIdMascota($this->getId());
						$color_mascota->setIdColor($color_agregado->getId());
						$color_mascota->delete();
					}
				}
			}
			//agrego fotos
			$usuario = Frontend_Usuario_Model_User::getLogedUser();
			$foto_mascota = new Saludmascotas_Model_FotoMascota();
			$foto_mascota->setWhere(
				Db_Helper::equal('id_usuario'), 
				Db_Helper::equal('id_mascota')
			);
			$foto_mascota->setIdUsuario($usuario->getId());
			$fotos_mascotas = $foto_mascota->search(null, 'ASC', null, 0, get_class($foto_mascota));
			//var_dump($fotos_mascotas);
			foreach($fotos_mascotas as $foto_mascota){
				$foto_mascota
					->setIdMascota($this->getId())
					->update()
				;
			}
		}
		return $updated;
	}
	public function insertFromUserInput($data=null,$use_null_values=false, $get_sql=false){
		if(!$this->getIdEstadomascota()){
			$estado = $this->getEstado();
			if(!$estado || !$this->setEstadoByName($estado))
				$this->setEstadoConDueno();
		}
		//var_dump($this->getData(),$raza=$this->getRaza(false), $id_especie=$this->getIdEspecie());
//		var_dump($raza=$this->getRaza(false), $id_especie=$this->getIdEspecie(), $raza=$this->getRaza(false) && $id_especie=$this->getIdEspecie());
//		die(__FILE__.__LINE__);
		if(!$this->getEntrenada())
			$this->setEntrenada(null);
		if(($raza=$this->getRaza(false)) && ($id_especie=$this->getIdEspecie())){
			if(!$this->setRazaByName($raza, $id_especie, true)){
				return false;
			}
		}
		$inserted = parent::insert($data,$use_null_values, $get_sql);
		if($inserted){
			// agrego colores
			$colors = Saludmascotas_Model_Color::getColorsAsCollection();
			$colores_seleccionados = $this->getColoresSeleccionados();
			foreach($colores_seleccionados as $color_seleccionado){
				$color = $colors->addFilterEq('color_rgb',$color_seleccionado);
				//var_dump($color && $color->count());
				if($color && $color->count()){//el color existe en la paleta
					$color = $color->getFirst();
					$color_mascota = new Saludmascotas_Model_ColorMascota();
					$color_mascota
						->setIdMascota($this->getId())
						->setIdColor($color->getId())
					;
					$color_mascota
						->insert()
					;
				}
			}
			
			//agrego fotos
			$usuario = Frontend_Usuario_Model_User::getLogedUser();
			$foto_mascota = new Saludmascotas_Model_FotoMascota();
			$foto_mascota->setWhere(
				Db_Helper::equal('id_usuario'), 
				Db_Helper::equal('id_mascota')
			);
			$foto_mascota->setIdUsuario($usuario->getId());
			$fotos_mascotas = $foto_mascota->search(null, 'ASC', null, 0, get_class($foto_mascota));
			//var_dump($fotos_mascotas);
			foreach($fotos_mascotas as $foto_mascota){
				$foto_mascota
					->setIdMascota($this->getId())
					->update()
				;
			}
		}
		return $inserted;
	}
	public function getUrlEditar($preserve_mascota_edicion=0, $paso=1){
//		var_dump($this->getId(), $this->getIdEstadomascota());
//		die(__FILE__.__LINE__);
		if($this->esEstadoPerdida())
			return Frontend_Mascota_Perdida_Helper::getUrlEditar($this->getId(), $preserve_mascota_edicion, $paso);
		elseif($this->esEstadoEnGuarda()||$this->esEstadoVista())
			return Frontend_Mascota_Encuentro_Helper::getUrlEditar($this->getId(), $preserve_mascota_edicion, $paso);
		return Frontend_Mascota_Helper::getUrlEditar($this->getId(), $preserve_mascota_edicion, $paso);
	}
	public function getUrlPerdidaEditar($preserve_mascota_edicion=0, $paso=1){
		return Frontend_Mascota_Perdida_Helper::getUrlEditar($this->getId(), $preserve_mascota_edicion, $paso);
	}
	public function getUrlEncuentroEditar($preserve_mascota_edicion=0, $paso=1){
		return Frontend_Mascota_Encuentro_Helper::getUrlEditar($this->getId(), $preserve_mascota_edicion, $paso);
	}
	public function getUrlSetParaCruza(){
		return Frontend_Mascota_Helper::getUrlSetParaCruza($this->getId());
	}
	public function getUrlSetParaAdoptar(){
		return Frontend_Mascota_Helper::getUrlSetParaAdoptar($this->getId());
	}
	public function getUrlSetParaVenta(){
		return Frontend_Mascota_Helper::getUrlSetParaVenta($this->getId());
	}
	public function getUrlSimpleView(){
		return Frontend_Mascota_Helper::getUrlSimpleView($this->getId());
	}
	public function getUrlConfirmacionesPendientes(){
		return Frontend_Mascota_Reencuentro_Helper::getUrlConfirmacionesPendientes($this->getId());
	}
	public function getUrlFinalizarAnuncio(){
		if($this->esEstadoPerdida()){
			return $this->getUrlFinalizarAnuncioPerdida();
		}
		return $this->getUrlFinalizarAnuncioEncuentro();
	}
	public function getUrlFinalizarAnuncioPerdida(){
		return Frontend_Mascota_Reencuentro_Helper::getUrlFinalizarAnuncioPerdida($this->getId());
	}
	public function getUrlFinalizarAnuncioEncuentro(){
		return Frontend_Mascota_Reencuentro_Helper::getUrlFinalizarAnuncioEncuentro($this->getId());
	}
	public function getUrlListado(){
		if($this->esEstadoPerdida()){
			return $this->getUrlListadoPerdida();
		}
		return $this->getUrlListadoEncuentro();
	}
	public function getUrlListadoPerdida(){
		return Frontend_Mascota_Perdida_Listado_Helper::getUrlMascota($this->getId());
	}
	public function getUrlListadoEncuentro(){
		return Frontend_Mascota_Encuentro_Listado_Helper::getUrlMascota($this->getId());
	}

//	public function update($data=null, $use_null_values=false, $match_fields=array('id')){
//		$this->addLocations();
//		return parent::update($data,$use_null_values, $match_fields);
//	}
//	protected function translateDuplicatedKey($error){
//		$ret = parent::translateDuplicatedKey($error);
//		if($meta = $error->getMeta()){
//			$key = $meta->getKey();
//			switch($key->getColumnName()){
//				case 'email':{
//					$ret = 'Ya existe un usuario con el <b>Email</b> <em>\''.$meta->getValor().'\'</em>';
//					break;
//				}
//				case 'username':{
//					$ret = 'El <b>nombre de usuario</b> <em>\'' . $meta->getValor() . '\'</em> ya esta utilizado';
//					break;
//				}
//			}
//			//$ret = 'La clave Nº'.$matches['nro_campo'].$key_name.' con el valor \''.$matches['valor'].'\' esta duplicado';
//		}
//		return $ret;
//	}
}

?>