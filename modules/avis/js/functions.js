function addRowAvis(id_tableau)
{

	var tableau = document.getElementById(id_tableau);	
	var ligne = tableau.insertRow(-1);//on a ajouté une ligne
	var position = ligne.rowIndex;

	if (position%2 == 1) ligne.className = "col";

	/*var colonne1 = ligne.insertCell(0);//on a une ajouté une cellule
	colonne1.innerHTML += position;//on y met la position
	*/
	var id_Cons = "avis_0";
	var last_select = tableau.rows.length-3;
	
	//var listeDeroulante = document.getElementById(id_Cons);
	var listeDeroulante = document.getElementById('avis_'+last_select);
	var colonne2 = ligne.insertCell(0);//on ajoute la seconde cellule
	var listOptions = "";

	listOptions=listeDeroulante.innerHTML;

	colonne2.innerHTML += "<span id='avis_rank_" + position + "'></span><select>"+listOptions+"</select><span id='lastAvis_" + position + "'></span>";
	//colonne2.innerHTML += "</select>";

	var colonne3 = ligne.insertCell(1);
	colonne3.innerHTML += "<a href=\"javascript://\"  id=\"avis_down_"+position+"\" name=\"avis_down_"+position+"\" onclick=\"deplacerLigneAvis(this.parentNode.parentNode.rowIndex, this.parentNode.parentNode.rowIndex+2, '"+id_tableau+"')\" style=\"visibility:hidden;\" ><i class=\"fa fa-arrow-down fa-2x\" title=\"Déplacer l'utilisateur vers le bas\"></i></a>";

	var colonne4 = ligne.insertCell(2);
	colonne4.innerHTML += "<a href=\"javascript://\" id=\"avis_up_"+position+"\" name=\"avis_up_"+position+"\" onclick=\"deplacerLigneAvis(this.parentNode.parentNode.rowIndex, this.parentNode.parentNode.rowIndex-1, '"+id_tableau+"')\" style=\"visibility:visible;\" ><i class=\"fa fa-arrow-up fa-2x\" title=\"Déplacer l'utilisateur vers le haut\"></i></a>";

	var colonne5 = ligne.insertCell(3);
	colonne5.innerHTML += "<a href=\"javascript://\" onclick=\"delRowAvis(this.parentNode.parentNode.rowIndex, '"+id_tableau+"')\" id=\"avis_suppr_"+position+"\" name=\"avis_suppr_"+position+"\" style=\"visibility:visible;\" ><i class=\"fa fa-user-times fa-2x\" title=\"Retirer l'utilisateur du circuit\"></i></a>";
	
	var colonne6 = ligne.insertCell(4);
	colonne6.innerHTML += "<a href=\"javascript://\" id=\"avis_add_"+position+"\" name=\"avis_add_"+position+"\" onclick=\"addRowAvis('"+id_tableau+"')\"style=\"visibility:visible;\" ><i class=\"fa fa-user-plus fa-2x\" title=\"Ajouter un utilisateur dans le circuit\"></i></a>";
	
	var colonne7 = ligne.insertCell(5);
	colonne7.innerHTML += "<input type=\"text\" id=\"avis_consigne_"+position+"\" name=\"avis_consigne_"+position+"\" value=\""+document.getElementById('avis_consigne_'+last_select).value+"\" style=\"width:95%;\"/>";
	
	var colonne8 = ligne.insertCell(6);
	colonne8.style.display = 'none';
	colonne8.innerHTML += "<input type=\"hidden\" id=\"avis_date_"+position+"\" name=\"avis_date_"+position+"\"/>";
	
	var colonne9 = ligne.insertCell(7);
	colonne9.style.display = 'none';
	colonne9.innerHTML += "<input type=\"checkbox\" id=\"avis_isSign_"+position+"\" name=\"avis_isSign_"+position+"\"/>";

	var colonne10 = ligne.insertCell(8);
	//colonne10.style.display = 'none';
	colonne10.innerHTML += '<i class="fa fa-plus fa-lg" title="Nouvel utilisateur ajouté"></i>';

	//document.getElementById('avis_consigne_'+last_select).value = "";

	refreshIconesAvis(id_tableau);
}

