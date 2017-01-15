function checkBackDate(inputDate) {

  var dataCreationDate;
  var dateToCheck = inputDate.value;

  if($('dataCreationDate')) {
    dataCreationDate = $('dataCreationDate').value;
    var t = dataCreationDate.split(/[- :.]/);
    var tmpDate = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
    var d1_dataCreationDate = tmpDate.getTime();
  }

  if (dateToCheck != "") {
    var tmpDate = new Date();
    tmpDate.setFullYear(dateToCheck.substr(6,4));
    tmpDate.setMonth(dateToCheck.substr(3,2) - 1);
    tmpDate.setDate(dateToCheck.substr(0,2));
    tmpDate.setHours(0);
    tmpDate.setMinutes(0);
    var d2_dateToCheck = tmpDate.getTime();
  }

  if (dateToCheck != "" && d1_dataCreationDate > d2_dateToCheck) {
    alert("La date de retour doit être supérieure à la date du courrier");
    inputDate.value = "";
  }

}

function setRturnForEffectiveDate() {
  $('effectiveDateStatus').selectedIndex = 1;
}

function saveContactToSession(size, prePath) {

  setTimeout(function() {
    var contactId = $("transmissionContactidAttach" + size).value;
    var addressId = $("transmissionAddressidAttach" + size).value;
    console.log(contactId);
    console.log(addressId);

    if (contactId) {
      new Ajax.Request(prePath + "index.php?display=true&module=attachments&page=saveTransmissionContact",
        {
          method:'post',
          parameters: {
            size      : size,
            contactId : contactId,
            addressId : addressId
          }
        });
    }

  }, 500);
}

function displayTransmissionContactCard(mode, id, size)
{
  var contactCard = $(id);

  var contactId = $("transmissionContact_attach" + size).value;

  if(contactCard && (mode == 'hidden' || mode == 'visible') && contactId != '') {
    contactCard.style.visibility = mode;
  } else if (contactId == '') {
    contactCard.style.visibility = "hidden";
  }
}

function disableTransmissionButton(currentValue) {
  var size = $('transmission').childElementCount;

  for (var i = 1; i <= size; i++) {
    $('transmission').lastElementChild.remove();
  }

  var addButton = $('newTransmissionButton0');

  addButton.style.display = "";
  if (currentValue == "" || $("chrono_display").style.display == "none") {
    addButton.style.opacity = 0.5;
  } else {
    addButton.style.opacity = 1;
  }
}

function showOrButtonForAttachment() {
  if ($("edit").style.display != "none" && $("newTransmissionButton0").style.display != "none") {
    $("divOr0").style.display = "";
  } else {
    $("divOr0").style.display = "none";
  }
}

function showTransmissionEditButton(currentValue, editParagraph, size) {
  if (currentValue == "") {
    $(editParagraph).style.display = "none";
    $("divOr" + size).style.display = "none";
  } else {
    $(editParagraph).style.display = "";
    if ($("newTransmissionButton" + size).style.display != "none")
      $("divOr" + size).style.display = "";
  }
}

function hideEditAndAddButton(editParagraph) {
  $(editParagraph).style.display = "none";
}

function delLastTransmission() {

  var size = $('transmission').childElementCount;

  if ($('delTransmissionButton' + size).style.opacity == 1) {
    if (size >= 1) {
      $('transmission').lastElementChild.remove();

      $('newTransmissionButton' + (size - 1)).style.display = "";
      if (size > 1) {
        $('delTransmissionButton' + (size - 1)).style.display = "";
        if ($("paraEdit" + (size - 1)).style.display != "none")
          $("divOr" + (size - 1)).style.display = "";
      } else if (size == 1) {
        if ($("edit").style.display != "none")
          $("divOr0").style.display = "";
      }
    }
  }
}

