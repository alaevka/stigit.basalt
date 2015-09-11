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
		//console.log($(this).attr('id'));
		//get data and create modal
		$.ajax({
        	type: "POST",
        	dataType: 'json',
        	url: "index.php?r=site/getissuedata",
        	data: "id="+$(this).attr('id'),
        	success: function(data,status){
        		$("#myModalLabel-issue").html('Просмотр задания '+data.issue_designation);	
        		$("#issue-view-table").html(data.result_table);
        		$("#update-issue-button-new-tab").attr('href', 'index.php?r=site/updateissue&id='+data.issue_id);
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
	
		

});

function viewWhatSelectedInFilter(val, moment) {
	$("#"+moment).html('выбрано: '+val);
}

