<?
class Admin_UsuarioActividad_Block_XmlList extends Jqgrid_Block_XmlList{
	protected function loadData($page, $rows, $sidx, $sord, $comparator){
		/*hay que hacer que consulte la base de datos
			sidx = columna de ordenacion
			sord = orden de ordenacion (asc/desc)
		*/
		//$usuario_actividad = new Inta_Model_UsuarioActividad();
		$usuario_actividad = new Inta_Model_UsuarioActividad();
		if($comparator!=null){
			$usuario_actividad->setWhere($comparator);
		}
		$datos = array();
		$total_items = $usuario_actividad->searchCount();
		$cantidad_paginas = ceil($total_items/$rows);
		
		//aca consultariamos la base de datos
//		$left = $total_items - ($page-1) * $rows;
//		$left = $left>$rows?$rows:$left;
//		for($i=0;$i<$left;$i++){
//			$obj = new Core_Object();
//			$obj->setId($i+($page-1)*$rows)
//				->setUsername('nombre de usuario')
//				->setNombre('nombre')
//				->setApellido('apellido')
//				->setActivo(true)
//				->setPrivilegios(rand(0,100)<50?'Total':'Vista')
//				->setUltimoAcceso('22/12/2008')
//			;
//			$datos[] = $obj;
//		}
		
		//$datos = $usuario_actividad->search(null,'ASC',null,0,true,array('id', 'username', 'nombre', 'apellido', 'activo', 'privilegios', 'ultimo_acceso'));
		$datos = $usuario_actividad->search($sidx,$sord,$rows,$rows*($page-1),get_class($usuario_actividad));
		//aca termina la consulta a la base
		
		
		$this->setPage($page);
		$this->setRecords($total_items);
		$this->setTotal($cantidad_paginas);
		//$this->setDataResult($datos);
		return($datos);
	}
}
?>