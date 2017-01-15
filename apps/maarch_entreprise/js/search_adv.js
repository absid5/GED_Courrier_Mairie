
/**
 * Load a query in the Advanced Search page
 *
 * @param valeurs Array Values of the search criteria which must be displayed
 * @param loaded_query Array Values of the search criteria
 * @param id_form String Search form identifier
 * @param ie_browser Bool Browser is internet explorer or not
 * @param error_ie_txt String Error message specific to ie browser
 **/
function load_query(valeurs, loaded_query, id_form, ie_browser, error_ie_txt)
{
    for( var critere in loaded_query)
    {
        if(valeurs[critere] != undefined) // in the valeurs array
        {
            add_criteria('option_'+critere, id_form, ie_browser, error_ie_txt);
        }
        eval("processingFunction=fill_field_"+loaded_query[critere]['type']);
        if (typeof(processingFunction) == 'function') // test if the funtion exists
        {
            processingFunction(loaded_query[critere]['fields'] );
        }
    }
}

/**
 * Fills inputs fields of text type in the search form whith value
 *
 * @param values Array Values of the search criteria which must be displayed
 **/
function fill_field_input_text(values)
{
    for( var key in values)
    {
        var tmp_elem = $(key);
        tmp_elem.value = values[key];
    }
}

/**
 * Fills date range in the search form whith value
 *
 * @param values Array Values of the search criteria which must be displayed
 **/
function fill_field_date_range(values)
{
    for( var key in values)
    {
        var tmp_elem = $(key);
        tmp_elem.value = values[key][0];
    }
}

/**
 * Selects items in a mutiple list (html select object with multiple) in the search form
 *
 * @param values Array Values of the search criteria which must be displayed
 **/
function fill_field_select_multiple(values)
{
    for( var key in values)
    {
        if(key.indexOf('_chosen') >= 0)
        {
            var available = key.substring(0, key.length -7)+'_available';
            var available_list = $(available);
            for(var j=0; j <values[key].length;j++)
            {
                for(var i=0; i<available_list.options.length;i++)
                {
                    if(values[key][j] == available_list.options[i].value)
                    {
                        available_list.options[i].selected='selected';
                    }
                }
            }
            Move_ext(available, key);
        }
        if(key.indexOf('_targetlist') >= 0)
        {
            var available = key.substring(0, key.length -7)+'_sourcelist';
            var available_list = $(available);
            for(var j=0; j <values[key].length;j++)
            {
                if (available_list) {
                    for(var i=0; i<available_list.options.length;i++)
                    {
                        if(values[key][j] == available_list.options[i].value)
                        {
                            available_list.options[i].selected='selected';
                        }
                    }
                } 
            }
            if (available) {
                Move_ext(available, key);
            }
        }
    }
}

/**
 * Selects an item in a simple list (html select object) in the search form
 *
 * @param values Array Values of the search criteria which must be displayed
 **/
function fill_field_select_simple(values)
{
    for( var key in values)
    {
        var tmp_elem = $(key);
        for(var j=0; j <values[key].length;j++)
        {
            for(var i=0; i<tmp_elem.options.length;i++)
            {
                if(values[key][j] == tmp_elem.options[i].value)
                {
                    tmp_elem.options[i].selected='selected';
                }
            }
        }
    }
}

/**
 * Adds criteria in the search form
 *
 * @param elem_comp String Identifier of the option of the criteria list to displays in the search form
 * @param id_form String Search form identifier
 * @param ie_browser Bool Is the browser internet explorer or not
 * @param error_txt_ie String Error message specific to ie browser
 **/
function add_criteria(elem_comp, id_form, ie_browser, error_txt_ie)
{
    // Takes the id of the chosen option which must be one of the valeurs array
    var elem = elem_comp.substring(7, elem_comp.length);
    var form = window.$(id_form);
    var valeur = valeurs[elem];
    if (ie_browser) {
        var div_node = $('search_parameters_'+elem);
    }
    if (typeof(valeur) != 'undefined') {
        if (ie_browser == true  && typeof(div_node) != 'undefined'
            && div_node != null) {
            alert(error_txt_ie);
        } else {
            var node = document.createElement('div');
            node.setAttribute('id', 'search_parameters_' + elem);
            tmp = '<table width="100%" border="0"><tr><td width="30%">';
            tmp += '<i class="fa fa-angle-right"></i> ' + valeur['label'];
            tmp += '</td><td>';
            tmp += valeur['value'];
            tmp += '</td><td width="30px">';
            tmp += '<a href="#" onclick="delete_criteria(\'' + elem + '\', \''
            tmp += id_form + '\');return false;">';
            tmp += '<i class="fa fa-remove fa-2x"></i></a>';
            tmp += '</td></tr></table>';
            // Loading content in the page
            node.innerHTML = tmp;
            form.appendChild(node);
            label = $(elem_comp);
            label.parentNode.selectedIndex = 0;
            label.style.display = 'none';
        //  label.disabled = !label.disabled;
        }
    } else {
        //Error if the valeur array has no key 'id'
        //alert('Error with Javascript Search Adv ');
    }
}