function addRowAvisPopup(id_tableau)
{

    var tableau = document.getElementById(id_tableau);  
    var ligne = tableau.insertRow(-1);//on a ajouté une ligne
    var position = ligne.rowIndex;

    if (position%2 == 1) ligne.className = "col";

    /*var colonne1 = ligne.insertCell(0);//on a une ajouté une cellule
    colonne1.innerHTML += position;//on y met la position
    */
    var id_Cons = "avisPopup_0";
	var last_select = tableau.rows.length-3;
	
	//var listeDeroulante = document.getElementById(id_Cons);
	var listeDeroulante = document.getElementById('avisPopup_'+last_select);
	var colonne2 = ligne.insertCell(0);//on ajoute la seconde cellule
	var listOptions = "";
    
    listOptions=listeDeroulante.innerHTML;

    colonne2.innerHTML += "<span id='avisPopup_rank_" + position + "'></span><select style='width:150px;' >"+listOptions+"</select><span id='lastAvisPopup_" + position + "'></span>";
    //colonne2.innerHTML += "</select>";

    var colonne3 = ligne.insertCell(1);
    colonne3.innerHTML += "<a href=\"javascript://\"  id=\"avisPopup_down_"+position+"\" name=\"avisPopup_down_"+position+"\" onclick=\"deplacerLigneAvisPopup(this.parentNode.parentNode.rowIndex, this.parentNode.parentNode.rowIndex+2, '"+id_tableau+"')\" style=\"visibility:hidden;\" ><i class=\"fa fa-arrow-down fa-2x\" title=\"Déplacer l'utilisateur vers le bas\"></i></a>";

    var colonne4 = ligne.insertCell(2);
    colonne4.innerHTML += "<a href=\"javascript://\" id=\"avisPopup_up_"+position+"\" name=\"avisPopup_up_"+position+"\" onclick=\"deplacerLigneAvisPopup(this.parentNode.parentNode.rowIndex, this.parentNode.parentNode.rowIndex-1, '"+id_tableau+"')\" style=\"visibility:visible;\" ><i class=\"fa fa-arrow-up fa-2x\" title=\"Déplacer l'utilisateur vers le haut\"></i></a>";

    var colonne5 = ligne.insertCell(3);
    colonne5.innerHTML += "<a href=\"javascript://\" onclick=\"delRowAvisPopup(this.parentNode.parentNode.rowIndex, '"+id_tableau+"')\" id=\"avisPopup_suppr_"+position+"\" name=\"avisPopup_suppr_"+position+"\" style=\"visibility:visible;\" ><i class=\"fa fa-user-times fa-2x\" title=\"Retirer l'utilisateur du circuit\"></i></a>";
    
    var colonne6 = ligne.insertCell(4);
    colonne6.innerHTML += "<a href=\"javascript://\" id=\"avisPopup_add_"+position+"\" name=\"avisPopup_add_"+position+"\" onclick=\"addRowAvisPopup('"+id_tableau+"')\"style=\"visibility:visible;\" ><i class=\"fa fa-user-plus fa-2x\" title=\"Ajouter un utilisateur dans le circuit\"></i></a>";
    
    var colonne7 = ligne.insertCell(5);
    colonne7.innerHTML += "<input type=\"text\" id=\"avisPopup_consigne_"+position+"\" name=\"avisPopup_consigne_"+position+"\" value=\""+document.getElementById('avisPopup_consigne_'+last_select).value+"\" style=\"width:95%;\"/>";
    
    var colonne8 = ligne.insertCell(6);
    colonne8.style.display = 'none';
    colonne8.innerHTML += "<input type=\"hidden\" id=\"avisPopup_date_"+position+"\" name=\"avisPopup_date_"+position+"\"/>";
    
    var colonne9 = ligne.insertCell(7);
    colonne9.style.display = 'none';
    colonne9.innerHTML += "<input type=\"checkbox\" id=\"avisPopup_isSign_"+position+"\" name=\"avisPopup_isSign_"+position+"\"/>";
    
    var colonne10 = ligne.insertCell(8);
	//colonne10.style.display = 'none';
	colonne10.innerHTML += '<i class="fa fa-plus fa-lg" title="Nouvel utilisateur ajouté"></i>';

	//document.getElementById('avisPopup_consigne_'+last_select).value = "";

    refreshIconesAvisPopup(id_tableau);
}

