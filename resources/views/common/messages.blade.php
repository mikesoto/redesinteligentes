@if(\Session::has('alert-success'))
    <p class="alert alert-success large-margin-top">
    	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      @for ($i=0; $i < count(\Session::get('alert-success')); $i++)
      	{{ \Session::get('alert-success')[$i] }}<br>
      @endfor
    </p>
    <?php \Session::forget('alert-success');?>
@endif