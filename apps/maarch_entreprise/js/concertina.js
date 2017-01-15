var Concertina = Class.create();
Concertina.prototype = {
  ffSmooth    : true,
  overflowQ : null,
  root        : null,
  hite        : null,
  anim        : null,
  ease        : null,
  trig        : null,
  targ        : null,
  tick        : null,
  targHite    : null,
  allTrigs    : [],
  allTargs    : [],
  targsToHide : [],
  targsToShow : [],
  i           : 0,
  loopLimit   : 50,
  clicked     : 0,
  declick     : null,
  initialize  : function (root,nest,hite,anim,ease) {
    this.root = $(root);
    this.hite = hite;
    this.anim = anim;
    this.ease = ease;
    if (!this.root) return;
    if (nest && this.root.childElements()[1].nodeName !== this.root.nodeName) {
      $(root).select($(root).nodeName).each(function(kids){
        new Concertina(kids,nest,true,anim,ease);
      });
    }
    this.allTrigs = this.root.childElements().findAll(function(trig){
      return trig.nodeName === this.root.firstDescendant().nodeName;
    }.bind(this)).invoke('addClassName','trig');
    this.allTargs = this.root.childElements().findAll(function(targ){
      return targ.nodeName === this.root.firstDescendant().next().nodeName;
    }.bind(this)).invoke('addClassName','targ');
    if (hite && hite === true) {
      this.allTargs.each(function(targ){
        this.hite = (targ.getHeight()+10 > this.hite) ?
                                  targ.getHeight()+10 : this.hite;
      }.bind(this)).invoke('setStyle',{height:this.hite+'px'});
    } else if (hite && hite > 1) {
      this.hite = hite;
      this.allTargs.invoke('setStyle',{height:this.hite+'px'});
    }
    if (!this.anim) {
      this.allTargs.invoke('hide').first().show();
    } else {
      this.allTargs.invoke('setStyle',{height:0,overflow:'hidden'});
      if (!this.hite) {
        this.allTargs.first().setStyle({height:'',overflow:'auto'});
      } else {
        this.allTargs.first().setStyle({height:this.hite+'px',overflow:'auto'});
      }
    }
    this.root.addClassName('concertina').setStyle({position:'relative',zoom:1})
             .observe('click',this.wait.bindAsEventListener(this));
    this.allTrigs.first().addClassName('open');
  },
  unclick     : function () {
    this.clicked = 0;
    this.declick = clearTimeout(this.declick);
  },
  wait        : function (event) {
    this.trig = Event.element(event);
    if (!this.allTrigs.include(this.trig)) return;
    this.clicked++;
    this.declick = setTimeout(this.unclick.bind(this),350);
    if (this.clicked === 1) {
      this.swap(event);
    }
  },
  swap        : function () {
    this.i = 0;
    this.targsToHide.clear();
    this.targsToShow.clear();
    clearInterval(this.tick);
    this.targ = this.trig.next();
    if (!this.anim) {
      this.allTargs.invoke('hide');
      if (this.trig.hasClassName('open')) {
        this.allTrigs.invoke('removeClassName','open');
      } else {
        this.allTrigs.invoke('removeClassName','open');
        this.trig.addClassName('open');
        this.targ.show();
      }
    } else {
      if (Prototype.Browser.Gecko && this.ffSmooth === true) {// HACK!!
        this.overflowQ = $$('body *').findAll(function(lmnt){
          return document.defaultView
            .getComputedStyle(lmnt,null)
            .getPropertyValue('overflow') === 'auto';
        }).invoke('setStyle',{overflow:'hidden'});
      }
      this.targsToHide = this.allTargs.findAll(function(targ){
        return targ.getHeight() > 0;
      }).invoke('setStyle',{overflow:'hidden'});
      if (this.trig.hasClassName('open')) {
        this.allTrigs.invoke('removeClassName','open');
      } else {
        this.allTrigs.invoke('removeClassName','open');
        this.trig.addClassName('open');
        if (!this.hite) {
          this.targHite = this.targ.setStyle({height:''}).getHeight()+10;
          this.targ.setStyle({height:0});
        }
        this.targsToShow.push(this.targ);
      }
      if (!this.ease) {
        this.tick = setInterval(this.tock.bind(this),1);
      } else {
        this.tick = setInterval(this.tock.bind(this),50);
      }
    }
  },
  tock        : function () {
    this.i++;
    if (!this.ease) {
      this.targsToHide.each(function(targ){
        var hite = this.hite || this.targHite;
        var frame = Math.ceil(hite/25);
        if (targ.getHeight() >= frame && this.i <= this.loopLimit) {
          targ.setStyle({height:(targ.getHeight() - frame) + 'px'});
        } else {
          targ.setStyle({height:0,overflow:'hidden'});
          this.targsToHide[this.targsToHide.indexOf(targ)] = null;
        }
      }.bind(this));
      this.targsToHide = this.targsToHide.compact();
      this.targsToShow.each(function(targ){
        var hite = this.hite || this.targHite;
        var frame = Math.ceil(hite/25);
        if (targ.getHeight() < hite && this.i <= this.loopLimit) {
          targ.setStyle({height:(targ.getHeight() + frame) + 'px'});
        } else {
          targ.setStyle({height:hite + 'px',overflowQ:'auto'});
          this.targsToShow[this.targsToShow.indexOf(targ)] = null;
        }
      }.bind(this));
      this.targsToShow = this.targsToShow.compact();
    } else {
      this.targsToHide.each(function(targ){
        if (targ.getHeight() > 0 && this.i <= this.loopLimit) {
          targ.setStyle({height:targ.getHeight() - Math.ceil(targ.getHeight()/5) + 'px'});
        } else {
          targ.setStyle({height:0,overflow:'hidden'});
          this.targsToHide[this.targsToHide.indexOf(targ)] = null;
        }
      }.bind(this));
      this.targsToHide = this.targsToHide.compact();
      this.targsToShow.each(function(targ){
        var hite = this.hite || this.targHite;
        if (targ.getHeight() < hite && this.i <= this.loopLimit) {
          targ.setStyle({height:targ.getHeight() + Math.ceil((hite - targ.getHeight())/5) + 'px'});
        } else {
          targ.setStyle({height:hite + 'px',overflowQ:'auto'});
          this.targsToShow[this.targsToShow.indexOf(targ)] = null;
        }
      }.bind(this));
      this.targsToShow = this.targsToShow.compact();
    }
    if (this.targsToShow.size() === 0 && this.targsToHide.size() === 0) {
      this.tick = clearInterval(this.tick);
      this.tick = null;
      this.i = 0;
      if (Prototype.Browser.Gecko && this.overflowQ) {// HACK!!
        this.overflowQ.invoke('setStyle',{overflow:''});
      }
    }
  }
};