function refreshIconesAvis(id_tableau){
	var tableau = document.getElementById(id_tableau);
	
	var arrayLignes = tableau.rows; //l'array est stocké dans une variable
	var longueur = arrayLignes.length;//on peut donc appliquer la propriété length
	var i=1; //on définit un incrémenteur qui représentera la clé


	while(i<longueur)
	{
		var disabledLine = false;
		//Maj de la couleur de ligne
		if(i % 2 == 0)
		{
			arrayLignes[i].className = "";
		}
		else
		{
			arrayLignes[i].className = "col";
		}
		
		var num = i-1;
		
		if (arrayLignes[i].cells[0].childNodes[1].disabled == true)
			disabledLine = true;
		//MAJ id et name
		//arrayLignes[i].cells[0].innerHTML = i;
		arrayLignes[i].cells[0].childNodes[0].id = "avis_rank_" + num;
		arrayLignes[i].cells[0].childNodes[1].name = "avis_"+num;
		arrayLignes[i].cells[0].childNodes[1].id="avis_"+num;
		arrayLignes[i].cells[0].childNodes[2].id = "lastAvis_" + num;
		arrayLignes[i].cells[1].childNodes[0].name = "avis_down_"+num;
		arrayLignes[i].cells[1].childNodes[0].id="avis_down_"+num;
		document.getElementById("avis_down_"+num).setAttribute('onclick','deplacerLigneAvis(this.parentNode.parentNode.rowIndex, this.parentNode.parentNode.rowIndex+2, \''+id_tableau+'\');');
				
		arrayLignes[i].cells[2].childNodes[0].name = "avis_up_"+num;	arrayLignes[i].cells[2].childNodes[0].id="avis_up_"+num;
		document.getElementById("avis_up_"+num).setAttribute('onclick','deplacerLigneAvis(this.parentNode.parentNode.rowIndex, this.parentNode.parentNode.rowIndex-1, \''+id_tableau+'\');');
		
		arrayLignes[i].cells[3].childNodes[0].name = "avis_suppr_"+num;	arrayLignes[i].cells[3].childNodes[0].id="avis_suppr_"+num;
		arrayLignes[i].cells[4].childNodes[0].name = "avis_add_"+num;	arrayLignes[i].cells[4].childNodes[0].id="avis_add_"+num;
		arrayLignes[i].cells[5].childNodes[0].name = "avis_consigne_"+num;	arrayLignes[i].cells[5].childNodes[0].id="avis_consigne_"+num;
		arrayLignes[i].cells[6].childNodes[0].name = "avis_date_"+num;	arrayLignes[i].cells[6].childNodes[0].id="avis_date_"+num;
		arrayLignes[i].cells[7].childNodes[0].name = "avis_isSign_"+num;	arrayLignes[i].cells[7].childNodes[0].id="avis_isSign_"+num;

		document.getElementById("avis_rank_" + num).innerHTML = "<span class='nbResZero' style='font-weight:bold;opacity:0.5;'>"+i+"</span> ";
	
		if(longueur > 2){
			document.getElementById("avis_add_0").style.visibility="hidden";
			document.getElementById("avis_suppr_"+num).style.visibility="visible";

		}else{
			document.getElementById("avis_add_0").style.visibility="visible";
			document.getElementById("avis_suppr_"+num).style.visibility="hidden";

		}
		if (i > 1){
			document.getElementById("avis_up_"+num).style.visibility="visible";
		}else{
			document.getElementById("avis_up_"+num).style.visibility="hidden";
		}
		
		if (i != longueur-1){
			document.getElementById("avis_add_"+num).style.visibility="hidden";

			document.getElementById("avis_isSign_"+num).style.visibility="hidden";
			document.getElementById("avis_isSign_"+num).checked=false;
			document.getElementById("lastAvis_" + num).innerHTML = "";

			document.getElementById("avis_down_"+num).style.visibility="visible";
		}
		else {
			document.getElementById("avis_add_"+num).style.visibility="visible";
			document.getElementById("avis_isSign_"+num).style.visibility="hidden";
			document.getElementById("avis_isSign_"+num).checked=true;
			//document.getElementById("lastAvis_" + num).innerHTML = " <i title='Conseiller décideur' style='color : #fdd16c' class='fa fa-certificate fa-lg fa-fw'></i>";

			document.getElementById("avis_down_"+num).style.visibility="hidden";

		}
		
		/* Ajout des conditions pour les lignes disabled */
		if (disabledLine){
			document.getElementById("avis_suppr_"+num).style.visibility="hidden";
			document.getElementById("avis_down_"+num).style.visibility="hidden";
			document.getElementById("avis_up_"+num).style.visibility="hidden";
			document.getElementById("avis_isSign_"+num).style.visibility="hidden";
		}
		if (num > 0) {
			if (arrayLignes[i-1].cells[0].childNodes[1].disabled == true)
				document.getElementById("avis_up_"+num).style.visibility="hidden";
		}
		/*************************************************/
		i++;
	}
}

