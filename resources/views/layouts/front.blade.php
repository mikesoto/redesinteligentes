<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Redes Inteligentes | {{ $title_page }}</title>
	<!-- Styles -->
	<link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" media="all" href="/css/jquery-ui.css"/>
	<link rel="stylesheet" type="text/css" media="all" href="/css/jquery-ui.structure.css"/>
	<link rel="stylesheet" type="text/css" media="all" href="/css/jquery-ui.theme.css"/>
	<link rel="stylesheet" type="text/css" media="all" href="/css/fontawesome.min.css"/>
	<link rel="stylesheet" type="text/css" href="/css/style.css">
</head>
<body>
	<div class="container">
		<header>
			<nav class="navbar navbar-default navbar-fixed-top main-navbar">
			  <div class="container-fluid">
			    <!-- Brand and toggle get grouped for better mobile display -->
			    <div class="navbar-header">
			      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-navbar" aria-expanded="false">
			        <span class="sr-only">Toggle navigation</span>
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
			      </button>
			      <a class="navbar-brand" href="/">
			      	<img src="/images/common/redesinteligentes-small.png">
			      </a>
			    </div>

			    <!-- Collect the nav links, forms, and other content for toggling -->
			    <div class="collapse navbar-collapse" id="main-navbar">
			      <ul class="nav navbar-nav">
			        <li <?php echo ($active_page == 'inicio')?   'class="active"' : ''; ?>><a href="/">Inicio</a></li>
			        <li <?php echo ($active_page == 'nosotros')? 'class="active"' : ''; ?>><a href="/nosotros">Nosotros</a></li>
			        <li <?php echo ($active_page == 'oficina')?  'class="active"' : ''; ?>><a href="/oficina-virtual">Oficina Virtual</a></li>
			        <li <?php echo ($active_page == 'contacto')? 'class="active"' : ''; ?>><a href="/contacto">Contacto</a></li>
			      </ul>
			    </div><!-- /.navbar-collapse -->
			  </div><!-- /.container-fluid -->
			</nav>
		</header>
		
		<div class="main-content">
		@yield('content')
		</div>

		<footer>
			<a class="" href="">Aviso de Privacidad</a> | &copy;Redes Inteligentes 2016. Todos los Derechos Reservados.
		</footer>
	</div><!-- /.container -->
	<!-- Javascript -->
	<script type="text/javascript" src="/js/jquery.min.js"></script>
	<script type="text/javascript" src="/js/jquery-ui.js"></script>
	<script type="text/javascript" src="/js/jquery.ui.datepicker-es.js"></script>
	<script type="text/javascript" src="/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="/js/main.js"></script>
</body>
</html>
