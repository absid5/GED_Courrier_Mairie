function add_this_tags(action_script, ui_script)
{
	//if(res_id == '' || coll_id == '')
	//{
	//	if(console)
	//	{
	//		console.log('Error add_this_tag :: coll_id or res_id is empty');
	//	}
	//}
	//Allons chercher l'info du formulaire en l etat...
	var content = $('tag_userform').value;

	if(content.length < 50) {
		if(action_script)
		{

			new Ajax.Request(action_script,
			{
				method:'post',
				parameters:
				{
					p_input_value : content
					//p_res_id : res_id,
					//p_coll_id : coll_id
					
				},
			    onSuccess: function(answer){
				eval("response = "+answer.responseText);
					//alert(answer.responseText);
					if(response.status == 0 )
					{
						//load_tags(ui_script,res_id,coll_id)
						load_tags(ui_script)
					} else if (response.status == 1 ){
						//var span = document.getElementById('show_tags');
						//span.innerHTML ="<font color=\"red\">vous n avez pas les droits pour ajouter ce mot clé !</font>";
						//alert("<?php echo _ADD_TAG; ?>");
						if(response.value == 'fr'){
							alert("Vous devez utiliser les mots clés existants!");
						}else if(response.value == 'en'){
							alert("You must use the tags exists !");
						}
					} else
					{
						if(console)
						{
							console.log('Erreur Ajax');
						}
					}
				},
			    onFailure: function(){ alert('Something went wrong...'); }
			});
			
		}
		else
		{
			if(console)
			{
				console.log('Error delete_this_tag::no script defined');
			}
		}
	} else {
		alert("Le mot-clé doit être inférieur à 50 caractères");
	}

}


function delete_this_tag(action_script,tag_label, ui_script)
{
	if(tag_label == '')
	{
		if(console)
		{
			console.log('Error delete_this_tag :: tag_label');
		}
	}
	if(action_script)
	{	
		new Ajax.Request(action_script,
		{
			method:'post',
			parameters:
			{
				p_tag_label : tag_label
			},
		    onSuccess: function(answer){
			eval("response = "+answer.responseText);
				//alert(answer.responseText);
				if(response.status == 0 )
				{
					load_tags(ui_script)
				}
				else
				{
					if(console)
					{
						console.log('Erreur Ajax');
					}
				}
			},
		    onFailure: function(){ alert('Something went wrong...'); }
		});
		
	}
	else
	{
		if(console)
		{
			console.log('Error delete_this_tag::no script defined');
		}
	}
}

//Affiche l'ensemble des tags dans la div désirée
//function load_tags(path_script,res_id,coll_id)
function load_tags(path_script)
{
	if(path_script)
	{		
		new Ajax.Request(path_script,
		{
			method:'post',
			parameters:
			{
				p_res_id : '10'
			},
		    onSuccess: function(answer){
			eval("response = "+answer.responseText);
				if(response.status == 0 )
				{
					//On lance la fonction d'affichage des tags.
					var inner = response.value;
					var mydiv = $("tag_displayed");
					mydiv.innerHTML = inner;
					var myform = $('tag_userform');
					myform.value = "";
				}
				else
				{
					if(console)
					{
						console.log('Erreur Ajax');
					}
				}
			},
		    onFailure: function(){ alert('Something went wrong...'); }
		});
		
	}
	else
	{
		if(console)
		{
			console.log('Error delete_this_tag::no script defined');
		}
	}
}

/** Declaration of the autocompleter object used for the folders*/
var tag_autocompleter;

/**
 * Launch the Ajax autocomplete object to activate autocompletion on tag 
 *
 * @param path_script String Path to the Ajax script
 * @param mode String Mode : market or project
 **/
function launch_autocompleter_tags(path_script)
{

	var input =  'tag_userform';
	var div   =  'show_tags';


	if( path_script)
	{
		// Ajax autocompleter object creation
		 tag_autocompleter = new Ajax.Autocompleter(input, div, path_script, {
		 method:'get',
		 paramName:'Input',
		 minChars: 2
		 });
	}
	else
	{
		if(console)
		{
			console.log('error parameters launch_autocompleter_folder function');
		}
	}
}

function tag_fusion(search_tag, new_tag, path_script, result_text, header_location)
{
	if(search_tag == '' || new_tag == '' || path_script == '')
	{
		if(console)
		{
			console.log('fields empty :: tag_fusion');
		}
	}
	if(path_script)
	{	
		new Ajax.Request(path_script,
		{
			method:'post',
			parameters:
			{
				a_search_tag : search_tag,
				a_new_tag : new_tag
			},
		    onSuccess: function(answer){
			eval("response = "+answer.responseText);
				//alert(answer.responseText);
				if(response.status == 0 )
				{
					alert(result_text);
					header(header_location);
				}
				else
				{
					if(console)
					{
						console.log('Erreur Ajax');
					}
				}
			},
		    onFailure: function(){ alert('Something went wrong...'); }
		});
		
	}

}

