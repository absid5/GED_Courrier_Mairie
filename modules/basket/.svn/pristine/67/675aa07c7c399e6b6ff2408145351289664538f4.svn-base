
//document.write('<script type="text/javascript" src="js/scrollbox.js"></script>');

 function show_config_action( id_action, inside_scrollbox, show_when_disabled)
 {
    //var div_to_show = $('action_'+id_action);
    var chkbox = $('checkbox_'+id_action)

    if(chkbox && (chkbox.disabled == false || show_when_disabled == true) )
    {
        var main_div = $('config_actions');

        if(main_div != null)
        {
            if(inside_scrollbox == false)
            {
                var childs = main_div.childNodes;
            }
            else
            {
                var childs = main_div.firstChild.childNodes;
            }
            if(chkbox && chkbox.disabled == true && show_when_disabled == true)
            {
                var actions_uses = $(id_action+'_actions_uses');
                actions_uses.style.display = 'none';
            }
            for(i=0; i < childs.length; i++)
            {
                if(childs[i].id=='action_'+id_action)
                {
                    childs[i].style.display = 'block';
                }
                else
                {
                    childs[i].style.display = 'none';
                }
            }
        }
    }


 }

 function check_this_box( id_box)
 {
    var to_check = $(id_box);

    if(to_check && to_check.disabled == false)
    {
        to_check.checked = 'checked';
    }
 }


 function manage_actions(id, inside_scrollbox, path_manage_script)
 {
    var hide_other_actions = false;
    new Ajax.Request(path_manage_script,
    {
        method:'post',
        parameters: { id_action : id},
        onSuccess: function(answer)
        {

            eval('response='+answer.responseText);
            if(response.status == 1 )
            {
                hide_other_actions = true;
            }
            var elem = $('allowed_basket_actions').getElementsByTagName('input');
            for(var i=0; i < elem.length; i++)
            {
                var id_action = elem[i].id.substring(9);
                var label_action = $('label_'+id_action);
                var param_action = $('link_'+id_action);
                if(label_action)
                {
                    label_action.style.fontWeight='normal';
                    label_action.style.fontStyle='normal';
                }
                if(param_action)
                {
                    param_action.style.display='inline';
                }
                if(hide_other_actions)
                {
                    if(elem[i].id == 'checkbox_'+id )
                    {
                        elem[i].checked = false;
                        elem[i].disabled = true;
                        if(label_action)
                        {
                            label_action.style.fontWeight='bold';
                        }
                    }
                    else
                    {
                        elem[i].disabled = true;
                        if(label_action)
                        {
                            label_action.style.fontStyle='italic';
                        }
                        if(param_action)
                        {
                            param_action.style.display="none";
                        }
                    }
                }
                else
                {
                    if(elem[i].id == 'checkbox_'+id )
                    {
                        elem[i].checked = false;
                        elem[i].disabled = true;
                        if(label_action)
                        {
                            label_action.style.fontWeight='bold';
                        }
                    }
                    else
                    {
                        elem[i].disabled = false;
                    }

                }
            }
        },
        onFailure: function(){}
    });


    var main_div = $('config_actions');
    if(main_div != null)
    {
        if(inside_scrollbox == false)
        {
            var childs = main_div.childNodes;
        }
        else
        {
            var childs = main_div.firstChild.childNodes;
        }
        for(i=0; i < childs.length; i++)
        {
            childs[i].style.display = 'none';
        }
    }
 }

 function autocomplete(indice_max, path_to_script)
 {
    for (var i=0;i<indice_max;i++)
    {
        new Ajax.Autocompleter("user_"+i, "options_"+i, path_to_script, {
            method:'get',
            paramName:'UserInput',
            parameters: 'baskets_owner='+$('baskets_owner').value, //si il y a besoin d'autres paramÃ¨tres
            minChars: 2,
            indicator: "indicator_"+i
        });
    }
}


function check_form_baskets(id_form)
{
    var form = $(id_form);
    var reg_user = new RegExp("^.+, .+ (.+)$");
    if(typeof(form) != 'undefined')
    {
        var found = false;
        var elems = document.getElementsByTagName('INPUT');
        for(var i=0; i<elems.length;i++)
        {
            if(elems[i].type == "text" && elems[i].id.indexOf('user_') >= 0)
            {
                if(elems[i].value != '')
                {
                    if(reg_user.test(elems[i].value))
                    {
                        //return 1; // Ok
                        found = true;
                    }
                    else
                    {
                        return 2;   // user field not in the right format
                    }
                }
            }
            else if(elems[i].type == "hidden")
            {
                if(elems[i].id == "baskets_owner" && elems[i].value == '')
                {
                    return 3;    // baskets_owner field is empty
                }

            }
        }
        if(found == true)
        {
            return 1;
        }
        else
        {
            return 4; // All user fields are empty
        }
    }
    else
    {
        return 5; // Error with the form id
    }

}

function check_form_baskets_secondary(id_form)
{
    var form = $(id_form);
    var reg_user = new RegExp("^.+, .+ (.+)$");
    if (typeof(form) != 'undefined') {
        var found = false;
        var elems = document.getElementsByTagName('INPUT');
        for (var i=0; i<elems.length;i++) {
            if (elems[i].type == "checkbox") {
                if(elems[i].checked == true)
                {
                    found=true;
                }
            }
        }
        if (found == true) {
            return 1;
        } else {
            return 4; // no basket checked
        }
    } else {
        return 5; // Error with the form id
    }
}

/**
 * Validates the groupbasket popup form, selects all list options ending with _chosen (type select_multiple) to avoid missing elements
 *
 * @param id_form String Search form identifier
 **/
function valid_actions_param(id_form)
{
    var frm = $(id_form);
    //var reg_chosen = new RegExp("_chosen$");
    var selects = frm.getElementsByTagName('select'); //Array
    for(var i=0; i< selects.length;i++)
    {
        if(selects[i].multiple && selects[i].id.indexOf('_chosen') >= 0 && selects[i].attributes.selected)
        {
            selectall_ext(selects[i].id);
        }
    }
}

function moveInWF(way, collId, resId, role, userId)
{
    if (way != '' && collId != '' &&  resId != '' && role != '' && userId != '') {
        //~ console.log(way);
        //~ console.log(collId);
        //~ console.log(resId);
        //~ console.log(role);
        //~ console.log(userId);
        new Ajax.Request(
            'index.php?display=true&module=basket&page=ajaxMoveInWF&display=true',
            {
                method:'post',
                asynchronous : false,
                parameters: {
                    way : way,
                    collId : collId,
                    resId : resId,
                    role : role,
                    userId : userId
                },
                onSuccess: function(answer) {
                    eval('response=' + answer.responseText);
                    if (response.status > 0) {
                        window.alert(response.error_txt);
                     } else {
                         //$('send').click();
                     }
                }
            }
        );  
    }
}

function simpleAjaxReturn(url){
    new Ajax.Request(url,
    {
        method:'post',
        onSuccess: function(answer){
            window.opener.location.href = 'index.php?module=basket&page=manage_basket_order&mode=reload';
        }
    });
}
