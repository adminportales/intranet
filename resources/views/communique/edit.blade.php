@extends('layouts.app')

@section('content')
    <div class="card-header">
        <h3>Crear comunicado</h3>
    </div>
    <div class="card-body">     
    <form action="{{ route('communique.update',$communique) }}" enctype="multipart/form-data" method="POST">
        @csrf
        @method('POST')
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">Titulo </label>
            <input type="text" class="form-control" name="title" placeholder="Ingrese el titulo del comunicado" value="{{$communique->title}}">
            
            @error('title')
            <small>
                <font color="red"> *Este campo es requerido* </font>
            </small>
            <br>
            @enderror
        </div>


        <div class="row">
            <div class="col-12">
                <div class="mb-2">
                    <label for="formFile" class="form-label">Imagen</label>
                    <input class="form-control" type="file" name="image" id="formFile">
                    
                    @error('image')
                    <small>
                        <font color="red"> *Este campo es requerido* </font>
                    </small>
                    <br>
                    @enderror
                
                </div>
            </div>
            {{-- <div class="col-4">
                <div class="mb-2">
                    <label for="formFile" class="form-label">Departamento</label>
                    <select class="form-select" aria-label="Default select example">
                        <option selected>Seleccionar departamento</option>
                        <option value="1">Todos</option>
                        <option value="2">Sistemas</option>
                        <option value="3">Ventas</option>
                        <option value="4">Diseno</option>
                        <option value="5">Marketing</option>

                    </select>
                </div>
            </div> --}}
        </div>

        {{-- <div class="row mb-3">
            <label for="exampleFormControlTextarea1" class="form-label">Enviar a</label>
            <div class="btn-group" role="group" aria-label="Basic checkbox toggle button group">
                <input type="checkbox" class="btn-check" id="btncheck1" autocomplete="off">
                <label class="btn btn-outline-primary" for="btncheck1">Promolife</label>

                <input type="checkbox" class="btn-check" id="btncheck2" autocomplete="off">
                <label class="btn btn-outline-primary" for="btncheck2">BH Trademarket</label>

                <input type="checkbox" class="btn-check" id="btncheck3" autocomplete="off">
                <label class="btn btn-outline-primary" for="btncheck3">Trademarket</label>

                <input type="checkbox" class="btn-check" id="btncheck4" autocomplete="off">
                <label class="btn btn-outline-primary" for="btncheck4">Promodreams</label>
            </div>
        </div> --}}

        <div class="mb-3">
            <label for="exampleFormControlTextarea1" class="form-label">Descripcion</label>
            <textarea class="form-control" id="exampleFormControlTextarea1" rows="4" name="description">{{$communique->description}}</textarea>
        
            @error('description')
            <small>
                <font color="red"> *Este campo es requerido* </font>
            </small>
            <br>
            @enderror
        
        </div>
        <input type="submit" class="btnCreate" value="ACTUALIZAR COMUNICADO"></button>
    </form>

</div>
@stop


@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

@stop

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
@stop