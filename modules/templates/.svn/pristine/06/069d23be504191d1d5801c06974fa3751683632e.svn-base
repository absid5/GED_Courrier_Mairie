function show_templates(show)
{
    var div = $('templates_div');
    if (div != null) {
        if (show == true) {
            div.style.display = 'block';
        } else {
            div.style.display = 'none';
            var list = $('templates');
            list.selectedIndex = 0;
        }
    }
}

function show_special_form(elem_to_view, elem_to_hide1)
{
    var elem_1 = window.document.getElementById(elem_to_view);
    var elem_2 = window.document.getElementById(elem_to_hide1);
    if (elem_1 != null) {
        elem_1.style.display = "block";
    }
    if (elem_2 != null) {
        elem_2.style.display = "none";
    }
}

function show_special_form_3_elements(elem_to_view, elem_to_hide1, elem_to_hide2)
{
    console.log(elem_to_view);
    var elem_0 = window.document.getElementById(elem_to_view);
    var elem_1 = window.document.getElementById(elem_to_hide1);
    var elem_2 = window.document.getElementById(elem_to_hide2);
    if (elem_0 != null) {
        elem_0.style.display = "block";
    }
    if (elem_1 != null) {
        elem_1.style.display = "none";
    }
    if (elem_2 != null) {
        elem_2.style.display = "none";
    }
}

function changeStyle(style_id, path_to_script) 
{
    //window.alert(path_to_script);
    new Ajax.Request(path_to_script,
    {
        method:'post',
        parameters: {template_style: style_id.value},
        onSuccess: function(answer){
            eval("response = "+answer.responseText)
        },
        onFailure: function() {
            $('modal').innerHTML = '<div class="error"><?php echo _SERVER_ERROR;?></div>'+form_txt;
            form.query_name.value = this.name;
        }
    });
}

function setradiobutton(target)
{
    $("html").style.display="inline";
    $("office").style.display="inline";
    $("txt").style.display="inline";
    $("span_html").style.display="inline";
    $("span_office").style.display="inline";
    $("span_txt").style.display="inline";
   	$("template_attachment_type_tr").style.display="none";

    if(target=="notes"|| target=="sendmail") {
        $("html").style.display="none";
        $("span_html").style.display="none";
        $("txt").click();
        $("txt").checked = true;
        $("office").style.display="none";
        $("span_office").style.display="none";
    } else if(target=="notifications") {
        $("txt").style.display="none";
        $("span_txt").style.display="none";
        $("html").click();
        $("html").checked = true;
        $("office").style.display="none";
        $("span_office").style.display="none";
    } else if (target=="attachments"){
    	$("template_attachment_type_tr").style.display="inline";
    }

    if (target != "attachments") {
    	$("template_attachment_type").selectedIndex="0";
    }

}

function select_template(path_to_script, attachment_type)
{
    new Ajax.Request(path_to_script,
    {
        method:'post',
        parameters: {attachment_type: attachment_type},
        onSuccess: function(answer){
            $('templateOffice').innerHTML = answer.responseText;
            if (typeof ($('templateOffice').onchange) == 'function')
                $('templateOffice').onchange();
        }
    });
}