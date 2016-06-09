@if (count($errors) > 0)
    <!-- Form Error List -->
    <div class="alert alert-danger text-left">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>Se ha producido un error en el formulario enviado.</strong>
        <br><br>
        <ul>
            @foreach ($errors[0]->getMessages() as $field => $error)
                @if($field == 'custom_error')
                    <li>{{ $error[0] }}</li>
                @elseif($field == 'email' && $error[0] == 'The email has already been taken.')
                    <li><strong>Email</strong> : Ese correo electrónico ya se encuentra registrado.</li>
                @else
                    <li><strong>{{ strtoupper(str_replace("_"," ", $field)) }}</strong> : es un campo obligatorio.</li>
                @endif
            @endforeach
            <?php \Session::forget('errors');?>
        </ul>
    </div>
@endif