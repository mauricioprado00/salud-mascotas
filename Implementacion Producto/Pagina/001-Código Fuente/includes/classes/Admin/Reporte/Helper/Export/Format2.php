<?php
class Admin_Reporte_Helper_Export_Format2 extends Core_Singleton{
	const USE_SHELL_EXEC = true;
	const DOWNLOAD_FILE_NAME = 'Actividades_x_Tipo_x_Agencia.docx';
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public function export(){
		$collection = $this->getCollection();
		$document_xml = $this->getXmlString($collection);
		$document_rels_xml = $this->getXmlStringRels($collection);
		if($dirname = $this->saveToFile($document_xml, $document_rels_xml, $collection)){
			$zipname = $this->createZip($dirname);
			Core_Http_Header::ContentType('application/vnd.openxmlformats-officedocument.wordprocessingml.document');
			Core_Http_Header::ContentDisposition(self::DOWNLOAD_FILE_NAME);
			readfile($zipname);
			unlink($zipname);
			Core_File_Helper::getInstance()->remDir($dirname);
		}
		die();
	}
	private function useShellExec(){
		return self::USE_SHELL_EXEC || !class_exists('ZipArchive');
	}
	private function createZip($dirname){
		$zipname = realpath($dirname).'.zip';
		if(file_exists($zipname))
			unlink($zipname);
		if($this->useShellExec()){
			chdir($dirname);
			//`zip -R $zipname $dirname`;
			`zip -r $zipname .`;
		}
		else{
			// create object
			$zip = new ZipArchive();
			
			// open archive 
			if ($zip->open($zipname, ZIPARCHIVE::CREATE) !== TRUE) {
			    die ("Could not open archive");
			}
			
			// list of files to add
			// list of files to add
			Core_File_Helper::getInstance()->listDir($dirname, $fileList);
			
			
			// add files
			foreach ($fileList as $f) {
				$ln = str_replace($dirname.'/', '',$f);
				$ln = str_replace($dirname, '',$ln);
			    $zip->addFile($f, $ln) or die ("ERROR: Could not add file: $f. ".__FILE__.__LINE__);
			}
			    
			// close and save archive
			$zip->close();
		}
		//echo "Archive created successfully.".$zipname;
		return $zipname;
	}
	private function saveToFile($xml, $rels_xml, $collection){
		$dirname = Core_File_Helper::getInstance()->tempnam();
		//$dirname = tempnam(dirname(__FILE__).'/tmp/', '');
		if(file_exists($dirname))
			unlink($dirname);
		mkdir($dirname);
		Core_File_Helper::getInstance()->copyAll(dirname(__FILE__).'/resource/docx/format2.v2', $dirname);
		
		$dir_embeddings = $dirname.'/word/embeddings';
		foreach($collection as $g_agencia){
			$agencia = $g_agencia->getAgencia();
			if($res = $agencia->getDocumentoCaracterizacion()){
				if(!file_exists($dir_embeddings)){
					mkdir($dir_embeddings);
				}
				copy($res->getFullPath(), $dir_embeddings.'/'.c($agencia->getDocumentoCaracterizacionToken())->getToken());
				//var_dump($res->getData(), $res->getFullPath());				
			}
		}
		
		file_put_contents($dirname.'/word/document.xml', $xml);
		file_put_contents($dirname.'/word/_rels/document.xml.rels', $rels_xml);
		return $dirname;
		//var_dump($dirname, scandir($dirname));
	}
	private $_collection = null;
	public function getCollection(){
		if(!isset($this->_collection)){
			$reporte = new Inta_Model_Reporte_View_ActividadByObjetivo();
			//$reporte->setWhere(Db_Helper::equal('id_usuario_logeado', 4));
			$reporte->setWhere(Db_Helper::equal('id_usuario_logeado', Admin_User_Model_User::getLogedUser()->getId()));
			$actividades = $reporte->search(null, 'ASC', null, 0, 'Inta_Model_Reporte_View_Actividad');
			$c = new Core_Collection($actividades);
	
			$g = $c->groupByAs(array('id_agencia', 'id_objetivo', 'id_resultado_esperado'), 'Inta_Collection_Reporte_ByResultadoEsperado');
			$g = $g->groupByAs(array('id_agencia', 'id_objetivo'), 'Inta_Collection_Grouped_Reporte_ByObjetivo');
			$g = $g->groupByAs(array('id_agencia'), 'Inta_Collection_Grouped_Reporte_ByAgencia');
			
			$this->_collection = $g;
		}
		return $this->_collection;
	}
	private function getXmlString($g){
		
//		foreach($g as $g_agencia){
//			$agencia = $g_agencia->getAgencia();
////			$documentos = $agencia->getListDocumento();
////			foreach($documentos as $doc)
//			if($res = $agencia->getDocumentoCaracterizacion()){
//				
//			}
//			
//			var_dump($res->getData(), $res->getFullPath());
//		}
//		die();

		$datamodel = ('
		<model for="entity">
			<model for="inta_actividad_byagencia">
				<model for="inta_actividad_byobjetivo">
					<model for="inta_actividad_byresultado_esperado">
						<method name="actividad_resultado_esperado" method="getResultadoEsperado" multiplicity="single" >
							<model for="resultado_esperado">
								<!--
								<field name="id" />
								-->
								<field name="descripcion" />
								<method name="resultado_esperado_indicador" method="getListIndicador" multiplicity="multiple">
									<model for="indicador_resultado">
										<field name="adecuado" />
										<field name="descripcion" />
										<method name="resultado_esperado_indicador_instancia" method="getIndicador" multiplicity="single">
										</method>
										<method name="resultado_esperado_indicador_medios_verificacion" method="getListMedioVerificacion" multiplicity="multiple">
											<model for="medio_verificacion_indicador_resultado">
												<method name="medio_verificacion_instancia" method="getMedioVerificacion" multiplicity="single">
												</method>
											</model>
										</method>
									</model>
								</method>
							</model>
						</method>
						<model for="inta_actividad">
							<method name="actividad_instancia" method="getActividad" multiplicity="single">
								<model for="actividad">
									<!--
									<field name="porcentaje_cumplimiento" />
									<field name="presupuesto_estimado" />
									<field name="ano" />
									<field name="observaciones" />
									<field name="comentario" />
									<field name="motivo_atrasado" />
									<field name="motivo_cancelado" />
									-->
									<field name="nombre" />
									<field name="porcentaje_tiempo" />
									<field name="mes_enero" />
									<field name="mes_febrero" />
									<field name="mes_marzo" />
									<field name="mes_abril" />
									<field name="mes_mayo" />
									<field name="mes_junio" />
									<field name="mes_julio" />
									<field name="mes_agosto" />
									<field name="mes_septiembre" />
									<field name="mes_octubre" />
									<field name="mes_noviembre" />
									<field name="mes_diciembre" />
									<field name="estado" />
									<method name="actividad_responsable" method="getResponsable" multiplicity="single">
										<model for="usuario">
											<!--
											<field name="activo" />
											<field name="username" />
											<field name="password" />
											<field name="privilegios" />
											<field name="ultimo_acceso" />
											-->
											<field name="nombre" />
											<field name="apellido" />
											<field name="email" />
										</model>
									</method>
									<method name="actividad_proyectos" method="getListProyecto" multiplicity="multiple">
										<model for="proyecto_actividad">
											<field name="monto" />
											<method name="proyecto_instancia" method="getProyecto" multiplicity="single">
												<model for="proyecto">
													<field name="nombre" />
												</model>
											</method>
										</model>
									</method>
									<method name="actividad_estrategias" method="getListEstrategia" multiplicity="multiple">
										<model for="estrategia_actividad">
											<method name="estrategia_instancia" method="getEstrategia" multiplicity="single">
												<model for="estrategia">
													<field name="nombre" />
												</model>
											</method> 
										</model>
									</method>
								</model>
							</method>
						</model>
					</model>
					<method name="actividad_objetivo" method="getObjetivo" multiplicity="single" >
						<model for="objetivo">
							<field name="id" />
							<field name="descripcion" />
							<method name="actividad_objetivo_problemas" method="getListProblema" multiplicity="multiple">
								<model for="objetivo_problema">
									<method name="actividad_objetivo_problema_instancia" method="getProblema" multiplicity="simple">
										<model for="problema">
											<field name="id" />
											<field name="nombre" />
											<field name="importancia_economica" />
											<field name="impacto_ambiental" />
											<field name="importancia_social" />
											<field name="familias_implicadas" />
											<field name="valor_agregado_potencial" />
											<field name="impacto_desarrollo" />
											<field name="prioridad" />
											<method name="problema_audiencia" method="getAudiencia" multiplicity="simple">
											</method>
										</model>
									</method>
								</model>
							</method>
						</model>
					</method>
				</model>
				<method name="actividad_agencia" method="getAgencia" multiplicity="single" >
					<model for="agencia">
						<field name="nombre" />
						<field name="id_localidad" />
						<field name="direccion" />
						<field name="telefono" />
						<field name="email" />
						<field name="agentes" />
						<field name="descripcion" />
						<method name="agencia_usuario" method="getListUsuario" multiplicity="multiple">
						</method>
						<method name="documento_caracterizacion" method="getDocumentoCaracterizacionToken" multiplicity="single">
						</method>
					</model>
				</method>
				<method name="audiencias_priorizadas" method="getAudienciasPriorizadas" multiplicity="single">
					<model for="entity">
						<model for="audiencia">
							<field name="nombre"/>
							<field name="id"/>
							<method name="audiencia_tipo_audiencia" method="getTipoAudiencia" multiplicity="single">
							</method>
							<method name="audiencia_problemas" method="getListProblema" multiplicity="multiple">
							</method>
						</model>
					</model>
				</method>
			</model>
		</model>');
		
//		Core_Http_Header::ContentType('text/xml');
//		echo '<?xml version="1.0" encoding="UTF-8" standalone="yes" ? >';
//		echo $g->toXmlString($datamodel);
//		die();//Jqgrid_Block_XmlServer
		$xs = new Core_Xslt_Server();
		$xs->setSource($g, $datamodel);
		$xs->appendStyle(dirname(__FILE__).'/resource/reporte2todoc.v6.xsl');
//		Core_Http_Header::ContentType('text/xml');
//		echo $xs->toXmlString();
//		die();
		return $xs->toXmlString();
	}
	private function getXmlStringRels($g){
		$agencias = new Core_Collection();
		foreach($g as $g_agencia){
			$agencia = $g_agencia->getAgencia();
			$agencias->addItem($agencia);
		}
		
		$datamodel = ('
		<model for="entity">
			<model for="agencia">
				<!--
				<field name="nombre" />
				<field name="id_localidad" />
				<field name="direccion" />
				<field name="telefono" />
				<field name="email" />
				<field name="agentes" />
				<field name="descripcion" />
				<method name="agencia_usuario" method="getListUsuario" multiplicity="multiple">
				</method>
				-->
				<method name="documento_caracterizacion" method="getDocumentoCaracterizacionToken" multiplicity="single">
				</method>
			</model>
		</model>');
		
//		Core_Http_Header::ContentType('text/xml');
//		echo '<?xml version="1.0" encoding="UTF-8" standalone="yes" ? >';
//		echo $agencias->toXmlString($datamodel);
//		die();//Jqgrid_Block_XmlServer
		$xs = new Core_Xslt_Server();
		$xs->setSource($agencias, $datamodel);
		$xs->appendStyle(dirname(__FILE__).'/resource/reporte2todocrels.v1.xsl');
//		Core_Http_Header::ContentType('text/xml');
//		echo $xs->toXmlString();
//		die();
		return $xs->toXmlString();
	}
}
?>