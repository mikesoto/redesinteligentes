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
		//only the main admin account can view all patrocinios
		if($cur_user->id > 1){
			//normal users can only view their own patrocinios
			$comsPatr_query->where('user_id','=',$cur_user->id);
		}
		$comsPatr = $comsPatr_query->get();
		
		//generate ganancias value
		$ganancias = 0.00;
		if($cur_user->id == 1){
			//earnings for the company come from the users investment
			foreach($comsPatr as $comPatr){
				$ganancias += 1150;
			}
		}else{
			//earnings for users are from the patrocinio amount
			foreach($comsPatr as $comPatr){
				if($comPatr->user_id == $cur_user->id){
					$ganancias += $comPatr->amount;
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
			$week_lunes 					= $dt->startOfWeek();
				$week_lunes_ref 		= carbon::parse($week_lunes->toDateTimeString());
			$week_martes 					= $week_lunes_ref->addDay();
				$week_martes_ref 		= carbon::parse($week_martes->toDateTimeString());
			$week_miercoles 			= $week_martes_ref->addDay();
				$week_miercoles_ref = carbon::parse($week_miercoles->toDateTimeString());
			$week_jueves 					= $week_miercoles->addDay();
				$week_jueves_ref 		= carbon::parse($week_jueves->toDateTimeString());
			$week_viernes 				= $week_jueves_ref->addDay();
				$week_viernes_ref 	= carbon::parse($week_viernes->toDateTimeString());
			$week_sabado 					= $week_viernes_ref->addDay();
				$week_sabado_ref 		= carbon::parse($week_sabado->toDateTimeString());
			$week_domingo 				= $week_sabado_ref->addDay();
	    
	    $data['week_num']      		= $week_num;
			$data['month_num']      	= $month_num;
			$data['year_num']      		= $year_num;
			$data['day_num']      		= $day_num;
			$data['day_of_week']      = $day_of_week;
			$data['week_lunes']      	= $week_lunes;
			$data['week_martes']      = $week_martes;
			$data['week_miercoles']   = $week_miercoles;
			$data['week_jueves']      = $week_jueves;
			$data['week_viernes']     = $week_viernes;
			$data['week_sabado']      = $week_sabado;
			$data['week_domingo']     = $week_domingo;
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

		return view('back.oficina_virtual',[
			'cur_user' => $cur_user,
			'active_page' => 'oficina',
			'title_page' => 'Oficina Virtual',
			'downlines' => $downlines,
			'patrocinios' => $patrocinios,
			'comsPatr' => $comsPatr,
			'ganancias' => $ganancias,
			'weeks_info' => $weeks_info,
			'mults_data' => $mults_data,
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
			  		//check if this user is already someone else's multiple
				  	if(in_array($tree_usr->id, $all_user_mults) ){
				  		//user is already someone's multiple, skipping this count
				  	}else{
					  	//check if this is a multiple of 5
					    if($n_count % 5 == 0){
					    	//found a multiple (not disabled or belonging to anyone else
					    	$mult_counter++;
					    	//user is a multiple, adding to disabled uplines');
					      array_push($multiples_arr, $tree_usr->id);
					      array_push($disabled_uplines, $tree_usr->id);
					      array_push($all_user_mults, $tree_usr->id);
					  	}
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

			//add succes message
			\Session::push('alert-success', 'Múltiplos de red creados y guardados con éxito');
			return redirect('/oficina-virtual');

		}else{
			echo '{
							"response" : "not authorized"
						}';
		}
	}
		
}



