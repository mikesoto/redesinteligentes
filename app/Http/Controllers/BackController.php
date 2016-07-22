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

  public function oficinaVirtual(Request $request){
  	// $usr = User::find(6);
  	// $ch_pwd = bcrypt('redes123');
  	// $usr->password = $ch_pwd;
  	// $usr->save();

  	//check if GET request filter exists for user
  	$req_usr = $request->input('u', false);
  	//define the current user
  	$cur_user = false;
  	if($req_usr){
  		if(!is_numeric($req_usr)){
  			//invalid user id requested in GET var
  			return redirect('/oficina-virtual');
  		}else{
  			//query the db for the requested user id
  			$cur_user =  User::find($req_usr);
  		}
  		if(!$cur_user){
  			//user id does not exist cancel the request with redirect
  			return redirect('/oficina-virtual');
  		}
  	}else{
  		//no requested filter for user, use currently logged in user
  		$cur_user = Auth::user();
  	}
  	//create tree by levels (each level is next index in array)
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
  	//get the two primary downlines for user
  	$downlines = User::where('upline', '=', $cur_user->id)->get();
  	//get all comisiones of type patrocinio
  	$patrocinios = User::where('patrocinador', '=', $cur_user->id)->get();
		$comsPatr_query = Comision::where('type','=','patrocinio');
		$comsMult_query = Comision::where('type','=','multiplo');
		$comsBono_query = Comision::where('type','=','bono20');
		//only the main admin account can view all patrocinios
		if($cur_user->id > 1){
			//normal users can only view their own patrocinios
			$comsPatr_query->where('user_id','=',$cur_user->id);
			$comsMult_query->where('user_id','=',$cur_user->id);
			$comsBono_query->where('user_id','=',$cur_user->id);
		}
		//execute the queries for comisiones
		$comsPatr = $comsPatr_query->get();
		$comsMult = $comsMult_query->get();
		$comsBono = $comsBono_query->get();
		//get the name of the new users for each patrocinio
		foreach($comsPatr as $comPatr){
			$recUser = User::find($comPatr->user_id);
			$comPatr->rec_user_name = $recUser->nombre.' '.$recUser->apellido_paterno;

			$newUser = User::find($comPatr->new_user_id);
			$comPatr->new_user_name = $newUser->nombre.' '.$newUser->apellido_paterno;

			$patUser = User::find($comPatr->patroc_id);
			$comPatr->pat_user_name = $patUser->nombre.' '.$patUser->apellido_paterno;

			$uplineUser = User::find($comPatr->upline_id);
			$comPatr->upline_user_name = $uplineUser->nombre.' '.$uplineUser->apellido_paterno;
		}

		//get the name of the new users for each multiple
		foreach($comsMult as $comMult){
			$recUser = User::find($comMult->user_id);
			$comMult->rec_user_name = $recUser->nombre.' '.$recUser->apellido_paterno;

			$newUser = User::find($comMult->new_user_id);
			$comMult->new_user_name = $newUser->nombre.' '.$newUser->apellido_paterno;

			$patUser = User::find($comMult->patroc_id);
			$comMult->pat_user_name = $patUser->nombre.' '.$patUser->apellido_paterno;

			$uplineUser = User::find($comMult->upline_id);
			$comMult->upline_user_name = $uplineUser->nombre.' '.$uplineUser->apellido_paterno;
		}

		//get the name of the new users for each multiple
		foreach($comsBono as $comBono){
			$recUser = User::find($comBono->user_id);
			$comBono->rec_user_name = $recUser->nombre.' '.$recUser->apellido_paterno;

			$newUser = User::find($comBono->new_user_id);
			$comBono->new_user_name = $newUser->nombre.' '.$newUser->apellido_paterno;

			$patUser = User::find($comBono->patroc_id);
			$comBono->pat_user_name = $patUser->nombre.' '.$patUser->apellido_paterno;

			$uplineUser = User::find($comBono->upline_id);
			$comBono->upline_user_name = $uplineUser->nombre.' '.$uplineUser->apellido_paterno;
		}
		
		//generate ganancias value
		$ganancias = 0.00;
		if($cur_user->id == 1){
			//earnings for the company come from the users investment's and comissions for company
			foreach($comsPatr as $comPatr){
				$ganancias += 1150;
				if($comPatr->user_id == 1){
					$ganancias += $comPatr->amount;
				}
			}
			//add multiples value only for the company
			foreach($comsMult as $comMult){
				if($comMult->user_id == 1){
					$ganancias += $comMult->amount;
				}
			}
			//add bono20s value only for the company
			foreach($comsBono as $comBono){
				if($comBono->user_id == 1){
					$ganancias += $comBono->amount;
				}
			}
		}else{
			//earnings for users are from the patrocinio amount
			foreach($comsPatr as $comPatr){
				if($comPatr->user_id == $cur_user->id){
					$ganancias += $comPatr->amount;
				}
			}
			//earnings for users are also from the multiples amount
			foreach($comsMult as $comMult){
				if($comMult->user_id == $cur_user->id){
					$ganancias += $comMult->amount;
				}
			}
			//earnings for users are also from the bono20 amount
			foreach($comsBono as $comBono){
				if($comBono->user_id == $cur_user->id){
					$ganancias += $comBono->amount;
				}
			}
		}

		

		//=============================== DATES INFO ========================
		function padLeft($var){
			return str_pad($var,2,'0',STR_PAD_LEFT);
		}

		function getWeekInfo($refDate){
    	$data = [];
	 	  $dt = Carbon::parse($refDate);
	 	  $week_num  						= $dt->weekOfYear;
	 	  $month_num 						= padLeft($dt->month);
			$year_num 						= $dt->year;
			$day_num 							= padLeft($dt->day);
			$day_of_week 					= $dt->dayOfWeek;
			$week_domingo 					= $dt->startOfWeek()->subDay();
				$week_domingo_ref 		= carbon::parse($week_domingo->toDateTimeString());
			$week_lunes 					= $week_domingo_ref->addDay();
				$week_lunes_ref 		= carbon::parse($week_lunes->toDateTimeString());
			$week_martes 			= $week_lunes_ref->addDay();
				$week_martes_ref = carbon::parse($week_martes->toDateTimeString());
			$week_miercoles 					= $week_martes->addDay();
				$week_miercoles_ref 		= carbon::parse($week_miercoles->toDateTimeString());
			$week_jueves 				= $week_miercoles_ref->addDay();
				$week_jueves_ref 	= carbon::parse($week_jueves->toDateTimeString());
			$week_viernes 					= $week_jueves_ref->addDay();
				$week_viernes_ref 		= carbon::parse($week_viernes->toDateTimeString());
			$week_sabado 				= $week_viernes_ref->addDay();
	    
	    $data['week_num']      		= $week_num;
			$data['month_num']      	= $month_num;
			$data['year_num']      		= $year_num;
			$data['day_num']      		= $day_num;
			$data['day_of_week']      = $day_of_week;
			$data['week_domingo']     = $week_domingo;
			$data['week_lunes']      	= $week_lunes;
			$data['week_martes']      = $week_martes;
			$data['week_miercoles']   = $week_miercoles;
			$data['week_jueves']      = $week_jueves;
			$data['week_viernes']     = $week_viernes;
			$data['week_sabado']      = $week_sabado;
			
	    return($data);
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
   	//get week num for the earliest comission
    $earliest_week_num = $earliest->weekOfYear;
    //get the number of weeks from earliest to current
    $weeks_diff = $cur_week_num - $earliest_week_num;
		//array to hold weeks info
    $weeks_info = array();
    //add the first week (week of earliest)
    array_push($weeks_info, getWeekInfo($earliest));
    //add the rest of the weeks up until today
    for($i=0; $i < $weeks_diff; $i++){
    	$next_date = $earliest->addWeek();
    	array_push($weeks_info, getWeekInfo($next_date));
    }
		//get the contents of the multiples json
		$string_json = file_get_contents(storage_path()."/app/multiples.json");
		$mults_data = json_decode($string_json);

		//get the contents of the bono20s json
		$string_json = file_get_contents(storage_path()."/app/bono20s.json");
		$bonos_data = json_decode($string_json);

		return view('back.oficina_virtual',[
			'cur_user' => $cur_user,
			'active_page' => 'oficina',
			'title_page' => 'Oficina Virtual',
			'downlines' => $downlines,
			'patrocinios' => $patrocinios,
			'comsPatr' => $comsPatr,
			'comsMult' => $comsMult,
			'comsBono' => $comsBono,
			'ganancias' => $ganancias,
			'weeks_info' => $weeks_info,
			'mults_data' => $mults_data,
			'bonos_data' => $bonos_data,
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
			'side'										=> $request->lado,
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
			'side'										=> 'required',
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
  	$count_uplines_left = User::where('upline','=',$request->upline)->where('side','=','left')->count();
  	$count_uplines_right = User::where('upline','=',$request->upline)->where('side','=','right')->count();
  	if(($count_uplines_left + $count_uplines_right) == 2){
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
	  
  	
  	//determine if requested side is already filled
  	if($count_uplines_left && $request->lado == 'left'){
  		$messages = array(
			  'required' => 'El usuario de upline ya cuenta con su primario izquierdo.',
			);
			$validator = \Validator::make(['custom_error' => ''], ['custom_error' => 'required'],$messages);
			\Session::push('errors', $validator->messages() );
			return redirect('/oficina-virtual')->withInput($request->except('upline'));
  	}
  	if($count_uplines_right && $request->lado == 'right'){
  		$messages = array(
			  'required' => 'El usuario de upline ya cuenta con su primario derecho.',
			);
			$validator = \Validator::make(['custom_error' => ''], ['custom_error' => 'required'],$messages);
			\Session::push('errors', $validator->messages() );
			return redirect('/oficina-virtual')->withInput($request->except('upline'));
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
    return redirect('/office/api/generateMultsList');//recalculate the multiples and overwrite the json file
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

	//function to compare the ids 
  function compare($a, $b){
    if($a->id < $b->id){
      return -1;
    }
    if($a->id > $b->id){
      return 1;
    }
  }

	public function generateMultsList(Request $request){
		//only the admin user can update persistant multiples data 
		if(Auth::user()->id == 1){
			//holds the objects of user ids and their mults (will be converted to json and stored in file)
			$cur_mults = [];
		  // will hold all multiple's id's to keep from assigning them down the line
  		$all_user_mults = []; 
			//get ordered list of all users
	  	$users_arr = User::orderBy('id', 'asc')->get();
		  //get count of original list
		  $list_length = count($users_arr);
			
			//loop through ordered users list to find mults
			foreach($users_arr as $root_user){
		   	//get the tree from the root user's position
		  	$tree = [];
		  	$tree[0] = [];
		  	$users = User::where('upline', '=', $root_user->id)->get();
		  	foreach($users as $socio){
		  		array_push($tree[0],$socio);
		  	}
		  	$i = -1;
		  	while(count($tree[$i+1])){
		  		$tree[$i+2] = self::getNextLevel($i+1,$tree[$i+1]);
		  		$i++;
		  	}
		  	//sort the tree
			  $sorted_tree = [];
			  foreach($tree as $lvl_group){
			    foreach($lvl_group as $soc){
			      array_push($sorted_tree,$soc);
			    }
			  }  
			  usort($sorted_tree, array($this, "compare"));   
  			//the main counter which is checked for multiples of five
			 	$n_count = 1;
			 	//array to hold all found multiples for this user
			 	$multiples_arr = [];
			 	//array to hold id's of disabled id's
			 	$disabled_uplines = [];
			 	//counts the number of multiples found
			 	$mult_counter = 0;
			  foreach($sorted_tree as $tree_usr){
			  	//check if the user's upline is listed in disabled uplines (previously found multiples or their children)
			  	if( in_array($tree_usr->upline, $disabled_uplines) ){
			  		//upline in disabled uplines, adding their id to disabled uplines and skipping this user
			  		array_push($disabled_uplines, $tree_usr->id);
			  	}else{
			  		$count_this = true;//by default
				  	//check if this is a multiple of 5
				    if($n_count % 5 == 0){
				    	//check if this user is already someone else's multiple
				  		if( !in_array($tree_usr->id, $all_user_mults) ){
					    	//found a multiple (not disabled or belonging to anyone else)
					    	$mult_counter++;
					    	//user is a multiple, adding to disabled uplines');
					      array_push($multiples_arr, $tree_usr->id);
					      array_push($disabled_uplines, $tree_usr->id);
					      array_push($all_user_mults, $tree_usr->id);
					    }else{
					    	$count_this = false;
					    }
				  	}
				  	if($count_this){
					  	//incriment the n_count so long as not disabled or belonging to anyone else
							$n_count++;
						}
						
					}	
			  }
			  //add the new object to the cur_mults array
				$new_user_obj = (object) array(
					"user_id" => $root_user->id,
					"multiples" => $multiples_arr
				);
				array_push($cur_mults, $new_user_obj);
			}

			//update the json file with the cur_mults array converted to json
			file_put_contents(storage_path()."/app/multiples.json", json_encode($cur_mults));


			//store any new multiples in comissiones table 
			foreach($cur_mults as $mult){
				$m_count = 0;
				foreach($mult->multiples as $m){
					$m_count++;
					if($m_count % 5 == 0 || $m_count % 6 == 0){
						$com_amt = 0.00;
						//TODO find the asignado for this user and add the comission there if not already created
					}else{
						$com_amt = 250.00;
					}
					//check if the comission already exists
					$comMult_res = Comision::where('type','=','multiplo')
																	 ->where('user_id','=',$mult->user_id)
																	 ->where('new_user_id', '=', $m)
																	 ->first();
					if($comMult_res){
						//comission multiplo already exists, skip this multiple
					}else{
						//comission multiplo does not exist, add to the database
						$m_user = User::find($m);
						$new_mult_com = Comision::create([
							'user_id' => $mult->user_id,
							'new_user_id' => $m,
							'upline_id' => $m_user->upline,
							'patroc_id' => $m_user->patrocinador,
							'asignado_id' => 0,
							'type' => 'multiplo',
							'amount' => $com_amt,
							'date_payed' => '0000-00-00',
							'created_at' => $m_user->fecha_ingreso
						]);
					}
				}			
			}

			//add succes message
			\Session::push('alert-success', 'Múltiplos de red creados y guardados con éxito');
			
			return redirect('/office/api/generateBono20List');

		}else{
			echo 'not authorized... redirecting';
			return redirect('/oficina-virtual');
		}
	}

	public function generateBono20List(Request $request){
		//only the admin user can update persistant bono20s data 
		if(Auth::user()->id == 1){
			//holds the objects of user ids and their bono20s (will be converted to json and stored in file)
			$cur_bono20s = [];
			//get ordered list of all users
	  	$users_arr = User::orderBy('id', 'asc')->get();
		  //get count of original list
		  $list_length = count($users_arr);
			//function to count each user's left and right side and separate user's into left and right arrays
		  function doLadoCounts($tree,$level,$to_count,$upline,$side){
		    $found = false;
		    //check if tree level exists
		    if( isset($tree[$level]) ){
		      //search through all socios in this level
		      foreach( $tree[$level] as $socio){
		        //look for the specified side and parent socio
		        if($socio->side == $side && $socio->upline == $upline){
		          //set found to true
		          $found = true;
		          //increment the count for this side
		          global $left_count;
		          global $right_count;
		          if($to_count == 'L'){
		            array_push($GLOBALS['lefties'], $socio);
		          }else{
		            array_push($GLOBALS['righties'], $socio);
		          }
		          //continue down to the next level
		          doLadoCounts($tree,$level+1,$to_count,$socio->id,'left');
		          doLadoCounts($tree,$level+1,$to_count,$socio->id,'right');
		        }
		      }
		      //socio not found on this level end function
		      if(!$found){
		        return false;
		      }
		    }
			}

			//loop through ordered users list to find bono20s
			foreach($users_arr as $root_user){
				// echo 'cur_user: '.$root_user->id.'<br>';
				
				//reset the arrays to hold the lefts and rights members of each side
			  $GLOBALS['lefties'] = array();
			  $GLOBALS['righties'] = array();
		   	
		   	//get the tree from the root user's position
		  	$tree = [];
		  	$tree[0] = [];
		  	$users = User::where('upline', '=', $root_user->id)->get();
		  	foreach($users as $socio){
		  		array_push($tree[0],$socio);
		  	}
		  	$i = -1;
		  	while(count($tree[$i+1])){
		  		$tree[$i+2] = self::getNextLevel($i+1,$tree[$i+1]);
		  		$i++;
		  	}
			 
			  //call doLadoCounts for the root user's left side
        doLadoCounts($tree,0,'L',$root_user->id,'left');
        //call doLadoCounts for the root user's right side
        doLadoCounts($tree,0,'R',$root_user->id,'right');

        //get lefties and righties data
        $l_arr = $GLOBALS['lefties'];
        $r_arr = $GLOBALS['righties'];
        $l_length = count($l_arr);
        $r_length = count($r_arr);
        //Sort the lefties and righties by id
        usort($l_arr, array($this, "compare"));
        usort($r_arr, array($this, "compare"));

        // //get count of longest list or the 1st if both are equal
        $max_length = ($l_length >= $r_length)? $l_length : $r_length;
        //both sides must have at least 1 registered user
        if(isset($l_arr[0]) && isset($r_arr[0])){
	        //get the date of the first registration
	        $start_date_str = ($l_arr[0]->fecha_ingreso <= $r_arr[0]->fecha_ingreso)? $l_arr[0]->fecha_ingreso : $r_arr[0]->fecha_ingreso;
	        $start_date = \DateTime::createFromFormat('Y-m-d H:i:s', $start_date_str.' 00:00:00');
	        //get the date of the last registration
	        $end_date_str = ($l_arr[$l_length -1]->fecha_ingreso >= $r_arr[$r_length -1]->fecha_ingreso)? $l_arr[$l_length -1]->fecha_ingreso : $r_arr[$r_length -1]->fecha_ingreso;
	        $end_date = \DateTime::createFromFormat('Y-m-d H:i:s', $end_date_str.' 00:00:00');
   				// echo 'start date: '.$start_date_str.'<br>';
   				// echo 'end date: '.$end_date_str.'<br>';
	        //reset array to hold all found bono20 comissions for this user
	        $bono20Coms = [];
	        $lc = 0;
	        $rc = 0;
	        $ltc = [];//holds left 10 counts users
	        $rtc = [];//holds right 10 counts users
	        // echo '<table border="1">
	        //         <tr>
	        //           <th>fecha</th>
	        //           <th>ids izq</th>
	        //           <th>ids drch</th>
	        //           <th>cont izq</th>
	        //           <th>cont drch</th>
	        //           <th>cont *10 izq</th>
	        //           <th>cont *10 drch</th>
	        //           <th>es bono 20?</th>
	        //         </tr>';
	        
	        for($dt = $start_date; $dt <= $end_date; $dt ){       
	          // echo '<tr>
	          //         <td>
	          //         	'.$dt->format('Y-m-d').'
	          //         </td>
	          //         <td>';
			                foreach($l_arr as $soc){
			                  if($soc->fecha_ingreso == $dt->format('Y-m-d')){
			                    $lc++;
			                    if($lc % 10 == 0){
			                      array_push($ltc, $soc);
			                      //echo '<strong>'.$soc->id.'</strong>,';
			                    }else{
			                      //echo $soc->id.',';
			                    }
			                  }
			                } 
	         //  echo   '</td>';
	        	// echo 	 '<td>';
	                foreach($r_arr as $soc){
	                  if($soc->fecha_ingreso == $dt->format('Y-m-d')){
	                    $rc++;
	                    if($rc % 10 == 0){
	                      array_push($rtc, $soc);
	                      //echo '<strong>'.$soc->id.'</strong>,';
	                    }else{
	                      //echo $soc->id.',';
	                    }
	                  }
	                }  
	         //  echo   '</td>';
	        	// echo 	 '<td>
	         //            '.$lc.'
	         //          </td>
	         //          <td>
	         //            '.$rc.'
	         //          </td>';
	                  $left_ten_count = count($ltc);
	                  $right_ten_count = count($rtc);
	          // echo '  <td>
	          //           '.$left_ten_count.'
	          //         </td>
	          //         <td>
	          //           '.$right_ten_count.'
	          //         </td>';
	          // echo '  <td>';
	                    //check if both sides have at least one ten count
	                    if($left_ten_count > 0 && $right_ten_count > 0){
	                      //find out which side has less ten counts (or default to left count if they are equal)
	                      $min_index = ($left_ten_count <= $right_ten_count)? $left_ten_count -1 : $right_ten_count-1;
	                      // echo 'min_index: '.$min_index.'<br>';
	                      //loop through the indexes of both arrays (until the shortest min_index)
	                      for($x = 0; $x <= $min_index; $x++){
	                        //check if both ten count arrays have a ten count in this index (only hold ten-count users) 
	                        if(isset($ltc[$x]) && isset($rtc[$x])){
	                          //we have a user in both x positions
	                          //check if the object already exists in the array
	                          $bv_exists = false;
	                          foreach($bono20Coms as $bvcom){
	                            if($bvcom->temp_id == $x+1){
	                              // the comission already exists in the report array
	                              $bv_exists = true;
	                            }
	                          }
	                          if(!$bv_exists){
	                            //not yet reported, create the new bono20 object
	                            if($ltc[$x]->fecha_ingreso >= $rtc[$x]->fecha_ingreso){
	                            	$last_fecha_ingreso = $ltc[$x]->fecha_ingreso;
	                            	$new_user_id = $ltc[$x]->id;
	                            	$patrocinador = $ltc[$x]->patrocinador;
	                            	$upline = $ltc[$x]->upline;
	                            }else{
	                            	$last_fecha_ingreso = $rtc[$x]->fecha_ingreso;
	                            	$new_user_id = $rtc[$x]->id;
	                            	$patrocinador = $rtc[$x]->patrocinador;
	                            	$upline = $rtc[$x]->upline;
	                            }
	                            $new_bvcom = (object) array(
	                              'temp_id' => $x+1,
	                              'left_id' => $ltc[$x]->id,
	                              'right_id' => $rtc[$x]->id,
	                              'fecha_sync' => $last_fecha_ingreso,
	                              'new_user_id' => $new_user_id,
	                              'patrocinador' => $patrocinador,
	                              'upline' => $upline
	                            );
	                            array_push($bono20Coms, $new_bvcom);
	                            //echo 'Si';
	                          }
	                        }
	                      }
	                    }
	         //  echo 		'</td>';
	        	// echo 	'</tr>';
          	$dt = date_add($dt, date_interval_create_from_date_string('1 day'));
	        }
	        //echo "</table>";
      	}else{
      		//echo 'user does not have at least 1 registered user on each side<br>';
      	}
      	//add this user's data to the more permanent $cur_bono20s array
      	$usr_report = (object) array(
      		"user_id" => $root_user->id,
      		"bonos" => $bono20Coms
      	);
      	array_push($cur_bono20s, $usr_report);
      	//echo '=============================================================<br>';
			}

			//update the file with the cur_bono20s array converted to json
			file_put_contents(storage_path()."/app/bono20s.json", json_encode($cur_bono20s));


			//store any new multiples in comissiones table 
			foreach($cur_bono20s as $usr){
				foreach($usr->bonos as $bn){
					$com_amt = 3500.00;
					//check if the comission already exists
					$comBono_res = Comision::where('type','=','bono20')
																	 ->where('user_id','=',$usr->user_id)
																	 ->where('new_user_id', '=', $bn->new_user_id)
																	 ->first();
					if($comBono_res){
						//comission bono20 already exists, skip this one
					}else{
						//comission bono20 does not exist, add to the database
						$new_mult_com = Comision::create([
							'user_id' => $usr->user_id,
							'new_user_id' => $bn->new_user_id,
							'upline_id' => $bn->upline,
							'patroc_id' => $bn->patrocinador,
							'asignado_id' => 0,
							'type' => 'bono20',
							'amount' => $com_amt,
							'date_payed' => '0000-00-00',
							'created_at' => $bn->fecha_sync
						]);
					}
				}			
			}

			//add succes message
			\Session::push('alert-success', 'Bonos 20 de toda la red creados y guardados con éxito');
			
			return redirect('/oficina-virtual');
		
		}else{
			echo 'not authorized... redirecting';
			return redirect('/oficina-virtual');
		}
	}
		
}



