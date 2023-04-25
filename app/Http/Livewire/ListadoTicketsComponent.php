<?php

namespace App\Http\Livewire;;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;
use  App\Models\Soporte\Ticket;
use App\Models\Soporte\Categoria;
use App\Models\Soporte\Mensaje;
use App\Models\Soporte\Historial;


class ListadoTicketsComponent extends Component
{

    use WithFileUploads;
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $ticket_id, $name, $categoria, $data, $categorias,$actualizar_status,$ticket_solucion,$mensaje;

    public function render()
    {

        $this->categorias = Categoria::where('status', true)->get();
        return view('livewire.listado-tickets-component', [

            'tickets' => Ticket::where('user_id',auth()->id())->orderBy('id')->paginate(15)

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
                'data' => 'required',
                'categoria' => 'required'
            ]

        );

       $ticket= Ticket::create(
            [
                'name' => $this->name,
                'data' => $this->data,
                'category_id' => (int) $this->categoria,
                'user_id' => auth()->user()->id,
                'status_id' => 1
            ]
        );


        Historial::create(
            [
              'ticket_id'=>$ticket->id,
               'user_id'=>auth()->user()->id,
               'type'=>'creado',
               'data'=>'problema'

            ]
            );



        $this->name = ' ';
        $this->data = ' ';
        $this->categoria = ' ';
        $this->dispatchBrowserEvent('ticket_success');
    }

    public function editarTicket($id)
    {
        $ticket = Ticket::find($id);
        $this->ticket_id = $ticket->id;
        $this->name = $ticket->name;
        $this->data = $ticket->data;
        $this->categoria = $ticket->category->id;

        Historial::create(

            [
                'ticket_id'=>$ticket->id,
                'user_id'=>auth()->user()->id,
                'type'=>'edito',
                'data'=>'algo'
            ]
            );

        $this->dispatchBrowserEvent('mostrar_data');
    }

    public function guardarEditar($id)
    {
         $ticketEditar = Ticket::find($id);
         if ($this->data == trim('<p><br data-cke-filler="true"></p>')) {
             $this->addError('data', 'La descripcion es obligatoria');
             return;
         }

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

         $this->name = ' ';
         $this->categoria = ' ';
       $this->dispatchBrowserEvent('editar');
    }

    public function finalizarTicket($id)
    {
        $actualizar_status=Ticket::find($id);
        $actualizar_status->update(
            [

               'status_id' => 3
            ]
            );
    }

    public function verTicket($id)
    {
        $ticket = Ticket::find($id);
        $this->ticket_solucion=$ticket;
        $this->ticket_id = $ticket->id;
        $this->name = $ticket->name;
        $this->data = $ticket->data;
        $this->categoria=$ticket->category->name;





        $this->dispatchBrowserEvent('borrar');


    }

    public function enviarMensaje(){




        Mensaje::create([
            'ticket_id' => $this->ticket_id,
            'message' =>$this->mensaje,
            'user_id'=>auth()->user()->id
        ]);

        $this->dispatchBrowserEvent('Mensaje');
    }


}
