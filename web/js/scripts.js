$(document).ready(function(){

	$(".login-input-box").keyup(function(){
		var login = $("#loginform-username").val();
		var password = $("#loginform-password").val();
		if(login != '' && password != '') {
			$("#login-submit-button").removeClass('disabled');
		} else {
			$("#login-submit-button").addClass('disabled');
		}
	});

	$('#issue-modal').on('shown.bs.modal', function() {
		//clear all form fields
	    $('#issueform-podr_list').tokenfield('setTokens', []);
	    $('#issueform-persons_list').tokenfield('setTokens', []);
	    $("#issueform-task_number, #issueform-ordernum, #issueform-peoordernum, #issueform-message, #issueform-date").val('');
	    $("#persons-check-list").html('<div class="alert alert-warning" role="alert">Пожалуйста, сначала укажите подразделения</div>');
	});

	$('#issue-cancel-button, #close-label-issue').click(function () {
	   	bootbox.confirm({size: 'small', message: "Вы уверены, что хотите закрыть форму выдачи задания?", callback: function(result) {
		  	if(result == true) {
		  		$('#issue-modal').modal('hide');
		  	}	
		}}); 
	})


	$("#add-podr-button").click(function(){
		$("#podr-select-modal").modal();
	});

	$("#add-persons-button").click(function(){
		$("#persons-select-modal").modal();
	});
	
	$(".issue-row").click(function(){
		$("#issue-view-preloader").css('display', 'block');
		$(".kv-grid-table").css('opacity', '0.5');
		//console.log($(this).attr('id'));
		//get data and create modal
		$.ajax({
        	type: "POST",
        	dataType: 'json',
        	url: "index.php?r=site/getissuedata",
        	data: "id="+$(this).attr('id'),
        	success: function(data,status){


        		$("#myModalLabel-issue").html('Задание '+data.issue_designation);

        		if(data.permissons_for_read == 0) {
        			$("#issue-view-table").html('<div class="alert alert-danger" role="alert">У Вас нет прав на просмотр "Форма свойств задания"</div>');
        			$("#update-issue-button-new-tab").hide();
        			$("#update-issue-button-new-tab").attr('href', '#');
        			$("#update-issue-top-button").hide();
        			$("#update-issue-top-button").attr('href', '#');
        		} else {
        			$("#issue-view-table").html(data.result_table);
        			if(data.permissions_for_write == 0) {
        				$("#update-issue-button-new-tab").hide();
	        			$("#update-issue-button-new-tab").attr('href', '#');
	        			$("#update-issue-top-button").hide();
	        			$("#update-issue-top-button").attr('href', '#');
        			} else {
        				$("#update-issue-button-new-tab").show();
        				$("#update-issue-button-new-tab").attr('href', 'index.php?r=site/updateissue&id='+data.issue_id);
        				$("#update-issue-top-button").show();
	        			$("#update-issue-top-button").attr('href', 'index.php?r=site/updateissue&id='+data.issue_id);
        			}
        		}

        		$("#issue-view-preloader").css('display', 'none');
        		$(".kv-grid-table").css('opacity', '1.0');
        		$("#issue-view-modal").modal();	

        		//check permissions to view edit button
        		// if(data.user_have_permission != 1) {
        		// 	$("#update-issue-button-new-tab").hide();
        		// 	$("#update-issue-button-new-tab").attr('href', '#');
        		// } else {
        		// 	$("#update-issue-button-new-tab").show();
        		// 	$("#update-issue-button-new-tab").attr('href', 'index.php?r=site/updateissue&id='+data.issue_id);
        		// }

        		//$("#update-issue-top-button").attr('href', 'index.php?r=site/updateissue&id='+data.issue_id);
        		//$("#update-issue-button").attr('data-id', data.issue_id);
        		

        	}
        });

		
	});

	$("#update-issue-button").click(function(){
		$("#issue-view-modal").modal('hide');	
		var issue_id = $(this).attr('data-id');
		$.get(
	        'index.php?r=site/updateissue',      
	        {
	            id: issue_id
	        },
	        function (data) {
	            $('#partial-update-form').html(data);
	            $("#issue-update-modal").modal();
	        }  
	    );
	});


	//$("#select-podr").click(function(){
	function _selectPodr() {
		var selected = [];
		var selected_values = {};
		$('#podr-check-list input:checked').each(function() {
		    selected.push({value: $(this).attr('value'), label:$(this).attr('data-title')});
		    selected_values[$(this).attr('value')] = $(this).attr('data-title');
		});
		
		$('#issueform-podr_list').tokenfield('setTokens', selected);
        $('#podr-select-modal').modal('hide');

        //create persons tree
        $.ajax({
        	type: "POST",
        	dataType: 'json',
        	url: "index.php?r=site/getpersons",
        	data: "selected_podr="+JSON.stringify(selected_values),
        	success: function(data,status){
        		$("#persons-check-list").html(data);
        		$('#persons-check-list').tree({checkbox: false});
        	}
        });
    }    
    //});

    $("#select-persons").click(function(){
    	var selected = [];
    	$('#persons-check-list input:checked').each(function() {
		    selected.push({value: $(this).attr('value'), label:$(this).attr('data-title')});
		});
		$('#issueform-persons_list').tokenfield('setTokens', selected);
        $('#persons-select-modal').modal('hide');
    });

    $('#podr-check-list').tree({checkbox: false});
    $('#issueform-podr_list').tokenfield()
    	.on('tokenfield:removetoken', function (e) {
    		$('#issueform-persons_list').tokenfield('setTokens', []);
    		$("#podr-check-list").find('input:checkbox').removeAttr('checked');
    		$("#persons-check-list").html('<div class="alert alert-warning" role="alert">Пожалуйста, сначала укажите подразделения</div>');
        	//update hidden field
        	// var isset_values = obj = JSON.parse($("#issueform-podr_values").val());
        	// console.log(isset_values);
    	});
    $('#issueform-persons_list').tokenfield();

    $("#podr-check-list").find(".checkbox-podr-link").click(function(){
    	var link_id = $(this).attr('data-id');
    	$("#checkbox_"+link_id).prop("checked", true);
    	_selectPodr();
    	return false;
    });


    //filter 
    function _selectPodrFilter() {
		var selected = [];
		var selected_values = {};
		$('#podr-check-list-filter input:checked').each(function() {
		    selected.push({value: $(this).attr('value'), label:$(this).attr('data-title')});
		    selected_values[$(this).attr('value')] = $(this).attr('data-title');
		});
		
		$('#searchtasks-podr_list').tokenfield('setTokens', selected);
		if(selected.length > 0) {
			$("#podr_list_moment").html('выбрано: '+selected.length+' подразделение(ия)');
		} else {
			$("#podr_list_moment").html('');
		}

        $('#podr-select-modal-filter').modal('hide');

        //create persons tree
        $.ajax({
        	type: "POST",
        	dataType: 'json',
        	url: "index.php?r=site/getpersons",
        	data: "selected_podr="+JSON.stringify(selected_values),
        	success: function(data,status){
        		$("#persons-check-list-filter").html(data);
        		$('#persons-check-list-filter').tree({checkbox: false});
        		$('#searchtasks-persons_list').tokenfield('setTokens', []);
        		$("#persons_list_moment").html('');
        	}
        });
    }    
    $("#searchtasks-states input").change(function() {	
    	var state_moment_string = '';
    	$('#searchtasks-states input:checked').each(function() {
    		state_moment_string = state_moment_string + $(this).parent().text();
    	});
    	if(state_moment_string != '') {
    		state_moment_string = 'выбрано: '+state_moment_string;
    	}
    	$("#state_moment").html(state_moment_string);
    });
    $('#searchtasks-podr_list').tokenfield({minWidth: 200})
	    .on('tokenfield:removedtoken', function (e) {
	   		$("#podr-check-list-filter").find('#checkbox_filter_'+e.attrs.value).removeAttr('checked');
	  		_selectPodrFilter();
  	});
	$('#searchtasks-podr_list').on('tokenfield:removetoken', function (e) {
		$('#searchtasks-persons_list').tokenfield('setTokens', []);
		$("#persons-check-list-filter").html('<div class="alert alert-warning" role="alert">Пожалуйста, сначала укажите подразделения</div>');
	   	$("#persons_list_moment").html('');
	});

    $("#add-podr-button-filter").click(function(){
		$("#podr-select-modal-filter").modal();
	});
	$('#podr-check-list-filter').tree({checkbox: false});
	$("#podr-check-list-filter").find(".checkbox-podr-link-filter").click(function(){
    	var link_id = $(this).attr('data-id');
    	$("#checkbox_filter_"+link_id).prop("checked", true);
    	_selectPodrFilter();
    	return false;
    });

	//------------------------------------------------------------------------------------------------------
	function _selectAgreedFilter() {
		var selected = [];
		var selected_values = {};
		$('#agreed-check-list-filter input:checked').each(function() {
		    selected.push({value: $(this).attr('value'), label:$(this).attr('data-title')});
		    selected_values[$(this).attr('value')] = $(this).attr('data-title');
		});

		
		$('#searchtasks-agreed_podr_list').tokenfield('setTokens', selected);
		if(selected.length > 0) {
			$("#agreed_podr_list_moment").html('выбрано: '+selected.length+' подразделение(ия)');
		} else {
			$("#agreed_podr_list_moment").html('');
		}

        $('#agreed-select-modal-filter').modal('hide');

    } 

    $('#searchtasks-agreed_podr_list').tokenfield({minWidth: 200})
	    .on('tokenfield:removedtoken', function (e) {
	   		$("#agreed-check-list-filter").find('#checkbox_filter_agreed_'+e.attrs.value).removeAttr('checked');
	  		_selectAgreedFilter();
  	});
	

	$("#add-agreed-button-filter").click(function(){
		$("#agreed-select-modal-filter").modal();
	});
	$('#agreed-check-list-filter').tree({checkbox: false});
	$("#agreed-check-list-filter").find(".checkbox-agreed-link-filter").click(function(){
    	var link_id = $(this).attr('data-id');
    	$("#checkbox_filter_agreed_"+link_id).prop("checked", true);
    	_selectAgreedFilter();
    	return false;
    });

    


	$("#add-persons-button-filter").click(function(){
		$("#persons-select-modal-filter").modal();
	});

	$("#select-persons-filter").click(function(){
    	var selected = [];
    	$('#persons-check-list-filter input:checked').each(function() {
		    selected.push({value: $(this).attr('value'), label:$(this).attr('data-title')});
		});
		$("#persons_list_moment").html('выбрано: '+selected.length+' исполнителя(ей)');
		$('#searchtasks-persons_list').tokenfield('setTokens', selected);
        $('#persons-select-modal-filter').modal('hide');
    });

    $('#searchtasks-persons_list').tokenfield({minWidth: 200});

    $('#searchtasks-persons_list').on('tokenfield:removedtoken', function (e) {
		$("#persons-check-list-filter").find('#checkbox_'+e.attrs.value).removeAttr('checked');
	});
	
    //states change modal
	function _setStateInDb(this_value, parent_value, status, object) {

		$.ajax({
        	type: "POST",
        	dataType: 'json',
        	url: "index.php?r=site/setstatenext",
        	data: "this_value="+this_value+"&parent_value="+parent_value+"&status="+status,
        	success: function(data,status){
        		if(data.error == 0) {
        			//success saved
        			//console.log('success');
        			$(object).removeClass('set-checkbox-state');

        		} else {
        			//unsuccess saved
        			//console.log('unsuccess');
        			$(this).prop('checked', false);
        			$(object).removeClass('set-checkbox-state');
        		}
        	}
        });

	}

	$(".states-change-checkbox").change(function() {

		$(this).addClass('set-checkbox-state');

		var this_value = $(this).val();
		var parent_value = $(this).attr('data-parent');
		//console.log(this_value+'-'+parent_value);
		if($(this).prop('checked') == true){
			//console.log(this_value+'-'+parent_value+'-checked');
			_setStateInDb(this_value, parent_value, 'checked', this);
		} else {
			//console.log(this_value+'-'+parent_value+'-unchecked');
			_setStateInDb(this_value, parent_value, 'unchecked', this);
		}
	});
		
	//------------------------------------------------------------------------------------------------

	function isInArray(value, array) {
	  	return array.indexOf(value) > -1;
	}

	function _showActionsNodes() {
		$('#jstree-actions li').each(function(){
			$(this).show();
		});
	}

	function _showStatesNodes() {
		$('#jstree-states li').each(function(){
			$(this).show();
		});
	}

	$("#permissions-header-actions-filter span").click(function(){

		_showActionsNodes();
			
		if($("#jstree-v_f_shras").jstree("get_selected").length > 0) {
			//console.log('view for shra');
			var selected_object = $("#jstree-v_f_shras").jstree(true).get_selected('full',true);
			if(selected_object[0].parent == '#') {
				//var selected_object_id = selected_object[0].data.id;

				//get selected element nodes text
				var selected_text_array = [];
				for (var i = 0; i <= selected_object[0].children.length; i++) {
					if($('#jstree-v_f_shras').jstree(true).get_node(selected_object[0].children[i]) != false) {
						selected_text_array.push($('#jstree-v_f_shras').jstree(true).get_node(selected_object[0].children[i]).text);
					}
					//$("#tree").jstree(true).hide_node(treeNode);

				}
				//console.log(selected_text_array);
				//get root actions nodes text
				var actions_text_array = [];
				$('#jstree-actions li').each(function(){
				 	//actions_text_array.push($(this).children('a').text());
				 	//console.log(selected_text_array);
				 	if(isInArray($(this).children('a').text(), selected_text_array)) {
				 	 	$(this).hide();
				 	}
				});
				//change filter link @maybetodo

			}
		} else if($("#jstree-v_f_pers").jstree("get_selected").length > 0) {
			//console.log('view for pers');
			var selected_object = $("#jstree-v_f_pers").jstree(true).get_selected('full',true);
			if(selected_object[0].parent == '#') {
				//var selected_object_id = selected_object[0].data.id;

				//get selected element nodes text
				var selected_text_array = [];
				for (var i = 0; i <= selected_object[0].children.length; i++) {
					if($('#jstree-v_f_pers').jstree(true).get_node(selected_object[0].children[i]) != false) {
						selected_text_array.push($('#jstree-v_f_pers').jstree(true).get_node(selected_object[0].children[i]).text);
					}
					//$("#tree").jstree(true).hide_node(treeNode);

				}
				//console.log(selected_text_array);
				//get root actions nodes text
				var actions_text_array = [];
				$('#jstree-actions li').each(function(){
				 	//actions_text_array.push($(this).children('a').text());
				 	//console.log(selected_text_array);
				 	if(isInArray($(this).children('a').text(), selected_text_array)) {
				 	 	$(this).hide();
				 	}
				});
				//change filter link @maybetodo
			}
		} 
		return false;
	});


	$("#permissions-header-states-filter span").click(function(){

		_showStatesNodes();
			
		if($("#jstree-v_f_shras").jstree("get_selected").length > 0) {
			//console.log('view for shra');
			var selected_object = $("#jstree-v_f_shras").jstree(true).get_selected('full',true);
			if(selected_object[0].parent == '#') {
				//var selected_object_id = selected_object[0].data.id;

				//get selected element nodes text
				var selected_text_array = [];
				for (var i = 0; i <= selected_object[0].children.length; i++) {
					if($('#jstree-v_f_shras').jstree(true).get_node(selected_object[0].children[i]) != false) {
						selected_text_array.push($('#jstree-v_f_shras').jstree(true).get_node(selected_object[0].children[i]).text);
					}
					//$("#tree").jstree(true).hide_node(treeNode);

				}
				//console.log(selected_text_array);
				//get root actions nodes text
				var actions_text_array = [];
				$('#jstree-states li').each(function(){
				 	//actions_text_array.push($(this).children('a').text());
				 	//console.log(selected_text_array);
				 	if(isInArray($(this).children('a').text(), selected_text_array)) {
				 	 	$(this).hide();
				 	}
				});
				//change filter link @maybetodo

			}
		} else if($("#jstree-v_f_pers").jstree("get_selected").length > 0) {
			//console.log('view for pers');
			var selected_object = $("#jstree-v_f_pers").jstree(true).get_selected('full',true);
			if(selected_object[0].parent == '#') {
				//var selected_object_id = selected_object[0].data.id;

				//get selected element nodes text
				var selected_text_array = [];
				for (var i = 0; i <= selected_object[0].children.length; i++) {
					if($('#jstree-v_f_pers').jstree(true).get_node(selected_object[0].children[i]) != false) {
						selected_text_array.push($('#jstree-v_f_pers').jstree(true).get_node(selected_object[0].children[i]).text);
					}
					//$("#tree").jstree(true).hide_node(treeNode);

				}
				//console.log(selected_text_array);
				//get root actions nodes text
				var actions_text_array = [];
				$('#jstree-states li').each(function(){
				 	//actions_text_array.push($(this).children('a').text());
				 	//console.log(selected_text_array);
				 	if(isInArray($(this).children('a').text(), selected_text_array)) {
				 	 	$(this).hide();
				 	}
				});
				//change filter link @maybetodo
			}
		} 
		return false;
	});


	
	
	$("#jstree-states").jstree({
		"plugins" : [
		    "dnd",
		]

	});

	//------------------------------------------------------------------------------------------------

	

	//for develop
	//$("#permissions-form-modal").modal();

});

