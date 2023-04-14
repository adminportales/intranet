<?php

namespace App\Http\Livewire;

use App\Models\Soporte\Categoria;
use App\Models\User;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\Calculation\Category;

class SoporteAdminComponent extends Component
{
    public $categorias, $name, $usuario_id;


    public function render()
    {


        $this->categorias = Categoria::orderBy('id')->get();

        $users = User::join('role_user', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('roles.name', '=', 'systems')
            ->select('users.*')
            ->get();

            
        return   view('livewire.soporte-admin-component')->with('users', $users);
    }


    public function verAsignacion($id)
    {
        $usuario = User::find($id);
        $this->usuario_id = $usuario->id;
        $this->name = $usuario->name;        
        //traer los tipos de ticket 
        

    }



    public function asignacion($categorias)
    {

        $usuario = User::find($this->usuario_id);
        $usuario->asignacionCategoria()->toggle([$categorias]);
      


    }
}
