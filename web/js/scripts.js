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
        		$("#issue-view-table").html(data.result_table);

        		//check permissions to view edit button
        		if(data.user_have_permission != 1) {
        			$("#update-issue-button-new-tab").hide();
        			$("#update-issue-button-new-tab").attr('href', '#');
        		} else {
        			$("#update-issue-button-new-tab").show();
        			$("#update-issue-button-new-tab").attr('href', 'index.php?r=site/updateissue&id='+data.issue_id);
        		}


        		$("#issue-view-preloader").css('display', 'none');
        		$(".kv-grid-table").css('opacity', '1.0');
        		
        		$("#update-issue-top-button").attr('href', 'index.php?r=site/updateissue&id='+data.issue_id);
        		$("#update-issue-button").attr('data-id', data.issue_id);
        		$("#issue-view-modal").modal();	

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
    $("#states-change-link").click(function(){
		$("#states-change-modal").modal();
		return false;
	});

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
		

	//permissions modal
	$("#permissions-link").click(function(){
		$("#permissions-modal").modal();
		return false;
	});

	$('#v_f_tree-v_f_shras, #v_f_tree-v_f_pers').tree({checkbox: false, expandUiIcon: 'ui-icon-minus', collapseUiIcon: 'ui-icon-plus'});

		
	$("ol.permissions-actions").sortable({
		group: 'no-drop',
		drop: false,
		nested: false,
		itemSelector: 'li',
	 	// pullPlaceholder: false,
	 	placeholderClass: 'sortable-placeholder',
	 	placeholder: '<li class="sortable-placeholder"></li>',
		onDragStart: function ($item, container, _super) {
		    // Duplicate items of the no drop area
		    if(!container.options.drop) 
		    	$item.clone().insertAfter($item);
		    	_super($item, container);
		},
		// isValidTarget: function ($item, container) {
		//   return true
		// }
		onDrop: function ($item, container, _super, event) {
			//var dataToSend = $("ul.simple_with_no_drag").sortable("serialize").get();
			$item.context.innerHTML = '<div class="selected-action-element row"><div class="col-xs-9">'+$item.context.innerHTML+'</div><div class="col-xs-3"><a href=""><span alt="чтение" title="чтение" class="glyphicon glyphicon-eye-open not-active" aria-hidden="true"></span></a>&nbsp;<a href=""><span alt="запись" title="запись" class="glyphicon glyphicon-floppy-save not-active" aria-hidden="true"></span></a>&nbsp;<a href=""><span alt="удалить" title="удалить" class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></div></div>'
			var v_f_shra = $item.context['parentElement']['id']
			var action_id = $item.context.id

			//check if already exist
			if(container.items.length > 0) {
				for(var i=0; i< container.items.length; i++) {
					if(container.items[i]['id'] == action_id) {
						console.log('isset as this');
						$item.remove()
					} else {
						//store in db
						$.ajax({
				        	type: "POST",
				        	dataType: 'json',
				        	url: "index.php?r=site/setpermissions",
				        	data: "v_f_shra="+v_f_shra+"&action_id="+action_id,
				        	success: function(data,status){
				        		
				        	}
				        });
					}
				}
			}

		  	$item.removeClass(container.group.options.draggedClass).removeAttr("style")
		  	$("body").removeClass(container.group.options.bodyClass)
		}
	});

	$("ul.simple_with_no_drag").sortable({
	  	group: 'no-drop',
	  	drag: false,
	  	nested: false,

	});

	//for states

	$("ol.permissions-states").sortable({
		group: 'no-drop-states',
		drop: false,
		nested: false,
		itemSelector: 'li',
	 	// pullPlaceholder: false,
	 	placeholderClass: 'sortable-placeholder',
	 	placeholder: '<li class="sortable-placeholder"></li>',
		onDragStart: function ($item, container, _super) {
		    // Duplicate items of the no drop area
		    if(!container.options.drop) 
		    	$item.clone().insertAfter($item);
		    	_super($item, container);
		},
		// isValidTarget: function ($item, container) {
		//   return true
		// }
		onDrop: function ($item, container, _super, event) {
			//var dataToSend = $("ul.simple_with_no_drag").sortable("serialize").get();
			$item.context.innerHTML = '<div class="selected-action-element row"><div class="col-xs-9">'+$item.context.innerHTML+'</div><div class="col-xs-3"><a href=""><span alt="чтение" title="чтение" class="glyphicon glyphicon-eye-open not-active" aria-hidden="true"></span></a>&nbsp;<a href=""><span alt="запись" title="запись" class="glyphicon glyphicon-floppy-save not-active" aria-hidden="true"></span></a>&nbsp;<a href=""><span alt="удалить" title="удалить" class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></div></div>'
			var v_f_shra = $item.context['parentElement']['id']
			var state_id = $item.context.id
			//console.log($item);
			//check if already exist
			if(container.items.length > 0) {
				for(var i=0; i< container.items.length; i++) {
					if(container.items[i]['id'] == state_id) {
						console.log('isset as this');
						$item.remove()
					} else {
						//store in db
						// $.ajax({
				  //       	type: "POST",
				  //       	dataType: 'json',
				  //       	url: "index.php?r=site/setpermissions",
				  //       	data: "v_f_shra="+v_f_shra+"&action_id="+action_id,
				  //       	success: function(data,status){
				        		
				  //       	}
				  //       });
					}
				}
			}

		  	$item.removeClass(container.group.options.draggedClass).removeAttr("style")
		  	$("body").removeClass(container.group.options.bodyClass)
		}
	});

	$("ul.simple_with_no_drag-states").sortable({
	  	group: 'no-drop-states',
	  	drag: false,
	  	nested: false,

	});



	// var selectedClass = 'highlight-action',
 //        clickDelay = 600,
 //        // click time (milliseconds)
 //        lastClick, diffClick; // timestamps


	// $("#draggable-actions li")
 //    // Script to deferentiate a click from a mousedown for drag event
 //    .bind('mousedown mouseup', function(e) {
 //        if (e.type == "mousedown") {
 //            lastClick = e.timeStamp; // get mousedown time
 //        } else {
 //            diffClick = e.timeStamp - lastClick;
 //            if (diffClick < clickDelay) {
 //                // add selected class to group draggable objects
 //                $(this).toggleClass(selectedClass);
 //            }
 //        }
 //    })
 //    .draggable({
 //        revertDuration: 10,
 //        // grouped items animate separately, so leave this number low
 //        containment: '#permissions-modal-body',
 //        start: function(e, ui) {
 //            ui.helper.addClass(selectedClass);
 //        },
 //        stop: function(e, ui) {
 //            // reset group positions
 //            $('.' + selectedClass).css({
 //                top: 0,
 //                left: 0
 //            });
 //        },
 //        drag: function(e, ui) {
 //            // set selected group position to main dragged object
 //            // this works because the position is relative to the starting position
 //            $('.' + selectedClass).css({
 //                top: ui.position.top,
 //                left: ui.position.left
 //            });
 //        }
 //    });

 //    $(".droppable-actions").droppable({
 //        drop: function(e, ui) {
 //            $(ui.draggable).clone().appendTo($(this)).removeClass(selectedClass).css({
 //                top: 0,
 //                left: 0
 //            });

 //        }
 //    });
	

	//for develop
	$("#permissions-modal").modal();

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

