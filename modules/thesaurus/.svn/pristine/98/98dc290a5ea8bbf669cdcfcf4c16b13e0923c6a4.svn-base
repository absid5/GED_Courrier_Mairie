function lauch_thesaurus_list(e){
	document.getElementById('return_previsualise_thes').style.background='rgb(255, 241, 143)';
	var path_to_script = "<?php echo $_SESSION['config']['businessappurl']; ?>index.php?display=true&page=get_thesaurus_parents&module=thesaurus";
	var content='<div style="text-align:center;margin-top:-10px;"><input id="close_thesaurus_tooltips" class="button" value="Fermer" name="close_thesaurus_tooltips" type="button" onClick="$(\'return_previsualise_thes\').style.display=\'none\';"></div>';
	content += '<br/><span style="position:relative;"><input type="text" value="" autocomplete="off" id="search_thes" name="search_thes" style="width:95%;"/>';
	content += '<input type="hidden" value="" id="thesaurus_id" name="thesaurus_id"/>';
	content += '<div id="show_thes" class="autocomplete" style="width: 100%;left: 0px;"></div><div class="autocomplete autocompleteIndex" id="searching_autocomplete" style="display: none;text-align:left;padding:5px;width: 100%;left: 0px;"><i class="fa fa-spinner fa-spin" aria-hidden="true"></i> chargement ...</div></span>';
	content += '<script>launch_autocompleter_contacts_v2(\'<?php echo $_SESSION['config']['businessappurl']; ?>index.php?display=true&module=thesaurus&page=autocomplete_thesaurus\', \'search_thes\', \'show_thes\', \'\', \'thesaurus_id\', \'thesaurus_id\');$(\'search_thes\').onblur=function(){var str = $(\'search_thes\').value;if(str.indexOf("(",-1)!=-1){str = str.split(\'\').reverse().join(\'\');index = str.indexOf("(",-1);str = str.substring(index+2, str.length);str = str.split(\'\').reverse().join(\'\');$(\'search_thes\').value = str;}};</script>';

	new Ajax.Request(path_to_script,
	{
		method:'post',
		 onSuccess: function(answer){
		 	var json = JSON.parse(answer.responseText);
		 	//console.log(json);
			//eval("response = "+answer.responseText);
			if(json){
				content += '<ul id="thesaurus_list">';
				for (var i = json.length - 1; i >= 0; i--) {
					content += '<li id="list_'+json[i].thesaurus_id+'" data-value="'+json[i].thesaurus_name+'"><i onclick="getChildThes(\''+json[i].thesaurus_id+'\',\'0\')" id="dev_list_'+json[i].thesaurus_id+'" class="fa fa-plus-square-o" aria-hidden="true" style="font-weight:bold;cursor:pointer;width:20px;text-align:center;padding:5px;"></i> <span onclick="add_thes(\''+json[i].thesaurus_id+'\',\''+addslashes(json[i].thesaurus_name)+'\')" id="list_'+json[i].thesaurus_id+'_span">'+json[i].thesaurus_name+'</span><li>';
				} 
				content += '</ul>';
			}else{
				content += '<p style="text-align:center;font-style:italic;padding:5px;color:grey;" >aucun terme</p>';		
			}
			content += '<style>#thesaurus_list span:hover{font-weight:bold;cursor:pointer;}</style>';
			toolTipThes(e, content);
		}
	});

}

function lauch_thesaurus_list_admin(e){
	var path_to_script = "<?php echo $_SESSION['config']['businessappurl']; ?>index.php?display=true&page=get_thesaurus_parents&module=thesaurus";
	var content='<div style="text-align:center;margin-top:-10px;"><input id="close_thesaurus_tooltips" class="button" value="Fermer" name="close_thesaurus_tooltips" type="button" onClick="$(\'return_previsualise_thes\').style.display=\'none\';"></div>';
	new Ajax.Request(path_to_script,
	{
		method:'post',
		 onSuccess: function(answer){
		 	var json = JSON.parse(answer.responseText);
		 	//console.log(json);
			//eval("response = "+answer.responseText);
			if(json){
				content += '<ul id="thesaurus_list">';
				for (var i = json.length - 1; i >= 0; i--) {
					content += '<li id="list_'+json[i].thesaurus_id+'" data-value="'+json[i].thesaurus_name+'"><i onclick="getChildThesAdmin(\''+json[i].thesaurus_id+'\',\'0\')" id="dev_list_'+json[i].thesaurus_id+'" class="fa fa-plus-square-o" aria-hidden="true" style="font-weight:bold;cursor:pointer;width:20px;text-align:center;padding:5px;"></i> <span onclick="add_thes_admin(\''+json[i].thesaurus_id+'\',\''+addslashes(json[i].thesaurus_name)+'\')" id="list_'+json[i].thesaurus_id+'_span">'+json[i].thesaurus_name+'</span><li>';
				} 
				content += '</ul>';
			}else{
				content += '<p style="text-align:center;font-style:italic;padding:5px;color:grey;" >aucun terme</p>';		
			}
			content += '<style>#thesaurus_list span:hover{font-weight:bold;cursor:pointer;}</style>';
			toolTipThes(e, content);
		}
	});

}

