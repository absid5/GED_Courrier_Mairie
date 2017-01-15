// Fonction pour gérer les changements dynamiques de sous-menu.
// Prend en variable le numéro du sous-menu à afficher.

function ChangeH2(objet){
    if(objet.getElementsByTagName('img')[0].src.indexOf("plus")>-1){
        objet.getElementsByTagName('img')[0].src="img/moins.png";
        objet.getElementsByTagName('span')[0].firstChild.nodeValue=" ";
    }else{
        objet.getElementsByTagName('img')[0].src="img/plus.png";
        objet.getElementsByTagName('span')[0].firstChild.nodeValue=" ";
    }
}

function Change2H2(objet){
    if(objet.getElementsByTagName('img')[0].src.indexOf("folderopen")>-1){
        objet.getElementsByTagName('img')[0].src="img/folder.gif";
        objet.getElementsByTagName('span')[0].firstChild.nodeValue=" ";
    }else{
        objet.getElementsByTagName('img')[0].src="img/folderopen.gif";
        objet.getElementsByTagName('span')[0].firstChild.nodeValue=" ";
    }
}

function afficheCalque(calque){
    calque.style.display='block';
}
function masqueCalque(calque){
    calque.style.display='none';
}

function getScrollXY() {
  var scrOfX = 0, scrOfY = 0;
  if( typeof( window.pageYOffset ) == 'number' ) {
    //Netscape compliant
    scrOfY = window.pageYOffset;
    scrOfX = window.pageXOffset;
  } else if( document.body && ( document.body.scrollLeft || document.body.scrollTop ) ) {
    //DOM compliant
    scrOfY = document.body.scrollTop;
    scrOfX = document.body.scrollLeft;
  } else if( document.documentElement && ( document.documentElement.scrollLeft || document.documentElement.scrollTop ) ) {
    //IE6 standards compliant mode
    scrOfY = document.documentElement.scrollTop;
    scrOfX = document.documentElement.scrollLeft;
  }
  return new Array(scrOfX,scrOfY);
}

var initialized = 0;
var etat = new Array();

function reinit()
{
    initialized = 0;
    etat = new Array();
}

function initialise(){
    for (i=0;i<500;i++){
        etat[i] = new Array();
        etat[i]["h2"] = document.getElementById('h2'+i);
        etat[i]["desc"] = document.getElementById('desc'+i);
        etat[i]["etat"] = 0;
    }
    initialized = 1;
}

function ferme(id){
    Effect.SlideUp(etat[id]["desc"]);
    ChangeH2(etat[id]["h2"]);
    etat[id]["etat"] = 0;
}

function ouvre(id){
    Effect.SlideDown(etat[id]["desc"]);
    ChangeH2(etat[id]["h2"])
    etat[id]["etat"] = 1;
}

function ferme2(id){
    Effect.SlideUp(etat[id]["desc"]);
    Change2H2(etat[id]["h2"]);
    etat[id]["etat"] = 0;
}

function ouvre2(id){
    Effect.SlideDown(etat[id]["desc"]);
    Change2H2(etat[id]["h2"])
    etat[id]["etat"] = 1;
}

function ferme3(id){
    Effect.SlideUp(etat[id]["desc"]);
    //ChangeH2(etat[id]["h2"]);
    etat[id]["etat"] = 0;
}

function ouvre3(id){
    Effect.SlideDown(etat[id]["desc"]);
    //ChangeH2(etat[id]["h2"])
    etat[id]["etat"] = 1;
}

function change(id){
    if (!initialized ){
        initialise()
    }
    // alert(etat[id]["etat"]);
    if (etat[id]["etat"]){
        ferme(id);
    }else{
        for (i=0;i<etat.length;i++){
            if (etat[i]["etat"]){
                ferme(i);
            }
        }
        ouvre(id);
    }
}

function change2(id){
    if (!initialized){
        initialise()
    }
    if (etat[id]["etat"]){
        ferme2(id);
    }else{
        for (i=0;i<etat.length;i++){
            if (etat[i]["etat"]){
                //ferme(i);
            }
        }
        ouvre2(id);
    }
}

function change3(id){
    if (!initialized ){
        initialise()
    }
    // alert(etat[id]["etat"]);
    if (etat[id]["etat"]){
        ferme3(id);
    }else{
        for (i=0;i<etat.length;i++){
            if (etat[i]["etat"]){
                ferme3(i);
            }
        }
        ouvre3(id);
    }
}

function show_special_form(id, var_visible)
{
    var elem = window.document.getElementById(id);
    if(elem != null)
    {
        elem.style.display = var_visible;
    }
}
