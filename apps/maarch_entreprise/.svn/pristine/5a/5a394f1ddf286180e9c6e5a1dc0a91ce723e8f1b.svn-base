Protohud = Class.create();
Protohud.prototype = {
  ffSmooth : true,
  overflowQ : null,
  openHUD : null,
  showHUD : null,
  hideHUD : [],
  tick : null,
  root : null,
  body : null,
  trig : null,
  allTrigs : null,
  targ : null,
  allTargs : null,
  trigHite : null,
  targHite : null,
  initialize : function (root,fixPosition) {
    this.root = $(root).cleanWhitespace().addClassName('protohud');
    this.body = this.root.up();
    while (this.body.nodeName !== "BODY") this.body = this.body.up();
    this.root.observe('click',this.swap.bindAsEventListener(this));
    this.allTrigs = this.root.childElements().findAll(function(trig){
      return trig.nodeName === this.root.childElements().first().nodeName;
    }.bind(this)).invoke('addClassName','trig');
    this.trigHite = this.allTrigs.max(function(trig){return trig.getHeight();});
    this.allTargs = this.root.childElements().findAll(function(targ){
      return targ.nodeName === this.root.childElements().first().next().nodeName;
    }.bind(this)).invoke('setStyle',{
      paddingTop : this.trigHite + 10 + 'px',
      maxHeight  : ((document.viewport.getHeight()/5)*3).ceil() + 'px',
      overflow   : 'hidden'
    }).invoke('addClassName','targ');
    this.allTargs.each(function(targ){
      this.root.insert(targ);
    }.bind(this));
    this.targHite = this.allTargs.max(function(targ){return targ.getHeight()});
    if (fixPosition) {
      if (!window.isLTIE7) {
        this.root.setStyle({position:'fixed'});
      } else {
        this.root.setStyle({position:'absolute'}).addClassName('fixIE');
      }
    } else {
      this.root.up().setStyle({
        paddingTop:this.trigHite+'px'
      }).setStyle({
        height:this.targHite+'px',
        overflow:'hidden',
        position:'relative',
        zIndex:1
      });
    }
  },
  swap : function (event) {
    this.tick = clearInterval(this.tick);
    this.trig = Event.element(event);
    while (!this.allTrigs.include(this.trig) && this.trig.nodeName !== 'BODY') {
      this.trig = this.trig.up();
    }
    if (Prototype.Browser.Gecko && this.ffSmooth === true) {// HACK!!
      this.overflowQ = $$('body *').findAll(function(lmnt){
        return document.defaultView
          .getComputedStyle(lmnt,null)
          .getPropertyValue('overflow') === 'auto';
      }).invoke('setStyle',{overflow:'hidden'});
    }
    this.allTrigs.invoke('removeClassName','open');
    this.allTargs.invoke('removeClassName','open')
                 .invoke('setStyle',{overflow:'hidden',zIndex:1});
    this.targ = this.allTargs[this.allTrigs.indexOf(this.trig)];
    this.hideHUD = this.allTargs.findAll(function(targ){
      return targ !== this.targ;
    }.bind(this));
    if (this.targ !== this.openHUD && this.targ !== this.showHUD) {
      this.showHUD = this.targ;
      this.trig.addClassName('open');
      this.targ.setStyle({zIndex:999});
    } else {
      this.showHUD = null;
      this.openHUD = null;
      this.hideHUD.push(this.targ);
    }
    this.tick = setInterval(this.tock.bind(this),33);
  },
  tock : function () {
    var newBottom;
    if (this.showHUD) {
      if (this.showHUD.positionedOffset()[1] < 0 ) {
        newBottom = this.targ.getHeight() + this.targ.positionedOffset()[1];
        newBottom = this.targ.getHeight() - newBottom;
        newBottom = (newBottom / -9).floor();
        newBottom = (parseInt(this.showHUD.getStyle('bottom')) + newBottom) + 'px';
        this.showHUD.setStyle({bottom:newBottom});
      } else {
        this.showHUD.setStyle({overflow:'',zIndex:1});
        this.openHUD = this.showHUD;
        this.showHUD = null;
      }
    }
    if (this.hideHUD.size() > 0) {
      this.hideHUD.each(function(targ){
        if (targ.offsetTop > (targ.getHeight()*-1) ) {
          newBottom = targ.getHeight() + targ.offsetTop;
          newBottom = (newBottom / 9).ceil();
          newBottom = (parseInt(targ.getStyle('bottom')) + newBottom) + 'px';
          targ.setStyle({bottom:newBottom});
        } else {
          targ.setStyle({bottom:''});
          if (this.openHUD === targ) this.openHUD = null;
          this.hideHUD[this.hideHUD.indexOf(targ)] = null;
        }
      }.bind(this));
    }
    this.hideHUD = this.hideHUD.compact();
    if (!this.showHUD && this.hideHUD.size() === 0) {
      this.tick = clearInterval(this.tick);
      if (Prototype.Browser.Gecko && this.overflowQ) {// HACK!!
        this.overflowQ.invoke('setStyle',{overflow:''});
      }
    }
  }
};
