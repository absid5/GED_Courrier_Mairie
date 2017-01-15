/*
  
  =Options

    Keypress.Observer accepts:

        shift::     we want the SHIFT key down
        alt::       we want the ALT key down
        control:: we want the CTRL key down
        meta::      we want the META key down

  =Usage

        // monitor ctrl-shift-G
        new Keydown.Observer(Ansi.G, function(e)
        {
            alert('Do something');
        },{shift:true, control:true});
    
        // monitor ctrl-meta-shift-P
        new Keydown.observe(Ansi.P, function(e)
        {
            print_document();
        }, {control:true, meta:true, shift:true});

  =Dependencies

  written against Prototype 1.6.0.1
    
*/
if(!window.Keypress) var Keypress = {};
if(!window.Ansi) var Ansi = {};
/**
 *  Our ANSI map for the alphabet of keyboard keys.
 */
Object.extend(Ansi,
{
    A: 97,      B: 98,      C: 99,      D: 100,     E: 101,
    F: 102,     G: 103,     H: 104,     I: 105,     J: 106,
    K: 107,     L: 108,     M: 109,     N: 110,     O: 111,
    P: 112,     Q: 113,     R: 114,     S: 115,     T: 116,
    U: 117,     V: 118,     W: 119,     X: 120,     Y: 121,
    Z: 122
});
/*
    Monitors the document's keypresses and issues callbacks if hit
*/
Keypress.Observer = Class.create();
Object.extend(Keypress.Observer.prototype,
{
  initialize: function(key_code, callback, options) {
        /*if(console) {
            console.log("initialize: \nkey_code:"+key_code);
            console.log(options);
            console.log("charset:" + document.charset);
        }  */
        var SHIFT_OFFSET = 32;
        this.ie = (navigator.userAgent.indexOf("MSIE")) != -1;
        this.utf_8 = !(typeof(document.charset) == 'undefined');
        this.debug = false;
        
        this.key_code = key_code;
        this.callback = callback || function(e){}
        this.options = $H(Object.extend({
            shift: false,
            meta: false,
            alt: false,
            control: false,
            listen:document
        }, options || {}));
        this.listen = this.options.unset('listen');
        this.options = this.options.toObject();
        
        // control changes the key_code for lowercase letters so this will adjust for that
        if(this.options.control) {
            //this.key_code -= this.control_offset();
            //console.log("apply control_offset: "+this.key_code);
        }

        // if they want a "G" and pass in a "g" this will correct the key_code
        if(this.options.shift && this.key_code >= 97 && this.key_code <= 122)
            this.key_code -= SHIFT_OFFSET;

        this.observe(this.listen);
    },
    observe:function(element)
    {
        $(element).observe('keydown', function(e)
        {
            var char_code = (e.charCode ? e.charCode : e.keyCode);
            var keyboard_options = {
                shift: e.shiftKey,
                meta: e.metaKey || false,
                alt: e.altKey,
                control: e.ctrlKey
            };
            
            if(this.debug && console)
                console.log(
                    "char_code: "+char_code
                    +"\nkey_code: "+this.key_code
                    +"\n"+$H(keyboard_options).toQueryString()
                    +"\n"+$H(this.options).toQueryString() 
                    +"\n\nUTF8: " + this.utf_8 
                    + "\ncontrol_offset: " + this.control_offset());           
            
            if(char_code == this.key_code && ($H(keyboard_options).toQueryString() == $H(this.options).toQueryString())) {
                if(this.debug && console) console.log("callback");
                this.callback(e);
            }
        }.bind(this));      
    },
    
    control_offset:function()
    {
        if(this.key_code >=65 && this.key_code <= 90)
            // capital letters
            return this.utf_8 ? 64 : 0;
        else if(this.key_code >= 97 && this.key_code <= 122)
            // lowercase letters
            return this.utf_8 ? 96 : 0;
        else
            // for non-letters
            return this.utf_8 && !this.ie ? 0 : -16;
    }
});
