var Tabricator = Class.create();
Tabricator.prototype = {
  root : null,
  trigtype : null,
  targtype : null,
  initialize : function (lmnt,trig,deep) {
    var root = this.root = $(lmnt).addClassName('tabricator').cleanWhitespace();
    this.trigtype = trig;
    this.targtype = this.root.select(trig).first().next().nodeName;
    var trigs = this.root.select(this.trigtype).each(function(trig){
      if (trig.up() === root) trig.addClassName('trig');
    });
    var targs = this.root.select(this.targtype).each(function(targ){
      if (targ.up() === root) {
        targ.addClassName('targ').hide();
        root.insert(targ);
      }
    });
    var i = trigs.length;
    if(deep != null){
      while (i--) {   
        if(trigs[i].id == deep){
          trigs[i].addClassName('open');
          targs[i].show();
          break;
        }
      }
    }else{
      trigs[0].addClassName('open');
      targs[0].show();
    }
    this.root.observe('click',this.swap.bindAsEventListener(this));
  },
  swap : function (event) {
    var trig = Event.element(event);
    if (trig.nodeName !== this.trigtype || trig.up() !== this.root) return;
    var trigs = this.root.select(this.trigtype).invoke('removeClassName','open');
    var targs = this.root.select(this.targtype).invoke('hide');
    var i = trigs.length;
    while (i--) {
      if (trigs[i] === trig) {
        trigs[i].addClassName('open');
        targs[i].show();
      }
    }
  }
};
