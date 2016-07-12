$(document).ready(function() {
	//functions to get the GET vars from the current URL
	$.extend({
	  getUrlVars: function(){
	    var vars = [], hash;
	    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
	    for(var i = 0; i < hashes.length; i++)
	    {
	      hash = hashes[i].split('=');
	      vars.push(hash[0]);
	      vars[hash[0]] = hash[1];
	    }
	    return vars;
	  },
	  getUrlVar: function(name){
	    return $.getUrlVars()[name];
	  }
	});

	$(".date_field").datepicker({
			dateFormat: 'dd/mm/yy',
		},
		$.datepicker.regional['es']
	);

	$(".date_birth_field").datepicker({
			dateFormat: 'dd/mm/yy',
	    changeMonth: true,
	    changeYear: true,
	    yearRange: "1950:2000",
	    showButtonPanel: true
		},
		$.datepicker.regional['es']
	);

	//check if the control panel panel is present
	if($("#panel-de-control").length){
		//set the onclick event for the close-notif-btn 
		$("#close-notif-btn").on('click',function(){
			$("#office-main-content").removeClass('col-sm-9');
			$("#office-main-content").addClass('col-sm-12');
		});
		//set the first button as active
		$("#cpanel-dashboard").focus();
		//array of available panels
		var office_panels = ['dashboard','red','comissiones','downloads'];
		//==================================================================================
		//======================== set on click for cpanel shortcuts =======================
		//==================================================================================
		$(".cpanel-link").on('click',function(){
			//hide all panels
			for(i=0; i < office_panels.length; i++){
				$("#office-panel-"+office_panels[i]).addClass('hidden');
			}
			//show this panel
			var panelName = $(this).attr('data-panelName');
			if(panelName == 'red'){
				//close the notif panel automatically
				$("#close-notif-btn").trigger('click');
				//show center map button
  			$("#red-map-controls").removeClass('hidden');
				//center the map for them once the map is loaded
				setTimeout(function(){$("#map-scrollbutton").trigger('click');}, 100);
			}
			$("#office-panel-"+panelName).removeClass('hidden');
		});

		// ============================ COUNT USER MULTIPLES ===========================	  

	  function mark_multiples(u_id,m_count){
	  	//add classes to the label and downlines container for this user in Red Panel
      $("#label-"+u_id).addClass('multiple');
      $("#label-"+u_id).append('<sup> * '+m_count+'</sup>');
      $("#downlines-"+u_id).addClass('multiple');
      //add classes to the row container for this user in Lados Panel
      $("#lado-row-"+u_id).addClass('multiple');
      $("#lado-row-"+u_id).append('<sup> * '+m_count+'</sup>');
      //add classes to the row container for this user in Listados Panel
      $("#listado-row-"+u_id).addClass('multiple');
      $("#listado-row-"+u_id).append('<sup> * '+m_count+'</sup>');
      
      //check if is a 5th multiple
      if(m_count % 5 === 0){
      	//console.log('found a 5th multiple');
    		//is a 5th multiple, add 5th-mult class to the label in Red Panel
    		$("#label-"+u_id).addClass('5th-mult');
    		$("#label-"+u_id).css('border','2px solid #4fc0d9');
      	$("#label-"+u_id).css('background-color','#666');
    		//add 5th-mult class to the row in Lados Panel
      	$("#lado-row-"+u_id).addClass('5th-mult');
      	$("#lado-row-"+u_id).css('border','2px solid #4fc0d9');
      	$("#lado-row-"+u_id).css('background-color','#aaa');
      	//add 5th-mult class to the row in Listados Panel
      	$("#listado-row-"+u_id).addClass('5th-mult');
      	$("#listado-row-"+u_id).css('border','2px solid #4fc0d9');
      	$("#listado-row-"+u_id).css('background-color','#aaa');
    	}
    	//check if is a 6th multiple
      if(m_count % 6 === 0){
      	//console.log('found a 6th multiple');
    		//is a 6th multiple, add 6th-mult class to the label in Red Panel
    		$("#label-"+u_id).addClass('6th-mult');
    		$("#label-"+u_id).css('border','2px solid #4fc0d9');
      	$("#label-"+u_id).css('background-color','#666');
    		//add 6th-mult class to the row in Lados Panel
      	$("#lado-row-"+u_id).addClass('6th-mult');
      	$("#lado-row-"+u_id).css('border','2px solid #4fc0d9');
      	$("#lado-row-"+u_id).css('background-color','#aaa');
      	//add 6th-mult class to the row in Listados Panel
      	$("#listado-row-"+u_id).addClass('6th-mult');
      	$("#listado-row-"+u_id).css('border','2px solid #4fc0d9');
      	$("#listado-row-"+u_id).css('background-color','#aaa');
    	}
	  }

	  function separate_mults(u_id,c_mults){
	  	var oth_mults = [];
		  var usr_mults = [];
	  	//seperates current user's mults from other user's mults
		  var mults_len = c_mults.length;
	    for(i = 0; i < mults_len; i++){ 
	    	if(typeof(c_mults[i]) == 'object'){
		    	this_mults_len = c_mults[i].multiples.length;     	
		      for(n = 0; n < this_mults_len; n++){
		      	if(c_mults[i].user_id != u_id){
		      		oth_mults.push(c_mults[i].multiples[n]);
		      	}else{
		      		usr_mults.push(c_mults[i].multiples[n]);
		      	}
		      }
		    }
	    }
	    return [oth_mults,usr_mults];
	  }

	  
	  all_user_mults = [] // will store all user's mults to keep from assigning them down the line
	  
	  function update_multiples(root_user, users_list){
		  //seperate current user's mults from other user's mults
		  sep_arrs = separate_mults(root_user,cur_mults);
		  var cur_user_mults = sep_arrs[1];
		  var others_mults = sep_arrs[0];
	    //loop through ordered users list to find mults
		 	var users_length = users_list.length;
		 	var mults_len = cur_mults.length;
		 	var n_count = 1;//the main counter which is checked for multiples of five
		 	var multiples_arr = [];//array to hold all found multiples for this user
		 	var disabled_uplines = [];//array to hold id's of disabled id's
		 	var mult_counter = 0;//counts the number of multiples found
		  for(i=0; i < users_length; i++){
		  	//check if the user's upline is listed in disabled uplines (previously found multiples or their children)
		  	if(disabled_uplines.indexOf(users_list[i].upline) > -1){
		  		//console.warn('user '+users_list[i].id+'\'s upline in disabled uplines, adding their id to disabled uplines and skipping this user');
		  		disabled_uplines.push(users_list[i].id);
		  	}else{
		  		//check if this user is already someone else's multiple
		  		//console.warn(others_mults);
			  	if(all_user_mults.indexOf(users_list[i].id) > -1){
			  		//console.log(users_list[i].id+' is already someone\'s multiple, skipping this count');
			  	}else{
				  	//check if this is a multiple of 5
				    if(n_count % 5 == 0){
				    	//found a multiple (not disabled or belonging to anyone else)
				    	mult_counter+=1;
				    	//console.log(n_count+' '+users_list[i].user+' is multiple '+mult_counter+' ... adding to disabled uplines');
				      multiples_arr.push(users_list[i].id);
				      disabled_uplines.push(users_list[i].id);
				      all_user_mults.push(users_list[i].id);
				    	//only mark multiples for logged in user
				    	if(root_user == cur_user){
					    	mark_multiples(users_list[i].id,mult_counter);
					    }
				  	}else{
				  		//is a normal n_count user
				  		//console.log(n_count+' '+users_list[i].user);
				  	}
				  	//always incriment the n_count as long as not disabled or belonging to anyone else
						n_count++;
					}
				}	
		  }
		  //console.log('found multiples for user: '+root_user);
		  //console.log(multiples_arr);
		  //compare stored multiples with found multiples and send update request if not identical
		  multiples_found_str = JSON.stringify(multiples_arr);
		 	multiples_stored_str = JSON.stringify(cur_user_mults);
		 //  console.log(multiples_found_str);
			// console.log(multiples_stored_str);
		  if(multiples_found_str  !=  multiples_stored_str){
		  	// console.log('arrays are not equal, sending update request');
			  var csrfToken = $('meta[name="csrf-token"]').attr('content');
			  $.ajaxSetup({
		      headers: {
		        'X-CSRF-TOKEN': csrfToken
		      }
				});
			  $.ajax({
				  method: "post",
				  url: '/office/api/mult_json_sync/',
				  data: { user_data : { "user_id" : root_user, "multiples" : multiples_arr} }
				})
			  .done(function( sync_result ) {
			  	//console.log(sync_result);
			  });
			  
			}else{
				//console.log('arrays are same, not sending update request for this user');
			}
			//done with this iteration
			//console.log('============================================================');
			return {
				'user_id' : root_user,
				'others_mults' : others_mults,
				'cur_user_mults' : cur_user_mults,
				'found_user_mults' : multiples_arr,
				'mult_counter' : mult_counter
			}
		}

		//array to hold response multiples data
		mults_data = [];
		//create a copy of users_sorted so as not to change the original list while looping
		users_sorted_copy = users_sorted.slice();
		
		//run through the entire list counting multiples for each user and sending sync if necessary
		//run the multiples count for the current user first (logged in user)
		//only generate the multiples live for the admin user
		if(cur_user == 1){
			mults_data.push(update_multiples(cur_user, users_sorted_copy));
		  //update the counts area with the total multiples of currently logged in user
		  $(".side-counts h4").append('| <label class="label label-danger">Multiples: '+mults_data[0].mult_counter+'</label> ');
		
		  if($.getUrlVar("calc_multiples") != null) {
				//loop through the copied array, shortening it each iteration
				copied_len = users_sorted_copy.length;
				for(c = 0; c < copied_len; c++){
					//get the next user from the list (removes it from the array also)
					shifted_user = users_sorted_copy.shift();//remove the first element from the copied list
					console.log('runing update_multiples for user '+shifted_user.id);
					//run the loop again for the next user
					mults_data.push(update_multiples(shifted_user.id, users_sorted_copy));
				}
			}
		}else{
			m_count = 0;
			//is not admin so pull their multiples from the json data
			for(i=0; i< cur_mults.length; i++){
				if(cur_mults[i].user_id == cur_user){
					
					for(n=0; n < cur_mults[i].multiples.length; n++){
						//check if the user exists in the cur_user's tree
						if($("#label-"+cur_mults[i].multiples[n]).length){
							m_count++;
							mark_multiples(cur_mults[i].multiples[n],m_count);
						}
					}
				}
			}
			//update the counts area with the total multiples of currently logged in user
		  $(".side-counts h4").append('| <label class="label label-danger">Multiples: '+m_count+'</label> ');
		}

		
		
	}

	//check if the create user form is present
	if($("#create-user-form").length){
		//==================================================================================
		//============== add ajax action for blur event of the upline field ================
		//==================================================================================
		$("#upline").on('blur',function(){
			upline_id = $(this).val();
			if(upline_id){
				//unhide the loading for upline
				$("#loading-upline-nombre").removeClass('hidden');
				//request the info for this user
				$.ajax({
		    	method: "GET",
				  url: "/office/api/user/"+upline_id,
				})
			  .done(function( upline ) {
			  	//hide the loading for upline
					$("#loading-upline-nombre").addClass('hidden');
			  	if(upline){
				    var uplineName = upline.nombre+' '+upline.apellido_paterno+' '+upline.apellido_materno;
				    $("#nombre_upline").val( uplineName );
				  }else{
				  	$("#upline").val('');
				  	$("#nombre_upline").val( '' );
				  }
			  })
			  .fail(function() {
			    console.log( "error getting upline user" );
			  });
			}
		});
		//if the upline has an old input value, trigger the blur event
		if($("#upline").val()){
			$("#upline").blur();
		}
		//==================================================================================
		//============== add ajax action for blur event of the upline field ================
		//==================================================================================
		$("#patrocinador").on('blur',function(){
			patro_id = $(this).val();
			if(patro_id){
				//unhide the loading for patrocinador
				$("#loading-patro-nombre").removeClass('hidden');
				//request the info for this user
				$.ajax({
		    	method: "GET",
				  url: "/office/api/user/"+patro_id,
				})
			  .done(function( patr ) {
			  	//hide the loading for patrocinador
					$("#loading-patro-nombre").addClass('hidden');
			  	if(patr){
				    var patrName = patr.nombre+' '+patr.apellido_paterno+' '+patr.apellido_materno;
				    $("#nombre_patrocinador").val( patrName );
				  }else{
				  	$("#patrocinador").val('');
				  	$("#nombre_patrocinador").val( '' );
				  }
			  })
			  .fail(function() {
			    console.log( "error getting upline user" );
			  });
			}
		});
		//if the patrocinador has an old input value, trigger the blur event
		if($("#patrocinador").val()){
			$("#patrocinador").blur();
		}
	}

	//disable submit and cancel buttons while the form is submitted and email sent
	$("#create-user-submit-btn").on('click',function(event){
		event.preventDefault();
		$("#create-user-modal-footer").html('Enviando... Por favor espere.');
		$("#create-user-form").submit();
	});

	$("#map-scrollbutton").click(function(){
		var w = document.getElementById("red-map-wrap").scrollWidth;
		var h = (parseInt(w)/2.22);
    $("#arbol").scrollLeft(h);
  });

  $("#map-zoomout").click(function(){
  	//get current zoom level
  	var z = $("#arbol").css('zoom');
  	//calc new zoom 
  	var nz = parseFloat(z) - 0.1;
  	console.log('current zoom '+z);
  	console.log('new zoom '+nz);
  	if(nz > 0){
	  	$("#arbol").css('zoom',nz);
	  	$("#arbol").css('-moz-transform',nz);
	  	//center the map
	  	$("#map-scrollbutton").trigger('click');
	  }
  });

  $("#map-zoomin").click(function(){
  	//get current zoom level
  	var z = $("#arbol").css('zoom');
  	//calc new zoom 
  	var nz = parseFloat(z) + 0.1;
  	console.log('current zoom '+z);
  	console.log('new zoom '+nz);
  	if(nz < 1.9){
	  	$("#arbol").css('zoom',nz);
	  	$("#arbol").css('-moz-transform',nz);
	  	//center the map
	  	$("#map-scrollbutton").trigger('click');
	  }
  });

  $(".red-nav-tab").click(function(){
  	var link = $(this).attr('href');
  	if(link == '#arbol'){
  		//show center map button
  		$("#red-map-controls").removeClass('hidden');
  	}else{
  		//hide center map button
  		$("#red-map-controls").addClass('hidden');
  	}
  });

});