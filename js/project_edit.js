
			$(document).ready(function() {
				//bind the onblur on inputs so the values are restored
				$( "input" ).blur(function() {
					//alert( "Handler for .blur() called." + this.name );
					if(this.value==''){
						this.value = this.placeholder;
					}
				});
				$.ajax({
					url: "project_get_list.php",
					cache: false,
					success: function(html){
						$("#projects_list").append(html);
					}
				});				
			});

			function getProjectData(projId){
				if(projId > 0){
					//alert(projId);
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
								var proj_details = $(this).find("project_details").text()
								$('#p_details').val(proj_details);
							});
						},
						error: function(xhr, textStatus, error){
							console.log(xhr.statusText);
							console.log(textStatus);
							console.log(error);
						}
					});
				} 
			}
			function deleteProjectData(projId){
				if(projId == null || projId == 0 ){
					return false;
				}
				var answer = confirm("Are you sure? (OK confirms your choice)")
				if (answer){//'OK' selected
					$.ajax({
						url: "project_delete.php",
						cache: false,
						type: 'POST',
						data: {'projectId': projId},
						success: function() {
							displayMessage("Project Deleted");	
							$.ajax({
								url: "",
								context: document.body,
								success: function(s,x){
									$(this).html(s);
								}
							});												
						},
						error: function(xhr, textStatus, error){
							displayMessage( "Error deleting data: " + error ); 
						}
					});
				}
			}
			function saveProjectData(projId){
				var str = $("form").serialize();
				console.log(str);
				$.ajax({
					url: "project_edit.php",
					cache: false,
					type: 'POST',
					data: str,
					success: function(resp) {
						if(resp.indexOf("error") > -1){
							msg = resp.replace("error","");																						
						} else {
							msg = "Project details updated!";
						}
						displayMessage(msg);
						$.ajax({
							url: "",
							context: document.body,
							success: function(s,x){
								$(this).html(s);
							}
						});					
					},
					error: function(xhr, textStatus, error){
						displayMessage("Error saving data: " + error ); 
					}
				});							
			}
			
			function displayMessage(msg){
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
			
		