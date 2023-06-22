@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card-header">
            <div class="d-flex flex-row">
                <a  href="{{ route('team.admon')}}">
                    <i class="fa fa-arrow-left fa-2x arrouw-back" aria-hidden="true"></i>
                </a>
                <h1  style="margin-left:16px; font-size:25px" class="separator">Detalles de Solicitud</h1>

                <div class="card-body">
                    @if (session('success'))
                    <div class="alert alert-success" role="success">
                        {{session('success')}}
                    </div>   
                    @endif
            </div>
    </div>

    <div class="row">
            <div class="col-md-6">
                <h5 class="title mt-3" style="font-size: 15px;">Estado</h5>
                    <p class="description" style="font-size: 15px;">
                        @if ($information_request->status == 'Aprobada')
                                    <div class="text-left">
                                        <span class="badge bg-success">{{$information_request->status}}</span>
                                    </div>
    
                                    @elseif($information_request->status == 'Rechazada')
                                    <div class="text-left">
                                        <span class="badge bg-danger">{{ $information_request->status }}</span>
                                    </div>
    
                                    @elseif($information_request->status == 'Preaprobada')
                                    <div class="text-left">
                                        <span class="badge bg-warning text-dark">{{ $information_request->status }}</span>
                                    </div>
    
                                    @elseif($information_request->status == 'Solicitud Creada')
                                    <div class="text-left">
                                        <span class="badge bg-info text-dark">{{ $information_request->status }}</span>
                                    </div>
                        @endif
                    </p>
            </div>

            <div class="col-md-6">
                <h5 class="title mt-3" style="font-size: 15px;">Fecha de Solicitud Creada</h5>
                    <p class="description" style="font-size: 15px;">
                        Fecha de solicitud creada: {{$information_request->created_at}}<br>
                    </p>
            </div>
                
            <div class="col-md-6">
                <h5 class="title mt-3" style="font-size: 15px;">Datos Generales del Personal de Nuevo Ingreso</h5>
                    <p class="description" style="font-size: 15px;">
                        id de Solicitud: {{$information_request->id}}<br>
                        Tipo de usuario: {{$information_request->type_of_user}}<br>
                        Nombre: {{$information_request->user->name.' '. $information_request->user->lastname}}<br>
                        Fecha requerida: {{$information_request->date_admission}}<br>
                        Área: {{$information_request->area}}<br>
                        Departamento: {{$information_request->departament}}<br>
                        Puesto: {{$information_request->position}}<br>
                        Extensión: {{$information_request->extension}}<br>
                        Jefe inmediato: {{$information_request->immediate_boss}}<br>
                        Empresa: {{$information_request->company}}<br>
                    </p>
            </div>

            <div class="col-md-6">
                <h5 class="title mt-3" style="font-size: 15px;">Asignación de Equipo de Cómputo y Telefonía</h5>
                    <p class="description" style="font-size: 15px;">
                        Tipo de computadora: {{$information_request->computer_type}}<br>
                        Celular: {{$information_request->cell_phone}}<br>
                        #: {{$information_request->number}}<br>
                        No. de extensión: {{$information_request->extension_number}}<br>
                        Equipo a utilizar: {{$information_request->equipment_to_use}}<br>
                        Accesorios: {{$information_request->accessories}}<br>
                        En caso de ser reasignación de equipo indique el usuario anterior: {{$information_request->previous_user}}<br>
                    </p>
            </div>

            <div class="col-md-6">
                <h5 class="title mt-3" style="font-size: 15px;">Cuenta(s) de Correo(s) Requerida(s)</h5>
                    <p class="description" style="font-size: 15px;">
                        Correo: {{$information_request->email}}<br>
                    </p>
            </div>
            
            <div class="col-md-6">
                <h5 class="title mt-3" style="font-size: 15px;">Firma: Número(s) de Contacto Telefónico</h5>
                    <p class="description" style="font-size: 15px;">
                        Firma: {{$information_request->signature_or_telephone_contact_numer}}<br>
                    </p>
            </div>

            <div class="col-md-6">
                <h5 class="title mt-3" style="font-size: 15px;">Lista de Distribución y Reenvíos: (todos@ están considerados por default)</h5>
                    <p class="description" style="font-size: 15px;">
                        Distribución y Reenvíos: {{$information_request->distribution_and_forwarding}}<br>
                    </p>
            </div>

            <div class="col-md-6">
                <h5 class="title mt-3" style="font-size: 15px;">Software Requerido</h5>
                    <p class="description" style="font-size: 15px;">
                        Office: {{$information_request->office}}<br>
                        Acrobat PDF: {{$information_request->acrobat_pdf}}<br>
                        PhotoShop: {{$information_request->photoshop}}<br>
                        Premier: {{$information_request->premier}}<br>
                        Audition: {{$information_request->audition}}<br>
                        Solid Works: {{$information_request->solid_works}}<br>
                        Autocad: {{$information_request->autocad}}<br>
                        ODOO: {{$information_request->odoo}}<br>
                        Usuario(s) de ODOO: {{$information_request->odoo_users}}<br>
                        Perfil de Trabajo en ODOO {{$information_request->work_profile_in_odoo}}<br>
                        Otros: {{$information_request->others}}<br>
                    </p>
            </div>

            <div class="col-md-6">
                <h5 class="title mt-3" style="font-size: 15px;">Carpetas Compartidas del Servidor a las que debe tener acceso</h5>
                    <p class="description" style="font-size: 15px;">
                        Requiere Acceso a Carpeta Compartida del Servidor: {{$information_request->access_to_server_shared_folder}}<br>
                        Ruta de la Carpeta: {{$information_request->folder_path}}<br>
                        Tipo de Acceso: {{$information_request->type_of_access}}<br>
                    </p>
            </div>

            <div class="col-md-6">
                <h5 class="title mt-3" style="font-size: 15px;">Observaciones</h5>
                    <p class="description" style="font-size: 15px;">
                        Observaciones: {{$information_request->observations}}<br>
                    </p>
            </div>
    </div>

    <form action="{{route('team.status')}}" method="POST">
        {!! Form::open(['route' => 'team.status', 'enctype' => 'multipart/form-data']) !!}
                @csrf
                <input type="text" value="{{$information_request->id}}" name="id" hidden>
                <div class="col-md-3">
                    <div class="form-group">
                            {!! Form::select('status', ['Aprobada'=> 'Aprobada', 'Preaprobada'=> 'Preaprobada', 'Rechazada'=> 'Rechazada'], 'Estado', ['class' => 'form-control','placeholder' => 'Seleccione el cambio de estado']) !!}
                            @error('status')
                            <small>
                                <font color="red"> *Este campo es requerido* </font>
                            </small> 
                            @enderror
                    </div>
                </div>
                {!! Form::submit('ACTUALIZAR', ['class' => 'btnCreate mt-4']) !!}         
        {!! Form::close()!!}
    </form>
</div>

<style>
    h1{
        text-align: 10%;
    }

    .container {
    display: flex;
}

.left-column,
.right-column {
    flex: 1;
}

.left-column {
    margin-right: 10px;
}

.right-column {
    margin-left: 10px;
}
</style>
@endsection