function refreshIconesAvisPopup(id_tableau){
    var tableau = document.getElementById(id_tableau);
    
    var arrayLignes = tableau.rows; //l'array est stocké dans une variable
    var longueur = arrayLignes.length;//on peut donc appliquer la propriété length
    var i=1; //on définit un incrémenteur qui représentera la clé


    while(i<longueur)
    {
        var disabledLine = false;
        //Maj de la couleur de ligne
        if(i % 2 == 0)
        {
            arrayLignes[i].className = "";
        }
        else
        {
            arrayLignes[i].className = "col";
        }
        
        var num = i-1;
        
        if (arrayLignes[i].cells[0].childNodes[1].disabled == true)
            disabledLine = true;
        //MAJ id et name
        //arrayLignes[i].cells[0].innerHTML = i;
        arrayLignes[i].cells[0].childNodes[0].id = "avisPopup_rank_" + num;
        arrayLignes[i].cells[0].childNodes[1].name = "avisPopup_"+num;
        arrayLignes[i].cells[0].childNodes[1].id="avisPopup_"+num;
        arrayLignes[i].cells[0].childNodes[2].id = "lastAvisPopup_" + num;
        arrayLignes[i].cells[1].childNodes[0].name = "avisPopup_down_"+num;
        arrayLignes[i].cells[1].childNodes[0].id="avisPopup_down_"+num;
        document.getElementById("avisPopup_down_"+num).setAttribute('onclick','deplacerLigneAvisPopup(this.parentNode.parentNode.rowIndex, this.parentNode.parentNode.rowIndex+2, \''+id_tableau+'\');');
                
        arrayLignes[i].cells[2].childNodes[0].name = "avisPopup_up_"+num;    arrayLignes[i].cells[2].childNodes[0].id="avisPopup_up_"+num;
        document.getElementById("avisPopup_up_"+num).setAttribute('onclick','deplacerLigneAvisPopup(this.parentNode.parentNode.rowIndex, this.parentNode.parentNode.rowIndex-1, \''+id_tableau+'\');');
        
        arrayLignes[i].cells[3].childNodes[0].name = "avisPopup_suppr_"+num; arrayLignes[i].cells[3].childNodes[0].id="avisPopup_suppr_"+num;
        arrayLignes[i].cells[4].childNodes[0].name = "avisPopup_add_"+num;   arrayLignes[i].cells[4].childNodes[0].id="avisPopup_add_"+num;
        arrayLignes[i].cells[5].childNodes[0].name = "avisPopup_consigne_"+num;  arrayLignes[i].cells[5].childNodes[0].id="avisPopup_consigne_"+num;
        arrayLignes[i].cells[6].childNodes[0].name = "avisPopup_date_"+num;  arrayLignes[i].cells[6].childNodes[0].id="avisPopup_date_"+num;
        arrayLignes[i].cells[7].childNodes[0].name = "avisPopup_isSign_"+num;    arrayLignes[i].cells[7].childNodes[0].id="avisPopup_isSign_"+num;

        document.getElementById("avisPopup_rank_" + num).innerHTML = "<span class='nbResZero' style='font-weight:bold;opacity:0.5;'>" + i + "</span> ";
    
        if(longueur > 2){
            document.getElementById("avisPopup_add_0").style.visibility="hidden";
            document.getElementById("avisPopup_suppr_"+num).style.visibility="visible";

        }else{
            document.getElementById("avisPopup_add_0").style.visibility="visible";
            document.getElementById("avisPopup_suppr_"+num).style.visibility="hidden";

        }
        if (i > 1){
            document.getElementById("avisPopup_up_"+num).style.visibility="visible";
        }else{
            document.getElementById("avisPopup_up_"+num).style.visibility="hidden";
        }
        
        if (i != longueur-1){
            document.getElementById("avisPopup_add_"+num).style.visibility="hidden";

            document.getElementById("avisPopup_isSign_"+num).style.visibility="hidden";
            document.getElementById("avisPopup_isSign_"+num).checked=false;
            document.getElementById("lastAvisPopup_" + num).innerHTML = "";

            document.getElementById("avisPopup_down_"+num).style.visibility="visible";
        }
        else {
            document.getElementById("avisPopup_add_"+num).style.visibility="visible";
            document.getElementById("avisPopup_isSign_"+num).style.visibility="hidden";
            document.getElementById("avisPopup_isSign_"+num).checked=true;
            //document.getElementById("lastAvisPopup_" + num).innerHTML = " <i title='Conseiller décideur' style='color : #fdd16c' class='fa fa-certificate fa-lg fa-fw'></i>";

            document.getElementById("avisPopup_down_"+num).style.visibility="hidden";

        }
        
        /* Ajout des conditions pour les lignes disabled */
        if (disabledLine){
            document.getElementById("avisPopup_suppr_"+num).style.visibility="hidden";
            document.getElementById("avisPopup_down_"+num).style.visibility="hidden";
            document.getElementById("avisPopup_up_"+num).style.visibility="hidden";
            document.getElementById("avisPopup_isSign_"+num).style.visibility="hidden";
        }
        if (num > 0) {
            if (arrayLignes[i-1].cells[0].childNodes[0].disabled == true)
                document.getElementById("avisPopup_up_"+num).style.visibility="hidden";
        }
        /*************************************************/
        i++;
    }
}

function delRowAvis(num, id_tableau){
	document.getElementById(id_tableau).deleteRow(num);
	
	refreshIconesAvis(id_tableau);
}

function delRowAvisPopup(num, id_tableau){
	document.getElementById(id_tableau).deleteRow(num);
	
	refreshIconesAvisPopup(id_tableau);
}

