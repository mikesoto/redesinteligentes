<!-- Modal -->
<div class="modal fade" id="register-user-modal" tabindex="-1" role="dialog" aria-labelledby="register-user-modal-label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="register-user-modal-label">Registrar Nuevo Socio</h4>
      </div>
      <form id="create-user-form" action="/office/create/user" method="POST">
        <div class="modal-body">
            {!! csrf_field() !!}
            <div class="col-sm-12">
              <p class="help-block">Datos de Ingreso:</p>
            </div>

            <div class="row">
              <div class="col-sm-4">
                <label for="patrocinador">ID de Patrocinador*</label>
                <input type="text" class="form-control" id="patrocinador" name="patrocinador" value="{{ old('patrocinador') }}" required>
              </div>
              <div class="col-sm-1 loading-container">
                <span id="loading-patro-nombre" class="hidden"><i class="fa fa-spinner fa-pulse"></i></span>
              </div>
              <div class="col-sm-7">
                <label for="nombre_patrocinador">Nombre Patrocinador*</label>
                <input type="text" class="form-control" id="nombre_patrocinador" name="nombre_patrocinador" value="{{ old('nombre_patrocinador') }}" disabled required>
              </div>
            </div>
            
            <div class="row">
              <div class="col-sm-4">
                <label for="upline">ID de Upline*</label>
                <input type="text" class="form-control" id="upline" name="upline" value="{{ old('upline') }}" required>
              </div>
              <div class="col-sm-1 loading-container">
                <span id="loading-upline-nombre" class="hidden"><i class="fa fa-spinner fa-pulse"></i></span>
              </div>
              <div class="col-sm-7">
                <label for="nombre_upline">Nombre Upline*</label>
                <input type="text" class="form-control" id="nombre_upline" name="nombre_upline"  value="{{ old('nombre_upline') }}" disabled required>
              </div>
            </div>
            
            <div class="row">
              <div class="col-sm-4">
                <label for="fecha_ingreso">Fecha de Ingreso*</label>
                <input type="text" class="form-control date_field" id="fecha_ingreso" name="fecha_ingreso" value="{{ (old('fecha_ingreso'))? old('fecha_ingreso') : date('d/m/Y', mktime(10,00,0, date('n'), date('j'), date('Y')) ) }}" required>
              </div>
            </div>
            
            <div class="col-sm-12">
              <hr>
              <p class="help-block">Datos Personales:</p>
            </div>

            <div class="row">
              <div class="col-sm-4">
                <label for="nombre">Nombre*</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
              </div>
              <div class="col-sm-4">
                <label for="apellido_paterno">Apellido Paterno*</label>
                <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" value="{{ old('apellido_paterno') }}" required>
              </div> 
              <div class="col-sm-4">
                <label for="apellido_materno">Apellido Materno*</label>
                <input type="text" class="form-control" id="apellido_materno" name="apellido_materno" value="{{ old('apellido_materno') }}" required>
              </div> 
            </div>

            <div class="row">
              <div class="col-sm-6">
                <label for="fecha_nac">Fecha de Nacimiento</label>
                <input type="text" class="form-control date_birth_field" id="fecha_nac" name="fecha_nac" value="{{ old('fecha_nac') }}">
              </div> 
              <div class="col-sm-6">
                <label for="ife">IFE</label>
                <input type="text" class="form-control" id="ife" name="ife" value="{{ old('ife') }}">
              </div> 
            </div>

            <div class="row">
              <div class="col-sm-6">
                <label for="tel_cel">Celular</label>
                <input type="text" class="form-control" id="tel_cel" name="tel_cel" value="{{ old('tel_cel') }}">
              </div> 
            </div>

            <div class="row">
              <div class="col-sm-7">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
              </div> 
            </div>

            <div class="col-sm-12">
              <hr>
              <p class="help-block">La dirección a donde se enviará la contraseña del usuario:</p>
            </div>

            <div class="row">
              <div class="col-sm-7">
                <label for="cp">Codigo Postal*</label>
                <input type="text" class="form-control" id="cp" name="cp" value="{{ old('cp') }}" required>
              </div> 
            </div>
            <div class="row">
              <div class="col-sm-7">
                <label for="direccion">Dirección*</label>
                <input type="text" class="form-control" id="direccion" name="direccion" value="{{ old('direccion') }}" required>
              </div> 
            </div>
            <div class="row">
              <div class="col-sm-7">
                <label for="colonia">Colonia*</label>
                <input type="text" class="form-control" id="colonia" name="colonia" value="{{ old('colonia') }}" required>
              </div> 
            </div>
            <div class="row">
              <div class="col-sm-7">
                <label for="delegacion">Municipio o Delegación*</label>
                <input type="text" class="form-control" id="delegacion" name="delegacion" value="{{ old('delegacion') }}" required>
              </div> 
            </div> 
            <div class="row">
              <div class="col-sm-7">
                <label for="estado">Estado*</label>
                <select class="form-control" name="estado" id="estado" required>
                  <option></option>
                  <option value="Aguascalientes"        {{ (old('estado') == 'Aguascalientes')?       'selected' : '' }}>Aguascalientes</option>
                  <option value="Baja California"       {{ (old('estado') == 'Baja California')?      'selected' : '' }}>Baja California</option>
                  <option value="Baja California Sur"   {{ (old('estado') == 'Baja California Sur')?  'selected' : '' }}>Baja California Sur</option>
                  <option value="Campeche"              {{ (old('estado') == 'Campeche')?             'selected' : '' }}>Campeche</option>
                  <option value="Chiapas"               {{ (old('estado') == 'Chiapas')?              'selected' : '' }}>Chiapas</option>
                  <option value="Chihuahua"             {{ (old('estado') == 'Chihuahua')?            'selected' : '' }}>Chihuahua</option>
                  <option value="Coahuila"              {{ (old('estado') == 'Coahuila')?             'selected' : '' }}>Coahuila</option>
                  <option value="Colima"                {{ (old('estado') == 'Colima')?               'selected' : '' }}>Colima</option>
                  <option value="Distrito Federal"      {{ (old('estado') == 'Distrito Federal')?     'selected' : '' }}>Distrito Federal</option>
                  <option value="Durango"               {{ (old('estado') == 'Durango')?              'selected' : '' }}>Durango</option>
                  <option value="Estado de México"      {{ (old('estado') == 'Estado de México')?     'selected' : '' }}>Estado de México</option>
                  <option value="Guanajuato"            {{ (old('estado') == 'Guanajuato')?           'selected' : '' }}>Guanajuato</option>
                  <option value="Guerrero"              {{ (old('estado') == 'Guerrero')?             'selected' : '' }}>Guerrero</option>
                  <option value="Hidalgo"               {{ (old('estado') == 'Hidalgo')?              'selected' : '' }}>Hidalgo</option>
                  <option value="Jalisco"               {{ (old('estado') == 'Jalisco')?              'selected' : '' }}>Jalisco</option>
                  <option value="Michoacán"             {{ (old('estado') == 'Michoacán')?            'selected' : '' }}>Michoacán</option>
                  <option value="Morelos"               {{ (old('estado') == 'Morelos')?              'selected' : '' }}>Morelos</option>
                  <option value="Nayarit"               {{ (old('estado') == 'Nayarit')?              'selected' : '' }}>Nayarit</option>
                  <option value="Nuevo León"            {{ (old('estado') == 'Nuevo León')?           'selected' : '' }}>Nuevo León</option>
                  <option value="Oaxaca"                {{ (old('estado') == 'Oaxaca')?               'selected' : '' }}>Oaxaca</option>
                  <option value="Puebla"                {{ (old('estado') == 'Puebla')?               'selected' : '' }}>Puebla</option>
                  <option value="Querétaro"             {{ (old('estado') == 'Querétaro')?            'selected' : '' }}>Querétaro</option>
                  <option value="Quintana Roo"          {{ (old('estado') == 'Quintana Roo')?         'selected' : '' }}>Quintana Roo</option>
                  <option value="San Luis Potosí"       {{ (old('estado') == 'San Luis Potosí')?      'selected' : '' }}>San Luis Potosí</option>
                  <option value="Sinaloa"               {{ (old('estado') == 'Sinaloa')?              'selected' : '' }}>Sinaloa</option>
                  <option value="Sonora"                {{ (old('estado') == 'Sonora')?               'selected' : '' }}>Sonora</option>
                  <option value="Tabasco"               {{ (old('estado') == 'Tabasco')?              'selected' : '' }}>Tabasco</option>
                  <option value="Tamaulipas"            {{ (old('estado') == 'Tamaulipas')?           'selected' : '' }}>Tamaulipas</option>
                  <option value="Tlaxcala"              {{ (old('estado') == 'Tlaxcala')?             'selected' : '' }}>Tlaxcala</option>
                  <option value="Veracruz"              {{ (old('estado') == 'Veracruz')?             'selected' : '' }}>Veracruz</option>
                  <option value="Yucatán"               {{ (old('estado') == 'Yucatán')?              'selected' : '' }}>Yucatán</option>
                  <option value="Zacatecas"             {{ (old('estado') == 'Zacatecas')?            'selected' : '' }}>Zacatecas</option>
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
                <input type="text" class="form-control" id="beneficiario" name="beneficiario" value="{{ old('beneficiario') }}" required>
              </div> 
            </div>
            <div class="row">
              <div class="col-sm-7">
                <label for="parentesco">Parentesco *</label>
                <input type="text" class="form-control" id="parentesco" name="parentesco" value="{{ old('parentesco') }}" required>
              </div> 
            </div>
            <div class="row">
              <div class="col-sm-7">
                <label for="beneficiario_fecha_nac">Fecha de Nacimiento (Beneficiario) *</label>
                <input type="text" class="form-control date_birth_field" id="beneficiario_fecha_nac" name="beneficiario_fecha_nac" value="{{ old('beneficiario_fecha_nac') }}" required>
              </div> 
            </div>
        </div>
        <div id="create-user-modal-footer" class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="submit" id="create-user-submit-btn" class="btn btn-default">Registrar</button>
        </div>
      </form>
    </div>
  </div>
</div>