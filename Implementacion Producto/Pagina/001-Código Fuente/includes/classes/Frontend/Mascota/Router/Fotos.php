<?php /*es útf8*/
class Frontend_Mascota_Router_Fotos extends Frontend_Router_Abstract{
	protected function initialize(){
		$this->addActions(
			'add'
			,'upload'
			,'ajax_upload'
			,'delete'
		);
	}
	protected function localDispatch(){
		die(__FILE__.__LINE__);
	}
	protected function add($jsonp_callback='', $id_mascota=null){
		$id_mascota = $id_mascota&&$id_mascota!='null'?$id_mascota:null;	
		$this->setPageReference('Carga de fotografías', '');
		Core_App::getLayout()
			->setModo('saludmascotas')
			->setActions('simple_layout','mascota_upload_foto')
		;
		//loaded layout
		$loaded_layout = Core_App::getLoadedLayout();
		$form_edit = $loaded_layout->getBlock('form_edit');
		$form_edit->setUploadUrl(Frontend_Mascota_Helper::getUrlUploadPhoto(false, $jsonp_callback, $id_mascota));
		$file_upload = $loaded_layout->getBlock('file_upload');
		$file_upload->setUploadUrl(Frontend_Mascota_Helper::getUrlUploadPhoto(true, $jsonp_callback, $id_mascota));
		
//		var_dump(Core_App::getLayout()->getActions());
//		die(__FILE__.__LINE__);

	}
	private function getFileName($ext, $key='archivo'){
		$usuario = Frontend_Usuario_Model_User::getLogedUser();
		$user_dir = 'usuario'.$usuario->getId();
		$dir = CFG_PATH_ROOT.'/'.CONF_SUBPATH_UPLOADS.$user_dir;
		if(!file_exists($dir))
			mkdir($dir);
		$file_name = md5(time().$key).'.'.$ext;
		return array($dir.'/'.$file_name, $user_dir.'/'.$file_name);
	}
	protected function upload($jsonp_callback='', $id_mascota=null){
		$error = '';
		if(Core_Http_Files::hasParameters() && $files = Core_Http_Files::getParameters('Core_Object')){
			foreach($files as $form_name=>$file){
				$ext = explode('.', $file->getName());
				$ext = strtolower(array_pop($ext));
				if(in_array($ext, array('png','jpg','gif'))){
					$file_name = $this->getFileName($ext, 'foto');
					if(move_uploaded_file($file->getTmpName(), $file_name[0])){
						$usuario = Frontend_Usuario_Model_User::getLogedUser();
						$id_usuario = $usuario->getId();
						$foto_mascota = new Saludmascotas_Model_FotoMascota();
						$foto_mascota
							->setRuta($file_name[1])
							->setIdUsuario($id_usuario)
							->setFechaCarga(time())
						;
						if($id_mascota)
							$foto_mascota->setIdMascota($id_mascota);
						$guardada = $foto_mascota->insert();
						if($guardada){
							$params = json_encode(array(
								'src'=>$foto_mascota->getUrl(),
								'thumb_src'=>$foto_mascota->getThumbUrl(),
								'id'=>$foto_mascota->getId(),
							));
							echo <<<salida
<script type="text/javascript">
	with(window.parent){
		{$jsonp_callback}.add_image($params);
		jQuery.fancybox.close();
	}
</script>
salida;
						}
						else{$error = 'Error durante el guardado del archivo';}
					}
					else{$error = 'Error durante la carga del archivo';}
				}
				else{$error = 'Solo se admiten imagenes PNG, JPG, GIF';}
			}
		}
		$error = json_encode($error);
							echo <<<salida
<script type="text/javascript">
	with(window.parent){
		alert($error)
	}
</script>
salida;
	}
	protected function ajax_upload($jsonp_callback='', $id_mascota=null){
		$headers = Core_Http_Header::getallheaders();
		if(
			// basic checks
			isset(
			$headers['X-Requested-With'],
			$headers['Content-Type'],
			$headers['Content-Length'],
			$headers['X-File-Size'],
			$headers['X-File-Name'],
			$headers['X-Current-File']
			) &&
			$headers['X-Requested-With']=='XMLHttpRequest' &&
			$headers['Content-Type'] === 'multipart/form-data' &&
			$headers['Content-Length'] === $headers['X-File-Size']
		){
			// create the object and assign property
			$file = new stdClass;
			$file->name = basename($headers['X-File-Name']);
			$file->size = $headers['X-File-Size'];
			$file->content = file_get_contents("php://input");
			list($current, $total) = explode('/', $headers['X-Current-File']);
			$this->addAjaxFile($jsonp_callback, $id_mascota, $file, $current==$total);
			//var_dump($file->content);
			exit('OK '.$headers['X-Current-File'].' '.strlen($file->content).'-'.$headers['X-File-Size']);
			// if everything is ok, save the file somewhere
			if(file_put_contents('files/'.$file->name, $file->content))
			exit('OK');
		}
		// if there is an error this will be the output instead of "OK"
		exit('Error');
	}
	private function addAjaxFile($jsonp_callback, $id_mascota, $file, $flush=true){
		$error = '';
		
		$ext = explode('.', $file->name);
		$ext = strtolower(array_pop($ext));
		if(in_array($ext, array('png','jpg','gif'))){
			$file_name = $this->getFileName($ext, 'foto');
			if((file_put_contents($file_name[0], $file->content))){
			//if(move_uploaded_file($file->getTmpName(), $file_name[0])){
				$usuario = Frontend_Usuario_Model_User::getLogedUser();
				$id_usuario = $usuario->getId();
				$foto_mascota = new Saludmascotas_Model_FotoMascota();
				$foto_mascota
					->setRuta($file_name[1])
					->setIdUsuario($id_usuario)
					->setFechaCarga(time())
				;
				if($id_mascota)
					$foto_mascota->setIdMascota($id_mascota);
				$guardada = $foto_mascota->insert();
				if($guardada){
					$ajax_uploaded_files = $usuario->getSessionVar('ajax_uploaded_files', array(__CLASS__));
					if(!$ajax_uploaded_files)
						$ajax_uploaded_files = array();
					$ajax_uploaded_files[] = $foto_mascota;
					if(!$flush){
						$usuario->setSessionVar('ajax_uploaded_files', $ajax_uploaded_files, array(__CLASS__));
					}
					else{
						$params = array();
						foreach($ajax_uploaded_files as $foto_mascota){
							$params[] = array(
								'src'=>$foto_mascota->getUrl(),
								'thumb_src'=>$foto_mascota->getThumbUrl(),
								'id'=>$foto_mascota->getId(),
							);
						}
						$params = substr(json_encode($params), 1, -1);
						echo <<<salida
<script type="text/javascript">
with(window.parent){
{$jsonp_callback}.add_image($params);
jQuery.fancybox.close();
}
</script>
salida;
						$usuario->setSessionVar('ajax_uploaded_files', null, array(__CLASS__));
					}
//					$params = json_encode(array(
//						'src'=>$foto_mascota->getUrl(),
//						'thumb_src'=>$foto_mascota->getThumbUrl(),
//						'id'=>$foto_mascota->getId(),
//					));
//					echo <<<salida
//<script type="text/javascript">
//with(window.parent){
//{$jsonp_callback}.add_image($params);
//jQuery.fancybox.close();
//}
//</script>
//salida;
				}
				else{$error = 'Error durante el guardado del archivo';}
			}
			else{$error = 'Error durante la carga del archivo';}
		}
		else{$error = 'Solo se admiten imagenes PNG, JPG, GIF';}
	}
	protected function delete($id){
		$return = new Core_Object();
		
		if($usuario = $this->getLogedUser()){
			$foto_mascota = new Saludmascotas_Model_FotoMascota();
			$foto_mascota->setId($id);
			if($foto_mascota->load()){
				if($foto_mascota->getIdUsuario()==$usuario->getId()){
					if($foto_mascota->delete(array('id'=>$id))){
						$return
							->setMessage('Foto eliminada correctamente')
							->setErrorId(0);
						;
					}
					else{
						$return
							->setMessage('No se pudo eliminar la foto, ha ocurrido un error en el proceso')
							->setErrorId(4);
						;
					}
				}
				else{
					$return
						->setMessage('La foto que intenta eliminar no le pertenece')
						->setErrorId(3);
					;
				}
			}
			else{
				$return
					->setMessage('La foto que intenta eliminar no existe')
					->setErrorId(2);
				;
			}
		}
		else{
			$return
				->setMessage('Ud no se encuentra logeado o la sesión se ha perdido')
				->setErrorId(1);
			;
		}
		echo $return->jsonEncode();
		die();
	}
	//protected function dispatchNode($nodo){//esto es cuando hay algo despues de la url.
//		echo 'nodo mascotas';
//		die(__FILE__.__LINE__);
//		$nodo = trim($nodo);
//		$nodo = strtolower($nodo);
//		switch($nodo){
//			case 'privacy-policy':{
//				$this->setPageReference('Políticas de Privacidad', 'Informate sobre las precauciones a tu privacidad');
//				Core_App::getLayout()
//					->setModo('saludmascotas')
//					->addActions('privacy_policy')
//				;
//				break;
//			}
//			case 'service-conditions':{
//				$this->setPageReference('Condiciones del Servicio', 'Informate sobre nuestro servicio');
//				Core_App::getLayout()
//					->setModo('saludmascotas')
//					->addActions('service_conditions')
//				;
//				break;
//			}
//			default:{
//				return false;
//				break;
//			}
//			case '':{
//				break;
//			}
//		}
//		return true;
//	}
}
?>