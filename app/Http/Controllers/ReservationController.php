<?php

namespace App\Http\Controllers;

use App\Models\boardroom;
use App\Models\Department;
use App\Models\Position;
use App\Models\Reservation;
use App\Models\User;
use App\Notifications\NotificacionEdit;
use App\Notifications\notificacionPJ;
use App\Notifications\notificacionPJEdit;
use App\Notifications\NotificacionReservaMasiva;
use App\Notifications\notificacionRH;
use App\Notifications\notificacionRHEdit;
use App\Notifications\NotificacionSalas;
use App\Notifications\notificacionSistemas;
use App\Notifications\notificacionSistemasEdit;
use App\Notifications\NotificacionReservaMasivaEdit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Manager;
use Exception;

class ReservationController extends Controller
{
    /////////////////////////////////////////////////////MOSTRAR VISTA//////////////////////////////////////////////
    public function index(Request $request)
    {
        $user = auth()->user();
        $salitas = boardroom::all();
        $boardroom = boardroom::all()->pluck('name', 'id');
        $eventos = Reservation::all();
        $guests = $eventos->pluck('guest');
        $departments  = Department::pluck('name', 'id')->toArray();
        $nombresInvitados = Reservation::whereIn('guest', $guests)->get();
        $arreglon = [];
        foreach ($nombresInvitados as $invitados) {
            $arreglo = explode(',', $invitados->guest);
            $arreglon[] = $arreglo;
        }

        $nameusers = [];
        foreach ($arreglon as $nombresusuarios) {
            $nombres = [];
            foreach ($nombresusuarios as $id) {
                $user = User::where('id', $id)->first();
                if ($user) {
                    $nombre = $user->name;
                    $apellido = $user->lastname;
                    $nombres[] = "$nombre $apellido";
                } else {
                    $nombres[] = "Usuario no encontrado";
                }
            }
            $nameusers[] = $nombres;
        }
        //dd($nameusers);
        //OBTENCIÓN DE LOS JEFES DE CADA ÁREA (GERENTES)//
        $managers = Manager::all();
        $gerentes = [];
        foreach ($managers as $manager) {
            $user = User::find($manager->users_id);
            if ($user) {
                $gerentes[$user->id] = $user->id;
            }
        }
       // dd($gerentes);    
        return view('admin.room.index', compact('user', 'salitas', 'boardroom', 'eventos', 'departments', 'nameusers', 'gerentes'));
    }
    ///////////////////////////////////////////////////////BUSCAR POR DEPARTAMENTOS//////////////////////////////////////
    public function Positions($id)
    {
        $dep = Department::find($id);
        $positions = Position::where("department_id", $id)->pluck("name", "id");
        $data = $dep->positions;
        $users = [];

        foreach ($data as $dat) {
            foreach ($dat->getEmployees as $emp) {
                if ($emp->user->status == 1) {  
                    $users["{$emp->user->id}"] = $emp->user->name . ' ' . $emp->user->lastname;
                }
            }
        }
        return response()->json(['positions' => $positions, 'users' => $users]);
    }
    /////////////////////////////////////////////FUNCIÓN CREAR EVENTO//////////////////////////////////////////////////
    public function store(Request $request)
    {
        //AUTENTIFICAR AL USUARIO//
        $user = auth()->user();

        //OBTENER LA INFORMACIÓN DEL FORM//
        $request->validate([
            'title' => 'required',
            'start' => 'required',
            'end' => 'required',
            'description' => 'required',
            'engrave' => 'required',
            'reservation' => 'required',
            'id_sala'=> 'required',
            'guest'=> 'required|array|min:1'
        ]);
        
        //dd($request->reservation);
        //dd($request);
        //VARIBLES PARA NO CONFUNDIRSE///
        $fecha_inicio =  $request->start;
        $fecha_termino =  $request->end;

        //OBTENEMOS LOS EVENTOS DEL DÍA//
        $EventosDelDia = Reservation::whereDate('start', Carbon::parse($fecha_inicio)->format('Y-m-d'))
            ->whereDate('end', Carbon::parse($fecha_termino)->format('Y-m-d'))
            ->where('id_sala', $request->id_sala)->get();
        //return ($EventosDelDia);

        //OBTENEMOS UN NUEVO ARREGLO DE LOS EVENTOS YA CREADOS PARA PODER CONVERTIR LAS HORAS A MILISEGUNDOS//
        $eventosRefactorizados = [];
        foreach ($EventosDelDia as $item) {
            $componentes = [
                'id' => $item['id'],
                'start' => strtotime($item['start']) * 1000,
                'end' => strtotime($item['end']) * 1000,
                'id_sala' => $item['id_sala']
            ];
            //array_push($eventosRefactorizados, $componentes); otra forma de traer el arreglo nuevo
            $eventosRefactorizados[] = $componentes;
        }
        //dd($eventosRefactorizados);

        //FORMATEAMOS LAS HORAS PARA PODERLAS CONVERTIR A MILISEGUNDOS//
        $inicio = $request->start; // Fecha de inicio del form
        $fechastart = Carbon::parse($inicio);
        $fechaInicio = strtotime($fechastart->format('Y-m-d H:i:s')) * 1000;

        $final = $request->end; //fecha de fin del form
        $fechaend = Carbon::parse($final);
        $fechaFinal = strtotime($fechaend->format('Y-m-d H:i:s')) * 1000;
        $fechaActual = now()->format('Y-m-d H:i:s');
        //dd($fechaActual);

        if ($fecha_inicio <= $fechaActual) {
            return redirect()->back()->with('message1', 'No se puede crear una reservación en una fecha pasada.');
        }

        if ($fecha_termino < $fecha_inicio) {
            return redirect()->back()->with('message1', "Una reservación no puede finalizar antes que la hora de inicio.");
        }

        $allreservation = Reservation::where('id_usuario', $user->id)
            ->where('start', '>=', $request->start)
            ->where('end', '<=', $request->end)
            ->exists();
        if ($allreservation) {
            return redirect()->back()->with('message1', 'No puedes reservar todas las salas a la misma fecha y hora.');
        }
        
        $gerentes = Reservation::where('start','<=', $fecha_inicio)
                                ->where('end','>=', $fecha_termino)
                                ->where('reservation', 'Sí')
                                ->exists();                 
        if ($gerentes) {
            return redirect()->back()->with('message1', 'Un gerente reservo toda la sala, por lo tanto no puedes crear un evento en esta fecha y hora.');
        }
        
        //CONDICIONES QUE DEBE PASAR ANRTES DE EDITAR AL EVENTO// 
        foreach ($eventosRefactorizados as $evento) {
            if (($fechaInicio >= $evento['start'] && $fechaInicio < $evento['end']) ||
                ($fechaFinal > $evento['start'] && $fechaFinal <= $evento['end']) ||
                ($fechaInicio <= $evento['start'] && $fechaFinal >= $evento['end'])
            ) {
                return redirect()->back()->with('message1', "El evento no puede tomar horas de otros eventos ya creados.");
            }
        }
        
        if ($request->has('guest') && is_array($request->guest)) {
            $NameUserGuest = $request->guest;
            $invitadosIds = User::whereIn(DB::raw("CONCAT(name, ' ', lastname)"), $NameUserGuest)
                                              ->pluck('id')
                                              ->toArray();
            }
        $invitados = implode(',' . ' ', $invitadosIds);
        
        //UNA VEZ QUE YA PASO LAS VALIDACIÓNES CREA EL EVENETO//
        $evento = new Reservation();
        $evento->title = $request->title;
        $evento->start = $request->start;
        $evento->end = $request->end;
        $evento->guest = $invitados;
        $evento->engrave = $request->engrave;
        $evento->chair_loan = $request->chair_loan ?? 0;
        $evento->proyector = $request->proyector ?? 0;
        $evento->description = $request->description;
        $evento->reservation = $request->reservation;
        $evento->id_usuario = $user->id;
        $evento->id_sala = $request->id_sala;
        $evento->save();

        //OBTENCIÓN DE INFORMACIÓN PARA ENVIAR LOS CORREOS//
        //LE DAMOS FORMATO A LAS FECHAS//
        setlocale(LC_TIME, 'es_ES');
        $diaInicio = Carbon::parse($request->start)->format('d');
        $MesInicio = Carbon::parse($request->start)->format('m');
        $AnoInicio = Carbon::parse($request->start)->format('Y');
        $LInicio = strftime('%B', mktime(0, 0, 0, $MesInicio, 1));
        $HoraInicio = Carbon::parse($request->start)->format('H:i');

        $diaFin = Carbon::parse($request->end)->format('d');
        $MesFin = Carbon::parse($request->end)->format('m');
        $AnoFin = Carbon::parse($request->end)->format('Y');
        $LFin = strftime('%B', mktime(0, 0, 0, $MesFin, 1));
        $HoraFin = Carbon::parse($request->end)->format('H:i');

        //OBTENEMOS LA INFORMACIÓN DE LA SALA//
        $sala = $evento->boordroms->name;
        $ubica = $evento->boordroms->location;

        //AUTENTIFICAMOS AL USUARIO PERO CON SU NOMBRE//
        $name = auth()->user()->name;

        //CONVERTIMOS EL REQUEST EN CADENA//
        $Participantes = implode(','.' ',$request->guest);

        //OBTENEMOS A TODOS LOS USUARIOS DEL FORMULARIO//
        if ($request->has('guest') && is_array($request->guest)) {
            $NameGuest = $request->guest;
            $invitadosIds = User::whereIn(DB::raw("CONCAT(name, ' ', lastname)"), $NameGuest)
                                              ->pluck('id')
                                              ->toArray();
            }
        
         ///Almanecan los correos que esten  mal para posteriormente mostrarlos en los mensajes///
        $correosInvalidosInvitados = [];
        $correosInvalidosMasivos = [];
        $correosInvalidosSistemas = [];
        $correosInvalidosRecursosHumanos = [];
 
        foreach ($invitadosIds as $invitado) {
            $user = User::where('id', $invitado)->first();
            if ($user) {
                $nombreUsuario = $user->name;
 
                try {
                    // Verificar si el correo del usuario es válido antes de enviar la notificación
                    if (filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                        $user->notify(new NotificacionSalas($name, $nombreUsuario, $diaInicio, $LInicio, $HoraInicio, $diaFin, $LFin,
                                                            $HoraFin, $ubica, $sala, $request->description));
                    } else {
                        // Agregar el correo electrónico inválido al arreglo
                        $correosInvalidosInvitados[] = $user->email;
                    }
                } catch (Exception $e) {
                    
                }
            }
        }
 
        //CORREOS MASIVOS CUANDO UN GERENTE RESERVA TODA LA SALA//
        //Por el momento puse esos ids para hacer pruebas es el ID de Federico, Tomas  y Ana Miriam.//
        if ($request->reservation == 'Sí') {
            $users = User::where('status', 1)->get();
         
            foreach ($users as $user) {
                $nombre = $user->name;
                try {
                    // Verificar si el correo del usuario es válido antes de enviar la notificación
                    if (filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                        $user->notify(new NotificacionReservaMasiva($name, $nombre, $sala, $ubica, $diaInicio, $LInicio, $HoraInicio, 
                                                                     $diaFin, $LFin, $HoraFin ));
                    } else {
                        // Agregar el correo electrónico inválido al arreglo
                        $correosInvalidosMasivos[] = $user->email;
                    }
                } catch (Exception $e) {
                     
                }
            }
            //Por el momento está con mi usuario para poner todos solo se debe colocar 
            // $topic = "/topics/PUBLICACIONES";
            $comunicado = new FirebaseNotificationController();
            $comunicado->reservationNotification($name, $diaInicio, $LInicio, $AnoInicio, $HoraInicio, $diaFin, $LFin, $AnoFin, $HoraFin);
        }
 
        //CORREO PARA EL DEPARTAMENTO DE PROJECT MANAGER//
        $Project = User::where('id', 31)->first()->name;
        $informar = User::where('id', 31)->first();
        $informar->notify(new notificacionPJ($Project, $name, $request->title, $sala, $ubica, $diaInicio, $LInicio,
                                            $HoraInicio, $diaFin, $LFin, $HoraFin, $request->engrave, $Participantes,
                                            $request->chair_loan, $request->proyector,$request->description
        ));
 
        //CORREO PARA EL DEPARTAMENTO DE RECURSOS HUMANOS PARA MATERIAL (SILLAS)//
        if ($request->chair_loan > 0) {
            //$userIDs =Department::all()->pluck('id'); // IDs DE RECURSOS HUMANOS//
            $dep = Department::find(1);
            $positions = Position::all()->where("department_id", 1)->pluck("name", "id");
            $data = $dep->positions;
            $users = [];
            foreach ($data as $dat) {
                foreach ($dat->getEmployees as $emp) {
                    $users["{$emp->user->id}"] = $emp->user->id;
                }
            }
            foreach ($users as $userID) {
                if ($userID == 6) {
                    $RH = User::where('id', $userID)->first();
                    if ($RH && filter_var($RH->email, FILTER_VALIDATE_EMAIL)) {
                        $RHName = $RH->name;
                        try {
                            $RH->notify(new notificacionRH($RHName, $name, $sala, $ubica, $diaInicio, $LInicio, $HoraInicio, 
                                                           $diaFin, $LFin, $HoraFin, $request->chair_loan, $request->description));
                        } catch (Exception $e) {
                            $correosInvalidosRecursosHumanos[] = $RH->email;
                        }
                    } else {
                        $correosInvalidosRecursosHumanos[] = $RH ? $RH->email : '';
                    }
         
                    break;
                }
            }
            //AQUÍ SE PUEDE AGREGAR EL CORREO DE ALGÚN OTRO COLABORADOR QUE NO SEA DE RH//
            $ADMINISTRACION = User::where('id', 147)->first();
         
            if ($ADMINISTRACION && filter_var($ADMINISTRACION->email, FILTER_VALIDATE_EMAIL)) {
                $AD = $ADMINISTRACION->name;
         
                try {
                    $ADMINISTRACION->notify(new notificacionRH($AD, $name, $sala, $ubica, $diaInicio, $LInicio, $HoraInicio, 
                                                                $diaFin, $LFin, $HoraFin, $request->chair_loan, $request->description));
                } catch (Exception $e) {
                    $correosInvalidosRecursosHumanos[] = $ADMINISTRACION->email;
                }
            } else {
                $correosInvalidosRecursosHumanos[] = $ADMINISTRACION ? $ADMINISTRACION->email : '';
            }
        }
 
        //CORREO PARA EL DEPARTAMENTO DE SISTEMAS PARA MATERIAL (PROYECTORES)//
        if ($request->proyector > 0) {
            $SISTEMAS = User::where('id', 127)->first();
         
            if ($SISTEMAS && filter_var($SISTEMAS->email, FILTER_VALIDATE_EMAIL)) {
                $DS = $SISTEMAS->name;
         
                try {
                    $SISTEMAS->notify(new notificacionSistemas($DS, $name, $sala, $ubica, $diaInicio, $LInicio, $HoraInicio, 
                                                        $diaFin, $LFin, $HoraFin, $request->proyector, $request->description));
                } catch (Exception $e) {
                    $correosInvalidosSistemas[] = $SISTEMAS->email;
                }
            } else {
                $correosInvalidosSistemas[] = $SISTEMAS ? $SISTEMAS->email : '';
            }
        }
 
        $mensajeInvalidos = '';
 
        if (!empty($correosInvalidosInvitados)) {
            $mensajeInvalidos .= "Los siguientes emails de los invitados son incorrectos: " . implode(', ', $correosInvalidosInvitados). ', por favor comunicate con ellos para avisarles acerca de la reunión';
        }
         
        if (!empty($correosInvalidosMasivos)) {
            if (!empty($mensajeInvalidos)) {
                $mensajeInvalidos .= ". ";
            }
            $mensajeInvalidos .= "Los siguientes emails son inválidos: " . implode(', ', $correosInvalidosMasivos). ", a ellos no les llegará la notificación de que se reservo toda la sala";
        }
         
        if (!empty($correosInvalidosSistemas)) {
            if (!empty($mensajeInvalidos)) {
                $mensajeInvalidos .= ". "; // Agregar un salto de línea si ya hay un mensaje
            }
            $mensajeInvalidos .= "El email del área de Sistemas es incorrecto: " . implode(', ', $correosInvalidosSistemas). ', es importante que te comuniques con el encargado del área para solicitar el proyector';
        }
         
        if (!empty($correosInvalidosRecursosHumanos)) {
            if (!empty($mensajeInvalidos)) {
                 $mensajeInvalidos .= ". "; // Agregar un salto de línea si ya hay un mensaje
            }
            $mensajeInvalidos .= "Los siguientes emails de RH son incorrectos: " . implode(', ', $correosInvalidosRecursosHumanos). ", comunicate con RH para solicitar las sillas.";
        }
         
        // Mostrar el mensaje de sesión si hay correos electrónicos inválidos
        if (!empty($mensajeInvalidos)) {
            return redirect()->back()->with('message2', $mensajeInvalidos);
        }
 
        return redirect()->back()->with('message', "Reservación creada correctamente.");
    }
    //////////////////////////////////////////////FUNCIÓN PARA EDITAR/////////////////////////////////////////////////
    public function update(Request $request)
    {
        $user = auth()->user();
        
        // INFORMACIÓN QUE DEBE VALIDAR QUE SE ENCUENTRE //
        $request->validate([
            'title' => 'required',
            'start' => 'required',
            'end' => 'required',
            'description' => 'required',
            'engrave' => 'required',
            'id_sala' => 'required'
        ]);

        $fecha_inicio = $request->start;
        $fecha_termino = $request->end;
        
        // OBTENEMOS LOS EVENTOS DEL DÍA //
        $EventosDelDia = Reservation::whereDate('start', Carbon::parse($fecha_inicio)->format('Y-m-d'))
                                    ->whereDate('end', Carbon::parse($fecha_termino)->format('Y-m-d'))
                                    ->where('id_sala', $request->id_sala)
                                    ->get();

        // OBTENEMOS UN NUEVO ARREGLO DE LOS EVENTOS YA CREADOS PARA PODER CONVERTIR LAS HORAS A MILISEGUNDOS //
        $eventosRefactorizados = [];
        foreach ($EventosDelDia as $item) {
            if ($item['id'] != $request->id_evento) {
                $componentes = [
                    'id' => $item['id'],
                    'start' => strtotime($item['start']) * 1000,
                    'end' => strtotime($item['end']) * 1000,
                    'id_sala' => $item['id_sala']
                ];
                $eventosRefactorizados[] = $componentes;
            }
        }

        // FORMATEAMOS LAS HORAS PARA PODERLAS CONVERTIR A MILISEGUNDOS //
        $inicio = $request->start;
        $fechastart = Carbon::parse($inicio);
        $fechaInicio = strtotime($fechastart->format('Y-m-d H:i:s')) * 1000;
        
        $final = $request->end;
        $fechaend = Carbon::parse($final);
        $fechaFinal = strtotime($fechaend->format('Y-m-d H:i:s')) * 1000;
        $fechaActual = now()->format('Y-m-d H:i:s');

        if ($fecha_inicio <= $fechaActual) {
            return redirect()->back()->with('message1', 'No se puede editar una reservación de una fecha pasada o elegir una fecha pasada.');
        }

        if ($fecha_termino < $fecha_inicio) {
            return redirect()->back()->with('message1', "Una reservación no puede finalizar antes que la hora de inicio.");
        }

        // CONDICIONES QUE DEBE PASAR ANTES DE EDITAR EL EVENTO //
        foreach ($eventosRefactorizados as $evento) {
            if (($fechaInicio >= $evento['start'] && $fechaInicio < $evento['end']) ||
            ($fechaFinal > $evento['start'] && $fechaFinal <= $evento['end']) ||
            ($fechaInicio <= $evento['start'] && $fechaFinal >= $evento['end'])
            ) {
                return redirect()->back()->with('message1', "El evento no puede tomar horas de otros eventos ya creados.");
            }
        }
        
        $event = Reservation::find($request->id_evento);
        if (!$event) {
            return redirect()->back()->with('message1', 'El evento que intentas editar no existe.');
        }

        // Verificar si el usuario tiene permiso para editar el evento
        if ($event->reservation === 'Sí' && $event->id_usuario !== $user->id) {
            return redirect()->back()->with('message1', 'No tienes permiso para editar este evento.');
        }

        // Si el usuario tiene permiso para editar el evento, ignorar la verificación de los eventos reservados por los gerentes.
        if ($event->reservation === 'Sí' && $event->id_usuario === $user->id) {
            // AGREGAMOS LOS NUEVOS USUARIOS AL VIEJO ARREGLO //
            $invitadospos = DB::table('reservations')
                              ->select('guest')
                              ->where('id', $request->id_evento)
                              ->first();
            $invitades = [];
            $usuarios = User::all();
            foreach ($usuarios as $usuario) {
                if ($request->has('guest' . strval($usuario->id))) {
                    $invitades[] = $usuario->id;
                }
            }
            if ($invitadospos) {
                $invitades = array_merge($invitades, [$invitadospos->guest]);
            }
            $invitados = implode(',', $invitades);

            // HACEMOS LA ACTUALIZACIÓN DE LA BASE DE DATOS //
            DB::table('reservations')->where('id', $request->id_evento)->update([
                'title' => $request->title,
                'start' => $request->start,
                'end' => $request->end,
                'guest' => $invitados,
                'engrave' => $request->engrave,
                'chair_loan' => $request->chair_loan ?? 0,
                'proyector' => $request->proyector ?? 0,
                'description' => $request->description,
                'reservation' => $request->reservation,
                'id_sala' => $request->id_sala
            ]);        
        } else {
            // Verificar eventos reservados por gerentes.
            $gerentes = Reservation::where('start', '<=', $fecha_inicio)
                                   ->where('end', '>=', $fecha_termino)
                                   ->where('reservation', 'Sí')
                                   ->exists();
            if ($gerentes) {
                return redirect()->back()->with('message1', 'Un gerente reservó toda la sala, por lo tanto no puedes editar el evento en esta fecha y hora.');
            }
        }

        // AGREGAMOS LOS NUEVOS USUARIOS AL VIEJO ARREGLO //
        $invitadospos = DB::table('reservations')
                          ->select('guest')
                          ->where('id', $request->id_evento)
                          ->first();
        $invitades = [];
        $usuarios = User::all();
        foreach ($usuarios as $usuario) {
            if ($request->has('guest' . strval($usuario->id))) {
                $invitades[] = $usuario->id;
            }
        }
        if ($invitadospos) {
            $invitades = array_merge($invitades, [$invitadospos->guest]);
        }
        $invitados = implode(',', $invitades);

        // HACEMOS LA ACTUALIZACIÓN DE LA BASE DE DATOS //
        DB::table('reservations')->where('id', $request->id_evento)->update([
            'title' => $request->title,
            'start' => $request->start,
            'end' => $request->end,
            'guest' => $invitados,
            'engrave' => $request->engrave,
            'chair_loan' => $request->chair_loan ?? 0,
            'proyector' => $request->proyector ?? 0,
            'description' => $request->description,
            'reservation' => $request->reservation,
            'id_sala' => $request->id_sala
        ]);

        //OBTENCIÓN DE INFORMACIÓN PARA ENVIAR LOS CORREOS//
        //LE DAMOS FORMATO A LAS FECHAS//
        setlocale(LC_TIME, 'es_ES');
        $diaInicio= Carbon::parse($request->start)->format('d');
        $MesInicio = Carbon::parse($request->start)->format('m');
        $AnoInicio = Carbon::parse($request->start)->format('Y');
        $LInicio = strftime('%B', mktime(0, 0, 0, $MesInicio, 1));
        $HoraInicio = Carbon::parse($request->start)->format('H:i');

        $diaFin= Carbon::parse($request->end)->format('d');
        $MesFin= Carbon::parse($request->end)->format('m');
        $AnoFin= Carbon::parse($request->end)->format('Y');
        $LFin = strftime('%B', mktime(0, 0, 0, $MesFin, 1));
        $HoraFin= Carbon::parse($request->end)->format('H:i');

        //OBTENEMOS LA SALA//
        $sala= $request->id_sala;
        $ubica = boardroom::where('id', $sala)->value('location');
        $names = boardroom::where('id', $sala)->value('name');

        //AUTWNTIFICAMOS AL USUARIO POR SU NOMBRE//
        $name= auth()->user()->name;

        //CREAMOS UN ARREGLO PARA OBTENER LOS DATOS NECESARIOS DEL GUEST//
        $invitadospos = DB::table('reservations')
                ->where('id', $request->id_evento)
                ->get();
        $array = explode(',', $invitadospos[0]->guest);

        //OBTENEMOS LOS NOMBRES DE LOS USUARIOS DENTRO DEL ARREGLO//
        $nombres = [];  
        foreach ($array as $usuario) {
            $nombre= User::where('id', $usuario)->first()->name; 
            $nombres[] = $nombre;  
            $guest= implode(',',$nombres);
        }

        $correosInvalidosEdit = [];
        $correosInvalidosMasivosEdit = [];
        $correosInvalidosRecursosHumanos = [];
        $correosInvalidosSistemas=[];

        //CORREO PARA LOS INVIDATOS DE LA REUNIÓN//
        foreach ($array as $invitado) {
            $user = User::where('id', $invitado)->first();

            if ($user && filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                $nombre = $user->name;
                
                try {
                    $user->notify(new NotificacionEdit($name, $nombre, $diaInicio, $LInicio, $HoraInicio, $diaFin, $LFin, 
                                               $HoraFin, $ubica, $names, $request->description));
                                            
                } catch (Exception $e) {
                    $correosInvalidosEdit[] = $user->email;
                }
            } else {
                $correosInvalidosEdit[] = $user ? $user->email : '';
            }
        }

        ///SON PARA LOS CORREOS MASIVOS///
        //Por el momento puse esos ids para hacer pruebas es el ID de Federico, Tomas  y Ana Miriam.//
        if ($request->reservation == 'Sí') {
            $users = User::where('status', 1)->get();
            foreach ($users as $user) {
                if (filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                     $nombre = $user->name;
                    try {
                        $user->notify(new NotificacionReservaMasivaEdit($name, $nombre, $names, $ubica, $diaInicio, $LInicio, $HoraInicio, 
                                                                $diaFin, $LFin, $HoraFin));
                    } catch (Exception $e) {
                        $correosInvalidosMasivosEdit[] = $user->email;
                    }
                
                } else {
                    $correosInvalidosMasivosEdit[] = $user->email;
                }
            }

            //Por el momento esta con mi usuario para poner todos solo se debe colocar 
            // $topic = "/topics/PUBLICACIONES";
            $comunicado = new FirebaseNotificationController();
            $comunicado->reservationNotificationedit($name, $diaInicio, $LInicio, $AnoInicio, $HoraInicio, $diaFin, $LFin, $AnoFin, $HoraFin);
        }

        //CORREO PARA EL DEPARTAMENTO DE PROJECT MANAGER//
        $Project =User::where('id', 31)->first()->name;
        $informar =User::where('id', 31)->first();
        $informar->notify(new notificacionPJEdit ($Project, $name, $request->title, $names,$ubica,$diaInicio,$LInicio,$HoraInicio, 
                                                  $diaFin, $LFin, $HoraFin,$request->engrave,$guest, $request->chair_loan, 
                                                  $request->proyector,$request->description));
                                                  
        //CORREO PARA EL DEPARTAMENTO DE RECURSOS HUMANOS PARA MATERIAL (SILLAS)//
        if ($request->chair_loan > 0) {
            //$userIDs =Department::all()->pluck('id'); // IDs DE RECURSOS HUMANOS//
            $dep = Department::find(1);
            $positions = Position::all()->where("department_id", 1)->pluck("name", "id");
            $data = $dep->positions;
            $users = [];
            foreach ($data as $dat) {
                foreach ($dat->getEmployees as $emp) {
                    $users["{$emp->user->id}"] = $emp->user->id;
                }
            }
            foreach ($users as $userID) {
                if ($userID == 6) {
                    $RH = User::where('id', $userID)->first();
        
                    if ($RH && filter_var($RH->email, FILTER_VALIDATE_EMAIL)) {
                        $RHName = $RH->name;
        
                        try {
                            $RH->notify(new notificacionRHEdit($RHName, $name, $names, $ubica, $diaInicio, $LInicio, $HoraInicio, 
                                                           $diaFin, $LFin, $HoraFin, $request->chair_loan, $request->description));
                        } catch (Exception $e) {
                            $correosInvalidosRecursosHumanos[] = $RH->email;
                        }
                    } else {
                        $correosInvalidosRecursosHumanos[] = $RH ? $RH->email : '';
                    }
        
                    break;
                }
            }
            //AQUÍ SE PUEDE AGREGAR EL CORREO DE ALGÚN OTRO COLABORADOR QUE NO SEA DE RH//
            $ADMINISTRACION = User::where('id', 147)->first();
        
            if ($ADMINISTRACION && filter_var($ADMINISTRACION->email, FILTER_VALIDATE_EMAIL)) {
                $AD = $ADMINISTRACION->name;
        
                try {
                    $ADMINISTRACION->notify(new notificacionRHEdit($AD, $name, $names, $ubica, $diaInicio, $LInicio, $HoraInicio, 
                                                                $diaFin, $LFin, $HoraFin, $request->chair_loan, $request->description));
                } catch (Exception $e) {
                    $correosInvalidosRecursosHumanos[] = $ADMINISTRACION->email;
                }
            } else {
                $correosInvalidosRecursosHumanos[] = $ADMINISTRACION ? $ADMINISTRACION->email : '';
            }
        }

        //CORREO PARA EL DEPARTAMENTO DE SISTEMAS PARA MATERIAL (PROYECTORES)//
        if ($request->proyector > 0) {
            $SISTEMAS = User::where('id', 127)->first();
        
            if ($SISTEMAS && filter_var($SISTEMAS->email, FILTER_VALIDATE_EMAIL)) {
                $DS = $SISTEMAS->name;
        
                try {
                    $SISTEMAS->notify(new notificacionSistemasEdit($DS, $name, $names, $ubica, $diaInicio, $LInicio, $HoraInicio, 
                                                         $diaFin, $LFin, $HoraFin, $request->proyector, $request->description));
                } catch (Exception $e) {
                    $correosInvalidosSistemas[] = $SISTEMAS->email;
                }
            } else {
                $correosInvalidosSistemas[] = $SISTEMAS ? $SISTEMAS->email : '';
            }
        }

        $mensajeInvalidos = '';

        if (!empty($correosInvalidosEdit)) {
            $mensajeInvalidos .= "Los siguientes emails de los invitados son incorrectos: " . implode(', ', $correosInvalidosEdit). '; por favor comunicate con el área de soporte.';
        }
        
        if (!empty($correosInvalidosMasivosEdit)) {
            if (!empty($mensajeInvalidos)) {
                $mensajeInvalidos .= ". ";
            }
            $mensajeInvalidos .= "Los siguientes emails son inválidos: " . implode(', ', $correosInvalidosMasivosEdit). "; por favor comunicate con el área de soporte.";
        }
        
        if (!empty($correosInvalidosSistemas)) {
            if (!empty($mensajeInvalidos)) {
                $mensajeInvalidos .= ". ";
            }
            $mensajeInvalidos .= "El email del área de Sistemas es incorrecto: " . implode(', ', $correosInvalidosSistemas). ', es importante que te comuniques con el encargado del área para solicitar el proyector';
        }
        
        if (!empty($correosInvalidosRecursosHumanos)) {
            if (!empty($mensajeInvalidos)) {
                $mensajeInvalidos .= ". "; 
            }
            $mensajeInvalidos .= "Los siguientes emails de RH son incorrectos: " . implode(', ', $correosInvalidosRecursosHumanos). ", comunicate con RH para solicitar las sillas.";
        }
        
        // Mostrar el mensaje de sesión si hay correos electrónicos inválidos
        if (!empty($mensajeInvalidos)) {
            return redirect()->back()->with('message2', $mensajeInvalidos);
        }

        return redirect()->back()->with('message2', "Evento editado correctamente.");
    }
    //////////////////////////////////////////////FUNCIÓN ELIMINAR///////////////////////////////////////////////////
    public function destroy(Request $request)
    {
        DB::table('reservations')->where('id', $request->id_evento)->delete();
        return redirect()->back()->with('message1', 'Evento eliminado.');
    }
    /////////////////////////////////////////////MOSTRAR EVENTOS////////////////////////////////////////////////////
    public function view(Reservation $reservation)
    {
        $reservation = Reservation::all();
        return response()->json($reservation);
    }
}