function deplacerLigneAvis(source, cible, id_tableau)
{

	var tableau = document.getElementById(id_tableau);
	//on initialise nos variables
	var ligne = tableau.rows[source];//on copie la ligne
	var nouvelle = tableau.insertRow(cible);//on insère la nouvelle ligne
	var cellules = ligne.cells;

	//on boucle pour pouvoir agir sur chaque cellule
	for(var i=0; i<cellules.length; i++)
	{
		nouvelle.insertCell(-1).innerHTML += cellules[i].innerHTML;//on copie chaque cellule de l'ancienne à la nouvelle ligne
		/*if (i == 6 && cellules[i].childNodes[0].value != ""){
			nouvelle.cells[5].childNodes[0].value = cellules[i].childNodes[0].value;
		}*/
		if (i == 0){
			nouvelle.cells[0].childNodes[1].selectedIndex = cellules[i].childNodes[1].selectedIndex;
		}

		if(i == 5){
			nouvelle.cells[5].childNodes[0].value = cellules[i].childNodes[0].value;
			//console.log(cellules[i].childNodes[0].value);
		}
		
		/*if (i > 6)
			nouvelle.cells[i].style.display = 'none';*/
		if (i == 7 || i == 6){
			nouvelle.cells[i].style.display = 'none';
		}
	}

	//on supprimer l'ancienne ligne
	tableau.deleteRow(ligne.rowIndex);//on met ligne.rowIndex et non pas source car le numéro d'index a pu changer
	refreshIconesAvis(id_tableau);
}

function deplacerLigneAvisPopup(source, cible, id_tableau)
{

    var tableau = document.getElementById(id_tableau);
	//on initialise nos variables
	var ligne = tableau.rows[source];//on copie la ligne
	var nouvelle = tableau.insertRow(cible);//on insère la nouvelle ligne
	var cellules = ligne.cells;

	//on boucle pour pouvoir agir sur chaque cellule
	for(var i=0; i<cellules.length; i++)
	{
		nouvelle.insertCell(-1).innerHTML += cellules[i].innerHTML;//on copie chaque cellule de l'ancienne à la nouvelle ligne
		/*if (i == 6 && cellules[i].childNodes[0].value != ""){
			nouvelle.cells[5].childNodes[0].value = cellules[i].childNodes[0].value;
		}
		if (i == 0){
			nouvelle.cells[0].childNodes[1].selectedIndex = cellules[i].childNodes[1].selectedIndex;
		}
		if (i > 6)
			nouvelle.cells[i].style.display = 'none';*/
		if (i == 7 || i == 6){
			nouvelle.cells[i].style.display = 'none';
		}
	}

	//on supprimer l'ancienne ligne
	tableau.deleteRow(ligne.rowIndex);//on met ligne.rowIndex et non pas source car le numéro d'index a pu changer
	refreshIconesAvisPopup(id_tableau);
}

function saveAvisModelPopup(id_tableau) {
	var tableau 		= document.getElementById(id_tableau);
	var title 			= $('titleModelAvisPopup').value;
	var id_list			= $('objectId_inputAvisPopup').value;

	var arrayLignes = tableau.rows; //l'array est stocké dans une variable
	var longueur 		= arrayLignes.length;//on peut donc appliquer la propriété length

	var conseillers = "";
	var consignes 	= "";
	var cons_empty 	= false;

	var i = 1;
	while(i < longueur)
	{
		var num = i - 1;
		if (document.getElementById("avisPopup_"+num).value == "" )
			cons_empty = true;
		conseillers	+= document.getElementById("avisPopup_"+num).value + "#";
		consignes		+= document.getElementById("avisPopup_consigne_"+num).value + "#";
		
		i++;
	}

	if (cons_empty){
		triggerFlashMsg('divErrorAvis', 'Sélectionner au moins un utilisateur');
	} else if (title == "") {
		triggerFlashMsg('divErrorAvis', 'Titre manquant');
	} else {
		new Ajax.Request("index.php?display=true&module=avis&page=getAvisModelByTitle",
			{

				method: 'GET',
				dataType:	'JSON',
				parameters: {
					title: title
				},
				onSuccess: function (responseValue) {
					var response = JSON.parse(responseValue.responseText);
					if (response.isWorkflowTitleFree) {

						new Ajax.Request("index.php?display=true&module=avis&page=saveAvisModel",
							{
								method:'POST',
								parameters: {
									title 			: title,
									id_list 		: id_list,
									consignes 	: consignes,
									conseillers	: conseillers
								},
								onSuccess: function(responseValue){
									var response = JSON.parse(responseValue.responseText);
									if (response.status == 1){
										triggerFlashMsg('divInfoAvis', 'Modèle sauvegardé');
										$('modalSaveAvisModelPopup').style.display = 'none';
									}
								}
							});

					} else {
						triggerFlashMsg('divErrorAvis', 'Titre déjà utilisé pour un modèle');
					}
				}
			});
	}

}