function lauch_thesaurus_list_admin_assoc(e){
	var path_to_script = "<?php echo $_SESSION['config']['businessappurl']; ?>index.php?display=true&page=get_thesaurus_parents&module=thesaurus";
	var content='<div style="text-align:center;margin-top:-10px;"><input id="close_thesaurus_tooltips" class="button" value="Fermer" name="close_thesaurus_tooltips" type="button" onClick="$(\'return_previsualise_thes\').style.display=\'none\';"></div>';
	new Ajax.Request(path_to_script,
	{
		method:'post',
		 onSuccess: function(answer){
		 	var json = JSON.parse(answer.responseText);
		 	//console.log(json);
			//eval("response = "+answer.responseText);
			if(json){
				content += '<ul id="thesaurus_list">';
				for (var i = json.length - 1; i >= 0; i--) {
					content += '<li id="list_'+json[i].thesaurus_id+'" data-value="'+json[i].thesaurus_name+'"><i onclick="getChildThesAdminAssoc(\''+json[i].thesaurus_id+'\',\'0\')" id="dev_list_'+json[i].thesaurus_id+'" class="fa fa-plus-square-o" aria-hidden="true" style="font-weight:bold;cursor:pointer;width:20px;text-align:center;padding:5px;"></i> <span onclick="add_thes_admin_assoc(\''+json[i].thesaurus_id+'\',\''+addslashes(json[i].thesaurus_name)+'\')" id="list_'+json[i].thesaurus_id+'_span">'+json[i].thesaurus_name+'</span><li>';
				} 
				content += '</ul>';
			}else{
				content += '<p style="text-align:center;font-style:italic;padding:5px;color:grey;" >aucun terme</p>';		
			}
			content += '<style>#thesaurus_list span:hover{font-weight:bold;cursor:pointer;}</style>';
			toolTipThes(e, content);
		}
	});

}

function add_thes(thesaurus_id,thesaurus_name){
	select = document.getElementById('thesaurus');
	var opt_array = [];
	//console.log(select.options.length);
	if(select.options.length == 0){
		select.options[select.options.length] = new Option (thesaurus_name, thesaurus_id);
		select.options[0].id  = 'thesaurus_'+thesaurus_id;
		select.options[0].selected = true;
	}else{
		for (var i=0; i < select.options.length; i++)
		{
			if(select.options[i].selected == true){
				opt_array.push(select.options[i].value);
			}else{
				select.removeChild(select.options[i]);
			}
			
		}
		
		if(opt_array.indexOf(thesaurus_id) == -1){
			select.options[select.options.length] = new Option (thesaurus_name, thesaurus_id);
			select.options[select.options.length-1].selected = true;
		}
	}
    
	Event.fire($("thesaurus"), "chosen:updated"); 
	$('return_previsualise_thes').style.display='none';
}

function add_thes_by_autocomplete(thesaurus_id){
	select = document.getElementById('thesaurus');
	var opt_array = [];

	var path_to_script = "<?php echo $_SESSION['config']['businessappurl']; ?>index.php?display=true&page=get_thesaurus_name&module=thesaurus";
	new Ajax.Request(path_to_script,
	{
		method:'post',
		parameters:
			{
				thesaurus_id : thesaurus_id
			},
		 onSuccess: function(answer){
		 	var json = JSON.parse(answer.responseText);
		 	//console.log(json.info);
			//eval("response = "+answer.responseText);
			if(json){
				thesaurus_name = json.info.thesaurus_name;
				if(select.options.length == 0){
					select.options[select.options.length] = new Option (thesaurus_name, thesaurus_id);
					select.options[0].id  = 'thesaurus_'+thesaurus_id;
					select.options[0].selected = true;
				}else{
					for (var i=0; i < select.options.length; i++)
					{
						if(select.options[i].selected == true){
							opt_array.push(select.options[i].value);
						}else{
							select.removeChild(select.options[i]);
						}
						
					}
					
					if(opt_array.indexOf(thesaurus_id) == -1){
						select.options[select.options.length] = new Option (thesaurus_name, thesaurus_id);
						select.options[select.options.length-1].selected = true;
					}
				}
			    
				Event.fire($("thesaurus"), "chosen:updated"); 
				$('return_previsualise_thes').style.display='none';
			}
		}
	});
}