function addNewTransmission(prePath, docId, canCreateContact, langString) {
  var size = $('transmission').childElementCount;

  if ($('newTransmissionButton' + size).style.opacity == 1) {

    var div = document.createElement('div');
    $('transmission').appendChild(div);
    size = $('transmission').childElementCount;
    //var lang = langString.split("#");

    $('newTransmissionButton' + (size - 1)).style.display = "none";
    $("divOr" + (size - 1)).style.display = "none";
    if (size > 1) {
      $('delTransmissionButton' + (size - 1)).style.display = "none";
    }

    div.className = "transmissionDiv";
    var content   = "<hr style='width:85%; height: 4px; margin-left:0px; margin-bottom:10px; margin-top: 10px'>" +
                    "<p>" +
                      "<label>" + "Type d'attachement" + "</label>" +
                      "<select name='transmissionType" + size + "' id='transmissionType" + size + "' />" +
                        "<option value='transmission'>Transmission</option>" +
                      "</select>" +
                      "&nbsp;<span class='red_asterisk'><i class='fa fa-star'></i></span>" +
                    "</p>" +
                    "<p>" +
                      "<label>" + "Numéro chrono" + "</label>" +
                      "<input type='text' name='DisTransmissionChrono" + size + "' id='DisTransmissionChrono" + size + "' disabled class='readonly' />" +
                      "<input type='hidden' name='transmissionChrono" + size + "' id='transmissionChrono" + size + "' />" +
                    "</p>" +
                    "<p>" +
                      "<label>" + "Fichier" + "</label>" +
                      "<select name='transmissionTemplate" + size + "' id='transmissionTemplate" + size + "' style='display:inline-block;' onchange='showTransmissionEditButton(this.options[this.selectedIndex].value, paraEdit" + size + ", " + size + ")'>" +
                      "</select>" +
                      "&nbsp;<span class='red_asterisk'><i class='fa fa-star'></i></span>" +
                    "</p>" +
                    "<p>" +
                      "<label>" + "Objet" + "</label>" +
                      "<input type='text' name='transmissionTitle" + size + "' id='transmissionTitle" + size + "' maxlength='250' value='' />" +
                      "&nbsp;<span class='red_asterisk'><i class='fa fa-star'></i></span>" +
                    "</p>" +
                    "<p>" +
                      "<label>" + "Date de retour attendue" + "</label>" +
                      "<input type='text' name='transmissionBackDate" + size + "' id='transmissionBackDate" + size + "' onClick='showCalender(this);' onfocus='checkBackDate(this)' value='' style='width: 75px' />" +
                      "<select name='transmissionExpectedDate" + size + "' id='transmissionExpectedDate" + size + "' style='margin-left: 20px;width: 105px' />" +
                        "<option value='EXP_RTURN'>Attente retour</option>" +
                        "<option value='NO_RTURN'>Pas de retour</option>" +
                      "</select>" +
                    "</p>";

                    if (canCreateContact) {
    content +=        "<label>" + "Destinataire" +
                        " <a href='#' title='" + "Ajouter un contact ou une adresse" + "' " +
                          "onclick='document.getElementById(\"contact_iframe_attach\").src=\"index.php?display=false&dir=my_contacts&page=create_contact_iframe&fromAttachmentContact=Y&transmissionInput="+size+"\";new Effect.toggle(\"create_contact_div_attach\", \"blind\", {delay:0.2});return false;' style='display:inline;' >" +
                          "<i class='fa fa-pencil fa-lg' title='" + "Ajouter un contact ou une adresse" + "'>" +
                          "</i>" +
                        "</a>" +
                      "</label>";
                    } else {
    content +=        "<label>Destinataire</label>";
                    }

    content +=        "<span style='position:relative'><input type='text' name='transmissionContact_attach" + size + "' id='transmissionContact_attach" + size + "' value='' " +
                        "onblur='displayTransmissionContactCard(\"visible\", \"transmissionContactCard" + size + "\", " + size + ");' " +
                        "onkeyup='erase_contact_external_id(\"transmissionContact_attach" + size + "\", \"transmissionContactidAttach" + size + "\");erase_contact_external_id(\"transmissionContact_attach" + size + "\", \"transmissionAddressidAttach" + size + "\");' />" +
                      "<a href='#' id='transmissionContactCard" + size + "' title='Fiche contact' onclick='document.getElementById(\"info_contact_iframe_attach\").src=\"" + prePath + "index.php?display=false&dir=my_contacts&page=info_contact_iframe&seeAllAddresses&contactid=\"+document.getElementById(\"transmissionContactidAttach"+size+"\").value+\"&addressid=\"+document.getElementById(\"transmissionAddressidAttach"+size+"\").value+\"\";new Effect.toggle(\"info_contact_div_attach\", \"blind\", {delay:0.2});return false;' style='visibility:hidden;'> " +
                        "<i class='fa fa-book fa-lg'></i>" +
                      "</a>" +
                    "<div id='transmission_show_contacts_attach" + size + "' class='autocomplete autocompleteIndex' style='display: none;width:100%;left:0px;'></div></span>" +

                    "<input type='hidden' id='transmissionContactidAttach" + size + "' name='transmissionContactidAttach" + size + "' value='' onchange='saveContactToSession(\"" + size + "\", \"" + prePath + "\")' />" +
                    "<input type='hidden' id='transmissionAddressidAttach" + size + "' name='transmissionAddressidAttach" + size + "' value='' />" +
                    "<p><div style='float: left;display: none;margin-bottom: 5px' id='paraEdit" + size + "'>" +
                      "<input type='button' style='margin-top: 0' value='" + "Editer" + "' name='transmissionEdit" + size + "' id='transmissionEdit" + size + "' class='button' " +
                        "onclick='window.open(\"" + prePath + "index.php?display=true&module=content_management&page=applet_popup_launcher&transmissionNumber=" + size + "&objectType=transmission&objectId=\"+$(\"transmissionTemplate" + size + "\").value+\"&attachType=transmission&objectTable=res_letterbox&contactId=\"+$(\"contactidAttach\").value+\"&addressId=\"+$(\"addressidAttach\").value+\"&chronoAttachment=\"+$(\"transmissionChrono" + size + "\").value+\"&titleAttachment=\"+$(\"transmissionTitle" + size + "\").value+\"&back_date=\"+$(\"transmissionBackDate" + size + "\").value+\"&resMaster=" + docId + "\", \"\", \"height=200, width=250,scrollbars=no,resizable=no,directories=no,toolbar=no\");" +
                        "hideEditAndAddButton(paraEdit" + size + ")' />" +
                      "<span style='display: none' id='divOr" + size + "'>" +
                        "&nbsp;ou&nbsp;" +
                      "</span>" +
                    "</div>" +
                    "<div style='float: left'>" +
                      "<i id='newTransmissionButton" + size + "' title='Nouvelle transmission' style='opacity: 1;cursor: pointer;' class='fa fa-plus-circle fa-2x' " +
                        "onclick='addNewTransmission(\"" + prePath + "\", " + docId + ", " + canCreateContact + ")'></i>" +
                      "&nbsp;" +
                      "<i id='delTransmissionButton" + size + "' title='Supprimer la dernière transmission' style='opacity: 1;cursor: pointer;' class='fa fa-minus-circle fa-2x' " +
                        "onclick='delLastTransmission()'></i>" +
                    "</div></p>";

    div.innerHTML = content;
    $('transmissionChrono' + size).value = $('chrono').value + "." + String.fromCharCode(64 + size);
    $('DisTransmissionChrono' + size).value = $('chrono').value + "." + String.fromCharCode(64 + size);
    $('transmissionTitle' + size).value = $('title').value;
    getTemplatesForSelect((prePath + "index.php?display=true&module=templates&page=select_templates"), "transmission", "transmissionTemplate" + size);
    launch_autocompleter_contacts_v2(prePath + "index.php?display=true&dir=indexing_searching&page=autocomplete_contacts", "transmissionContact_attach" + size, "transmission_show_contacts_attach" + size, "", "transmissionContactidAttach" + size, "transmissionAddressidAttach" + size)
  }
}

