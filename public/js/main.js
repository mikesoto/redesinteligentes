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
		var office_panels = ['dashboard','red','comissiones','downloads','balance'];
		//==================================================================================
		//======================== set on click for cpanel shortcuts =======================
		//==================================================================================
		$(".cpanel-link").on('click',function(){
			//hide all panels
			for(i=0; i < office_panels.length; i++){
				$("#office-panel-"+office_panels[i]).addClass('hidden');
			}
			//close the notif panel automatically
			$("#close-notif-btn").trigger('click');
			//show this panel
			var panelName = $(this).attr('data-panelName');
			if(panelName == 'red'){
				//show center map button
  			$("#red-map-controls").removeClass('hidden');
				//center the map for them once the map is loaded
				setTimeout(function(){$("#map-scrollbutton").trigger('click');}, 100);
			}
			$("#office-panel-"+panelName).removeClass('hidden');
		});

		// ============================ COUNT USER MULTIPLES ===========================	  
	  function mark_multiples(u_id,m_count){
	  	var earn = true;
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
      	earn = false;
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
      	earn = false;
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
    	if(earn){
    		mults_earnings += 250.00;
    	}
	  }

		m_count = 0;
		mults_earnings = 0.00;
		//pull their multiples data from the json file ->  array (cur_mults)
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
		//update the dashboard with the total multiples count
		$("#dash-mult-count").html(m_count);
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