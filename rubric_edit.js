	$( document ).ready(function() {
		$.ajax({
			url: "project_get_list.php",
			cache: false,
			success: function(html){
				$("#projects_list").append(html);
			}
		});
		var defaultValue = {update:false, task_id:0, project_id: 0};
		progstate = Storage.get('progstate') || defaultValue;
		
		/*
		$.ajax({
			url: "rubric_get_list.php",
			cache: false,
			success: function(html){
				$("#rubank").append(html);
			}
		});
		*/
		/************************/
        /* Add dynamic controls */
		/************************/
        var iCnt = 0;

        $('#btAdd').click(function() {
			if(iCnt > 0){
				removeAll();
			}
        
			var taskDesc = $("#taskCnt").val();
			var rubCnt = $("#rubCnt").val();
			console.log("Rubrics to be added = " + rubCnt);
			/* Add dynamic controls */
			var row = $("#ta_container").append("div").addClass("row");
			$(row).attr('id', 'rubrow');

			for(var i = 0; i < rubCnt; i++){
				iCnt = iCnt + 1;
				var col = $(document.createElement('div'));
				$(col).attr('id', 'rub' + iCnt);
				col.addClass("col-xs-4 col-md-2 col-lg-2 margin-left-md rubricon");
				$(col).append('h3').text("Rubric Level Description " + parseInt(rubCnt - i));
				$(col).append('<textarea class="form-control rubric" rows="3" id="ta' + parseInt(rubCnt - i) + '" />');
				$("#rubrow").after(col);
			}
		
			progstate.update = false;
			Storage.set('progstate', progstate);			
        });

        $('#btRemove').click(function() {   // REMOVE ELEMENTS ONE PER CLICK.
            if (iCnt !== 0) { 
              $('#rub' + iCnt).remove(); 
              iCnt = iCnt - 1; 
            }
        });

        $('#btRemoveAll').click(function() {    // REMOVE ALL THE ELEMENTS IN THE CONTAINER.
          removeAll();
        });
        
		$('document').on('click', '.rubric', function(){
			// do something
			alert("Hurrah!");
		});
    });

	function getProjectData(projId){
		if(projId > 0){
			//get the persistence object
			var progstate = Storage.get("progstate");
			//update a value on object
			progstate.project_id = projId;
			//save object back to persistence
			Storage.set('progstate', progstate);			

			console.log("In getProjectData(" + projId + ")");
			//Remove any existing rubric information
			$("#projrubes").empty();
			$("#ta_container").empty();
			$("#taskDesc").empty();
			$.ajax({
				type: "POST",
				url: "project_get_detail.php",
				cache: false,
				data: {'ID': projId},
				dataType: "xml",
				success: function(xml) {
					//console.log(xml);
					$(xml).find('projects').each(function(){
						//var nName = this.nodeName;
						//alert("Node Name = " + nName);
						var proj = $(this).find("project").text();
						$('#project').val(proj);
						var proj_details = $(this).find("project_details").text();
						$('#p_details').val(proj_details);
					});
					console.log("Calling 'getRubricsForProject'...")
					getRubricsForProject(projId);
				},
				error: function(xhr, textStatus, error){
					console.log(xhr.statusText);
					console.log(textStatus);
					console.log(error);
				}
			});
		} 
	}
	function getRubricsForProject(projId){
		console.log("In 'getRubricsForProject( "  + projId + ")'");
		console.log("\najax call to get_project_rubrics.php...");
		$.ajax({
			url: "get_project_rubrics.php",
			cache: false,
			type: 'POST',
			data:{project_id: projId},
			dataType: "text",
			success: function(html){
				//console.log("Returned HTML:\n " + html);
				$("#projrubes").append(html);
			},
			error: function(xhr, textStatus, error){
				displayMessage("Error loading rubric data: " + error ); 
			}
		});
	}