function saveAvisModel(id_tableau) {
	var tableau 		= document.getElementById(id_tableau);
	var title 			= $('titleModelAvis').value;
	var id_list			= $('objectId_inputAvis').value;

	var arrayLignes = tableau.rows; //l'array est stocké dans une variable
	var longueur 		= arrayLignes.length;//on peut donc appliquer la propriété length

	var conseillers = "";
	var consignes 	= "";
	var cons_empty 	= false;

	var i = 1;
	while(i < longueur)
	{

		var num = i - 1;
		if (document.getElementById("avis_"+num).value == "" )
			cons_empty = true;
		conseillers	+= document.getElementById("avis_"+num).value + "#";
		consignes		+= document.getElementById("avis_consigne_"+num).value + "#";
		
		i++;
	}


	if (cons_empty){
		triggerFlashMsg('divErrorAvis', 'Sélectionner au moins un utilisateur');
	} else if (title == "") {
		triggerFlashMsg('divErrorAvis', 'Titre manquant');
	} else {
		new Ajax.Request("index.php?display=true&module=avis&page=getAvisModelByTitle",
			{

				method: 'GET',
				dataType:	'JSON',
				parameters: {
					title: title
				},
				onSuccess: function (responseValue) {
					var response = JSON.parse(responseValue.responseText);
					if (response.isWorkflowTitleFree) {

						new Ajax.Request("index.php?display=true&module=avis&page=saveAvisModel",
							{
								method:'POST',
								parameters: {
									title 			: title,
									id_list 		: id_list,
									consignes 	: consignes,
									conseillers	: conseillers
								},
								onSuccess: function(responseValue){
									var response = JSON.parse(responseValue.responseText);
									if (response.status == 1){
										triggerFlashMsg('divInfoAvis', 'Modèle sauvegardé');
										$('modalSaveAvisModel').style.display = 'none';
									}
								}
							});

					} else {
						triggerFlashMsg('divErrorAvis', 'Titre déjà utilisé pour un modèle');
					}
				}
			});
	}

}

function load_listmodel_avis(selectedOption, objectType, diff_list_id, save_auto) {
	if (save_auto == undefined || save_auto == '') {
		save_auto = false;
	}
	var div_id = diff_list_id || 'tab_avisSetWorkflow';
	
	var objectId = selectedOption.value || selectedOption;
	
	var diff_list_div = $(div_id);
	new Ajax.Request("index.php?display=true&module=avis&page=load_listmodel_avis",
		{
			method:'post',
			parameters: {
				objectType	: objectType,
				objectId		: objectId
			},
			onSuccess: function(answer){
				eval("response = "+answer.responseText);
				if(response.status == 0 ) {
					diff_list_div.innerHTML = response.div_content;
					if (save_auto){
						var res_id = $('values').value;
						var coll_id = $('coll_id').value;
						saveAvisWorkflow(res_id, coll_id, diff_list_id);
					}
				} else if (response.status != 1 ){
					diff_list_div.innerHTML = '';
					try{
						$('frm_error').innerHTML = response.error_txt;
					} catch(e){}
				}
			}
		}
	);
}

function load_listmodel_avisPopup(selectedOption, objectType, diff_list_id, save_auto) {
	if (save_auto == undefined || save_auto == '') {
		save_auto = false;
	}
	var div_id = diff_list_id || 'tab_avisSetWorkflow';
	
	var objectId = selectedOption.value || selectedOption;
	
	var diff_list_div = $(div_id);
	new Ajax.Request("index.php?display=true&module=avis&page=load_listmodel_avis_popup",
		{
			method:'post',
			parameters: {
				objectType	: objectType,
				objectId		: objectId
			},
			onSuccess: function(answer){
				eval("response = "+answer.responseText);
				if(response.status == 0 ) {
					diff_list_div.innerHTML = response.div_content;
					if (save_auto){
						var res_id = $('values').value;
						var coll_id = $('coll_id').value;
						saveAvisWorkflowPopup(res_id, coll_id, diff_list_id);
					}
				} else if (response.status != 1 ){
					diff_list_div.innerHTML = '';
					try{
						$('frm_error').innerHTML = response.error_txt;
					} catch(e){}
				}
			}
		}
	);
}