function getTemplatesForSelect(path_to_script, attachment_type, selectToChange)
{
  new Ajax.Request(path_to_script,
    {
      method:'post',
      parameters: {attachment_type: attachment_type},
      onSuccess: function(answer){
        $(selectToChange).innerHTML = answer.responseText;
      }
    });
}

function hide_index(mode_hide, display_val)
{
	var tr_link = $('attach_link_tr');
	var tr_title = $('attach_title_tr');
	var indexes = $('indexing_fields');
	var comp_index = $('comp_indexes');
	if(mode_hide == true)
	{
		if(tr_link && display_val)
		{
			Element.setStyle(tr_link, {display : display_val});
			Element.setStyle(tr_title, {display : display_val});
		}
		if(indexes)
		{
			Element.setStyle(indexes, {display : 'none'});
		}
		if(comp_index)
		{
			Element.setStyle(comp_index, {display : 'none'});
		}
		//show link and hide index
	}
	else
	{
		if(tr_link && display_val)
		{
			Element.setStyle(tr_link, {display : 'none'});
			Element.setStyle(tr_title, {display : 'none'});
		}
		if(indexes)
		{
			Element.setStyle(indexes, {display : display_val});

		}
		if(comp_index)
		{
			Element.setStyle(comp_index, {display : 'block'});
		}
		//hide link and show index
	}
}

