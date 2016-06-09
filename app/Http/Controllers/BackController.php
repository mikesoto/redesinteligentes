<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use App\Comision;
use Mail;
use Auth;

class BackController extends Controller
{

	/**
  * Create a new controller instance.
  *
  * @return void
  */
  public function __construct()
  {
    $this->middleware('auth');
  }


  private function getNextLevel($lvl,$cur){
  	$next = $lvl+1;
  	//check if current array has users
  	if(count($cur)){
  		// echo 'level '.$lvl.' has '.count($cur).'<br/>';
  		// echo 'getting next level('.$next.')<br/>';
  		//create the next level array
	  	$n_lvl = [];
	  	$n_count = 1;
	  	//fill next level array with each socio's downline users
	  	foreach($cur as $socio){
	  		//collect downlines for each user in current level
	  		$collection = User::where('upline', '=', $socio->id)->get();
	  		//echo "socio ".$n_count." has ".count($collection)." downlines<br/>";
	  		//add each user to the next level array
	  		foreach($collection as $user){
	  			array_push($n_lvl,$user);
	  		}
	  		$n_count++;
	  	}
	  }else{ 
	  	$n_lvl = false;
	  }
	  return $n_lvl;
  }

  public function oficinaVirtual(){
  	$cur_user = Auth::user();//tu
  	$tree = [];
  	$tree[0] = [];
  	$users = User::where('upline', '=', $cur_user->id)->get();
  	foreach($users as $socio){
  		array_push($tree[0],$socio);
  	}
  	$i = -1;
  	while(count($tree[$i+1])){
  		$tree[$i+2] = self::getNextLevel($i+1,$tree[$i+1]);
  		$i++;
  	}
  	$downlines = User::where('upline', '=', $cur_user->id)->get(); //can only have two
  	$patrocinios = User::where('patrocinador', '=', $cur_user->id)->get(); //can have many
		$comsPatr_query = Comision::where('type','=','patrocinio');
		//only the main admin account can view all patrocinios
		if($cur_user->id > 1){
			//users can only view their own patrocinios
			$comsPatr_query->where('user_id','=',$cur_user->id);
		}
		$comsPatr = $comsPatr_query->get();
		//generate ganancias value
		$ganancias = 0.00;
		foreach($comsPatr as $comPatr){
			if($comPatr->user_id == $cur_user->id){
				$ganancias += $comPatr->amount;
			}
		}

		function padLeft($var){
			return str_pad($var,2,'0',STR_PAD_LEFT);
		}

		//get earliest comision date 
		date_default_timezone_set("America/Mexico_City");
    $today = Carbon::today();
    //get the current week_num
 	  $cur_week_num = $today->weekOfYear;
 	  //set earliest to today by default
    $earliest = $today;
    //search for earliest commission date
    foreach($comsPatr as $comPatr){
    	if($comPatr->created_at < $earliest){
    		$earliest = $comPatr->created_at;
    	}
    }

    function getWeekInfo($refDate){
    	$data = [];
	   	//get week info for refDate
	 	  $week_num = $refDate->format("W");
	 	  $data['week_num'] = $week_num;
	    //get month info for refDate
	    $month_num = padLeft($refDate->month);
	    $data['month_num'] = $month_num;
	    //get year info for refDate
	    $year_num = $refDate->format("Y");
	    $data['year_num'] = $year_num;
	    //get day number
	    $day_num = $refDate->format("d");
	    $data['day_num'] = $day_num;
	    //get day of week number
	    $day_of_week = date('w', $refDate->getTimestamp());
	    $data['day_of_week'] = $day_of_week;
	    //get date for start of refDate week
	    $week_start = date('d-m-Y', strtotime('-'.$day_of_week.' days', $refDate->getTimestamp()));
	    $data['week_start'] = $week_start;
	    $week_lunes = date('Y-m-d', strtotime($week_start));
	    $data['week_lunes'] = $week_lunes;
	    $week_martes = date('Y-m-d', strtotime($week_lunes. ' + 1 days'));
	    $data['week_martes'] = $week_martes;
	    $week_miercoles = date('Y-m-d', strtotime($week_martes. ' + 1 days'));
	    $data['week_miercoles'] = $week_miercoles;
	    $week_jueves = date('Y-m-d', strtotime($week_miercoles. ' + 1 days'));
	    $data['week_jueves'] = $week_jueves;
	    $week_viernes = date('Y-m-d', strtotime($week_jueves. ' + 1 days'));
	    $data['week_viernes'] = $week_viernes;
	    $week_sabado = date('Y-m-d', strtotime($week_viernes. ' + 1 days'));
	    $data['week_sabado'] = $week_sabado;
	    $week_domingo = date('Y-m-d', strtotime($week_sabado. ' + 1 days'));
	    $data['week_domingo'] = $week_domingo;
	    return($data);
    }
   	//get week num for the earliest comission
    $earliest_week_num = $earliest->weekOfYear;
    $weeks_diff = $cur_week_num - $earliest_week_num;
    $weeks_info = [];
    for($i=0; $i< $weeks_diff; $i++){
    	$weeks_info[$i] = getWeekInfo($earliest->addDays($i * 7));
    }


		return view('back.oficina_virtual',[
			'cur_user' => $cur_user,
			'active_page' => 'oficina',
			'title_page' => 'Oficina Virtual',
			'downlines' => $downlines,
			'patrocinios' => $patrocinios,
			'comsPatr' => $comsPatr,
			'ganancias' => $ganancias,
			'weeks_info' => $weeks_info,
			'tree' => $tree,
		]);
	}