/**
 * Deletes a criteria in the search form
 *
 * @param elem_comp String Identifier of the option of the criteria list to delete in the search form
 * @param id_form String Search form identifier
 **/
function delete_criteria(id_elem, id_form)
{
    var form = $(id_form);
    var tmp = (id_elem.indexOf('option_') >= 0) ? id_elem : 'option_' + id_elem;
    var label =  $(tmp);
    label.style.display = '';
//  label.disabled = !label.disabled;
    tmp = (id_elem.indexOf('option_') >= 0) ? id_elem.substring(7, id_elem.length) : id_elem;
   //form.removeChild($('search_parameters_'+tmp));
    $('search_parameters_'+tmp).parentElement.removeChild( $('search_parameters_'+tmp));
}

/**
 * Validates the search form, selects all list options ending with _chosen (type select_multiple) to avoid missing elements
 *
 * @param id_form String Search form identifier
 **/
function valid_search_form(id_form)
{
    var frm = $(id_form);
    var selects = frm.getElementsByTagName('select'); //Array
    for (var i=0; i< selects.length;i++) {
        if (selects[i].multiple && (selects[i].id.indexOf('_chosen') >= 0
        	|| selects[i].id.indexOf('_targetlist') >= 0)
        ) {
            selectall_ext(selects[i].id);
        }
    }
}

/**
 * Clears the search form : delete all optional criteria in the form
 *
 * @param id_form String Search form identifier
 * @param id_list String Criteria list identifier
 **/
function clear_search_form(id_form, id_list)
{
    var list = $(id_list);
    for (var i=0; i <list.options.length; i++) {
        if (list.options[i].style.display == 'none') {
            delete_criteria(list.options[i].id, id_form);
        }
    }
    var elems = document.getElementsByTagName('INPUT');
    for (var i=0; i<elems.length;i++) {
        if(elems[i].type == 'text') {
            elems[i].value ='';
        }
    }
    var lists = document.getElementsByTagName('SELECT');
    for (var i=0; i<lists.length;i++) {
        lists[i].selectedIndex =0;
    }
    var copie_false = $('copies_false');
    if (copie_false) {
        copie_false.checked = true;
    }
}

/**
 * Clears the queries list : remove an option in this list
 *
 * @param item_value String Identifier of the item to remove
 **/
function clear_q_list(item_value)
{
    var query = $('query');

    if (item_value && item_value != '' && query) {
        var item = $('query_' + item_value);
        if (item) {
            query.removeChild(item);
        }
    }
    if (query && query.options.length > 1) {
        var q_list = $('default_query');
        if (q_list) {
            q_list.selected = 'selected';
        }
        var del_button = $('del_query');
        if (del_button) {
            del_button.style.display = 'none';
        }
    } else {
        var div_query = $('div_query');
        if (div_query) {
            div_query .style.display = 'none';
        }
    }
}

/**
 * Load a saved query in the Advanced Search page (using Ajax)
 *
 * @param id_query String Identifier of the saved query to load
 * @param id_form_to_load String Identifier of the search form
 * @param sql_error_txt String SQL error message
 * @param server_error_txt String Server error message
 * @param manage_script String Ajax script
 **/
function load_query_db(id_query, id_list, id_form_to_load, sql_error_txt, server_error_txt, manage_script)
{
    if (id_query != '') {
        var query_object = new Ajax.Request(
            manage_script,
            {
                method:'post',
                parameters: {
                                id : id_query,
                                action : 'load'
                            },
                onSuccess: function(answer){
                    eval("response = " + answer.responseText + ';');
                    if (response.status == 0) {
                        clear_search_form(id_form_to_load, id_list);
                        //Clears the search form
                        if (response.query instanceof Object
                            && response.query != {}) {
                            load_query(valeurs, response.query, id_form_to_load);
                        }
                        var del_button = $('del_query');
                        del_button.style.display = 'inline';
                        $('query_' + id_query).selected = "selected";
                    } else if(response.status == 2) {
                        $('error').update(sql_error_txt);
                    } else {
                        $('error').update(server_error_txt);
                    }
                },
                onFailure: function(){
                    $('error').update(server_error_txt);
                }
            });
        }
    }

/**
 * Delete a saved query in the database (using Ajax)
 *
 * @param id_query String Identifier of the saved query to delete
 * @param id_list String Identifier of the queries list
 * @param id_form_to_load String Identifier of the search form
 * @param sql_error_txt String SQL error message
 * @param server_error_txt String Server error message
 * @param path_script String Ajax script
 **/
function del_query_db( id_query, id_list, id_form_to_load, sql_error_txt, server_error_txt, path_script)
{
    if (id_query != '') {
        var query_object = new Ajax.Request(
        path_script,
        {
            method:'post',
            parameters: {
                            id : id_query.value,
                            action : "delete"
                        },
            onSuccess: function(answer){

                eval("response = "+answer.responseText+';');
                if (response.status == 0) {
                    clear_search_form(id_form_to_load,id_list); //Clears search form
                    clear_q_list(id_query.value);
                } else if(response.status == 2) {
                    $('error').update(sql_error_txt);
                } else {
                    $('error').update(server_error_txt);
                }
            },
            onFailure: function(){
                $('error').update(server_error_txt);
            }
        });
    }
}

