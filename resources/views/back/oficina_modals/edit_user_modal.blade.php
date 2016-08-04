<!-- Modal -->
<div class="modal fade" id="edit-user-modal" tabindex="-1" role="dialog" aria-labelledby="edit-user-modal-label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="edit-user-modal-label">Actualizar Socio: <strong>{{ $cur_user->user }}</strong></h4>
      </div>
      <form id="update-user-form" action="/office/update/user" method="POST">
        <input type="hidden" name="update_usr" value="{{ $cur_user->id }}">
        <div class="modal-body">
            {!! csrf_field() !!}
            <div class="col-sm-12">
              <p class="help-block">Datos de Ingreso:</p>
            </div>

            <div class="row">
              <div class="col-sm-4">
                <label for="patrocinador">ID de Patrocinador*</label>
                <input type="text" class="form-control" id="patrocinador" name="patrocinador" value="{{ $cur_user->patrocinador }}" disabled>
              </div>
              <div class="col-sm-1 loading-container">
                <span id="loading-patro-nombre" class="hidden"><i class="fa fa-spinner fa-pulse"></i></span>
              </div>
              <div class="col-sm-7">
                <!-- <label for="nombre_patrocinador">Nombre Patrocinador*</label>
                <input type="text" class="form-control" id="nombre_patrocinador" name="nombre_patrocinador" value="{{ $cur_user->nombre_patrocinador }}" disabled required> -->
              </div>
            </div>
            
            <div class="row">
              <div class="col-sm-4">
                <label for="upline">ID de Upline*</label>
                <input type="text" class="form-control" id="upline" name="upline" value="{{ $cur_user->upline }}" disabled>
              </div>
              <div class="col-sm-1 loading-container">
                <span id="loading-upline-nombre" class="hidden"><i class="fa fa-spinner fa-pulse"></i></span>
              </div>
              <div class="col-sm-7">
                <!-- <label for="nombre_upline">Nombre Upline*</label>
                <input type="text" class="form-control" id="nombre_upline" name="nombre_upline"  value="{{ $cur_user->nombre_upline }}" disabled required> -->
              </div>
            </div>
            
            <div class="row">
              <div class="col-sm-5">
                <label for="fecha_ingreso">Fecha de Ingreso*</label>
                <input type="text" class="form-control date_field" id="fecha_ingreso" name="fecha_ingreso" value="{{ $cur_user->fecha_ingreso }}" disabled>
              </div>
              <div class="col-sm-7">
                @if($cur_user->id != 1)
                  <label for="lado">Lado</label>
                  <select id="lado" name="lado" class="form-control" disabled>
                    <option></option>
                    <option value="left" @if($cur_user->side == 'left') {{ 'selected' }} @endif>Izquierdo</option>
                    <option value="right" @if($cur_user->side == 'right') {{ 'selected' }} @endif>Derecho</option>
                  </select>
                @endif
              </div>
            </div>
            <div class="col-sm-12">
              <hr>
              <p class="help-block">Datos Personales:</p>
            </div>

            <div class="row">
              <div class="col-sm-4">
                <label for="nombre">Nombre*</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $cur_user->nombre }}" required>
              </div>
              <div class="col-sm-4">
                <label for="apellido_paterno">Apellido Paterno*</label>
                <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" value="{{ $cur_user->apellido_paterno }}" required>
              </div> 
              <div class="col-sm-4">
                <label for="apellido_materno">Apellido Materno*</label>
                <input type="text" class="form-control" id="apellido_materno" name="apellido_materno" value="{{ $cur_user->apellido_materno }}" required>
              </div> 
            </div>

            <div class="row">
              <div class="col-sm-6">
                <label for="fecha_nac">Fecha de Nacimiento</label>
                <input type="text" class="form-control" id="fecha_nac" name="fecha_nac" value="{{ ($cur_user->fecha_nac != '0000-00-00')? date( 'd/m/Y', strtotime($cur_user->fecha_nac) ) : '00/00/0000' }}">
              </div> 
              <div class="col-sm-6">
                <label for="ife">IFE</label>
                <input type="text" class="form-control" id="ife" name="ife" value="{{ $cur_user->ife }}">
              </div> 
            </div>

            <div class="row">
              <div class="col-sm-6">
                <label for="tel_cel">Celular</label>
                <input type="text" class="form-control" id="tel_cel" name="tel_cel" value="{{ $cur_user->tel_cel }}">
              </div> 
            </div>

            <div class="row">
              <div class="col-sm-7">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $cur_user->email }}">
              </div> 
            </div>

            <div class="col-sm-12">
              <hr>
              <p class="help-block">La dirección a donde se enviará la contraseña del usuario:</p>
            </div>

            <div class="row">
              <div class="col-sm-7">
                <label for="cp">Codigo Postal*</label>
                <input type="text" class="form-control" id="cp" name="cp" value="{{ $cur_user->cp }}" required>
              </div> 
            </div>
            <div class="row">
              <div class="col-sm-7">
                <label for="direccion">Dirección*</label>
                <input type="text" class="form-control" id="direccion" name="direccion" value="{{ $cur_user->direccion }}" required>
              </div> 
            </div>
            <div class="row">
              <div class="col-sm-7">
                <label for="colonia">Colonia*</label>
                <input type="text" class="form-control" id="colonia" name="colonia" value="{{ $cur_user->colonia }}" required>
              </div> 
            </div>
            <div class="row">
              <div class="col-sm-7">
                <label for="delegacion">Municipio o Delegación*</label>
                <input type="text" class="form-control" id="delegacion" name="delegacion" value="{{ $cur_user->delegacion }}" required>
              </div> 
            </div> 
            <div class="row">
              <div class="col-sm-7">
                <label for="estado">Estado*</label>
                <select class="form-control" name="estado" id="estado" required>
                  <option></option>
                  <option value="Aguascalientes"        {{ ($cur_user->estado == 'Aguascalientes')?       'selected' : '' }}>Aguascalientes</option>
                  <option value="Baja California"       {{ ($cur_user->estado == 'Baja California')?      'selected' : '' }}>Baja California</option>
                  <option value="Baja California Sur"   {{ ($cur_user->estado == 'Baja California Sur')?  'selected' : '' }}>Baja California Sur</option>
                  <option value="Campeche"              {{ ($cur_user->estado == 'Campeche')?             'selected' : '' }}>Campeche</option>
                  <option value="Chiapas"               {{ ($cur_user->estado == 'Chiapas')?              'selected' : '' }}>Chiapas</option>
                  <option value="Chihuahua"             {{ ($cur_user->estado == 'Chihuahua')?            'selected' : '' }}>Chihuahua</option>
                  <option value="Coahuila"              {{ ($cur_user->estado == 'Coahuila')?             'selected' : '' }}>Coahuila</option>
                  <option value="Colima"                {{ ($cur_user->estado == 'Colima')?               'selected' : '' }}>Colima</option>
                  <option value="Distrito Federal"      {{ ($cur_user->estado == 'Distrito Federal')?     'selected' : '' }}>Distrito Federal</option>
                  <option value="Durango"               {{ ($cur_user->estado == 'Durango')?              'selected' : '' }}>Durango</option>
                  <option value="Estado de México"      {{ ($cur_user->estado == 'Estado de México')?     'selected' : '' }}>Estado de México</option>
                  <option value="Guanajuato"            {{ ($cur_user->estado == 'Guanajuato')?           'selected' : '' }}>Guanajuato</option>
                  <option value="Guerrero"              {{ ($cur_user->estado == 'Guerrero')?             'selected' : '' }}>Guerrero</option>
                  <option value="Hidalgo"               {{ ($cur_user->estado == 'Hidalgo')?              'selected' : '' }}>Hidalgo</option>
                  <option value="Jalisco"               {{ ($cur_user->estado == 'Jalisco')?              'selected' : '' }}>Jalisco</option>
                  <option value="Michoacán"             {{ ($cur_user->estado == 'Michoacán')?            'selected' : '' }}>Michoacán</option>
                  <option value="Morelos"               {{ ($cur_user->estado == 'Morelos')?              'selected' : '' }}>Morelos</option>
                  <option value="Nayarit"               {{ ($cur_user->estado == 'Nayarit')?              'selected' : '' }}>Nayarit</option>
                  <option value="Nuevo León"            {{ ($cur_user->estado == 'Nuevo León')?           'selected' : '' }}>Nuevo León</option>
                  <option value="Oaxaca"                {{ ($cur_user->estado == 'Oaxaca')?               'selected' : '' }}>Oaxaca</option>
                  <option value="Puebla"                {{ ($cur_user->estado == 'Puebla')?               'selected' : '' }}>Puebla</option>
                  <option value="Querétaro"             {{ ($cur_user->estado == 'Querétaro')?            'selected' : '' }}>Querétaro</option>
                  <option value="Quintana Roo"          {{ ($cur_user->estado == 'Quintana Roo')?         'selected' : '' }}>Quintana Roo</option>
                  <option value="San Luis Potosí"       {{ ($cur_user->estado == 'San Luis Potosí')?      'selected' : '' }}>San Luis Potosí</option>
                  <option value="Sinaloa"               {{ ($cur_user->estado == 'Sinaloa')?              'selected' : '' }}>Sinaloa</option>
                  <option value="Sonora"                {{ ($cur_user->estado == 'Sonora')?               'selected' : '' }}>Sonora</option>
                  <option value="Tabasco"               {{ ($cur_user->estado == 'Tabasco')?              'selected' : '' }}>Tabasco</option>
                  <option value="Tamaulipas"            {{ ($cur_user->estado == 'Tamaulipas')?           'selected' : '' }}>Tamaulipas</option>
                  <option value="Tlaxcala"              {{ ($cur_user->estado == 'Tlaxcala')?             'selected' : '' }}>Tlaxcala</option>
                  <option value="Veracruz"              {{ ($cur_user->estado == 'Veracruz')?             'selected' : '' }}>Veracruz</option>
                  <option value="Yucatán"               {{ ($cur_user->estado == 'Yucatán')?              'selected' : '' }}>Yucatán</option>
                  <option value="Zacatecas"             {{ ($cur_user->estado == 'Zacatecas')?            'selected' : '' }}>Zacatecas</option>
                </select>
              </div> 
            </div>
            
            <div class="col-sm-12">
              <hr>
              <p class="help-block">Datos de Beneficiario:</p>
            </div>

            <div class="row">
              <div class="col-sm-7">
                <label for="beneficiario">Beneficiario *</label>
                <input type="text" class="form-control" id="beneficiario" name="beneficiario" value="{{ $cur_user->beneficiario }}" required>
              </div> 
            </div>
            <div class="row">
              <div class="col-sm-7">
                <label for="parentesco">Parentesco *</label>
                <input type="text" class="form-control" id="parentesco" name="parentesco" value="{{ $cur_user->parentesco }}" required>
              </div> 
            </div>
            <div class="row">
              <div class="col-sm-7">
                <label for="beneficiario_fecha_nac">Fecha de Nacimiento (Beneficiario) *</label>
                <input type="text" class="form-control" id="beneficiario_fecha_nac" name="beneficiario_fecha_nac" value="{{ ($cur_user->beneficiario_fecha_nac != '0000-00-00')? date( 'd/m/Y', strtotime($cur_user->beneficiario_fecha_nac)) : '00/00/0000' }}">
              </div> 
            </div>
        </div>
        <div id="edit-user-modal-footer" class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
          <button type="submit" id="edit-user-submit-btn" class="btn btn-default">Actualizar</button>
        </div>
      </form>
    </div>
  </div>
</div>