function viewWhatSelectedInFilter(val, moment) {
	$("#"+moment).html('выбрано: '+val);
}
function showSelectedDateRange(moment) {
	if(moment == 'task_deadline_moment') {
		var date_from = $("#searchtasks-deadline_from").val();
		var date_to = $("#searchtasks-deadline_to").val();
		if(date_from == '' && date_to != '') {
			$("#"+moment).html('до '+date_to);
		} else if(date_from != '' && date_to == '') {
			$("#"+moment).html('от '+date_from);
		} else if(date_from != '' && date_to != '') {
			$("#"+moment).html('от '+date_from+' до '+date_to);
		} else {
			$("#"+moment).html('');
		}
		
	}
	if(moment == 'task_type_date_3_moment') {
		var date_from = $("#searchtasks-task_type_date_3_from").val();
		var date_to = $("#searchtasks-task_type_date_3_to").val();
		if(date_from == '' && date_to != '') {
			$("#"+moment).html('до '+date_to);
		} else if(date_from != '' && date_to == '') {
			$("#"+moment).html('от '+date_from);
		} else if(date_from != '' && date_to != '') {
			$("#"+moment).html('от '+date_from+' до '+date_to);
		} else {
			$("#"+moment).html('');
		}
		
	}

	if(moment == 'task_type_date_1_moment') {
		var date_from = $("#searchtasks-task_type_date_1_from").val();
		var date_to = $("#searchtasks-task_type_date_1_to").val();
		if(date_from == '' && date_to != '') {
			$("#"+moment).html('до '+date_to);
		} else if(date_from != '' && date_to == '') {
			$("#"+moment).html('от '+date_from);
		} else if(date_from != '' && date_to != '') {
			$("#"+moment).html('от '+date_from+' до '+date_to);
		} else {
			$("#"+moment).html('');
		}
		
	}

	if(moment == 'task_type_date_4_moment') {
		var date_from = $("#searchtasks-task_type_date_4_from").val();
		var date_to = $("#searchtasks-task_type_date_4_to").val();
		if(date_from == '' && date_to != '') {
			$("#"+moment).html('до '+date_to);
		} else if(date_from != '' && date_to == '') {
			$("#"+moment).html('от '+date_from);
		} else if(date_from != '' && date_to != '') {
			$("#"+moment).html('от '+date_from+' до '+date_to);
		} else {
			$("#"+moment).html('');
		}
		
	}

	if(moment == 'task_type_date_2_moment') {
		var date_from = $("#searchtasks-task_type_date_2_from").val();
		var date_to = $("#searchtasks-task_type_date_2_to").val();
		if(date_from == '' && date_to != '') {
			$("#"+moment).html('до '+date_to);
		} else if(date_from != '' && date_to == '') {
			$("#"+moment).html('от '+date_from);
		} else if(date_from != '' && date_to != '') {
			$("#"+moment).html('от '+date_from+' до '+date_to);
		} else {
			$("#"+moment).html('');
		}
		
	}
}

