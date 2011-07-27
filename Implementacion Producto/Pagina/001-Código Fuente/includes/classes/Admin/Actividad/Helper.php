<?
class Admin_Actividad_Helper extends Core_Singleton{
	public function getOpcionesEstadosPosiblesDeCambio($actividad){
		if(!is_a($actividad,'Inta_Model_Actividad')){
			$actividad = new Inta_Model_Actividad($actividad->getData());
		}
		return $actividad->getOpcionesEstadosPosiblesDeCambio();
	}
	public function canEditPorcentajeCumplimiento($actividad){
		if(!is_a($actividad,'Inta_Model_Actividad')){
			$actividad = new Inta_Model_Actividad($actividad->getData());
		}
		return $actividad->canEditPorcentajeCumplimiento();
	}
	public function canEditCronograma($actividad){
		if(!is_a($actividad,'Inta_Model_Actividad')){
			$actividad = new Inta_Model_Actividad($actividad->getData());
		}
		return $actividad->canEditCronograma();
	}
	public function canEditPorcentajeTiempo($actividad){
		if(!is_a($actividad,'Inta_Model_Actividad')){
			$actividad = new Inta_Model_Actividad($actividad->getData());
		}
		return $actividad->canEditPorcentajeTiempo();
	}
	public function canEditPresupuestoEstimado($actividad){
		if(!is_a($actividad,'Inta_Model_Actividad')){
			$actividad = new Inta_Model_Actividad($actividad->getData());
		}
		return $actividad->canEditPresupuestoEstimado();
	}
	public function canEditNombre($actividad){
		if(!is_a($actividad,'Inta_Model_Actividad')){
			$actividad = new Inta_Model_Actividad($actividad->getData());
		}
		return $actividad->canEditNombre();
	}
	public function canEditAno($actividad){
		if(!is_a($actividad,'Inta_Model_Actividad')){
			$actividad = new Inta_Model_Actividad($actividad->getData());
		}
		return $actividad->canEditAno();
	}
	public function canEditIdResponsable($actividad){
		if(!is_a($actividad,'Inta_Model_Actividad')){
			$actividad = new Inta_Model_Actividad($actividad->getData());
		}
		return $actividad->canEditIdResponsable();
	}
	public function canViewMotivoAtrasado($actividad){
		if(!is_a($actividad,'Inta_Model_Actividad')){
			$actividad = new Inta_Model_Actividad($actividad->getData());
		}
		return $actividad->canViewMotivoAtrasado();
	}
	public function canViewMotivoCancelado($actividad){
		if(!is_a($actividad,'Inta_Model_Actividad')){
			$actividad = new Inta_Model_Actividad($actividad->getData());
		}
		return $actividad->canViewMotivoCancelado();
	}
	public function getResponsable($actividad){
		if(!is_a($actividad,'Inta_Model_Actividad')){
			$actividad = new Inta_Model_Actividad($actividad->getData());
		}
		return $actividad->getResponsable();
	}
	public function getActividad($actividad){
		if(!is_a($actividad,'Inta_Model_Actividad')){
			$actividad = new Inta_Model_Actividad($actividad->getData());
		}
		return $actividad;
	}
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
        public static function adapterActividad($post){
            
        }
        public static function actionAgregarEditarActividad($actividad, $aActividadProyecto, $aResultadoEsperadoActividad){
		if(!is_a($actividad,'Inta_Model_Actividad')){
			$actividad = new Inta_Model_Actividad($actividad->getData());
		}
		if(!$actividad->hasId()){/** aca hay que agregar a la base de datos*/
//                    die("soy un kradekk");
			$resultado = $actividad->replace()?true:false;
			//$insertada = true;// insertarEnLaBase()
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Actividad a�adida correctamente'));
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo agregar la Actividad, error en la operaci�n");
			}
                        //Mat, meto el link actividad proyecto
                        $resultadoActividadProyecto = true;
                        foreach($aActividadProyecto As $actividad_proyecto){
                            $actividad_proyecto->setIdActividad($actividad->getId());
                            if(!$actividad_proyecto->replace())
                                $resultadoActividadProyecto = false;
                        }

                        //Mat, meto el link actividad resultado esperado
                        $resultadoActividadResultadoEsperado = true;
                        foreach($aResultadoEsperadoActividad As $resultado_esperado_actividad){
                            $resultado_esperado_actividad->setIdActividad($actividad->getId());
                            if(!$resultado_esperado_actividad->replace())
                                $resultadoActividadResultadoEsperado = false;
                        }
		}
		else{/** aca hay que actualizar el registro*/
			//$actualizada = true;// actualizarEnLaBase()
			$resultado = $actividad->update(null)?true:false;
			//echo Core_Helper::DebugVars(Inta_Db::getInstance()->getLastQuery());
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Actividad actualizada correctamente'));
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo actualizar la Actividad, error en la operaci?n");
			}
//                        return($resultado);

//                        $actividad_proyecto = new Inta_Model_ActividadProyecto();
//                        $actividad_proyecto->delete(array('id_actividad'=>$id_actividad));
                         //Mat, meto el link actividad proyecto
                        $resultadoActividadProyecto = true;
                        foreach($aActividadProyecto As $actividad_proyecto){
                            $actividad_proyecto->setIdActividad($actividad->getId());
                            if(!$actividad_proyecto->replace())
                                $resultadoActividadProyecto = false;
                        }

                         //Mat, meto el link actividad resultado esperado
                        $resultadoActividadResultadoEsperado = true;
                        foreach($aResultadoEsperadoActividad As $resultado_esperado_actividad){
                            $resultado_esperado_actividad->setIdActividad($actividad->getId());
                            if(!$resultado_esperado_actividad->replace())
                                $resultadoActividadResultadoEsperado = false;
                        }

//                        $actividad_proyecto->setIdActividad($actividad->getId());
//                        echo "<br>IDACTIVIDAD: " . $actividad->getId();
//                        $contador = 0;
//                        foreach($actividad['id_proyecto'] As $var_id_proyecto){
//                            $actividad_proyecto->setIdProyecto($var_id_proyecto);
//                            $actividad_proyecto->setMonto(isset($actividad['monto['.$contador.']']) ? $actividad['monto'] : 0);
//                            echo "<br>vd_actividad_proyecto: " . var_dump($actividad_proyecto);
//                            $resultado_proyecto = $actividad_proyecto->replace()?true:false;
//                            if($resultado_proyecto){
//                             Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Actividad a�adida correctamente'));
//                            }
//                            else{
//                                    Admin_App::getInstance()->addErrorMessage("No se pudo relacionar con el Proyecto, error en la operaci�n");
//                            }
//                            $contador ++;
//                        }
 		}
//                $resultado = $resultado&&$resultado_proyecto?true:false;
		return($resultado);
	}
	public static function actionEliminarActividad($id_actividad){
		if(self::eliminarActividad($id_actividad)){
			Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Actividad Eliminada Correctamente'));
		}
		else{
			Admin_App::getInstance()->addErrorMessage(self::getInstance()->__t('No se pudo eliminar la Actividad'));
		}
	}
	public static function eliminarActividad($id_actividad){
		$actividad = new Inta_Model_Actividad();
//		return($actividad->setId($id_actividad)->delete());
		$ret = $actividad->setId($id_actividad)->delete();
		foreach($actividad->getTranslatedErrors() as $error)
			Admin_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
		return($ret);
	}
}
?>