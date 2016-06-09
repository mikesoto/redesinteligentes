@extends('layouts.front')
@section('content')
<div id="carousel-home" class="carousel slide" data-ride="carousel">
  <!-- Indicators -->
  <ol class="carousel-indicators">
    <li data-target="#carousel-home" data-slide-to="0" class="active"></li>
    <li data-target="#carousel-home" data-slide-to="1"></li>
    <li data-target="#carousel-home" data-slide-to="2"></li>
    <li data-target="#carousel-home" data-slide-to="3"></li>
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner" role="listbox">
    <div class="item active">
      <img src="/images/carousel/invertir.png" alt="Carousel Image">
      <div class="carousel-caption">
        <!-- <h3>Carousel Image</h3>
        <p>Lorem Ipsum Dolor Sit Amet</p> -->
      </div>
    </div>
    <div class="item">
      <img src="/images/carousel/ganancias.jpg" alt="Carousel Image">
      <div class="carousel-caption">
        <!-- <h3>Carousel Image</h3>
        <p>Lorem Ipsum Dolor Sit Amet</p> -->
      </div>
    </div>
    <div class="item">
      <img src="/images/carousel/wealth.jpg" alt="Carousel Image">
      <div class="carousel-caption">
        <!-- <h3>Carousel Image</h3>
        <p>Lorem Ipsum Dolor Sit Amet</p> -->
      </div>
    </div>
    <div class="item">
      <img src="/images/carousel/style.jpg" alt="Carousel Image">
      <div class="carousel-caption">
        <!-- <h3>Carousel Image</h3>
        <p>Lorem Ipsum Dolor Sit Amet</p> -->
      </div>
    </div>
  </div>

  <!-- Controls -->
  <a class="left carousel-control" href="#carousel-home" role="button" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#carousel-home" role="button" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>



<div class="modules-container">
	<div class="row">
		<div class="col-sm-4">
			<div class="panel panel-primary">
			  <div class="panel-heading">
			    <h3 class="panel-title">Oportunidad</h3>
			  </div>
			  <div class="panel-body">
          <img src="/images/front-modules/opportunidad.jpg" class="img img-responsive">
			    <p class="module-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi et tristique nibh. Aliquam nec sodales arcu, vel sollicitudin risus. Nulla facilisi. Donec ultrices nisi purus, ac aliquet dolor fermentum vitae. Ut ornare maximus tempor. Sed interdum et massa vitae suscipit.</p>
			    <div class="text-center">
            <button class="btn btn-warning" id="plan-cta-btn">Aprende Más</button>
          </div>
        </div>
			</div>
		</div>  
		<div class="col-sm-4">
			<div class="panel panel-primary">
			  <div class="panel-heading">
			    <h3 class="panel-title">Plan de Negocios</h3>
			  </div>
			  <div class="panel-body">
          <img src="/images/front-modules/plan-de-negocios.jpg" class="img img-responsive">
			    <p class="module-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi et tristique nibh. Aliquam nec sodales arcu, vel sollicitudin risus. Nulla facilisi. Donec ultrices nisi purus, ac aliquet dolor fermentum vitae. Ut ornare maximus tempor. Sed interdum et massa vitae suscipit.</p>
			    <div class="text-center">
            <button class="btn btn-success" id="plan-cta-btn">Aprende Más</button>
          </div>
        </div>
			</div>
		</div>  
		<div class="col-sm-4">
			<div class="panel panel-primary">
			  <div class="panel-heading">
			    <h3 class="panel-title">Oficina Virtual</h3>
			  </div>
			  <div class="panel-body">
          <img src="/images/front-modules/oficina-virtual.jpg" class="img img-responsive">
			    <p class="module-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi et tristique nibh. Aliquam nec sodales arcu, vel sollicitudin risus. Nulla facilisi. Donec ultrices nisi purus, ac aliquet dolor fermentum vitae. Ut ornare maximus tempor. Sed interdum et massa vitae suscipit.</p>
			    <div class="text-center">
            <a class="btn btn-info" id="oficina-cta-btn" href="/oficina-virtual">Iniciar Sessión</a>
          </div>
        </div>
			</div>
		</div>  		
	</div>
</div>
@endsection