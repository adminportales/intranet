<div>
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h3>Soporte</h3>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#ModalAgregar">
                <i class="bi bi-plus-square">Crear solicitud</i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table text-center">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Categoría</th>
                        <th scope="col">Status</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tickets as $ticket)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $ticket->name }}</td>
                            <td class="col-2">{{ $ticket->category->name }}</td>
                            <td class="col-2">
                                @if ($ticket->status->name == 'Resuelto')
                                    <div class="alert-sm alert-success rounded-3" role="alert">
                                        {{ $ticket->status->name }}</div>
                                @elseif ($ticket->status->name == 'Creado')
                                    <div class="alert-sm alert-info rounded-3" role="alert">
                                        {{ $ticket->status->name }}</div>
                                @elseif ($ticket->status->name == 'En proceso')
                                    <div class="alert-sm alert-primary rounded-3" role="alert">
                                        {{ $ticket->status->name }}</div>
                                @elseif ($ticket->status->name == 'Ticket Cerrado')
                                    <div class="alert-sm alert-warning rounded-3" role="alert">
                                        {{ $ticket->status->name }}</div>
                                @endif

                            </td>
                            <td>

                                <button type="button" class="btn btn-success btn-sm " data-bs-toggle="modal"
                                    data-bs-target="#ModalVer" wire:click="verTicket({{ $ticket->id }})"><i
                                        class="bi bi-eye"></i></button>
                                @if ($ticket->status->name == 'Creado' || $ticket->status->name == 'En proceso' || $ticket->status->name == 'Resuelto')
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#ModalEditar" wire:click="editarTicket({{ $ticket->id }})"><i
                                            class="bi bi-pencil"></i></button>

                                    <button type="button" class="btn btn-info btn-sm"
                                        onclick="finalizar({{ $ticket->id }})"><i
                                            class="bi bi-check-square"></i></button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $tickets->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Agregar-->
    <div wire:ignore.self data class="modal fade" id="ModalAgregar" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Agregar ticket</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="Problema" class="form-label">Problema a resolver</label>
                            <input type="text"
                                class="form-control input-lg @error('name') is-invalid @enderror "placeholder="ingresa el problema a resolver"
                                name="name" wire:model="name" value="{{ old('name') }}">
                            @error('name')
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="Problema" class="form-label ">Categoría</label>
                            <div class="input-group mb-3">
                                <label class="input-group-text " for="inputGroupSelect01">Categoría</label>
                                <select wire:model="categoria" name="categoria"
                                    class="form-select @error('categoria') is-invalid @enderror"
                                    id="inputGroupSelect01">
                                    <option value="" selected>Seleccionar</option>
                                    @foreach ($categorias as $categoriaa)
                                        <option value="{{ $categoriaa->id }}">{{ $categoriaa->name }}</option>
                                    @endforeach
                                </select>
                                @error('categoria')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div wire:ignore class="mb-3 text-input-crear">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea wire:model="data" id="editor" cols="20" rows="3" class="form-control" name="data"></textarea>
                        </div>
                        @error('data')
                            <p class="text-danger fz-1 font-bold m-0">{{ $message }}</p>
                        @enderror
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-success" wire:click='guardar'>Guardar</button>
                    <div wire:loading.flex wire:target="guardar">
                        Guardando
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Modal editar --}}
    <div wire:ignore.self class="modal fade" id="ModalEditar" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Editar ticktet</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="Problema" class="form-label ">Problema a resolver</label>
                            <input type="text" class="form-control input-lg @error('name') is-invalid @enderror "
                                placeholder="ingresa el problema a resolver" name="name" wire:model="name"
                                value="{{ old('name') }}">
                            @error('name')
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="Problema" class="form-label">Categoría</label>
                            <div class="input-group mb-3">
                                <label class="input-group-text" for="inputGroupSelect01">Categoría</label>
                                <select wire:model="categoria" name="categoria"
                                    class="form-select @error('categoria') is-invalid @enderror"
                                    id="inputGroupSelect01">
                                    <option value="" selected>Seleccionar</option>
                                    @foreach ($categorias as $categoriaa)
                                        <option value="{{ $categoriaa->id }}">{{ $categoriaa->name }}</option>
                                    @endforeach
                                </select>
                                @error('categoria')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div wire:ignore class="mb-3 text-input-editar">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea wire:model="data" id="editorEditar" cols="20" rows="3" class="form-control" name="data"></textarea>

                        </div>
                        @error('data')
                            <p class="text-danger fz-1 font-bold m-0">{{ $message }}</p>
                        @enderror
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-success"
                        wire:click="guardarEditar({{ $ticket_id }})">Guardar</button>
                    <div wire:loading.flex wire:target="guardarEditar">
                        Guardando
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Modal ver --}}
    <div wire:ignore.self class="modal fade " id="ModalVer" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation" wire:ignore>
                            <button class="nav-link active" id="home-tab" data-bs-toggle="tab"
                                data-bs-target="#home" type="button" role="tab" aria-controls="home"
                                aria-selected="true">Detalles en ticket</button>
                        </li>
                        <li class="nav-item" role="presentation" wire:ignore>
                            <button class="nav-link" id="historial-tab" data-bs-toggle="tab"
                                data-bs-target="#historial" type="button" role="tab" aria-controls="historial"
                                aria-selected="false">Historial</button>
                        </li>
                        <li class="nav-item" role="presentation" wire:ignore>
                            <button class="nav-link" id="mensaje-tab" data-bs-toggle="tab" data-bs-target="#mensaje"
                                type="button" role="tab" aria-controls="historial"
                                aria-selected="false">Mensajes</button>
                        </li>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel"
                            aria-labelledby="home-tab" wire:ignore.self>
                            <p><span class="fw-bold ">Problema a resolver :</span> <span
                                    class="">{{ $name }}</span></p>

                            <p><span class="fw-bold">Categoría :</span> <span
                                    class="Psop">{{ $categoria }}</span></p>

                            <p><span class="fw-bold">Descripción :</span></p>

                            <div class="text-mostrar">
                                <p>{!! $data !!}</p>
                            </div>
                            <hr>
                            <p><span class="fw-bold ">Solución:</span></p>
                            @if ($ticket_solucion)
                                @foreach ($ticket_solucion->solution as $solucion)
                                    {!! $solucion->description !!}
                                @endforeach
                            @endif
                            <div class="modal-footer">

                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="historial" role="tabpanel" aria-labelledby="historial-tab"
                            wire:ignore.self>

                            @if ($ticket_solucion)
                                @foreach ($ticket_solucion->historial as $cambio)
                                    @if ($cambio->type == 'creado')
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-auto text-center  flex-column  d-none  d-sm-flex">
                                                    <div class="row h-50">
                                                        <div class="col">&nbsp;</div>
                                                        <div class="col ">&nbsp;</div>
                                                    </div>
                                                    <h5 class="m-2">
                                                        <span class=" rounded-circle bg-light "><i
                                                                class="bi bi-check-circle-fill"></i></span>
                                                    </h5>
                                                    <div class="row h-50">
                                                        <div class="col border-end">
                                                            &nbsp;
                                                        </div>
                                                        <div class="col">
                                                            &nbsp;
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col py-2">
                                                    <div class="card ">

                                                        <div class="card-body rounded-3  shadow " id="historial">
                                                            <div class="float-end text-dark">
                                                                ({{ $cambio->created_at->diffForHumans() }})
                                                            </div>
                                                            <h4 class="card-title text-green">Ticket
                                                                {{ $cambio->type }}</h4>
                                                            <p class="card-text text-dark">{!! $cambio->data !!}</p>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif ($cambio->type == 'edito')
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-auto text-center  flex-column  d-none  d-sm-flex">
                                                    <div class="row h-50">
                                                        <div class="col">&nbsp;</div>
                                                        <div class="col ">&nbsp;</div>
                                                    </div>
                                                    <h5 class="m-2">
                                                        <span class=" rounded-circle bg-light "><i
                                                                class="bi bi-pencil-square"></i></span>
                                                    </h5>
                                                    <div class="row h-50">
                                                        <div class="col border-end">
                                                            &nbsp;
                                                        </div>
                                                        <div class="col">
                                                            &nbsp;
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col py-2">
                                                    <div class="card">

                                                        <div class="card-body rounded-3  shadow " id="historial">
                                                            <div class="float-end text-dark">
                                                                ({{ $cambio->created_at->diffForHumans() }})</div>
                                                            <h4 class="card-title text-green">{{ $cambio->type }}</h4>
                                                            <p class="card-text text-dark">{!! $cambio->data !!}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif ($cambio->type == 'Mensaje')
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-auto text-center  flex-column  d-none  d-sm-flex">
                                                    <div class="row h-50">
                                                        <div class="col">&nbsp;</div>
                                                        <div class="col ">&nbsp;</div>
                                                    </div>
                                                    <h5 class="m-2">
                                                        <span class=" rounded-circle bg-light "><i
                                                                class="bi bi-envelope"></i></span>
                                                    </h5>
                                                    <div class="row h-50">
                                                        <div class="col border-end">
                                                            &nbsp;
                                                        </div>
                                                        <div class="col">
                                                            &nbsp;
                                                        </div>
                                                    </div>
                                                </div>
                                                @if ($cambio->user_id == auth()->user()->id)
                                                    <div class="col py-2">
                                                        <div class="card">
                                                            <div class="card-body rounded-3  shadow " id="historial">
                                                                <div class="float-end text-dark">
                                                                    ({{ $cambio->created_at->diffForHumans() }})</div>
                                                                <h4 class="card-title text-green">{{ $cambio->type }}
                                                                    de {{ auth()->user()->name }}</h4>
                                                                <p class="card-text text-dark">{!! $cambio->data !!}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="col py-2">
                                                        <div class="card">
                                                            <div class="card-body rounded-3  shadow " id="historial">
                                                                <div class="float-end text-dark">
                                                                    ({{ $cambio->created_at->diffForHumans() }})</div>
                                                                {{-- {{$user->name}} --}}
                                                                <h4 class="card-title text-green">{{ $cambio->type }}
                                                                    de Soporte</h4>
                                                                <p class="card-text text-dark">{!! $cambio->data !!}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @elseif ($cambio->type == 'status')
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-auto text-center  flex-column  d-none  d-sm-flex">
                                                    <div class="row h-50">
                                                        <div class="col">&nbsp;</div>
                                                        <div class="col ">&nbsp;</div>
                                                    </div>
                                                    <h5 class="m-2">
                                                        <span class=" rounded-circle bg-light "><i
                                                                class="bi bi-eye"></i></span>
                                                    </h5>
                                                    <div class="row h-50">
                                                        <div class="col border-end">
                                                            &nbsp;
                                                        </div>
                                                        <div class="col">
                                                            &nbsp;
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col py-2">
                                                    <div class="card">

                                                        <div class="card-body rounded  shadow ">
                                                            <div class="float-end text-dark">
                                                                ({{ $cambio->created_at->diffForHumans() }})</div>
                                                            <h4 class="card-title text-green">Visto</h4>
                                                            <p class="card-text text-dark">{!! $cambio->data !!}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif ($cambio->type == 'solucion')
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-auto text-center  flex-column  d-none  d-sm-flex">
                                                    <div class="row h-50">
                                                        <div class="col">&nbsp;</div>
                                                        <div class="col ">&nbsp;</div>
                                                    </div>
                                                    <h5 class="m-2">
                                                        <span class=" rounded-circle bg-light "><i
                                                                class="bi bi-check2-all">
                                                            </i></span>
                                                    </h5>
                                                    <div class="row h-50">
                                                        <div class="col border-end">
                                                            &nbsp;
                                                        </div>
                                                        <div class="col">
                                                            &nbsp;
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col py-2">
                                                    <div class="card">

                                                        <div class="card-body shadow">
                                                            <div class="float-end text-dark">
                                                                ({{ $cambio->created_at->diffForHumans() }})</div>
                                                            <h4 class="card-title text-green">{{ $cambio->type }}</h4>
                                                            <p class="card-text text-dark">{!! $cambio->data !!}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif ($cambio->type == 'status_finished')
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-auto text-center  flex-column  d-none  d-sm-flex">
                                                    <div class="row h-50">
                                                        <div class="col">&nbsp;</div>
                                                        <div class="col ">&nbsp;</div>
                                                    </div>
                                                    <h5 class="m-2">
                                                        <span class=" rounded-circle bg-light "><i
                                                                class="bi bi-check2-all">
                                                            </i></span>
                                                    </h5>
                                                    <div class="row h-50">
                                                        <div class="col border-end">
                                                            &nbsp;
                                                        </div>
                                                        <div class="col">
                                                            &nbsp;
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col py-2">
                                                    <div class="card">
                                                        <div class="card-body  shadow ">
                                                            <div class="float-end text-dark">
                                                                ({{ $cambio->created_at->diffForHumans() }})</div>
                                                            <h4 class="card-title text-green">Ticket Cerrado</h4>
                                                            <p class="card-text text-dark">{!! $cambio->data !!}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                        <div class="tab-pane fade" id="mensaje" role="tabpanel" aria-labelledby="mensaje-tab"
                            wire:ignore.self>
                            @if ($mensajes)
                                @foreach ($mensajes->mensajes as $mensaje)
                                    @if ($mensaje->user_id == auth()->user()->id)
                                        <div class="d-flex flex-row justify-content-end mb-3  pt-3">
                                            <span class="p-2 shadow bg-ligth rounded-3  text-dark"><span
                                                    class="mb-3 pt-3">{!! $mensaje->mensaje !!}
                                                </span><span>{{ $mensajes->created_at->diffForHumans() }}</span></span>
                                            <i class="bi bi-person-circle"></i>
                                        </div>
                                    @else
                                        <div class="d-flex flex-row justify-content-start">
                                            <i class="bi bi-person-circle"></i>
                                            <span class="p-1 shadow bg-ligth rounded-3 text-dark"><span
                                                    class="fw-bold">{{ $mensaje->usuarios->name }}</span>{!! $mensaje->mensaje !!}
                                                <span><span>{{ $mensajes->created_at->diffForHumans() }}</span></span></span>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                            <hr>
                            <div>
                                <div wire:ignore class="mb-3 text-input-mensaje">
                                    <textarea wire:model="mensaje" id="editorMensaje" cols="20" rows="3" class="form-control"
                                        name="mensaje"></textarea>

                                </div>
                                @error('mensaje')
                                    <p class="text-danger fz-1 font-bold m-0">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="modal-footer">
                                @if ($ticket_solucion)
                                    @if ($ticket_solucion->status_id == 4)
                                        <button type="button" class="btn btn-danger"
                                            data-bs-dismiss="modal">Cerrar</button>
                                    @else
                                        <button type="button" class="btn btn-danger"
                                            data-bs-dismiss="modal">Cerrar</button>
                                        <button type="button" class="btn btn-success"
                                            wire:click="enviarMensaje">Enviar</button>
                                        <div wire:loading.flex wire:target="enviarMensaje">
                                            Enviando
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        let ckEditorCreate, ckEditorEdit, ckEditorMensaje;
        ClassicEditor
            .create(document.querySelector('#editor'), {
                removePlugins: ['MediaEmbed'],
                extraPlugins: [MyCustomUploadAdapterPlugin],
            })
            .then(newEditor => {
                ckEditorCreate = newEditor;
                ckEditorCreate.model.document.on('change', () => {
                    const content = ckEditorCreate.getData();
                    @this.data = content
                    console.log(content);
                });
            })
            .catch(error => {
                console.error(error);
            });
        class MyUploadAdapter {
            constructor(loader) {
                this.loader = loader;
            }
            upload() {
                return this.loader.file
                    .then(file => new Promise((resolve, reject) => {
                        this._initRequest();
                        this._initListeners(resolve, reject, file);
                        this._sendRequest(file);
                    }));
            }
            abort() {
                if (this.xhr) {
                    this.xhr.abort();
                }
            }
            _initRequest() {
                const xhr = this.xhr = new XMLHttpRequest();

                xhr.open('POST', "{{ route('upload', ['_token' => csrf_token()]) }}", true);
                xhr.responseType = 'json';
            }
            _initListeners(resolve, reject, file) {
                const xhr = this.xhr;
                const loader = this.loader;
                const genericErrorText = `Couldn't upload file: ${ file.name }.`;

                xhr.addEventListener('error', () => reject(genericErrorText));
                xhr.addEventListener('abort', () => reject());
                xhr.addEventListener('load', () => {
                    const response = xhr.response;

                    if (!response || response.error) {
                        return reject(response && response.error ? response.error.message : genericErrorText);
                    }
                    resolve(response);
                });
                if (xhr.upload) {
                    xhr.upload.addEventListener('progress', evt => {
                        if (evt.lengthComputable) {
                            loader.uploadTotal = evt.total;
                            loader.uploaded = evt.loaded;
                        }
                    });
                }
            }
            _sendRequest(file) {
                const data = new FormData();
                data.append('upload', file);
                this.xhr.send(data);
            }
        }

        function MyCustomUploadAdapterPlugin(editor) {
            editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                return new MyUploadAdapter(loader);
            };
        }

        ClassicEditor
            .create(document.querySelector('#editorEditar'), {
                removePlugins: ['MediaEmbed'],
                extraPlugins: [MyCustomUploadAdapterPlugin],
            })
            .then(newEditor => {
                ckEditorEdit = newEditor;
                // Escucha el evento 'change'
                ckEditorEdit.model.document.on('change', () => {
                    const content = ckEditorEdit.getData();
                    @this.data = content
                    console.log(content);
                });

            })
            .catch(error => {
                console.error(error);
            });


        ClassicEditor
            .create(document.querySelector('#editorMensaje'), {
                // extraPlugins: [MyCustomUploadAdapterPlugin],
            })
            .then(newEditor => {
                ckEditorMensaje = newEditor;


            })
            .catch(error => {
                console.error(error);
            });

        window.addEventListener("mostrar_data", () => {
            ckEditorEdit.setData(@this.data);

        });

        window.addEventListener('cargar', () => {
            if (ckEditorMensaje) {
                ckEditorMensaje.destroy();

                ClassicEditor
                    .create(document.querySelector('#editorMensaje'), {
                        removePlugins: ['MediaEmbed'],
                        extraPlugins: [MyCustomUploadAdapterPlugin],
                    })
                    .then(newEditor => {
                        ckEditorMensaje = newEditor;

                        ckEditorMensaje.model.document.on('change', () => {
                            const content = ckEditorMensaje.getData();
                            @this.mensaje = content
                            console.log(content);
                        });

                    })
                    .catch(error => {
                        console.error(error);
                    });
            }

        })

        window.addEventListener('ticket_success', () => {
            Swal.fire({
                icon: 'success',
                title: 'Ticket enviado correctamente',
                showConfirmButton: false,
                timer: 1500
            })

            $('#ModalAgregar').modal('hide')

            ckEditorCreate.setData("");

        });
        window.addEventListener('editar', () => {
            Swal.fire({

                icon: 'success',
                title: 'Ticket editado correctamente',
                showConfirmButton: false,
                timer: 1500
            })

            $('#ModalEditar').modal('hide')
            ckEditorEdit.setData("");


        });
        window.addEventListener('Mensaje', () => {
            Swal.fire({

                icon: 'success',
                title: 'Mensaje enviado correctamente',
                showConfirmButton: false,
                timer: 1500
            })
            // $('#ModalVer').modal('hide');
            ckEditorMensaje.setData("");


        });

        window.addEventListener('category_empty', () => {
            Swal.fire({

                icon: 'erro',
                title: 'La categoria no tiene un usuario asignado',
                showConfirmButton: false,
                timer: 1500
            })
        })

        function finalizar(id) {
            Swal.fire({
                title: 'Quieres finalizar el ticket?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Finalizar'
            }).then((result) => {
                if (result.isConfirmed) {
                    let resultado = @this.finalizarTicket(id)
                    Swal.fire(
                        'Finalizado ',
                        'El ticket a sido finalizado',
                        'success'
                    )
                } else {
                    return;
                }

            })

        }
    </script>
</div>