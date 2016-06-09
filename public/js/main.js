$(document).ready(function() {
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
		var office_panels = ['dashboard','red','comissiones'];
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

		//set the onclick event for each user row in the network table
		function setRedItemsClick(){
			//remove any existing event listeners
			$( ".red-item").unbind("click");
			//set the on click event to all existing red-item elements
			$(".red-item").on('click',function(){
				user_id = $(this).data('user-id');
				status = $(this).data('status');
				if($(this).data('downlines') != ''){
					if( $(this).data('downlines').length ){
						downlines = $(this).data('downlines').split(',');
					}else{
						downlines = [$(this).data('downlines')];
					}
				}else{
					downlines = [];
				}
				row_id = $(this).attr('id');
				padding = $("#"+row_id+' td').css('padding-left').replace("px", "");
				console.log('row_id: '+row_id);
				//check the current status (open or closed)
				console.log('current status: '+status);
				if(status == 'closed'){
					//check if the downlines have already been pulled
					console.log('downlines: '+downlines);
					if(downlines[0]){
						console.info('downlines exist... unhiding rows');
						//unhide the existing rows
						$("#dl-data-"+downlines[0]).removeClass('hidden');
						if(downlines[1]){
							$("#dl-data-"+downlines[1]).removeClass('hidden');
						}else{
							$("#dl-empty-"+user_id).removeClass('hidden');
						}
						//change the status for this row to open
						$(this).data('status','open');
						console.warn('done-------------------------------');
					}else{
						console.info('downlines do not exist... getting usr_dls');
						//unhide the loading for this row
						$("#load-dl-"+user_id).removeClass('hidden');
						//get the downline data
						$.ajax({
				    	method: "GET",
						  url: "/office/api/getdownlines/"+user_id,
						})
					  .done(function( usr_dls ) {
					  	console.log('usr_dls: '+usr_dls);
					  	//hide the loading for this row
							$("#load-dl-"+user_id).addClass('hidden');
					  	if(usr_dls.length){
					  		//add the new rows under this row
					  		new_rows =  '<tr id="dl-data-'+usr_dls[0].id+'" class="red-item red-user-'+usr_dls[0].side+'" data-user-id="'+usr_dls[0].id+'" data-upline="'+usr_dls[0].upline+'" data-status="closed" data-downlines="">';
					  		new_rows += '   <td>'+usr_dls[0].nombre+' '+usr_dls[0].apellido_paterno+' '+usr_dls[0].apellido_materno+' (ID:'+usr_dls[0].id+')<span id="load-dl-'+usr_dls[0].id+'" class="pull-right hidden">...</span></td>';
					  		new_rows += '</tr>';
					  		if(usr_dls[1]){
					  			new_rows += '<tr id="dl-data-'+usr_dls[1].id+'" class="red-item red-user-'+usr_dls[1].side+'" data-user-id="'+usr_dls[1].id+'" data-upline="'+usr_dls[0].upline+'" data-status="closed" data-downlines="">';
						  		new_rows += '   <td>'+usr_dls[1].nombre+' '+usr_dls[1].apellido_paterno+' '+usr_dls[1].apellido_materno+' (ID:'+usr_dls[1].id+')<span id="load-dl-'+usr_dls[1].id+'" class="pull-right hidden">...</span></td>';
						  		new_rows += '</tr>';
					  		}else{
					  			new_rows += '<tr id="dl-empty-'+user_id+'" class="red-user-right">';
						  		new_rows += '   <td><span class="ghost">---Disponible---</span></td>';
						  		new_rows += '</tr>';
					  		}
					  		$("#"+row_id).after(new_rows);
					  		//set the paddings
					  		$("#dl-data-"+usr_dls[0].id+' td').css('padding-left',(parseInt(padding)+8)+'px');
					  		if(usr_dls[1]){
					  			$("#dl-data-"+usr_dls[1].id+' td').css('padding-left',(parseInt(padding)+8)+'px');
					  		}else{
					  			$("#dl-empty-"+user_id+' td').css('padding-left',(parseInt(padding)+8)+'px');
					  		}
					  		//set the data downlines for this row 
					  		console.info('usr_dls returned... seting data-downlines');
					  		str_val = usr_dls[0].id;
					  		if(usr_dls[1]){
					  			str_val += ','+usr_dls[1].id;
					  		}
					  		$("#"+row_id).data('downlines', str_val);
					  		console.log('data-downlines: '+str_val);
						    //change the status for this row to open
						    console.info('setting status to open');
								$("#"+row_id).data('status','open');
								setRedItemsClick();
								console.warn('done-------------------------------');
						  }else{
						  	console.log('there are no downlines for this user');
						  	//status stays closed and downlines stays empty
						  	console.warn('done-------------------------------');
						  }
					  })
					  .fail(function() {
					    console.log( "error getting user's downlines" );
					  });
					}
				}else{
					console.info('status is open... hiding existing rows');
					//hide the downlines for this user
					$("#dl-data-"+downlines[0]).addClass('hidden');
					if(downlines[1]){
						$("#dl-data-"+downlines[1]).addClass('hidden');
					}else{
						$("#dl-empty-"+user_id).addClass('hidden');
					}
					//set the status to closed
					$(this).data('status','closed');
					console.warn('done-------------------------------');
				}
			});
		}
		setRedItemsClick();
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
		var h = (parseInt(w)/2.65);
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
  	if(nz < 1.3){
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