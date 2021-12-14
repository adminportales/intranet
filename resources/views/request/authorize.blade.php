@extends('layouts.app')

@section('content')
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h3>Solicitudes recibidas</h3>
            <a href="{{ route('request.create') }}" type="button" class="btn btn-success">Agregar</a>
        </div>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col"># </th>
                    <th scope="col">Solicitante</th>
                    <th scope="col">Tipo</th>
                    <th scope="col">Pago</th>
                    <th scope="col">Fecha ausencia</th>
                    <th scope="col">Fecha reingreso</th>
                    <th scope="col">Motivo</th>
                    <th scope="col">Jefe status </th>
                    <th scope="col">RH status</th>
                    @can('rh.superior')
                        <th scope="col">Opciones</th>
                    @endcan
                </tr>
            </thead>
            <tbody>
                @foreach ($requests as $request)
                    <tr>
                        <td>{{ $request->id }}</td>
                        <td>{{ $request->employee->user->name . ' ' . $request->employee->user->lastname }}
                        </td>
                        <td>{{ $request->type_request }}</td>
                        <td>{{ $request->payment }}</td>
                        <td>{{ $request->absence }}</td>
                        <td>{{ $request->admission }}</td>
                        <td>{{ $request->reason }}</td>
                        <td>{{ $request->direct_manager_status }}</td>
                        <td>{{ $request->human_resources_status }}</td>


                        @can('rh.superior')
                            <td>
                                <a href="{{ route('request.edit', ['request' => $request->id]) }}" type="button"
                                    class="btn btn-primary">Detalles</a>
                            @endcan

                            @can('rh')
                                <form class="form-delete"
                                    action="{{ route('request.destroy', ['request' => $request->id]) }}" method="POST">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger">Borrar</button>
                                </form>
                            </td>
                        @endcan
                    </tr>
                @endforeach

            </tbody>
        </table>

    </div>
@stop

@section('scripts')

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $('.form-delete').submit(function(e) {
            e.preventDefault();

            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡El registro se eliminará permanentemente!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '¡Si, eliminar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            })
        });
    </script>
@stop