function add_thes_admin_assoc(thesaurus_id,thesaurus_name){
	select = document.getElementById('thesaurus_name_associate');
	var opt_array = [];
	//console.log(select.options.length);
	if(select.options.length == 0){
		select.options[select.options.length] = new Option (thesaurus_name, thesaurus_id);
		select.options[0].id  = 'thesaurus_'+thesaurus_id;
		select.options[0].selected = true;
	}else{
		for (var i=0; i < select.options.length; i++)
		{
			if(select.options[i].selected == true){
				opt_array.push(select.options[i].value);
			}else{
				select.removeChild(select.options[i]);
			}
			
		}
		
		if(opt_array.indexOf(thesaurus_id) == -1){
			select.options[select.options.length] = new Option (thesaurus_name, thesaurus_id);
			select.options[select.options.length-1].selected = true;
		}
	}
    
	Event.fire($("thesaurus_name_associate"), "chosen:updated"); 
	$('return_previsualise_thes').style.display='none';
}

function add_thes_admin(thesaurus_id,thesaurus_name){
	document.getElementById('thesaurus_parent_id').value=thesaurus_id;
	document.getElementById('thesaurus_parent_label').value=thesaurus_name;
	$('return_previsualise_thes').style.display='none';
	load_specific_thesaurus(thesaurus_id);
}



function getChildThes(thesaurus_id,level) {
	var indent ='';
	var path_to_script = "<?php echo $_SESSION['config']['businessappurl']; ?>index.php?display=true&page=get_thesaurus_childs&module=thesaurus";
	if(document.getElementById('dev_list_'+thesaurus_id).className == 'fa fa-plus-square-o'){
		for (var i = level; i >= 0; i--) {
			indent += '<i style="font-weight:bold;width:20px;text-align:center;display: inline-block;"></i>';
		}
		level++;
		new Ajax.Request(path_to_script,
		{
			method:'post',
			parameters:
			{
				thesaurus_id : thesaurus_id
			},
			 onSuccess: function(answer){
			 	var json = JSON.parse(answer.responseText);
			 	//console.log(json);
			 	if(json){
			 		document.getElementById('list_'+thesaurus_id+'_span').style.fontWeight = "bold";
			 		document.getElementById('list_'+thesaurus_id+'_span').style.color = "rgb(22, 173, 235)";
			 		document.getElementById('dev_list_'+thesaurus_id).style.color = "rgb(22, 173, 235)";
			 		document.getElementById('dev_list_'+thesaurus_id).className = 'fa fa-minus-square-o';
					for (var i = json.length - 1; i >= 0; i--) {
						if(json[i].total != 0){
							document.getElementById('list_'+thesaurus_id).innerHTML += '<li id="list_'+json[i].thesaurus_id+'" data-value="'+json[i].thesaurus_name+'">'+indent+'<i onclick="getChildThes(\''+json[i].thesaurus_id+'\',\''+level+'\')" id="dev_list_'+json[i].thesaurus_id+'" class="fa fa-plus-square-o" aria-hidden="true" style="font-weight:bold;cursor:pointer;width:20px;text-align:center;padding:5px;"></i><span onclick="add_thes(\''+json[i].thesaurus_id+'\',\''+addslashes(json[i].thesaurus_name)+'\')" id="list_'+json[i].thesaurus_id+'_span">'+json[i].thesaurus_name+'</span></li>';

						}else{
							document.getElementById('list_'+thesaurus_id).innerHTML += '<li id="list_'+json[i].thesaurus_id+'" data-value="'+json[i].thesaurus_name+'">'+indent+'<i id="dev_list_'+json[i].thesaurus_id+'" class="fa fa-plus-square-o" aria-hidden="true" style="opacity:0.5;font-weight:bold;cursor:pointer;width:20px;text-align:center;padding:5px;"></i><span onclick="add_thes(\''+json[i].thesaurus_id+'\',\''+addslashes(json[i].thesaurus_name)+'\')" id="list_'+json[i].thesaurus_id+'_span">'+json[i].thesaurus_name+'</span></li>';	
						}
						//console.log(json[i].thesaurus_name);
					} 
				}else{
					alert('Aucun éléments présent');
					document.getElementById('dev_list_'+thesaurus_id).className = 'fa fa-plus-square-o';
				}
				
			},
			onLoading: function(answer){
		 		document.getElementById('dev_list_'+thesaurus_id).className = 'fa fa-spinner fa-spin';
			
			},
		});
	}else{
		level--;
		for (var i = level; i >= 0; i--) {
			indent += '<i style="font-weight:bold;width:20px;text-align:center;display: inline-block;"></i>';
		}
		level++;
		document.getElementById('dev_list_'+thesaurus_id).className = 'fa fa-plus-square-o';
		//console.log(document.getElementById('list_'+thesaurus_id));
		document.getElementById('list_'+thesaurus_id).innerHTML = indent+'<i onclick="getChildThes(\''+thesaurus_id+'\',\''+level+'\')" id="dev_list_'+thesaurus_id+'" class="fa fa-plus-square-o" aria-hidden="true" style="font-weight:bold;cursor:pointer;width:20px;text-align:center;padding:5px;"></i><span onclick="add_thes(\''+thesaurus_id+'\',\''+addslashes(document.getElementById('list_'+thesaurus_id).getAttribute("data-value"))+'\')" id="list_'+thesaurus_id+'_span">'+document.getElementById('list_'+thesaurus_id).getAttribute("data-value")+'</span>';
	}
	

	
}

