@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card-header">
        <h1 style="font-size:20px">Estado actual de Solicitud</h1>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped" id="table-directory">
                <thead>
                    <tr>
                        <th scope="col" style="text-align: center">Categoria</th>
                        <th scope="col" style="text-align: center">Descripción</th>
                        <th scope="col" style="text-align: center">Estado</th>
                        <th scope="col" style="text-align: center">ID de Solicitud</th>
                        <th scope="col" style="text-align: center">Fecha de Solicitud</th>
                        <th scope="col" style="text-align: center">Opciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($systems_request as $systems )
                    <tr>
                        <td>{{$systems->category}}</td>
                        <td>{{$systems->description}}</td>
                        <td style="text-align: center">{{$systems->status}}</td>
                        <td style="text-align: center">{{$systems->id}}</td>
                        <td style="text-align: center">{{$systems->updated_at}}</td> 
                        <td>
                            <button  sttype="button" class="btn btn-primary" style="font-size: 11px">Ver Más</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

    <style>
        table {
        font-size: 79.5%;
        }
    </style>
@endsection
            