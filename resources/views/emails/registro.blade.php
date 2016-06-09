<html>
<head>
	<meta charset="UTF-8" />
	<title>Registrado a Redes Inteligentes</title>
</head>
<body>
	<img src="http://app.redesinteligentes.com.mx/images/common/redesinteligentes-small.png">
	<h4>
		Estimado(a): {{ $newUser->nombre.' '.$newUser->apellido_paterno.' '.$newUser->apellido_materno }},
	</h4>
	<p>
		A nombre del equipo REDES INTELIGENTES, te damos la más cordial bienvenida, esperando que podamos 
		trabajar en conjunto para lograr tus metas, te sugerimos en todo momento dirigirte con honestidad 
		y respeto siguiendo los lineamientos estipulados en el manual de Asociado, de Redes Inteligentes, 
		adicionalmente te recomendamos acudir a: {{ $newUser->patrocinadorNombre }} que es la persona que te invito 
		y quien te brindara apoyo y capacitación, de ahora en adelante eres parte del equipo de líderes con visión.
	</p>
	<p>
		Te recordamos tus datos de registro: 
	</p>
	<p>  
		Número de Asociado: {{ $newUser->id }}<br>
		Usuario: {{ $newUser->user }}<br>
		Contraseña: {{ $newUser->pwd_str }}<br>
		Puedes iniciar sessión al sistema de oficina virtual en:<br>
		<a href="http://app.redesinteligentes.com.mx/oficina-virtual" target="_blank">
			http://app.redesinteligentes.com.mx/oficina-virtual
		</a>
	</p>
</body>
</html>