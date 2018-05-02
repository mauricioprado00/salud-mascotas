<?php
class Test_Saludmascotas_Usuario extends Core_Singleton{
	public function getInstance(){
		return self::getInstanceOf(__CLASS__);
	}
	public function enviarEmailRegistro(){
		Core_Http_Header::ContentType('text/plain');
		//busco usuario
		$usuario = new Saludmascotas_Model_User();
		$usuario->setWhere(Db_Helper::equal('email', 'mauricioprado00@gmail.com'));
		$usuarios = $usuario->search(null, 'asc', 1, 0, 'Saludmascotas_Model_User');
		if(!count($usuarios))
			die("no hay usuarios".__FILE__.__LINE__);
		$usuario = $usuarios[0];
		
		//preparo layout y template		
		$email_layout = new Saludmascotas_Email('email_usuario_register');
		
		$activation_url = Frontend_Usuario_Helper::getUrlActivate($usuario);
		$recover_url = Frontend_Usuario_Helper::getUrlRecover($usuario);
		$email_template = 
			$email_layout
				->loadDom()
				->getBlock('ususario_register_message');
		$email_template
			->setUsuario($usuario)
			->setActivationUrl($activation_url)
			->setRecoverUrl($recover_url)
		;
		
		//$reserva->addAutofilterOutput('utf8_decode');
		
		
		//envio email
		$subject = 'usuario registrado';
		$email_layout->AddAddress($usuario->getEmail(), $usuario->getNombre().' '.$usuario->getApellido());
		$email_layout->fromSelf();
		$email_layout->agregarNotificacion();
		$mensaje_no_html = 'Si no puede visualizar correctamente este email puede dirigirse a la siguiente url para activar su cuenta {!url}';
		$params = new Core_Object(array('url'=>$activation_url));
		$mensaje_no_html = $params->DataStrtr('', $mensaje_no_html);
		$sent_flag = $email_layout->enviar($subject, $mensaje_no_html);
		var_dump($sent_flag);
	}
	public function add_user(){
		$usuario = new Saludmascotas_Model_User();
		$usuario
			->setActivo(null)
			->setNombre('Hugo Mauricio')
			->setApellido('Prado Macat')
			->setTelefono('(03541)428883')
			->setCelular('(03541)155265489')
			->setEmail('mauricioprado00@gmail.com')
			->setFechaAlta(time())
			
			->setUsername('mauricio2')
			->setPassword('asdtyu')
		;
		Core_Http_Header::ContentType('text/plain');
		$resultado = $usuario->insert()?true:false;
		foreach($usuario->getTranslatedErrors() as $error){
			var_dump($error);
			var_dump($error->getTranslatedDescription());
		}
	
		var_dump($resultado);

	}
	
}

?>