function showAttachmentsForm(path, width, height) {
    
    if(typeof(width)==='undefined'){
        var width = '800';
    }
    
    if(typeof(height)==='undefined'){
        var height = '480';
    }  
    
    new Ajax.Request(path,
    {
        method:'post',
        parameters: { url : path
                    },  
        onSuccess: function(answer) {
            eval("response = "+answer.responseText);
           
            if(response.status == 0){
                var modal_content = convertToTextVisibleNewLine(response.content);
                createModal(modal_content, 'form_attachments', height, width, 'fullscreen'); 
            } else {
                window.top.$('main_error').innerHTML = response.error;
            }
        }
    });
}

function get_num_rep(res_id){
	trig_elements = document.getElementsByClassName('trig');
	for (i=0; i<trig_elements.length; i++){
		var id = trig_elements[i].id;
		var splitted_id = id.split("_");
		if (splitted_id.length == 3 && splitted_id[0] == 'ans' && splitted_id[2] == res_id) return splitted_id[1];
	}
	return 0;
}
function ValidAttachmentsForm (path, form_id) {

  console.log(Form.serialize(form_id));
    new Ajax.Request(path,
    {
        asynchronous:false,
        method:'post',
        parameters: Form.serialize(form_id),
        encoding: 'UTF-8',                       
        onSuccess: function(answer){
            eval("response = "+answer.responseText);
            if(response.status == 0){
                destroyModal('form_attachments');

            if ($('viewframe') != undefined) {
                var srcViewFrame = $('viewframe').src;
                $('viewframe').src = srcViewFrame;
            }
				if ($('cur_idAffich')) var num_rep = $('cur_idAffich').value;
				if ($('cur_resId')) var res_id_master = $('cur_resId').value;
				if (response.cur_id) var rep_id = response.cur_id;
				if (num_rep == 0) num_rep = get_num_rep(rep_id);
				
				if($('viewframevalidRep'+num_rep+'_'+rep_id)) {
					if (response.majFrameId > 0){
						$('viewframevalidRep'+num_rep+'_'+rep_id).src = "index.php?display=true&module=visa&page=view_pdf_attachement&res_id_master="+res_id_master+"&id="+response.majFrameId;	
						if ($('cur_rep')) $('cur_rep').value = response.majFrameId;
						$('viewframevalidRep'+num_rep+'_'+rep_id).id = 'viewframevalidRep'+num_rep+'_'+response.majFrameId;
					}
					else
						$('viewframevalidRep'+num_rep+'_'+rep_id).src = $('viewframevalidRep'+num_rep+'_'+rep_id).src;
				}

				if($('ans_'+num_rep+'_'+rep_id)) {
					$('ans_'+num_rep+'_'+rep_id).innerHTML = response.title;
					if (response.isVersion){
						$('ans_'+num_rep+'_'+rep_id).setAttribute('onclick','updateFunctionModifRep(\''+response.majFrameId+'\', '+num_rep+', '+response.isVersion+');');			
						$('ans_'+num_rep+'_'+rep_id).id = 'ans_'+num_rep+'_'+response.majFrameId;
					}
				}
				
				if ($('cur_idAffich')){
					console.log('test refresh');
					loadNewId('index.php?display=true&module=visa&page=update_visaPage',res_id_master, $('coll_id').value);
				}
                eval(response.exec_js);
                /*var height = (parseInt($('visa_left').style.height.replace(/px/,""))-65)+"px";
                $('visa_listDoc').style.height=height;
                $('visa_left').style.height=height;
                $('visa_right').style.height=height;
                $('tabricatorRight').style.height=(parseInt($('tabricatorRight').offsetHeight)-40)+"px";
                height = (parseInt($('tabricatorRight').offsetHeight)-150)+"px";
                $('list_attach').style.height=height;*/
            } else {
                alert(response.error);
            }
        }
    });
	
}