function saveAvisWorkflow(res_id, coll_id, id_tableau, fromDetail){
	var tableau = document.getElementById(id_tableau);
	
	var arrayLignes = tableau.rows; //l'array est stocké dans une variable
	var longueur = arrayLignes.length;//on peut donc appliquer la propriété length
	var i=1; //on définit un incrémenteur qui représentera la clé
	
	var conseillers = "";
	var consignes = "";
	var dates = "";
	var isSign = "";
	
	var cons_empty = false;

	var detail = "";

	if (fromDetail == undefined || fromDetail == "N" ) {
		detail = "N";
	} else if (fromDetail == "Y") {
		detail = "Y";
	}

	while(i<longueur)
	{
		
		var num = i-1;
		if (document.getElementById("avis_"+num).value == "" ) cons_empty = true;
		conseillers += document.getElementById("avis_"+num).value + "#";
		consignes += document.getElementById("avis_consigne_"+num).value + "#";
		dates += document.getElementById("avis_date_"+num).value + "#";
		if (document.getElementById("avis_isSign_"+num).checked == true) isSign += "1#";
		else isSign += "0#";
		
		
		i++;
	}

	/*if (cons_empty){
		$('divErrorAvis').innerHTML = 'Sélectionner au moins un utilisateur';
		$('divErrorAvis').style.display = 'table-cell';
		Element.hide.delay(5, 'divErrorAvis');
	}
	else*/
	new Ajax.Request("index.php?display=true&module=avis&page=saveAvisWF",
	{
		
			method:'post',
			parameters: { 
				res_id : res_id,
				coll_id : coll_id,
				conseillers : conseillers,
				consignes : consignes,
				dates : dates,
				list_sign : isSign,
				fromDetail : detail,
				cons_empty : cons_empty
			},
			onSuccess: function(answer){
				eval("response = "+answer.responseText);
				if (response.status == 1) {
					$('divInfoAvis').innerHTML = 'Mise à jour du circuit effectuée';
                    $('divInfoAvis').style.display = 'table-cell';
                    Element.hide.delay(5, 'divInfoAvis');
				} else if (response.status == 2){
					$('divErrorAvis').innerHTML = 'Sélectionner au moins un utilisateur';
					$('divErrorAvis').style.display = 'table-cell';
					Element.hide.delay(5, 'divErrorAvis');
				}

			}
	});
}

function saveAvisWorkflowPopup(res_id, coll_id, id_tableau, fromDetail){
	var tableau = document.getElementById(id_tableau);
	
	var arrayLignes = tableau.rows; //l'array est stocké dans une variable
	var longueur = arrayLignes.length;//on peut donc appliquer la propriété length
	var i=1; //on définit un incrémenteur qui représentera la clé
	
	var conseillers = "";
	var consignes = "";
	var dates = "";
	var isSign = "";
	
	var cons_empty = false;

	var detail = "";

	if (fromDetail == undefined || fromDetail == "N" ) {
		detail = "N";
	} else if (fromDetail == "Y") {
		detail = "Y";
	}

	while(i<longueur)
	{
		
		var num = i-1;
		if (document.getElementById("avisPopup_"+num).value == "" ) cons_empty = true;
		conseillers += document.getElementById("avisPopup_"+num).value + "#";
		consignes += document.getElementById("avisPopup_consigne_"+num).value + "#";
		dates += document.getElementById("avisPopup_date_"+num).value + "#";
		if (document.getElementById("avisPopup_isSign_"+num).checked == true) isSign += "1#";
		else isSign += "0#";
		
		
		i++;
	}

	/*if (cons_empty){
		$('divErrorAvis').innerHTML = 'Sélectionner au moins un utilisateur';
		$('divErrorAvis').style.display = 'table-cell';
		Element.hide.delay(5, 'divErrorAvis');
	}
	else*/
	new Ajax.Request("index.php?display=true&module=avis&page=saveAvisWF",
	{
		
			method:'post',
			parameters: { 
				res_id : res_id,
				coll_id : coll_id,
				conseillers : conseillers,
				consignes : consignes,
				dates : dates,
				list_sign : isSign,
				fromDetail : detail,
				cons_empty : cons_empty
			},
			onSuccess: function(answer){
				eval("response = "+answer.responseText);
				if (response.status == 1) {
					$('divInfoAvis').innerHTML = 'Mise à jour du circuit effectuée';
                    $('divInfoAvis').style.display = 'table-cell';
                    Element.hide.delay(5, 'divInfoAvis');
				} else if (response.status == 2){
					$('divErrorAvis').innerHTML = 'Sélectionner au moins un utilisateur';
					$('divErrorAvis').style.display = 'table-cell';
					Element.hide.delay(5, 'divErrorAvis');
				}

			}
	});
}