/*
	$.ajax({
			type: "POST",
			url: "project_get_detail.php",
			cache: false,
			data: {'ID': projId},
			dataType: "xml",
			success: function(xml) {
				console.log(xml);
				$(xml).find('projects').each(function(){
					//var nName = this.nodeName;
					//alert("Node Name = " + nName);
					var proj = $(this).find("project").text();
					$('#project').val(proj);
					var proj_details = $(this).find("project_details").text();
					$('#p_details').val(proj_details);
				});
				getRubricsForProject(projId);
			},
			error: function(xhr, textStatus, error){
				console.log(xhr.statusText);
				console.log(textStatus);
				console.log(error);
			}
		});
	}
*/	
	
	function getTask(taskId, taskDesc){
		//returns the task and rubric information for editing
		progstate.task_id = taskId;
		Storage.set('progstate', progstate);
		//Add the task description
		$('#taskDesc').val(taskDesc);
		
		$.ajax({
			url: "get_task_details.php",
			cache: false,
			type: 'POST',
			data:{task_id: taskId},
			dataType: "text",
			success: function(html){
				//console.log("Returned HTML:\n " + html);
				iCnt = 5;
				$("#ta_container").empty();
				$("#ta_container").append(html);
				progstate = Storage.get("progstate");
				progstate.update = true;
				Storage.set('progstate', progstate);				
			},
			error: function(xhr, textStatus, error){
				displayMessage("Error loading rubric data: " + error ); 
			}
		});	
	}	
	
	function saveRubricData(projId){
		//Get values from added textareas
		var numItems = $('.rubric').length;
		var rStr = "";
		//Save task text first and get the task id
		var taskDesc = $('#taskDesc').val();
		console.log("\nIn saveRubricData: Project Id = " + projId + "Task Desc: " + taskDesc);
		console.log("calling _addRubricDataForTask...");
		progstate = Storage.get("progstate");
		if(progstate.update){
			progstate = Storage.get("progstate");
			taskId = progstate.task_id;
			console.log("Progstate.task_id = " + progstate.task_id);			
			_updateRubricDataForTask(projId, taskId)
		} else {
			_addRubricDataForTask(projId, taskDesc);			
		}
	}
	
	function _addRubricDataForTask(projId, taskDesc){
		console.log("in _addRubricDataForTask");
		var numItems = $('.rubric').length;
		
		$.ajax({
			url: "task_add.php",
			cache: false,
			type: 'POST',
			data:{task_desc: taskDesc, project_id: projId},
			success: function(resp) {
				
				if(resp.indexOf("error") > -1){
					msg = resp.replace("error","");
					alert("Error saving task description: " . msg);					
				} else {
					console.log("Task description saved. Saving Rubric items...");
					for(var i = numItems; i > 0; i--){
						var rText = $('#ta' + parseInt(i)).val();
						var rLevel = parseInt(i);
						$.ajax({
							url: "rubric_add.php",
							cache: false,
							type: 'POST',
							data:{project_id: projId, task_id: resp, r_level: rLevel, r_text: rText},
							success: function(resp) {
								if(resp.indexOf("error") > -1){
									msg = resp.replace("error","");																						
								} else {
									console.log("Rubric " + i + " saved: " + rText);
									msg = "Project Rubric details updated!";
								}
							},
							error: function(xhr, textStatus, error){
								displayMessage("Error saving data: " + error ); 
							}
						});
					}		
					
					msg = "Project Rubric details updated!"; // + resp;
					console.log(msg);
					displayMessage(msg);
					console.log("Removing all added elements");
					removeAll();
					console.log("getting project data for display");
					getProjectData(projId);
				}
			},
			error: function(xhr, textStatus, error){
				displayMessage("Error saving data: " + error ); 
			}
		});			  
	}
	
	function _updateRubricDataForTask(projId, taskId){
		console.log("in _updateRubricDataForTask");
		var numItems = $('.rubric').length;

		for(var i = numItems; i > 0; i--){
			//for(var i = 1; i < numItems + 1; i++){
			var rText = $('#ta' + parseInt(i)).val();
			var rLevel = parseInt(i);
			console.log("Progstate.task_id = " + progstate.task_id);						
			$.ajax({
				url: "rubric_edit.php",
				cache: false,
				type: 'POST',
				data:{project_id: projId, task_id: taskId, r_level: rLevel, r_text: rText},
				success: function(resp) {
					if(resp.indexOf("error") > -1){
						msg = resp.replace("error","");																						
					} else {
						msg = "Project Rubric details updated!";
					}
				},
				error: function(xhr, textStatus, error){
					displayMessage("Error saving data: " + error ); 
				}
			});
		}
		msg = "Project Rubric details updated!"; // + resp;
		console.log(msg);
		displayMessage(msg);
		console.log("Removing all added elements");
		removeAll();
		console.log("getting project data for display");
		getProjectData(projId);
	}
	
	function displayMessage(msg){
		if (typeof msg != 'undefined'){
			BootstrapDialog.show({
				title:"Rubricon",
				size: BootstrapDialog.SIZE_NORMAL,
				cssClass: 'login-dialog',
				message: msg,
				draggable: true,
				buttons: [{
					label: 'Close',
					action: function(dialogItself){
						dialogItself.close();
					}
				}]
			});
		}
	}
	
	function removeAll(){
		console.log("In removeAll()");
		var numItems = $('.rubric').length;
		if(numItems>0){
			//$(".rubric").remove();
			$(".rubricon").detach();
		}
		console.log("Number of items to remove: " + numItems);
		/*
		while(numItems > 0){
		  $('#rub' + numItems).remove();
		  numItems = $('.rubric').length;
		  console.log("In while loop. Number of items to remove: " + numItems);
		} 
		*/
		iCnt = 0;
		console.log("all items removed. Exit removeAll()");
	}

	var Storage = {
		set: function(key, value) {
			//console.log("Storage: key = " + key + "; value = " + value.toString());
			localStorage[key] = JSON.stringify(value);
		},
		get: function(key) {
			return localStorage[key] ? JSON.parse(localStorage[key]) : null;
		}
	};
	//Version 2.0 Add a simple ORM representation of the Project/Task/Rubric hierarchy
	var Project = {	 
		project_id : 0, 		
		task : {
			task_id : 0,     
			update: false,
			rubrics:[]
		}
	};
	var Rubric = {
		id:0,
		level:0,
		text:""
	};
