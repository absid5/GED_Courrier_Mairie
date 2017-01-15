var debug = (function(){
    return !!document.location.search.match(/debug=true/);
})();

var suites = ["maarch", "maarchTreeview", "maarchTreeviewAjax", "maarchTreeviewTooltip"];
var toTest = ["1.6.0.2", "1.6.0.3", "1.6.1", "1.7"];
var loadedSuites = [];

function Runner(element, suites, protovers){
    this.container = document.getElementById(element);
    this.suites = suites;
    this.protovers = protovers;
    this.orColors = {};

    var createTable = function(){
        return document.createElement('table');
    };
    var createRow = function(){
        return document.createElement('tr');
    };
    var createCell = function(suite, protover){
        var tmpCell = document.createElement('td');
        tmpCell.setAttribute("id", protover.replace(".", "-", "g") + "_" + suite);
        var tmpImg = document.createElement("img");
        tmpImg.setAttribute("src", "spinner.gif");
        tmpCell.appendChild(tmpImg);
        return tmpCell;
    };
    var createHeaderCell = function(value){
        var tmpTh = document.createElement('th');
        tmpTh.innerHTML = value;
        return tmpTh;
    };

    var tmpTable = createTable();
    var tmpHeader = document.createElement("thead");
    var tmpHeaderRow = createRow();
    tmpHeader.appendChild(tmpHeaderRow);
    var tmpFirstCell = createHeaderCell("");
    tmpFirstCell.style.border = "none";
    tmpHeaderRow.appendChild(tmpFirstCell);
    
    for (var j=0; j<this.protovers.length; j++){
        tmpHeaderRow.appendChild(createHeaderCell("Prototype v" + this.protovers[j]));
    }
    tmpTable.appendChild(tmpHeader);
    
    var tmpTbody = document.createElement("tbody");
    tmpTable.appendChild(tmpTbody); 
    this.container.appendChild(tmpTable); 
    
    for (var i=0; i < this.suites.length; i++){
        var tmpRow = createRow();
        tmpRow.appendChild(createHeaderCell(this.suites[i]));
        
        for (j=0; j<this.protovers.length; j++){
            var tmpCell = createCell(this.suites[i], this.protovers[j]);
            
            tmpCell.onclick = (function(inst, i, j){
                return function(){
                    inst.showDetails.apply(inst, [inst.suites[i], inst.protovers[j]]);
                };
            })(this, i, j);
            
            tmpRow.appendChild(tmpCell);
        }
        tmpTbody.appendChild(tmpRow);
    }  
}

Runner.prototype.showDetails = function(suite, protover, inst){

    var classToShow = protover.replace(".", "-", "g") + "_" + suite;
    var cells = document.getElementsByTagName("td");
    for (var i=0; i<cells.length;i++){
        var tmpClass = cells[i].id;
        if(this.orColors[tmpClass]){
            cells[i].style.backgroundColor = this.orColors[tmpClass];
        }
        var iframe = document.getElementById("iframe_" + tmpClass);
        iframe.style.display = "none";
    }
    
    document.getElementById("iframe_" + classToShow).style.display = "block";
    var cell = document.getElementById(classToShow);
    this.orColors[classToShow] = cell.style.backgroundColor;
    cell.style.backgroundColor = "lightblue";
};
Runner.prototype.run = function(){
    var _loadTest = function(protover, suite){
        var iframe = document.createElement('iframe');
        //alert("avant" + iframe);
        iframe.setAttribute("id", "iframe_" + protover.replace(".", "-", "g") + "_" + suite);
        //alert("apres" + iframe);
        iframe.style.width = "100%";
        iframe.style.height = "500px";
        iframe.style.display = "none";
        iframe.setAttribute("src", baseURL+"?protover="+protover+"&suite="+suite);
        document.getElementById("tests").appendChild(iframe);
    };
    var baseURL= document.location.pathname.split("/").slice(0,-1).join('/') + "/unit/index.html";
    var w = 1;
    for (var i=0; i<suites.length; i++){
        for (var j = 0; j<toTest.length; j++){
            //window.setTimeout(_loadTest, 250*w++, this.protovers[j], this.suites[i  ]);
            window.setTimeout((function(p, s){
                return function(){_loadTest(p,s)};
            })(this.protovers[j], this.suites[i  ]), 1000*w++);
            //_loadTest(this.protovers[i], this.suites[j]);
        }
    }
};
Runner.prototype.getResult = function(res){
    //console.log(res);
    var success = res.passedCount == res.totalCount;
    var cell = document.getElementById(res.protover.replace(".", "-", "g") + "_" + res.suite);
    if(cell){
        //cell.setAttribute("style", "background-color:" + (success?"lightgreen":"lightcoral"));
        cell.style.backgroundColor = success? "lightgreen" : "lightcoral";
        cell.innerHTML = (success?"Success!":"Failed!") + "<br/>" + res.passedCount + "/" + res.totalCount;
    }    
};