	public function createUser(Request $request){
		$cur_usr = Auth::user();
		//check if the current user is authorized to register user (only user 1)
  	if($cur_usr->id != 1){
  		$messages = array(
			  'required' => 'No está autorizado para registrar nuevos usuarios.',
			);
			$validator = \Validator::make(['custom_error' => ''], ['custom_error' => 'required'],$messages);
			\Session::push('errors', $validator->messages() );
			return redirect('/oficina-virtual');
  	}
		$fields = [
			'upline' 									=> $request->upline,
			'patrocinador' 						=> $request->patrocinador,
			'fecha_ingreso' 					=> $request->fecha_ingreso,
			'nombre' 									=> $request->nombre,
			'apellido_paterno' 				=> $request->apellido_paterno,
			'apellido_materno' 				=> $request->apellido_materno,
			'fecha_nac' 							=> $request->fecha_nac,
			'ife' 										=> $request->ife,
			'tel_cel' 								=> $request->tel_cel,
			'cp' 											=> $request->cp,
			'direccion' 							=> $request->direccion,
			'colonia' 								=> $request->colonia,
			'delegacion' 							=> $request->delegacion,
			'estado' 									=> $request->estado,
			'beneficiario' 						=> $request->beneficiario,
			'parentesco' 							=> $request->parentesco,
			'beneficiario_fecha_nac' 	=> $request->beneficiario_fecha_nac,				
		];
		$rules = [
			'upline' 									=> 'required',
			'patrocinador' 						=> 'required',
			'fecha_ingreso' 					=> 'required',
			'nombre' 									=> 'required',
			'apellido_paterno' 				=> 'required',
			'apellido_materno' 				=> 'required',
			'fecha_nac' 							=> '',
			'ife' 										=> '',
			'tel_cel' 								=> '',
			'cp' 											=> 'required',
			'direccion' 							=> 'required',
			'colonia' 								=> 'required',
			'delegacion' 							=> 'required',
			'estado' 									=> 'required',
			'beneficiario' 						=> 'required',
			'parentesco' 							=> 'required',
			'beneficiario_fecha_nac' 	=> 'required',		
		];
		//email field only validated if not empty
		if($request->email != ''){
			$fields['email'] = $request->email;
			$rules['email'] = 'email';
		}
		$validator = \Validator::make($fields, $rules);
    if($validator->fails())
    {
      \Session::push('errors', $validator->messages() );
      return redirect('/oficina-virtual')->withInput();
    }
    //check if the upline user already has his two primary (left and right)
  	$count_uplines = User::where('upline','=',$request->upline)->count();
  	if($count_uplines >= 2){
  		$messages = array(
			  'required' => 'El usuario de upline ya cuenta con sus dos primarios. Por lo tanto no puede ser upline de este nuevo usuario.',
			);
			$validator = \Validator::make(['custom_error' => ''], ['custom_error' => 'required'],$messages);
			\Session::push('errors', $validator->messages() );
			return redirect('/oficina-virtual')->withInput($request->except('upline'));
  	}
    //all validations passed, >>>>>

  	//convert ingreso date to mysql format
  	$ing_arr = explode("/", $request->fecha_ingreso);
  	$fields['fecha_ingreso'] = $ing_arr[2].'-'.$ing_arr[1].'-'.$ing_arr[0];
  	//convert fecha_nac to mysql format
  	if($request->fecha_nac != ''){
	  	$fn_arr = explode("/", $request->fecha_nac);
	  	if(is_array($fn_arr) && count($fn_arr) == 3){
	  		$fields['fecha_nac'] = $fn_arr[2].'-'.$fn_arr[1].'-'.$fn_arr[0];
	  	}
	  }
	  //convert beneficiario_fecha_nac to mysql format
	  if($request->beneficiario_fecha_nac != ''){
	  	$bfn_arr = explode("/", $request->beneficiario_fecha_nac);
	  	if(is_array($bfn_arr) && count($bfn_arr) == 3){
	  		$fields['beneficiario_fecha_nac'] = $bfn_arr[2].'-'.$bfn_arr[1].'-'.$bfn_arr[0];
	  	}
	  }
	  
  	
  	//determine which side based on count_uplines
  	if($count_uplines == 0){
  		$fields['side'] = 'left';
  	}
  	if($count_uplines == 1){
  		$fields['side'] = 'right';
  	}

    //create user password
    $pwd_str = 'redes'.rand(1000,9999);
    $fields['password'] = bcrypt($pwd_str);

    //find the new user's asignado (5 levels up or empresa)
    $fields['asignado'] = self::getAsignado($fields['upline']);

    $newUser = User::create($fields);
    //create username string
    $username = substr($fields['nombre'],0,3).$newUser->id;
    $newUser->user = $username;
    $newUser->save();

    //generate patrocinio comision
    $comissiones = self::genComPatr($newUser);

    //unencrypted version for email/sms
    $newUser->pwd_str = $pwd_str;
    //get patrocinador info for the email
    $patr = User::find($fields['patrocinador']);
    $newUser->patrocinadorNombre = $patr->nombre.' '.$patr->apellido_paterno.' '.$patr->apellido_materno;
    //================ ENVIAR CORREO AL NUEVO USUARIO =======================
    //account:	registro@redesinteligentes.com.mx
		// Contraseña:	Mnbvc2016%
		// Outgoing Server:	p3plcpnl0538.prod.phx3.secureserver.net
		// SMTP Port: 465
		// Encryption: ssl
		$to_email = 'registro@redesinteligentes.com.mx';
		if($request->email != ''){
			$to_email = [$request->email,'registro@redesinteligentes.com.mx'];
		}
		$mailed = Mail::send('emails.registro', ['newUser' => $newUser], function ($message) use ($newUser,$to_email) {
        $message->from('registros@redesinteligentes.com.mx', 'Redes Inteligentes')
        				->to($to_email)
        				->subject('Nuevo Registro en Redes Inteligentes');
    });

    $smsId = false;
  	  if($request->tel_cel != ''){
	 	   $clean_cell = str_replace(array('-', ' ', '.'), array('','',''), $request->tel_cel);
	 	   if(is_numeric($clean_cell)){
	 	   	$msg = "Bienvenido%20a%20REDES%20INTELIGENTES!%0A%0ATus%20Datos%20de%20Registro%3A%0A%0ANumero%20de%20Asociado%3A%20".$newUser->id."%0AUsuario%3A%20".$username."%0AContrase%C3%B1a%3A%20".$pwd_str;
		    $url = "http://sms-tecnomovil.com/SvtSendSms?username=NUTRIRED&password=Mnbvc2016%25&message=".$msg."&numbers=".$clean_cell;
				$xml = file_get_contents($url);
				if($response = new \SimpleXmlElement($xml)) {
			    if($response->status == 'ok'){
			    	$smsId = $response->messageId;
			    }
				}
			}
		}

    \Session::push('alert-success', 'Nuevo usuario '.$request->email.' creado exitosamente.');
    \Session::push('alert-success', 'Usuario: '.$newUser->user);
    \Session::push('alert-success', 'Contraseña: '.$newUser->pwd_str);
    if($mailed){
    	$recipients = ( is_array($to_email) )? $to_email[0].' y '.$to_email[1] : $to_email;
    	\Session::push('alert-success', 'Datos de ingreso enviados con éxito a '. $recipients);
    }
    if(isset($clean_cell) && is_numeric($clean_cell)){
    	\Session::push('alert-success', 'Datos de ingreso enviados con éxito a '. $clean_cell.' con sus datos de ingreso.');
    }
    return redirect('/oficina-virtual');
	}