function resetAvisWorkflow(res_id, coll_id, id_tableau, fromDetail){
	var tableau = document.getElementById(id_tableau);
	
	var arrayLignes = tableau.rows; //l'array est stocké dans une variable
	var longueur = arrayLignes.length;//on peut donc appliquer la propriété length
	var i=1; //on définit un incrémenteur qui représentera la clé
	
	var conseillers = "";
	var consignes = "";
	var dates = "";
	var isSign = "";
	
	var cons_empty = false;

	var detail = "";

	if (fromDetail == undefined || fromDetail == "N" ) {
		detail = "N";
	} else if (fromDetail == "Y") {
		detail = "Y";
	}

	while(i<longueur)
	{
		
		var num = i-1;
		if (document.getElementById("avis_"+num).value == "" ) cons_empty = true;
		conseillers += document.getElementById("avis_"+num).value + "#";
		consignes += document.getElementById("avis_consigne_"+num).value + "#";
		dates += document.getElementById("avis_date_"+num).value + "#";
		if (document.getElementById("avis_isSign_"+num).checked == true) isSign += "1#";
		else isSign += "0#";
		
		
		i++;
	}

	new Ajax.Request("index.php?display=true&module=avis&page=resetAvisWF",
	{
		
			method:'post',
			parameters: { 
				res_id : res_id,
				coll_id : coll_id,
				conseillers : conseillers,
				consignes : consignes,
				dates : dates,
				list_sign : isSign,
				fromDetail : detail,
				cons_empty : cons_empty
			},
			onSuccess: function(answer){
				eval("response = "+answer.responseText);
                    location.reload(); 
				}


	});
}

function resetAvisWorkflowPopup(res_id, coll_id, id_tableau, fromDetail){
	var tableau = document.getElementById(id_tableau);
	
	var arrayLignes = tableau.rows; //l'array est stocké dans une variable
	var longueur = arrayLignes.length;//on peut donc appliquer la propriété length
	var i=1; //on définit un incrémenteur qui représentera la clé
	
	var conseillers = "";
	var consignes = "";
	var dates = "";
	var isSign = "";
	
	var cons_empty = false;

	var detail = "";

	if (fromDetail == undefined || fromDetail == "N" ) {
		detail = "N";
	} else if (fromDetail == "Y") {
		detail = "Y";
	}

	while(i<longueur)
	{
		
		var num = i-1;
		if (document.getElementById("avisPopup_"+num).value == "" ) cons_empty = true;
		conseillers += document.getElementById("avisPopup_"+num).value + "#";
		consignes += document.getElementById("avisPopup_consigne_"+num).value + "#";
		dates += document.getElementById("avisPopup_date_"+num).value + "#";
		if (document.getElementById("avisPopup_isSign_"+num).checked == true) isSign += "1#";
		else isSign += "0#";
		
		
		i++;
	}

	new Ajax.Request("index.php?display=true&module=avis&page=resetAvisWF",
	{
		
			method:'post',
			parameters: { 
				res_id : res_id,
				coll_id : coll_id,
				conseillers : conseillers,
				consignes : consignes,
				dates : dates,
				list_sign : isSign,
				fromDetail : detail,
				cons_empty : cons_empty
			},
			onSuccess: function(answer){
				eval("response = "+answer.responseText);
                    location.reload(); 
				}


	});
}

function checkRealDateAvis() {

    var docDate;
    var processLimitDate;
    var avisLimitDate;
    
    var nowDate = new Date ();
    var date3 = new Date();
    
    var current_date = Date.now();
    


   /* if($('doc_date')) {
        docDate = $('doc_date').value;
		var date2 = new Date();
		date2.setFullYear(docDate.substr(6,4));
		date2.setMonth(docDate.substr(3,2));
		date2.setDate(docDate.substr(0,2));
		date2.setHours(0);
		date2.setMinutes(0);
		var d2_docDate=date2.getTime();
    } */

    if($('process_limit_date')) {
        processLimitDate = $('process_limit_date').value;
		var date4 = new Date();
		date4.setFullYear(processLimitDate.substr(6,4));
		date4.setMonth(processLimitDate.substr(3,2)-1);
		date4.setDate(processLimitDate.substr(0,2));
		date4.setHours(0);
		date4.setMinutes(0);
                date4.setSeconds(0);
		var d4_processLimitDate=date4.getTime();
    }


    if($('recommendation_limit_date')) {
        avisLimitDate = $('recommendation_limit_date_tr').value;
		var date5 = new Date();
		date5.setFullYear(avisLimitDate.substr(6,4));
		date5.setMonth(avisLimitDate.substr(3,2)-1);
		date5.setDate(avisLimitDate.substr(0,2));
		date5.setHours(0);
		date5.setMinutes(0);
		date5.setSeconds(0);
		var d5_avisLimitDate;
		var d5_avisLimitDate=date5.getTime();
	 }

    if(d4_processLimitDate != "" && avisLimitDate != "" && d5_avisLimitDate > d4_processLimitDate) {          
        alert("La date limite d'avis doit être antérieure à la date limite du courrier ");
        $('recommendation_limit_date').value = "";
        $('recommendation_limit_date_tr').value = "";

    }
	
    if (current_date > d5_avisLimitDate && avisLimitDate != "") {        
	alert("La date d'avis doit être supérieure à la date du jour ");
        $('recommendation_limit_date').value = "";
        $('recommendation_limit_date_tr').value = "";
        
    }
}
