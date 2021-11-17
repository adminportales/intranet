@extends('layouts.app')

@section('dashboard')


<div class="contenedor-logo">
  <ul class="logos" style="padding-left: 10px;">
    <li><a href="#"><img style="max-width: 50px;" src="{{asset('/img/bhtrade.png')}}" alt="bhtrade"></a> </li>
    <li><a href="#"><img style="max-width: 80px;" src="{{asset('/img/promolife.png')}}" alt="promolife"></a> </li>
    <li><a href="#"><img style="max-width: 50px;" src="{{asset('/img/promodreams.png')}}" alt="promodreams"></a> </li>
    <li><a href="#"><img style="max-width: 50px;" src="{{asset('/img/trademarket.png')}}" alt="trademarket"></a> </li>
  </ul>
</div>

<form>
  <div class="row mb-3">
    <div class="col-8">
      <label for="exampleFormControlInput1" class="form-label">Nombre </label>
      <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Ingrese el titulo del comunicado">
    </div>
    <div class="col-4 mb-3">
      <label for="exampleFormControlInput1" class="form-label">Fecha de solicitud </label>
      <input type="date" class="form-control" placeholder="Fecha de Solicitud">
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-8">
      <div class="mb-2">
        <label for="formFile" class="form-label">Tipo de solicitud</label>
        <select class="form-select" aria-label="Default select example">
          <option selected>Seleccionar tipo</option>
          <option value="1">Salir durante jornada</option>
          <option value="2">Vacaciones</option>
        </select>
      </div>
    </div>
    <div class="col-4">
      <label for="exampleFormControlInput1" class="form-label">Dia de aplicacion</label>
      <input type="date" class="form-control" placeholder="Fecha de Solicitud">
    </div>
  </div>
  <div class="mb-3">
    <label for="exampleFormControlTextarea1" class="form-label">Descripcion</label>
    <textarea class="form-control" id="exampleFormControlTextarea1" rows="4"></textarea>
  </div>

  <div class="form-row">
    <div class="form-group col col-lg-6">
      <div class="form-check">
        <input class="form-check-input" type="radio" name="radiobutton" id="" value="option1" checked>
        <label class="form-check-label" for="radiobutton1" style="color:#000000;">
          Descontar Tiempo/Dia
        </label>
      </div>

      <div class="form-check">
        <input class="form-check-input" type="radio" name="radiobutton" id="radiobutton2" value="option2">
        <label class="form-check-label" for="exampleRadios2" style="color:#000000;" >
          A cuenta de vacaciones
        </label>
      </div>
    </div>
  </div>

  <button class="btnCreate"><b>CREAR SOLICITUD</b></button>
</form>
@stop

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

@stop

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
@stop