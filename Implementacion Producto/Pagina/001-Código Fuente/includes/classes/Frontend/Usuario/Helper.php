<?php //es útf8
class Frontend_Usuario_Helper extends Frontend_Helper{
	public static function getUrl(){
		return 'user';
	}
	public static function getUrlLogin($usuario=null){
		return self::getUserUrl('user/login', $usuario, 'username');
		return 'user/login';
	}
	public static function getUrlLogout(){
		return self::getUserUrl('user/logout');
		return 'user/logout';
	}
	public static function getUrlRegister(){
		return 'user/register';
	}
	public static function getUrlUpdate(){
		return 'user/update';
	}
	public static function getUrlUpdateAddress(){
		return 'user/update_address';
	}
	public static function getUrlUpdatePassword(){
		return 'user/update_password';
	}
	public static function getUrlRecover($usuario=null){
		return self::getUserUrl('user/recover', $usuario);
	}
	public static function getUrlServiceConditions(){
		return 'user/service-conditions';
	}
	public static function getUrlPrivacyPolicy(){
		return 'user/privacy-policy';
	}
	public static function getUrlActivate($usuario){
		$max_time = time() + 60 * 2;//5 minutos
		if(is_object($usuario)&&is_a($usuario, 'Saludmascotas_Model_User'))
			$username = $usuario->getUsername();
		elseif(is_string($usuario))
			$username = $usuario;
		else return null;
		$hash = Core_App::SignData($username . $max_time);
		//$hash = $username . $max_time . CONF_SECRETHASH;
		//$hash = md5($hash);
		return 'user/activate/' . $username . '/' . $max_time . '/' . $hash;
	}
	public static function validateActivacionSignature($username, $max_time, $hash, $only_signature=false){
		return $hash == Core_App::SignData($username . $max_time) && ($only_signature||$max_time>time());
	}
	private static function getUserUrl($url, $usuario=null, $field='email'){
		if(!isset($usuario))
			return $url;
			
		if(is_object($usuario)&&is_a($usuario, 'Saludmascotas_Model_User')){
			$field_value = $usuario->getData($field);
		}
		elseif(is_string($usuario)){
			$field_value = $usuario;
		}
		else return null;
		
		return $url.'/'.$field_value;
	}
	public static function actionAgregarEditarUsuario($usuario){
		if(!is_a($usuario,'Frontend_Usuario_Model_User')){
			$array = $usuario->getData();
			$usuario = new Frontend_Usuario_Model_User();
			$usuario->loadFromArray($array);
		}
		$usuario->setFieldLabel('password2', ('Contraseña'));
		$usuario->addValidator('password2', new Zend_Validate_Identical(array('token'=>$usuario->getPassword())));
		if(!$usuario->validateFields()){
			Core_App::getInstance()->addErrorMessage("No se pudo registrar el usuario");
			Core_Helper::LoadValidationTranslation();
			foreach($usuario->getValidationMessages() as $key=>$messages){
				foreach($messages as $message){
					Core_App::getInstance()->addErrorMessage($message);
				}
			}
			return false;
		}
		$usuario->unsetData('password2');
		if(!$usuario->hasId()){/** aca hay que agregar a la base de datos*/
			$usuario->setFechaAlta(time());
			$resultado = $usuario->insert()?true:false;
			if($resultado){
				Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Usuario registrado correctamente'), true);
				Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Revisa tu email para continuar con la activación de usuario'), true);
				self::actionEnviarCorreoRegistro($usuario);
			}
			else{
				Core_App::getInstance()->addErrorMessage("No se pudo registrar el usuario");
				foreach($usuario->getTranslatedErrors() as $error){
					Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
				}
			}
		}
		else{/** aca hay que actualizar el registro*/
			//$actualizada = true;// actualizarEnLaBase()
			$resultado = $usuario->update(null)?true:false;
			//echo Core_Helper::DebugVars(Inta_Db::getInstance()->getLastQuery());
			if($resultado){
				Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Sus datos han sido actualizados'));
			}
			else{
				Core_App::getInstance()->addErrorMessage("No se pudo actualizar sus datos");
				foreach($usuario->getTranslatedErrors() as $error){
					Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
				}
			}
 		}
		return($resultado);
	}
	public static function actionAgregarEditarDomicilio($domicilio){
		if(!is_a($domicilio,'Frontend_Model_Domicilio')){
			$array = $domicilio->getData();
			$domicilio = new Frontend_Model_Domicilio();
			$domicilio->loadFromArray($array);
		}
		$errors = array();
		if(((float)$domicilio->getLng())==0 || ((float)$domicilio->getLat())==0 ){
			$errors[] = 'Debe seleccionar un punto en el mapa';
		}
		if(!$domicilio->validateFields()||$errors){
			Core_App::getInstance()->addErrorMessage("No se pudo actualizar el domicilio");
			Core_Helper::LoadValidationTranslation();
			foreach($errors as $message){
				Core_App::getInstance()->addErrorMessage(self::getInstance()->__t($message));
			}
			if($domicilio->getValidationMessages())
			foreach($domicilio->getValidationMessages() as $key=>$messages){
				foreach($messages as $message){
					Core_App::getInstance()->addErrorMessage($message);
				}
			}
			return false;
		}
		$usuario = self::getLogedUser();
		if(!$domicilio->hasId()){/** aca hay que agregar a la base de datos*/
			//$domicilio->setFechaAlta(time());
			$resultado = $domicilio->insert()?true:false;
			if($resultado){
				//$domicilio->restorePrivateData();
				$usuario->setIdDomicilio($domicilio->getId());
				if($usuario->update())
					Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Su domicilio fué registrado correctamente'));
				else
					Core_App::getInstance()->addErrorMessage("Su domicilio no pudo ser registrado, intentelo nuevamente");
				//Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Revisa tu email para continuar con la activación de domicilio'), true);
				//self::actionEnviarCorreoRegistro($domicilio);
			}
			else{
				Core_App::getInstance()->addErrorMessage("Su domicilio no pudo ser actualizado");
				foreach($domicilio->getTranslatedErrors() as $error){
					Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
				}
			}
		}
		else{/** aca hay que actualizar el registro*/
			//$actualizada = true;// actualizarEnLaBase()
			$resultado = $domicilio->update(null)?true:false;
			//echo Core_Helper::DebugVars(Inta_Db::getInstance()->getLastQuery());
			if($resultado){
				//$domicilio->restorePrivateData();
				$usuario->setIdDomicilio($domicilio->getId());
				if($usuario->update())
					Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Su domicilio fué actualizado correctamente'));
				else
					Core_App::getInstance()->addErrorMessage("Su domicilio no pudo ser actualizado, intentelo nuevamente");
				
			}
			else{
				Core_App::getInstance()->addErrorMessage("Su domicilio no pudo ser actualizado");
				foreach($domicilio->getTranslatedErrors() as $error){
					Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
				}
			}
 		}
		return($resultado);
	}
	public static function actionActualizarPassword($usuario){
		if(!is_a($usuario,'Frontend_Usuario_Model_User')){
			$array = $usuario->getData();
			$usuario = new Frontend_Usuario_Model_User();
			$usuario->loadFromArray($array);
		}
		$errors = array();
		if($usuario->getCurrentPassword()!=$usuario->getPassword()){
			$errors[] = 'La <b>contraseña actual</b> no es válida';
		}
		$usuario->setFieldLabel('current_password', ('Nueva Contraseña'));
		$usuario->addValidator('current_password', new Zend_Validate_NotEmpty());
		$usuario->setFieldLabel('new_password', ('Nueva Contraseña'));
		$usuario->addValidator('new_password', new Zend_Validate_NotEmpty());
		$usuario->setFieldLabel('new_password2', ('Nueva Contraseña'));
		$usuario->addValidator('new_password2', new Zend_Validate_Identical(array('token'=>$usuario->getNewPassword())));
		if(!$usuario->validateFields()||$errors){
			Core_App::getInstance()->addErrorMessage("No se pudo actualizar su contraseña");
			Core_Helper::LoadValidationTranslation();
			foreach($errors as $message){
				Core_App::getInstance()->addErrorMessage(self::getInstance()->__t($message));
			}
			if($usuario->getValidationMessages())
			foreach($usuario->getValidationMessages() as $key=>$messages){
				foreach($messages as $message){
					Core_App::getInstance()->addErrorMessage($message);
				}
			}
			return false;
		}
//		$usuario->setPassword($usuario->getNewPassword());
//		$usuario->unsetData('current_password');
//		$usuario->unsetData('new_password');
//		$usuario->unsetData('new_password2');

		$resultado = $usuario->update(array('password'=>$usuario->getNewPassword()))?true:false;
		if($resultado){
			Core_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Su contraseña ha sido actualizada'), true);
		}
		else{
			Core_App::getInstance()->addErrorMessage("No se pudo actualizar su contraseña");
			foreach($usuario->getTranslatedErrors() as $error){
				Core_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
			}
		}
		return($resultado);
	}
	public static function activateUsuario($usuario, $max_time, $hash){
		$usuario = self::getUsuario($usuario);
		$username = $usuario->getUsername();
		if(self::validateActivacionSignature($username, $max_time, $hash)){
			$activado = $usuario
				->setActivo('si')
				->update()
			;
			if(!$activado){
				Core_App::getInstance()->addErrorMessage(self::getInstance()->__t('Ha ocurrido un error, no se pudo realizar la activación. Intentelo en otro momento.'), true);
			}
			else{
				Core_App::getInstance()->addErrorMessage(self::getInstance()->__t('Su usuario ha sido activado correctamente.'), true);
				return true;
			}
		}
		else{
			Core_App::getInstance()->addErrorMessage(self::getInstance()->__t('El enlace no es válido o ha expirado el usuario no pudo ser activado'), true);
			if(!self::actionEnviarCorreoActivación($usuario/*, $max_time, $hash*/)){
				Core_App::getInstance()->addErrorMessage(self::getInstance()->__t('Ha ocurrido un error, no se pudo enviar un nuevo correo de activación para el usuario. Intentelo en otro momento.'), true);
			}
			else{
				Core_App::getInstance()->addErrorMessage(self::getInstance()->__t('Se ha enviado un nuevo email de activación, revise su casilla de email'), true);
			}
		}
		return false;
	} 
	public static function solicitudRecuperacion($username_or_email){
		$validate = new Zend_Validate();
		$validate->addValidator(new Zend_Validate_NotEmpty());
		$validate->addValidator(new Zend_Validate_EmailAddress());
		Core_Helper::LoadValidationTranslation();
		if(!$validate->isValid($username_or_email)){
			$label = 'Email';
			foreach($validate->getMessages() as $message){
				$message = preg_replace('/\'([^\']*)\'/', '<em>$1</em>', $message);
				$message = str_replace('%fieldname%', '<b>'.$label.'</b>', $message);
				Core_App::getInstance()->addErrorMessage($message);
			}
			return false;
		}
		$usuario = new Frontend_Usuario_Model_User();
		$usuario->setEmail($username_or_email);
		if(!$usuario->load()){
			Core_App::getInstance()->addErrorMessage(self::getInstance()->__t('La dirección de correo proveida no concuerda con la de ningún usuario.'), true);
			return false;
		}
		$enviado = Frontend_Usuario_Helper::actionEnviarCorreoRecuperacion($username_or_email);
		if($enviado){
			Core_App::getInstance()->addInfoMessage(self::getInstance()->__t('Se ha enviado un email a tu casilla de email con la información de acceso, revisala.'), true);
			return true;
		}
		Core_App::getInstance()->addErrorMessage(self::getInstance()->__t('Ha ocurrido un error, no se pudo realizar la activación. Intentelo en otro momento.'), true);
		return false;
	}
	public static function tryLogin($username, $password){
		$valid = true;
		$validate = new Zend_Validate();
		$validate->addValidator(new Zend_Validate_NotEmpty());
		Core_Helper::LoadValidationTranslation();
		if(!$validate->isValid($username)){
			$label = 'Nombre de usuario o email';
			foreach($validate->getMessages() as $message){
				$message = preg_replace('/\'([^\']*)\'/', '<em>$1</em>', $message);
				$message = str_replace('%fieldname%', '<b>'.$label.'</b>', $message);
				Core_App::getInstance()->addErrorMessage($message);
			}
		}
		if(!$validate->isValid($password)){
			$label = 'Contraseña';
			foreach($validate->getMessages() as $message){
				$message = preg_replace('/\'([^\']*)\'/', '<em>$1</em>', $message);
				$message = str_replace('%fieldname%', '<b>'.$label.'</b>', $message);
				Core_App::getInstance()->addErrorMessage($message);
			}
		}
		if(!$valid)
			return false;
		$usuario = new Frontend_Usuario_Model_User();
		$usuario
			->login($username, $password)
		;
		return $usuario->isLoged();
	}
	public static function logout(){
		$x = new Frontend_Usuario_Model_User();
		$x->logout();
		Core_App::getInstance()->addInfoMessage(self::getInstance()->__t("Has cerrado sesión correctamente"), true);
		//Core_Http_Header::Redirect(Core_App::getUrlModel()->getUrl(''));
	}
	public static function actionEnviarCorreoRegistro($usuario){
		//preparo layout y template		
		$email_layout = new Saludmascotas_Email('email_usuario_register');
		
		$activation_url = Frontend_Usuario_Helper::getUrlActivate($usuario);
		$recover_url = Frontend_Usuario_Helper::getUrlRecover($usuario);
		$email_template = 
			$email_layout
				->loadDom()
				->getBlock('usuario_register_message');
		$email_template
			->setUsuario($usuario)
			->setActivationUrl($activation_url)
			->setRecoverUrl($recover_url)
		;
		
		//$reserva->addAutofilterOutput('utf8_decode');
		
		//envio email
		$subject = 'Usuario Registrado en SaludMascotas';
		$email_layout->AddAddress($usuario->getEmail(), $usuario->getNombre().' '.$usuario->getApellido());
		$email_layout->fromSelf();
		$email_layout->agregarNotificacion();
		$mensaje_no_html = 'Si no puede visualizar correctamente este email puede dirigirse a la siguiente url para activar su cuenta {!url}';
		$params = new Core_Object(array('url'=>$activation_url));
		$mensaje_no_html = $params->DataStrtr('', $mensaje_no_html);
		$sent_flag = $email_layout->enviar($subject, $mensaje_no_html);
		return $sent_flag;
	}
	private static function getUsername($usuario){
		if(is_object($usuario)&&is_a($usuario, 'Saludmascotas_Model_User'))
			$username = $usuario->getUsername();
		elseif(is_string($usuario))
			$username = $usuario;
		else return null;
		return $username;
	}
	public static function getUsuario($usuario, $use_field='username'){
		if(is_string($usuario)){
			$ousuario = new Frontend_Usuario_Model_User();
			$ousuario->setData($use_field, $usuario);
			if(!$ousuario->load()){
				return null;
			}
			$usuario = $ousuario;
		}
		return $usuario;
	}
	public static function actionEnviarCorreoActivación($usuario, $max_time=null, $hash=null){
		if(isset($max_time, $hash)){
			$username = self::getUsername($usuario);
			if(!self::validateActivacionSignature($username, $max_time, $hash, true)){
				return null;
			}
		}
		$usuario = self::getUsuario($usuario);
		if(!isset($usuario))
			return null;
		
		//preparo layout y template		
		$email_layout = new Saludmascotas_Email('email_usuario_activation');
		
		$activation_url = Frontend_Usuario_Helper::getUrlActivate($usuario);
		$recover_url = Frontend_Usuario_Helper::getUrlRecover($usuario);
		$email_template = 
			$email_layout
				->loadDom()
				->getBlock('usuario_activacion_message');
		$email_template
			->setUsuario($usuario)
			->setActivationUrl($activation_url)
			->setRecoverUrl($recover_url)
		;
		
		//$reserva->addAutofilterOutput('utf8_decode');
		
		//envio email
		$subject = 'Activación de Usuario en SaludMascotas';
		$subject = utf8_decode($subject);
		$email_layout->AddAddress($usuario->getEmail(), $usuario->getNombre().' '.$usuario->getApellido());
		$email_layout->fromSelf();
		$email_layout->agregarNotificacion();
		$mensaje_no_html = 'Si no puede visualizar correctamente este email puede dirigirse a la siguiente url para activar su cuenta {!url}';
		$params = new Core_Object(array('url'=>$activation_url));
		$mensaje_no_html = $params->DataStrtr('', $mensaje_no_html);
		$sent_flag = $email_layout->enviar($subject, $mensaje_no_html);
		return $sent_flag;
	}
	public static function actionEnviarCorreoRecuperacion($username_or_email){
		if(!$usuario = self::getUsuario($username_or_email)){
			if(!$usuario = self::getUsuario($username_or_email, 'email')){
				return null;
			}
		}
		
		$login_url = self::getUrlLogin($usuario);
		//preparo layout y template		
		$email_layout = new Saludmascotas_Email('email_usuario_recover');
		
		$email_template = 
			$email_layout
				->loadDom()
				->getBlock('usuario_recover_message')
				->setLoginUrl($login_url)
		;
		$email_template
			->setUsuario($usuario)
		;
		
		//$reserva->addAutofilterOutput('utf8_decode');
		
		//envio email
		$subject = 'Recuperación de Usuario en SaludMascotas';
		$subject = utf8_decode($subject);
		$email_layout->AddAddress($usuario->getEmail(), $usuario->getNombre().' '.$usuario->getApellido());
		$email_layout->fromSelf();
		$email_layout->agregarNotificacion();
		$mensaje_no_html = '';
//		$params = new Core_Object(array('url'=>$activation_url));
//		$mensaje_no_html = $params->DataStrtr('', $mensaje_no_html);
		$sent_flag = $email_layout->enviar($subject, $mensaje_no_html);
		return $sent_flag;
	}
	public static function actionEliminarActividad($id_actividad){
//		if(self::eliminarActividad($id_actividad)){
//			Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Actividad Eliminada Correctamente'));
//		}
//		else{
//			Admin_App::getInstance()->addErrorMessage(self::getInstance()->__t('No se pudo eliminar la Actividad'));
//		}
	}
	public static function eliminarActividad($id_actividad){
//		$actividad = new Inta_Model_Actividad();
//		$ret = $actividad->setId($id_actividad)->delete();
//		foreach($actividad->getTranslatedErrors() as $error)
//			Admin_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
//		return($ret);
	}

}
?>