	private function getAsignado($upline){
  	// default to empresa
  	$asignado = User::find(1); 
		//get first upline user
		$user1 = User::find($upline);
		//check if the user1's upline is not empresa
		if($user1->upline > 0){
			//continue to level 2
			$user2 = User::find($user1->upline);
			//check if the user2's upline is not empresa
  		if($user2->upline > 0){
  			//continue to level 3
  			$user3 = User::find($user2->upline);
  			//check if the user3's upline is not empresa
	  		if($user3->upline > 0){
	  			//continue to level 4
	  			$user4 = User::find($user3->upline);
	  			//check if the user4's upline is not empresa
		  		if($user4->upline > 0){
		  			//continue to level 5
		  			$user5 = User::find($user4->upline);
		  			//this is the user's asignado
		  			$asignado = $user5;
		  		}
	  		}
  		}	
		}
  	return $asignado;
  }

  private function genComPatr($newUser){
  	//patrocinio (every patrocinador gets this comission)
  	// this comission is assigned to the patrocinador
  	$com_patr = Comision::create([
  		'created_at'	=> $newUser->fecha_ingreso,
  		'user_id' 		=> $newUser->patrocinador,
  		'new_user_id' => $newUser->id,
      'upline_id' 	=> $newUser->upline,
      'patroc_id' 	=> $newUser->patrocinador,
      'asignado_id' => $newUser->asignado,
      'type' 				=> 'patrocinio',
      'amount' 			=> 250.00,
  	]);
  }




  //======== API Functions ============================
	public function getUser($id){
		$user = User::find($id);
		return $user;
	}

	public function getUserByEmail($email){
		$user = User::where('email', '=', $email)->first();
		return $user;
	}

	public function getUserDownlines($user_id){
		$downlines = User::where('upline','=', $user_id)->get();
		return $downlines;
	}
		
}



