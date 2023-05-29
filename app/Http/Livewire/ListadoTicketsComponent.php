<?php

namespace App\Http\Livewire;


use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;
use  App\Models\Soporte\Ticket;
use App\Models\Soporte\Categoria;
use App\Models\Soporte\Mensaje;
use App\Models\Soporte\Historial;
use App\Notifications\EditarTicketNotification;
use App\Notifications\SoporteNotification;
use App\Notifications\StatuSoporteFinalizadoNotification;
use App\Notifications\MessageSoporteNotification;

class ListadoTicketsComponent extends Component
{

    use WithFileUploads;
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $ticket_id, $name, $categoria, $data, $categorias, $actualizar_status, $ticket_solucion, $mensaje,$mensajes,$user;


    public function render()
    {

        $this->categorias = Categoria::where('status', true)->get();
        return view('livewire.listado-tickets-component', [

            'tickets' => Ticket::where('user_id', auth()->id())->orderBy('id')->paginate(15)

        ]);
    }



    public function guardar()
    {
        if ($this->data == trim('<p><br data-cke-filler="true"></p>')) {
            $this->addError('data', 'La descripcion es obligatoria');
            return;
        }

        $this->validate(
            [
                'name' => 'required',
                'data' => 'required|max:100000',
                'categoria' => 'required'
            ]
        );

        //encuentra la categoria del ticket
        $category = Categoria::find((int) $this->categoria);
        //encuentrar a los usuarios relacionados con la categoria del ticket

        $usuarios =  $category->usuarios;


        $ticket = Ticket::create(
            [
                'name' => $this->name,
                'data' => $this->data,
                'category_id' => (int) $this->categoria,
                'user_id' => auth()->user()->id,
                'status_id' => 1
            ]
        );



        //Historial de creado
        Historial::create(
            [
                'ticket_id' => $ticket->id,
                'user_id' => auth()->user()->id,
                'type' => 'creado',
                'data' => $ticket->data

            ]
        );


        $Notificacion =
            [
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'name_ticket' => $ticket->name,
                'data' => $ticket->data,
                'tiempo' => $ticket->created_at,
                'categoria'=>$category->name,
                'username'=>$usuarios['0']->name
            ];

        //arreglo para enviar la notificacion al usuario de la categoria
        foreach ($usuarios as $usuario) {

            $usuario->notify(new SoporteNotification($Notificacion));
        }



        $this->name = '';
        $this->categoria = '';
        $this->dispatchBrowserEvent('ticket_success');
    }


    public function editarTicket($id)
    {
        $ticket = Ticket::find($id);
        $this->ticket_id = $ticket->id;
        $this->name = $ticket->name;
        $this->data = $ticket->data;
        $this->categoria = $ticket->category->id;
        $this->dispatchBrowserEvent('mostrar_data');
    }

    public function guardarEditar($id)
    {
        $ticketEditar = Ticket::find($id);
        if ($this->data == trim('<p><br data-cke-filler="true"></p>')) {
            $this->addError('data', 'La descripcion es obligatoria');
            return;
        }

        //dd($ticketEditar->category_id);
        $category = Categoria::find($ticketEditar->category_id);
        $usuarios = $category->usuarios;


        $this->validate(
            [
                'name' => 'required',
                'data' => 'required',
                'categoria' => 'required'
            ]

        );

        $ticketEditar->update([
            'name' => $this->name,
            'data' => $this->data,
            'category_id' => $this->categoria,
        ]);

        Historial::create([
            'ticket_id' => $ticketEditar->id,
            'user_id' => auth()->user()->id,
            'type' => 'edito',
            'data' => $ticketEditar->data
        ]);

        $notificacionEditar = [
            'name' => auth()->user()->name,
            'name_ticket' => $ticketEditar->name,
        ];

        foreach ($usuarios  as $usuario) {
            $usuario->notify(new EditarTicketNotification($notificacionEditar));
        }

        $this->name = '';
        $this->categoria = ' ';
        $this->dispatchBrowserEvent('editar');
    }

    public function finalizarTicket($id)
    {
        $actualizar_status = Ticket::find($id);
        $category = Categoria::find($actualizar_status->category_id);
        $usuarios = $category->usuarios;
        $actualizar_status->update(
            [

                'status_id' => 4
            ]
        );



        Historial::create(

            [
                'ticket_id' => $actualizar_status->id,
                'user_id' => auth()->user()->id,
                'type' => 'status_finished',
                'data' => $actualizar_status->status->name
            ]
        );


        $NotificacionStatus =
            [
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'name_ticket' => $actualizar_status->name,
                'status' => $actualizar_status->status->name,
                'username'=>$usuarios['0']->name


            ];

        //for each para enviar notificacion de status a los usuarios relacionados
        foreach ($usuarios as $usuarios) {
            $usuarios->notify(new StatuSoporteFinalizadoNotification($NotificacionStatus));
        }
    }

    public function verTicket($id)
    {
        $ticket = Ticket::find($id);
        $message = Mensaje::find($id);
        $this->user=$message;
        $this->mensajes=$ticket;
        $this->ticket_solucion = $ticket;
        $this->ticket_id = $ticket->id;
        $this->name = $ticket->name;
        $this->data = $ticket->data;
        $this->categoria = $ticket->category->name;
        $this->dispatchBrowserEvent('cargar');
    }

    public function enviarMensaje()
    {

        $ticket = Ticket::find($this->ticket_id);
        $category = Categoria::find($ticket->category_id);

        $usuarios = $category->usuarios;
        // Mensaje::create([
        //     'ticket_id' => $this->ticket_id,
        //     'message' => $this->mensaje,
        //     'user_id' => auth()->user()->id
        // ]);

        //si el usuario envia un mensaje al de soporte se cambia el status a proceso

        if ($ticket->status_id == 1) {
            Mensaje::create([
                'ticket_id' => $this->ticket_id,
                'message' => $this->mensaje,
                'user_id' => auth()->user()->id
            ]);
        } else {

            Mensaje::create([
                'ticket_id' => $this->ticket_id,
                'message' => $this->mensaje,
                'user_id' => auth()->user()->id
            ]);
            $ticket->update([
                'status_id' => 2
            ]);
        }

        // $ticket->update([
        //     'status_id' => 2
        // ]);

        Historial::create(

            [
                'ticket_id' => $this->ticket_id,
                'user_id' => auth()->user()->id,
                'type' => 'Mensaje',
                'data' => $this->mensaje
            ]
        );

        $notificationMessage = [
            'name' => auth()->user()->name,
            'name_ticket' => $ticket->name
        ];

        foreach ($usuarios as $usuario) {
            $usuario->notify(new MessageSoporteNotification($notificationMessage));
        }

        $this->dispatchBrowserEvent('Mensaje');
    }
}