function getChildThesAdmin(thesaurus_id,level) {
	var indent ='';
	var path_to_script = "<?php echo $_SESSION['config']['businessappurl']; ?>index.php?display=true&page=get_thesaurus_childs&module=thesaurus";
	if(document.getElementById('dev_list_'+thesaurus_id).className == 'fa fa-plus-square-o'){
		for (var i = level; i >= 0; i--) {
			indent += '<i style="font-weight:bold;width:20px;text-align:center;display: inline-block;"></i>';
		}
		level++;
		new Ajax.Request(path_to_script,
		{
			method:'post',
			parameters:
			{
				thesaurus_id : thesaurus_id
			},
			 onSuccess: function(answer){
			 	var json = JSON.parse(answer.responseText);
			 	//console.log(json);
			 	if(json){
			 		document.getElementById('list_'+thesaurus_id+'_span').style.fontWeight = "bold";
			 		document.getElementById('list_'+thesaurus_id+'_span').style.color = "rgb(22, 173, 235)";
			 		document.getElementById('dev_list_'+thesaurus_id).style.color = "rgb(22, 173, 235)";
			 		document.getElementById('dev_list_'+thesaurus_id).className = 'fa fa-minus-square-o';
					for (var i = json.length - 1; i >= 0; i--) {
						if(json[i].total != 0){
							document.getElementById('list_'+thesaurus_id).innerHTML += '<li id="list_'+json[i].thesaurus_id+'" data-value="'+json[i].thesaurus_name+'">'+indent+'<i onclick="getChildThesAdmin(\''+json[i].thesaurus_id+'\',\''+level+'\')" id="dev_list_'+json[i].thesaurus_id+'" class="fa fa-plus-square-o" aria-hidden="true" style="font-weight:bold;cursor:pointer;width:20px;text-align:center;padding:5px;"></i><span onclick="add_thes_admin(\''+json[i].thesaurus_id+'\',\''+addslashes(json[i].thesaurus_name)+'\')" id="list_'+json[i].thesaurus_id+'_span">'+json[i].thesaurus_name+'</span></li>';

						}else{
							document.getElementById('list_'+thesaurus_id).innerHTML += '<li id="list_'+json[i].thesaurus_id+'" data-value="'+json[i].thesaurus_name+'">'+indent+'<i id="dev_list_'+json[i].thesaurus_id+'" class="fa fa-plus-square-o" aria-hidden="true" style="opacity:0.5;font-weight:bold;cursor:pointer;width:20px;text-align:center;padding:5px;"></i><span onclick="add_thes_admin(\''+json[i].thesaurus_id+'\',\''+addslashes(json[i].thesaurus_name)+'\')" id="list_'+json[i].thesaurus_id+'_span">'+json[i].thesaurus_name+'</span></li>';	
						}
						//console.log(json[i].thesaurus_name);
					} 
				}else{
					alert('Aucun éléments présent');
					document.getElementById('dev_list_'+thesaurus_id).className = 'fa fa-plus-square-o';
				}
				
			},
			onLoading: function(answer){
		 		document.getElementById('dev_list_'+thesaurus_id).className = 'fa fa-spinner fa-spin';
			
			},
		});
	}else{
		level--;
		for (var i = level; i >= 0; i--) {
			indent += '<i style="font-weight:bold;width:20px;text-align:center;display: inline-block;"></i>';
		}
		level++;
		document.getElementById('dev_list_'+thesaurus_id).className = 'fa fa-plus-square-o';
		//console.log(document.getElementById('list_'+thesaurus_id));
		document.getElementById('list_'+thesaurus_id).innerHTML = indent+'<i onclick="getChildThesAdmin(\''+thesaurus_id+'\',\''+level+'\')" id="dev_list_'+thesaurus_id+'" class="fa fa-plus-square-o" aria-hidden="true" style="font-weight:bold;cursor:pointer;width:20px;text-align:center;padding:5px;"></i><span onclick="add_thes_admin(\''+thesaurus_id+'\',\''+addslashes(document.getElementById('list_'+thesaurus_id).getAttribute("data-value"))+'\')" id="list_'+thesaurus_id+'_span">'+document.getElementById('list_'+thesaurus_id).getAttribute("data-value")+'</span>';
	}
	

	
}
function getChildThesAdminAssoc(thesaurus_id,level) {
	var indent ='';
	var path_to_script = "<?php echo $_SESSION['config']['businessappurl']; ?>index.php?display=true&page=get_thesaurus_childs&module=thesaurus";
	if(document.getElementById('dev_list_'+thesaurus_id).className == 'fa fa-plus-square-o'){
		for (var i = level; i >= 0; i--) {
			indent += '<i style="font-weight:bold;width:20px;text-align:center;display: inline-block;"></i>';
		}
		level++;
		new Ajax.Request(path_to_script,
		{
			method:'post',
			parameters:
			{
				thesaurus_id : thesaurus_id
			},
			 onSuccess: function(answer){
			 	var json = JSON.parse(answer.responseText);
			 	//console.log(json);
			 	if(json){
			 		document.getElementById('list_'+thesaurus_id+'_span').style.fontWeight = "bold";
			 		document.getElementById('list_'+thesaurus_id+'_span').style.color = "rgb(22, 173, 235)";
			 		document.getElementById('dev_list_'+thesaurus_id).style.color = "rgb(22, 173, 235)";
			 		document.getElementById('dev_list_'+thesaurus_id).className = 'fa fa-minus-square-o';
					for (var i = json.length - 1; i >= 0; i--) {
						if(json[i].total != 0){
							document.getElementById('list_'+thesaurus_id).innerHTML += '<li id="list_'+json[i].thesaurus_id+'" data-value="'+json[i].thesaurus_name+'">'+indent+'<i onclick="getChildThesAdminAssoc(\''+json[i].thesaurus_id+'\',\''+level+'\')" id="dev_list_'+json[i].thesaurus_id+'" class="fa fa-plus-square-o" aria-hidden="true" style="font-weight:bold;cursor:pointer;width:20px;text-align:center;padding:5px;"></i><span onclick="add_thes_admin_assoc(\''+json[i].thesaurus_id+'\',\''+addslashes(json[i].thesaurus_name)+'\')" id="list_'+json[i].thesaurus_id+'_span">'+json[i].thesaurus_name+'</span></li>';

						}else{
							document.getElementById('list_'+thesaurus_id).innerHTML += '<li id="list_'+json[i].thesaurus_id+'" data-value="'+json[i].thesaurus_name+'">'+indent+'<i id="dev_list_'+json[i].thesaurus_id+'" class="fa fa-plus-square-o" aria-hidden="true" style="opacity:0.5;font-weight:bold;cursor:pointer;width:20px;text-align:center;padding:5px;"></i><span onclick="add_thes_admin_assoc(\''+json[i].thesaurus_id+'\',\''+addslashes(json[i].thesaurus_name)+'\')" id="list_'+json[i].thesaurus_id+'_span">'+json[i].thesaurus_name+'</span></li>';	
						}
						//console.log(json[i].thesaurus_name);
					} 
				}else{
					alert('Aucun éléments présent');
					document.getElementById('dev_list_'+thesaurus_id).className = 'fa fa-plus-square-o';
				}
				
			},
			onLoading: function(answer){
		 		document.getElementById('dev_list_'+thesaurus_id).className = 'fa fa-spinner fa-spin';
			
			},
		});
	}else{
		level--;
		for (var i = level; i >= 0; i--) {
			indent += '<i style="font-weight:bold;width:20px;text-align:center;display: inline-block;"></i>';
		}
		level++;
		document.getElementById('dev_list_'+thesaurus_id).className = 'fa fa-plus-square-o';
		//console.log(document.getElementById('list_'+thesaurus_id));
		document.getElementById('list_'+thesaurus_id).innerHTML = indent+'<i onclick="getChildThesAdminAssoc(\''+thesaurus_id+'\',\''+level+'\')" id="dev_list_'+thesaurus_id+'" class="fa fa-plus-square-o" aria-hidden="true" style="font-weight:bold;cursor:pointer;width:20px;text-align:center;padding:5px;"></i><span onclick="add_thes_admin_assoc(\''+thesaurus_id+'\',\''+addslashes(document.getElementById('list_'+thesaurus_id).getAttribute("data-value"))+'\')" id="list_'+thesaurus_id+'_span">'+document.getElementById('list_'+thesaurus_id).getAttribute("data-value")+'</span>';
	}
	

	
}
function addslashes(ch) {
	ch = ch.replace(/\\/g,"\\\\")
	ch = ch.replace(/\'/g,"\\'")
	ch = ch.replace(/\"/g,"\\\"")
	return ch
}

function launch_thesaurus_tooltips(trigger, target,thesaurus_name) {
	document.getElementById('return_previsualise_thes').style.background='rgb(206, 233, 241)';
	var path_to_script = "<?php echo $_SESSION['config']['businessappurl']; ?>index.php?display=true&page=get_thesaurus_info&module=thesaurus";
	var content='<div style="text-align:center;margin-top:-10px;"><input id="close_thesaurus_tooltips" class="button" value="Fermer" name="close_thesaurus_tooltips" type="button" onClick="$(\'return_previsualise_thes\').style.display=\'none\';"></div>';
	new Ajax.Request(path_to_script,
	{
		method:'post',
		parameters:
		{
			thesaurus_name : thesaurus_name
		},
		 onSuccess: function(answer){
		 	var json = JSON.parse(answer.responseText);
		 	//console.log(json);

			//eval("response = "+answer.responseText);
			content += '<ul id="thesaurus_list">';
			if (json.info.thesaurus_parent_id) {
				level = 2;
				indent = '<i style="font-weight:bold;width:20px;text-align:center;display: inline-block;"></i>';
				content += '<li title="terme parent" style="font-weight:bold;color:rgb(22, 173, 235)"><i class="fa fa-minus-square-o" aria-hidden="true" style="font-weight:bold;width:20px;text-align:center;padding:5px;"></i> <span>'+json.info.thesaurus_parent_id+'</span><li>';
				content += '<li title="terme" style="font-weight:bold;color:rgb(22, 173, 235)"><i style="font-weight:bold;width:20px;text-align:center;display: inline-block;"></i><i class="fa fa-minus-square-o" aria-hidden="true" style="font-weight:bold;width:20px;text-align:center;padding:5px;"></i> <span>'+json.info.thesaurus_name+'</span><li>';
			}else{
				indent = '';
				level = 1;
				content += '<li title="terme" style="font-weight:bold;color:rgb(22, 173, 235)">'+indent+'<i class="fa fa-minus-square-o" aria-hidden="true" style="font-weight:bold;width:20px;text-align:center;padding:5px;"></i> <span>'+json.info.thesaurus_name+'</span><li>';
			}
			if(json.info.thesaurus_description != null){
				content += '<li style="font-style:italic;text-align:center;padding:10px;white-space:nowrap;" title="description">'+indent+'<i style="font-weight:bold;width:20px;text-align:center;display: inline-block;"></i><i style="font-weight:bold;width:20px;text-align:center;display: inline-block;"></i> <div style="border: 1px dashed;padding: 5px;white-space: normal;display: block;width:95%;">'+json.info.thesaurus_description+'</div><li>';
			}else{
				content += '<li style="font-style:italic;color:grey;text-align:center;padding:10px;" title="description">'+indent+'<i style="font-weight:bold;width:20px;text-align:center;display: inline-block;"></i><i style="font-weight:bold;width:20px;text-align:center;display: inline-block;"></i> <div style="border: 1px dashed;padding: 5px;white-space: normal;display: block;width:95%;">aucune description</div><li>';
			}
			if (json.info.used_for) {
				content += '<li style="text-align: center;font-style: italic;padding: 10px;color: rgb(0, 157, 197);margin-bottom: -10px;font-size: 10px;" title="'+json.info.used_for+'"><u>Utilisé pour</u> : '+json.info.used_for+'</li>';		
			}
			if(json.info.thesaurus_name_associate != null){
				content += '<li style="font-style:italic;text-align:center;padding:10px;" title="terme(s) associé(s)">'+indent+'<i style="font-weight:bold;width:20px;text-align:center;display: inline-block;"></i><i style="font-weight:bold;width:20px;text-align:center;display: inline-block;"></i> <div style="border: 1px dashed;padding: 5px;white-space: normal;display: block;width:95%;">'+json.info.thesaurus_name_associate+'</div><li>';

			}else{
				content += '<li style="font-style:italic;color:grey;text-align:center;padding:10px;" title="terme(s) associé(s)">'+indent+'<i style="font-weight:bold;width:20px;text-align:center;display: inline-block;"></i><i style="font-weight:bold;width:20px;text-align:center;display: inline-block;"></i> <div style="border: 1px dashed;padding: 5px;white-space: normal;display: block;width:95%;">aucun terme associé</div><li>';
			}

			if(json.info_children){
				for (var i = json.info_children.length - 1; i >= 0; i--) {
					if(json.info.thesaurus_name != json.info_children[i]){
						if(json.info_children[i].total != 0){
							content += '<li id="list_'+json.info_children[i].thesaurus_id+'" title="terme(s) spécique(s) à :  « '+json.info.thesaurus_name+' »" data-value="'+json.info_children[i].thesaurus_name+'">'+indent+'<i style="font-weight:bold;width:20px;text-align:center;display: inline-block;"></i><i id="dev_list_'+json.info_children[i].thesaurus_id+'" onclick="getChildThes(\''+json.info_children[i].thesaurus_id+'\',\''+level+'\')" class="fa fa-plus-square-o" aria-hidden="true" style="cursor:pointer;font-weight:bold;width:20px;text-align:center;padding:5px;"></i> <span id="list_'+json.info_children[i].thesaurus_id+'_span" onclick="add_thes(\''+json.info_children[i].thesaurus_id+'\', \''+addslashes(json.info_children[i].thesaurus_name)+'\');">'+json.info_children[i].thesaurus_name+'</span><li>';

						}else{
							content += '<li id="list_'+json.info_children[i].thesaurus_id+'" title="terme(s) spécique(s) à :  « '+json.info.thesaurus_name+' »" data-value="'+json.info_children[i].thesaurus_name+'">'+indent+'<i style="font-weight:bold;width:20px;text-align:center;display: inline-block;"></i><i id="dev_list_'+json.info_children[i].thesaurus_id+'" class="fa fa-plus-square-o" aria-hidden="true" style="opacity:0.5;cursor:pointer;font-weight:bold;width:20px;text-align:center;padding:5px;"></i> <span id="list_'+json.info_children[i].thesaurus_id+'_span" onclick="add_thes(\''+json.info_children[i].thesaurus_id+'\', \''+addslashes(json.info_children[i].thesaurus_name)+'\');">'+json.info_children[i].thesaurus_name+'</span><li>';
						}
					}
				} 
			}

			if(json.info_annexe){
				//console.log(json.info_annexe);
				opt_array = [];
				content_tab = [];
				for (var i = json.info_annexe.length - 1; i >= 0; i--) {
					opt_array.push(json.info_annexe[i].thesaurus_id);
					if(json.info.thesaurus_name != json.info_annexe[i]){
						if(json.info_annexe[i].total != 0){
							content_tab[json.info_annexe[i].thesaurus_id] = '<li id="list_'+json.info_annexe[i].thesaurus_id+'" title="autre(s) terme(s) lié(s) à :  « '+json.info.thesaurus_parent_id+' »" data-value="'+json.info_annexe[i].thesaurus_name+'" >'+indent+'<i id="dev_list_'+json.info_annexe[i].thesaurus_id+'" onclick="getChildThes(\''+json.info_annexe[i].thesaurus_id+'\',\'1\')" class="fa fa-plus-square-o" aria-hidden="true" style="cursor:pointer;font-weight:bold;width:20px;text-align:center;padding:5px;"></i> <span id="list_'+json.info_annexe[i].thesaurus_id+'_span" onclick="add_thes(\''+json.info_annexe[i].thesaurus_id+'\', \''+addslashes(json.info_annexe[i].thesaurus_name)+'\');">'+json.info_annexe[i].thesaurus_name+'</span><li>';

						}else{
							content_tab[json.info_annexe[i].thesaurus_id] = '<li id="list_'+json.info_annexe[i].thesaurus_id+'" title="autre(s) terme(s) lié(s) à :  « '+json.info.thesaurus_parent_id+' »" data-value="'+json.info_annexe[i].thesaurus_name+'" >'+indent+'<i id="dev_list_'+json.info_annexe[i].thesaurus_id+'" class="fa fa-plus-square-o" aria-hidden="true" style="opacity:0.5;cursor:pointer;font-weight:bold;width:20px;text-align:center;padding:5px;"></i> <span id="list_'+json.info_annexe[i].thesaurus_id+'_span" onclick="add_thes(\''+json.info_annexe[i].thesaurus_id+'\', \''+addslashes(json.info_annexe[i].thesaurus_name)+'\');">'+json.info_annexe[i].thesaurus_name+'</span><li>';
						}
					}
				}
				if(opt_array.indexOf(json.info.thesaurus_id) != -1){
					content_tab.splice(json.info.thesaurus_id, 1);
					content += content_tab.join("");
				}

			}

			content += '</ul>';
			content += '<style>#thesaurus_list span:hover{font-weight:bold;cursor:pointer;}</style>';
			document.getElementById("thesaurus_chosen_"+json.info.thesaurus_id).onclick=function(e){toolTipThes(e, content)}; 
			}
	});

}

function load_specific_thesaurus(thesaurus_id) {
	var path_to_script = "<?php echo $_SESSION['config']['businessappurl']; ?>index.php?display=true&page=get_thesaurus_childs&module=thesaurus";
	var content='';

	if(document.getElementById('thesaurus_parent_id').value != ""){
		new Ajax.Request(path_to_script,
		{
			method:'post',
			parameters:
			{
				thesaurus_id : thesaurus_id
			},
			 onSuccess: function(answer){
			 	thesaurus_parent_label=document.getElementById('thesaurus_parent_label').value;

			 	var json = JSON.parse(answer.responseText);
				//eval("response = "+answer.responseText);
				if(json){
					document.getElementById('thesaurus_name_specific').innerHTML = "Terme(s) spécifique(s) pour :<br/>« " + thesaurus_parent_label + " »";
					content += '<ul style="text-align:left;padding-left:20px;">';
					for (var i = json.length - 1; i >= 0; i--) {				
							content += '<li style="list-style-type:initial;padding:5px;" title="Accéder à la page du terme">';
							content += "<a href='<?php echo $_SESSION['config']['businessappurl']; ?>index.php?page=manage_thesaurus_list_controller&mode=up&module=thesaurus&id="+json[i].thesaurus_id+"&start=0&order=asc&order_field=&what='>"+json[i].thesaurus_name+"</a>";				
							content += '</li>';

					} 
					document.getElementById('thesaurus_list_specific_content').innerHTML = content;
					content += '</ul>';
				}else{
					document.getElementById('thesaurus_name_specific').innerHTML = "Terme(s) spécifique(s) pour :<br/>« " + thesaurus_parent_label + " »";
					content = '<i style="color:grey;">Aucun terme spécifique</i>';
					document.getElementById('thesaurus_list_specific_content').innerHTML = content;
				}
				
				}
		});
	}else{
		document.getElementById('thesaurus_name_specific').innerHTML = "Terme(s) spécifique(s) pour :<br/><i>Pas de terme générique</i>";
		content = '<i style="color:grey;">Aucun terme spécifique</i>';
		document.getElementById('thesaurus_list_specific_content').innerHTML = content;
	}
	
}

function toolTipThes(e, content){
    var DocRef;
    //console.log(e);
    if(e){
        mouseX = e.pageX;
        mouseY = e.pageY;
    }
    else{
        mouseX = event.clientX;
        mouseY = event.clientY;

        if( document.documentElement && document.documentElement.clientWidth) {
            DocRef = document.documentElement;
        } else {
            DocRef = document.body;
        }

        mouseX += DocRef.scrollLeft;
        mouseY += DocRef.scrollTop;
    }
    var topPosition  = mouseY + 10;
    var leftPosition = mouseX - 200;
    
    var writeHTML = content
    $('return_previsualise_thes').update(writeHTML);
    $('return_previsualise_thes').innerHTML;

    var divWidth = $('return_previsualise_thes').getWidth();
    if (divWidth > 0) {
        leftPosition = mouseX - (divWidth + 40);
    }
	if(leftPosition < 0){
		leftPosition = - leftPosition;
	}
    var divHeight = $('return_previsualise_thes').getHeight();
    if (divHeight > 0) {
        topPosition = mouseY - (divHeight - 2);
    }
    
    if (topPosition < 0) {
        topPosition = 10;
    }
    
    //var scrollY = (document.all ? document.scrollTop : window.pageYOffset);
    var scrollY = f_filterResults (
        window.pageYOffset ? window.pageYOffset : 0,
        document.documentElement ? document.documentElement.scrollTop : 0,
        document.body ? document.body.scrollTop : 0
    );
    
    if (topPosition < scrollY) {
        topPosition = scrollY + 10;
    }
    
    $('return_previsualise_thes').style.top='5px';
    $('return_previsualise_thes').style.left='5px';
    
    //$('return_previsualise_thes').style.maxWidth='600px';
    $('return_previsualise_thes').style.minWidth='400px';
    $('return_previsualise_thes').style.maxHeight='600px';
    $('return_previsualise_thes').style.display='block';
    //document.getElementById("thesaurus_chosen").onclick=function(e){$('return_previsualise_thes').style.display='none';};
    
}