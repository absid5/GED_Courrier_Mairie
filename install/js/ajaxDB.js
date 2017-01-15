function ajaxDB(
    url,
    parameters,
    divRetour,
    top
)
{   
    var ajaxUrl  = url;

    var parametersTemp = parameters.split('|');

    var strAjaxParameters = '{';
    for (cpt=0; cpt<parametersTemp.length; cpt++) {
        strAjaxParameters += parametersTemp[cpt];
        strAjaxParameters += ":";
        strAjaxParameters += "'";
        cpt++;
        parametersTemp[cpt] = parametersTemp[cpt].replace(/\\/gi, '/');
        strAjaxParameters += parametersTemp[cpt];
        strAjaxParameters += "'";
        if (cpt < parametersTemp.length) {
            strAjaxParameters += ", ";
        }
    }
    strAjaxParameters += "ajax:'true'";
    strAjaxParameters += ", div:'"+divRetour+"'";
    strAjaxParameters += '}'
    var ajaxParameters = eval('(' + strAjaxParameters + ')');

    /**********/

    if (top == 'true') {
        var retour_ok = window.top.$('#'+divRetour+'_ok');
        var retour_ko = window.top.$('#'+divRetour+'_ko');
    } else {
        var retour_ok = $('#'+divRetour+'_ok');
        var retour_ko = $('#'+divRetour+'_ko');
    }

    /**********/

    $(document).ready( function() {
        $.getJSON('ajax.php?script='+ajaxUrl, ajaxParameters, function(data){
            if (data.status == 1) {
                if (data.text == 'redirect') {
                    goTo('index.php?step=docservers');
                    return;
                }
                retour_ok.html(data.text);
                retour_ko.html('');
                slide(divRetour);
                $('.'+divRetour).slideToggle('slow');
                if ($('.wait')) {
                    $('.wait').css('display','none');
                }

            } else if(data.status == 0) {
                retour_ko.html(data.text);
                if ($('.wait')) {
                    $('.wait').css('display','none');
                }
                if ($('#ajaxReturn_createDocservers_button')) {
                    $('#ajaxReturn_createDocservers_button').css('display', 'block');
                }
            } else if(data.status == 2){
                retour_ko.html(data.text);
                if ($('.wait')) {
                    $('.wait').css('display','none');
                }
                if ($('#ajaxReturn_createDocservers_button')) {
                    $('#ajaxReturn_createDocservers_button').css('display', 'block');
                }
                slide(divRetour);
                $('.'+divRetour).slideToggle('slow');
                if ($('.wait')) {
                    $('.wait').css('display','none');
                }

            }else if(data.status == 3){
                if (confirm("La base de données existe déjà. Voulez vous garder cette base?")){

                    data.text = 'redirect';
                    if (data.text == 'redirect') {
                    goTo('index.php?step=config');
                    return;
                    }
                    retour_ok.html(data.text);
                    retour_ko.html('');
                    slide(divRetour);
                    $('.'+divRetour).slideToggle('slow');
                    if ($('.wait')) {
                        $('.wait').css('display','none');
                    }
               
                }else{
                    if (confirm("Voulez-vous en créer une nouvelle? (futur evolution : mise à jour de la base de données si existance de script de mise a jour")){

          
                            goTo('index.php?step=database');
                            return;
                        
                     



                    }else{
                        goTo('index.php?step=welcome');
                            return;
                    }
                }

            }
        });
    });
}