function modifyAttachmentsForm(path, width, height) {

    if(typeof(width)==='undefined'){
        var width = '800';
    }
    
    if(typeof(height)==='undefined'){
        var height = '480';
    }  

    new Ajax.Request(path,
    {
        method:'post',
        parameters: { url : path
                    },  
        onSuccess: function(answer) {
            eval("response = "+answer.responseText);
           
            if(response.status == 0){
                var modal_content = convertToTextVisibleNewLine(response.content);
                createModalinAttachmentList(modal_content, 'form_attachments', height, width, 'fullscreen'); 
            } else {
                window.top.$('main_error').innerHTML = response.error;
            }
        }
    });
}

function setFinalVersion(path) {  

var check = $('final').value;

    new Ajax.Request(path,
    {
        asynchronous:false,
        method:'post',
        encoding: 'UTF-8',                       
        onSuccess: function(answer){
            eval("response = "+answer.responseText);
            if(response.status == 0 || response.status == 1){
                eval(response.exec_js);
            } else {
                alert(response.error);
            }
        }
    });
}

function loadSelectedContact() {
    ContactAndAddress = $('selectContactIdRes').value;
    value = ContactAndAddress.split("#");  
    $('contactidAttach').value=value[0];
    $('addressidAttach').value=value[1];
    $('contact_attach').value=value[2];
    $('contact_attach').focus();
}

function createModalinAttachmentList(txt, id_mod, height, width, mode_frm){
    if(height == undefined || height=='') {
        height = '100px';
    }

    if(width == undefined || width=='') {
        width = '400px';
    }

    if( mode_frm == 'fullscreen') {
        width = (screen.availWidth)+'px';
        height = (screen.availHeight)+'px';
    }

    if(id_mod && id_mod!='') {
        id_layer = id_mod+'_layer';
    } else {
        id_mod = 'modal';
        id_layer = 'lb1-layer';
    }
    var tmp_width = width;
    var tmp_height = height;
    var layer_height = window.top.window.$('container').clientHeight;
    var layer_width = window.top.window.document.getElementsByTagName('html')[0].offsetWidth - 5;
    var layer = new Element('div', {'id':id_layer, 'class' : 'lb1-layer', 'style' : "display:block;filter:alpha(opacity=70);opacity:.70;z-index:"+get_z_indexes()['layer']+';width :'+ (layer_width)+"px;height:"+layer_height+'px;'});

    if( mode_frm == 'fullscreen') {
        var fenetre = new Element('div', {'id' :id_mod,'class' : 'modal', 'style' :'top:0px;left:0px;width:'+width+';height:'+height+";z-index:"+get_z_indexes()['modal']+";position:absolute;" });
    } else {
        var fenetre = new Element('div', {'id' :id_mod,'class' : 'modal', 'style' :'top:0px;left:0px;'+'width:'+width+';height:'+height+";z-index:"+get_z_indexes()['modal']+";margin-top:0px;margin-left:0px;position:absolute;" });
    }

    Element.insert(window.top.window.document.body,layer);
    Element.insert(window.top.window.document.body,fenetre);

    if( mode_frm == 'fullscreen') {
        navName = BrowserDetect.browser;
        if (navName == 'Explorer') {
            if (width == '1080px') {
                fenetre.style.width = (window.top.window.document.getElementsByTagName('html')[0].offsetWidth - 55)+"px";
            }
        } else {
            fenetre.style.width = (window.top.window.document.getElementsByTagName('html')[0].offsetWidth - 30)+"px";
        }
        fenetre.style.height = (window.top.window.document.getElementsByTagName('body')[0].offsetHeight - 20)+"px";
    }

    Element.update(fenetre,txt);
    Event.observe(layer, 'mousewheel', function(event){Event.stop(event);}.bindAsEventListener(), true);
    Event.observe(layer, 'DOMMouseScroll', function(event){Event.stop(event);}.bindAsEventListener(), false);
    window.top.window.$(id_mod).focus();
}

function setButtonStyle(radioButton, fileFormat, statusValidateButton) {
    if (radioButton == "yes" && fileFormat != "pdf" && statusValidateButton == "1") {
        $('edit').style.visibility="hidden";
    } else if (radioButton == "no" && fileFormat != "pdf") {
        $('edit').style.visibility="visible";
    }
}


function cleanTitle(str) {
    //permet de supprimer les # dans le titre qui bloque l'ouverture de l'applet java
    var res = str.replace(/#/g, " ");
    return(res);
}