function Loader(){
    this.defaultProtoVer = "1.7";
    this.args = {};
    var kv = (document.location.search.substr(0,1)== "?" ?
                document.location.search.substring(1) :
                document.location.search).split("&");
    for (var i=0; i<kv.length; i++){
        var tmp = kv[i].split("=");
        this.args[tmp[0]] = tmp[1];
    }
}
Loader.prototype.loadPrototype = function(){
    if (!this.args.hasOwnProperty("protover")){
        //console.log("no protover : loading prototype v1.7");
        this._loadPrototype(this.defaultProtoVer);
    } else {
        //console.log("protover: loading prototype v"+protover);
        this._loadPrototype(this.args.protover);
    }
};
Loader.prototype._loadPrototype = function(version){
    //console.log(version);
    document.write("<script type=\"text/javascript\" src=\"../../lib/prototype-"+version+".js\"></script>");
};
Loader.prototype.loadMaarch = function(){
    //console.log(version);
    document.write("<script type=\"text/javascript\" src=\"../../src/js/maarch.js\"></script>");
};
Loader.prototype._loadSuite = function(suite){
    document.write("<script type=\"text/javascript\" src=\""+suite+"Test.js\"></script>");
    loadedSuites.push(suite);
    
};
Loader.prototype.loadSuites = function(){
    if(!this.args.hasOwnProperty("suite")){
        for(var i=0; i<suites.length; i++){
            this._loadSuite(suites[i]);
        }
    } else {
        this._loadSuite(this.args.suite);
    }
};
Loader.prototype.loadMaarchLib = function(lib, modifiers){
    modifiers = modifiers || [];
    Maarch.require(lib, function(){
        for(var i=0; i<modifiers.length; i++){
            if (modifiers[i].length==2){
                Maarch[lib][modifiers[i][0]](modifiers[i][1]);
            } else {
                Maarch[lib][modifiers[i][0]]();
            }
        }
    });

};
Loader.prototype.depends = function(suite){
    if (!!suite){
        this._loadSuite(suite)
    }
};


//function loadPrototype(){
//    var protover = document.location.search.replace(/.*protover=([0-9\.]*).*/, "$1");
//    if(debug){
//        console.log("protover : ", protover);
//        console.log("document.location.search", document.location.search);
//        console.log("!!!protover: ",!!!protover);
//        console.log("protover != document.location.search: ",protover != document.location.search);
//        console.log("toTest.toString.match(new RegExp(protover)) : ", !toTest.toString().search(new RegExp(protover.replace(/[\.]/, '\.'))));
//    }
//    if ( !!!protover || (protover == document.location.search || !toTest.toString().match(new RegExp(protover)))){
//        //console.log("no protover : loading prototype v1.7");
//        _loadPrototype("1.7");
//    } else {
//        //console.log("protover: loading prototype v"+protover);
//        _loadPrototype(protover);
//    }
//}

//function _loadPrototype(version){
//    //console.log(version);
//    document.write("<script type=\"text/javascript\" src=\"../../lib/prototype-"+version+".js\"></script>");
//}

//function loadMaarch(){
//    //console.log(version);
//    document.write("<script type=\"text/javascript\" src=\"../../src/js/maarch.js\"></script>");
//}

//function _loadSuite(suite){
//    document.write("<script type=\"text/javascript\" src=\""+suite+"Test.js\"></script>");
//    loadedSuites.push(suite);
//}

//function loadSuites(){
//    var suite = document.location.search.replace(/.*suite=([a-zA-Z]*).*/, "$1");
//    if (debug){
//        console.log("suite : ", suite);
//        console.log("document.location.search", document.location.search);
//        console.log("!!!suite: ",!!!suite);
//        console.log("suite != document.location.search: ",suite != document.location.search);
//        console.log("suites.toString.match(new RegExp(suite)) : ", !suites.toString().search(new RegExp(suite.replace(/[\.]/, '\.'))));
//    }
//    if ( !!!suite || (suite == document.location.search || !suite.toString().match(new RegExp(suite)))){
//        for(var i=0; i<suites.length; i++){
//            _loadSuite(suites[i]);
//        }
//    } else {
//        _loadSuite(suite);
//    }
//}