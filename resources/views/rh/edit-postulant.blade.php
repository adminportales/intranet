@extends('layouts.app')

@section('content')
    <div class="card-header">

        <div class="d-flex justify-content-between">
            <div class="d-flex flex-row" >
                <a  href="{{ route('rh.postulants') }}">
                    <i class="fa fa-arrow-left fa-2x arrouw-back" aria-hidden="true"></i> 
                </a>
                <h3 style="margin-left:16px;" class="separator">Alta de Candidato </h3> 
            </div>
            
            <div>                
                <form 
                    action="{{ route('rh.morePostulant', ['postulant_id' => $postulant->id]) }}"
                    method="GET">
                    @csrf
                    <button type="submit" class="btn btn-primary"> 
                        Recepcion de Documentos
                        <i class="ms-2 fa fa-arrow-right" aria-hidden="true"></i>
                    </button>
                </form>
            </div>
        </div>
    <div class="card-body">

    <div class="container" >
        <div class="stepwizard">
            <div class="stepwizard-row setup-panel">
                <div class="stepwizard-step col-xs-3" style="width: 20%;">  
                    <a href="#step-1" type="button" class="btn btn-default btn-circle" disabled="disabled">1</a>
                    <p><small>Alta de Candidato</small></p>
                </div>
                <div class="stepwizard-step col-xs-3"  style="width: 20%;"> 
                    <a href="#step-2" type="button" class="btn btn-default btn-circle no-selected" disabled="disabled">2</a>
                    <p><small>Recepción de Documentos</small></p>
                </div>
                <div class="stepwizard-step col-xs-3"  style="width: 20%;"> 
                    <a href="#step-3" type="button" class="btn btn-default btn-circle no-selected" disabled="disabled">3</a>
                    <p><small>Kit legal de Ingreso</small></p>
                </div>
                <div class="stepwizard-step col-xs-3"  style="width: 20%;"> 
                    <a href="#step-4" type="button" class="btn btn-default btn-circle no-selected" disabled="disabled">4</a>
                    <p><small>Plan de Trabajo</small></p>
                </div>
                <div class="stepwizard-step col-xs-3"  style="width: 20%;"> 
                    <a href="#step-4" type="button" class="btn btn-default btn-circle no-selected" disabled="disabled">4</a>
                    <p><small>Kit Legal Firmado</small></p>
                </div>
            </div>
        </div>
    </div>

    <br>
        @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
        {!! Form::model($postulant, ['route' => ['rh.updatePostulant', $postulant], 'method' => 'put','enctype' => 'multipart/form-data']) !!}

        <h5>Información personal</h5>
        <p></p>
        <div class="row form-group">

            <div class="col-sm">
                {!! Form::label('name', 'Nombre') !!}
                {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre de usuario']) !!}
                @error('name')
                    <small>
                        <font color="red"> *Este campo es requerido* </font>
                    </small>
                    <br>
                @enderror
            </div>
                
            <div class="col-sm">
                {!! Form::label('lastname', 'Apellidos') !!}
                {!! Form::text('lastname', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre de usuario']) !!}
                @error('lastname')
                    <small>
                        <font color="red"> *Este campo es requerido* </font>
                    </small>
                    <br>
                @enderror
            </div>

            <div class="col-sm">
                {!! Form::label('vacant', 'Vacante') !!}
                {!! Form::text('vacant', null, ['class' => 'form-control','placeholder' => 'Selecciona status de postulante']) !!}
                @error('vacant')
                    <small>
                        <font color="red"> *Este campo es requerido* </font>
                    </small>
                    <br>
                @enderror
            </div> 
        </div>

       
        <div class="row form-group">
            <div class="col-sm">
                {!! Form::label('birthdate', 'Fecha de nacimiento') !!}
                {!! Form::date('birthdate', null, ['class' => 'form-control','placeholder' => 'Selecciona status de postulante']) !!}
                @error('birthdate')
                    <small>
                        <font color="red"> *Este campo es requerido* </font>
                    </small>
                    <br>
                @enderror
            </div> 

            <div class="col-sm">
                {!! Form::label('nss', 'NSS') !!}
                {!! Form::text('nss', null, ['class' => 'form-control','placeholder' => 'Selecciona status de postulante']) !!}
                @error('nss')
                    <small>
                        <font color="red"> *Este campo es requerido* </font>
                    </small>
                    <br>
                @enderror
            </div> 

            <div class="col-sm">
                {!! Form::label('curp', 'CURP') !!}
                {!! Form::text('curp', null, ['class' => 'form-control','placeholder' => 'Selecciona status de postulante']) !!}
                @error('curp')
                    <small>
                        <font color="red"> *Este campo es requerido* </font>
                    </small>
                    <br>
                @enderror
            </div> 
        </div>

        <div class="row form-group">
            <div class="col-sm">
                {!! Form::label('full_address', 'Domicilio completo') !!}
                {!! Form::text('full_address', null, ['class' => 'form-control','placeholder' => 'Selecciona status de postulante']) !!}
                @error('full_address')
                    <small>
                        <font color="red"> *Este campo es requerido* </font>
                    </small>
                    <br>
                @enderror
            </div>

            <div class="col-sm">
                {!! Form::label('phone', 'Celular') !!}
                {!! Form::text('phone', null, ['class' => 'form-control','placeholder' => 'Selecciona status de postulante']) !!}
                @error('phone')
                    <small>
                        <font color="red"> *Este campo es requerido* </font>
                    </small>
                    <br>
                @enderror
            </div>

            <div class="col-sm">
                {!! Form::label('message_phone', 'Telefono de recados') !!}
                {!! Form::text('message_phone', null, ['class' => 'form-control','placeholder' => 'Selecciona status de postulante']) !!}
                @error('message_phone')
                    <small>
                        <font color="red"> *Este campo es requerido* </font>
                    </small>
                    <br>
                @enderror
            </div>
        </div>

      
        <div class="row form-group">
            <div class="col-sm">
                {!! Form::label('cv', 'Adjuntar CV') !!}
                {!! Form::file('cv',  ['class' => 'form-control','placeholder' => 'Selecciona status de postulante']) !!}
              
            </div>

            <div class="col-sm">
                {!! Form::label('email', 'Correo electrónico') !!}
                {!! Form::text('email', null, ['class' => 'form-control','placeholder' => 'Selecciona status de postulante']) !!}
                @error('email')
                    <small>
                        <font color="red"> *Este campo es requerido* </font>
                    </small>
                    <br>
                @enderror
            </div>

            <div class="col-sm">
            </div>
                
        </div>

    {!! Form::submit('ACTUALIZAR INFORMACIÓN DEL CANDIDATO', ['class' => 'btnCreate mt-4']) !!}
    {!! Form::close() !!}
   
    </div>
@stop

@section('styles')
   <style>
        .text-info{
            display: none;
        }
        .fa-info-circle{
            margin-left: 8px;
            color: #1A346B;
        }

        .fa-info-circle:hover {
            margin-left: 8px;
            color: #0084C3;
        }
      
        #icon-text {
            display: none;
            margin-left: 16px;
            color: #fff;
            background-color: #1A346B;
            padding: 0 12px 0 12px;
            border-radius: 10px;
            font-size: 14px;
        }

        #content:hover~#icon-text{
            display: block;
        }

        .text-info{
            display: none;
        }
        .fa-info-circle{
            margin-left: 8px;
            color: #1A346B;
        }

        .fa-info-circle:hover {
            margin-left: 8px;
            color: #0084C3;
        }
      
        #icon-text {
            display: none;
            margin-left: 16px;
            color: #fff;
            background-color: #1A346B;
            padding: 0 12px 0 12px;
            border-radius: 10px;
            font-size: 14px;
        }

        #content:hover~#icon-text{
            display: block;
        }

        .stepwizard-step p {
            margin-top: 0px;
            color:#666;
        }
        .stepwizard-row {
            display: table-row;
        }
        .stepwizard {
            display: table;
            width: 100%;
            position: relative;
        }
        .btn-default{
            background-color: #0084C3;
        }

        .stepwizard-row:before {
            top: 14px;
            bottom: 0;
            position: absolute;
            content:" ";
            width: 100%;
            height: 1px;
            background-color: #ccc;
            z-index: 0;
        }
        .stepwizard-step {
            display: table-cell;
            text-align: center;
            position: relative;
        }
        .btn-circle {
            width: 30px;
            height: 30px;
            text-align: center;
            padding: 6px 0;
            font-size: 12px;
            line-height: 1.428571429;
            border-radius: 15px;
            color: #fff;
        }

        .no-selected{
            width: 30px;
            height: 30px;
            text-align: center;
            padding: 6px 0;
            font-size: 12px;
            line-height: 1.428571429;
            border-radius: 15px;
            color: #000;
            background-color: #fff;
            border-color: #0084C3;
        }

   </style>
@endsection


@section('scripts')
    <script>
        const postulantStatus = document.getElementById("pstatus");
        postulantStatus.addEventListener("change", statusChange);

        function statusChange(event) {
            const currentValue = event.target.value;
            console.log(currentValue);
            if(currentValue == 'candidato' || currentValue == 'empleado'){
                document.getElementById('more-information').style.display = 'block';
            }else{
                document.getElementById('more-information').style.display = 'none';
            }
        }
    </script>
@stop