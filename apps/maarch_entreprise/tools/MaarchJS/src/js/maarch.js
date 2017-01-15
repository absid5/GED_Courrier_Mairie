
/**
 * Maarch
 *
 * Maarch.js is an object-oriented set of library based on
 * [Prototype website](http://prototypejs.org/ "Prototype javascript framework").
 * It provides UI components to build web applications.
 *
 * Its main goals are to be:
 *
 *   - very efficient, for any browser and javascript engine;
 *   - highly extensible.
 *
 * In order to achieve those goals, maarch.js relies heavily on a few core
 * concepts (see below): minimalism (KISS), laziness and "DOM is not that
 * bad after all" (a.k.a "Stay close to DOM").
 *
 * #### Supported Platforms
 *
 * Maarch.js has been tested with a wide range of browsers and Prototype
 * versions. It is currently tested against:
 *
 *   - Internet Explorer from version 6
 *   - Firefox from version 3.6
 *   - Opera from version 11
 *   - Chrome from version 9
 *
 * Prototype framework is supported from version 1.6.0.2. If your version
 * of Prototype is inferior or equal to 1.6.0.1 maarch.js won't load
 *
 * #### Getting Started
 *
 * If your application does not already include Prototype javascript
 * framework, follow instructions from
 * [Prototype documentation](http://api.prototypejs.org/ "Prototype documentation").
 *
 * To use Maarch.js, download the latest release and uncompress it  to a
 * suitable location. Then, include it in your application :
 *
 *     lang: html
 *     <script type="text/javascript" src="/path/to/prototype.js"></script>
 *     <script type="text/javascript" src="/path/to/maarchjs/maarch.js"></script>
 *
 * If you changed the organization of maarchjs directories, you will have to
 * adjust the path to css files (relatively to `maarchjs/js` folder):
 *
 *     Maarch.cssPath = "../path/to/css/directory";
 *
 * Then, to use library `foo`, insert in your application when you need it:
 *
 *     Maarch.require("foo", function(){
 *         //do stuff with foo library
 *     });
 *
 * #### Core Concepts
 *
 * ##### Minimalism
 *
 * One of the main goal of maarch.js is to be lightweight and efficient.
 * To achieve this goal, it stands to strict minimalism.
 *
 * A library could implement features A, B, C and D. It then has to
 * test if A, B, C and D are active to know what to do. It has an impact on
 * the execution of the library, the complexity of the library, the internal
 * dependencies of the library, and eventually and more importantly the final
 * user experience.
 *
 * Plus, from all those nice and shiny features, most of people will only
 * use B. So why should they care about A, C and D ?
 *
 * Javascript is a great language: prototypes are tough to get into, but
 * they are a beautiful thing to work with. Amongst their strength is the
 * possibility to alter an object dynamically and with great flexibility.
 * Extensibility comes in a very natural way.
 *
 * Taking full advantage of that, maarch.js libraries come with a minimal
 * set of feature. Quite possibly the minimal for it to behave a natural way.
 *
 * Other features can be activated by executing a function we call "modifier".
 * The modifiers inject the desired feature by adding new methods or
 * overriding existing ones.
 *
 * It makes it easy to develop "plugins" and to have the library do just what
 * you want, nothing more, nothing less.
 *
 * ##### Laziness
 *
 * The similar concept applied to a higher level gives lazy loading.
 *
 * For the moment, maarch.js only has one library ([[Maarch.treeview]]).
 * However, this number will grow.
 *
 * Why would we make you load tons of libraries if you only want to use
 * a single one?
 * Maarch.js let you load just the libraries you want:
 *
 *     lang: html
 *     <script type="text/javascript" src="/path/to/maarchjs/maarch.js"></script>
 *     <script type="text/javascript">
 *         Maarch.require("foo");
 *         Maarch.require("bar");
 *     </script>
 *
 * In addition, it is now accepted that loading huge javascript file too
 * early (typically in the head of the HTML page) is bad for user experience.
 * Maarch.js lets you load it whenever you want (even just before you need it).
 * To avoid errors, it even lets you define callbacks to execute your code
 * when the library is available:
 *
 *     Maarch.require("foo", function(){
 *         //do stuff with library foo
 *     });
 *
 *
 * ##### "DOM is not that bad after all" (a.k.a "Stay close to DOM")
 *
 * To optimize maarch.js, we did not want to keep the state of an object
 * in-memory and make sure that the HTML representation in the DOM is in sync.
 * There are two main reasons:
 *
 *   - It is error prone: the in-memory registry and the HTML representation
 *   can fast become out of sync. There are plenty of reasons : it might be
 *   a bug, the side effect of another script, etc. Who knows what the effects
 *   would be ? We did not want to take this case into account, it would only
 *   add complexity.
 *   - It duplicates data: the same information is kept in memory, and
 *   in the HTML and DOM structure.
 *   - DOM might not be a panacea, but it is powerful. Combined with
 *   the semantic of HTML elements and attributes, it is a natural registry
 *   that makes sense and is easily accessible from other scripts.
 *   - This way, the DOM tree is self-sufficient and we can let the browser
 *   rendering engine take care of the presentation.
 *
 * This leads to longer css files, but the counterpart is better performance
 * and lesser complexity.
 *
 *
 **/
var Maarch = (function(){

    //////////////////////////
    /// Private attributes ///
    //////////////////////////


    var requiredPrototype = '1.6.0.2';

    function convertVersionString(versionString){
        var r = versionString.split('.');
        return parseInt(r[0], 10)*1000000 + parseInt(r[1], 10)*10000 + parseInt(r[2], 10)*100 + (r[3] ? parseInt(r[3], 10) : 0);
    }
    var fixPrototype = function(){
        //If method on is not defined for Element objects
        if (!Element.on){
            //we create it
            function on(element, event, selector, callback){
                var newCallback;
                element = $(element);
                if (Object.isFunction(selector) && Object.isUndefined(callback)) {
                    callback = selector;
                    selector = null;
                }
                if (selector){
                     newCallback= function(evt){
                        var elt = evt.findElement(selector) || window.document;
                        if (elt != window.document){
                            callback(evt, elt);
                        }
                    };
                } else {
                    newCallback = callback;
                }
                element.observe(event, newCallback);
            }

            Element.addMethods({
                on: on
            });
        }
    };



    /////////////////////////
    /// Public attributes ///
    /////////////////////////



    /**
     * Maarch.Version -> String
     *
     * The version of Maarch.js
     **/
    var Version = '0.1.0';

    /**
     * Maarch.libs -> Array
     * A list of the available libraries. Currently, are available :
     *
     * - [[Maarch.treeview]]: a library to display hierarchical information as
     * a tree (think the left pane of Windows Explorer)
     **/
    var libs = $A(['treeview']);

    /**
     * Maarch.jsPath -> String
     *
     * The url to the folder containing the file `maarch.js`.
     *
     * For example: `maarch.js` is located at
     * `http://www.my-server/static/js/maarch.js`, `Maarch.jsPath` will be
     * `http://www.my-server/static/js/`.
     *
     * This is used to load libraries dynamically.
     **/
    var jsPath = (function(){
        return $$('script').find(function(el){
            return el.src.match(/maarch\.js$/);
        }).src.replace(/(.*)maarch\.js/, "$1");
    })();

    /**
     * Maarch.cssPath -> String
     *
     * The url to the folder containing the maarch.js css files relatively
     * to Maarch.jsPath. this value defaults to `../css/`.
     *
     * For example, if your files are organized this way:
     *
     *     www-root/
     *       `-- static/
     *          `-- maarchjs/
     *            |-- js/
     *            `-- css/
     *
     * `Maarch.cssPath` must be set to `../css/`
     * (`Maarch.jsPath` is `http://www.my-server.com/static/maarchjs/js/`
     *  so Maarch.cssPath will be `http://www.my-server.com/static/maarchjs/js/../css/`)
     *
     *
     * This is used to load libraries dynamically.
     **/
    var cssPath = '../css/';


    /**
     * Maarch.require(library, callback) -> null
     * - library (String): the name of the library to load.
     * - callback (Function): the function to call when the library is
     * loaded.
     *
     * `Maarch.require` allows you to load libraries dynamically when you
     * need them. This must be the preferred way to load Maarch.js libraries.
     * for more information about this and other core concepts of Maarch.js,
     * see [[Maarch]].
     *
     * Usage :
     *
     *     Maarch.require('treeview', function(){
     *         // do stuff...
     *     });
     *
     **/
    var require = function(library, callback){
        if (Maarch.libs.include(library)){
            Maarch.injectJS('maarch-treeview');
            Maarch.injectCSSFile('maarch-treeview');
        }
        if (callback && callback instanceof Function){
            new PeriodicalExecuter(function(pe) {
                if (typeof Maarch[library] != 'undefined') {
                    pe.stop();
                    callback();
                }
            }, 0.01);
        }
    };


    /**
     * Maarch.injectCSSFile(stylesheet) -> null
     * - stylesheet (String): the name of the stylesheet to load.
     *
     * Dynamically loads a stylesheet in the current page.
     *
     * The url of the stylesheet that will be loaded is
     *     Maarch.jsPath + Maarch.cssPath + stylesheet + '.css'
     *
     * i.e: with following settings :
     *
     *     Maarch.jsPath = "http://my-server.com/maarchjs/js/"
     *     Maarch.cssPath = "../css/"
     *     stylesheet = "maarch-treeview"
     *
     * the url called will be :
     *     http://my-server.com/maarchjs/js/../css/maarch-treeview.css
     **/
    var injectCSSFile = function(stylesheet){
        if ($$('link[href$=' + stylesheet + '.css]').size() == 0){
            $$("head")[0].insert({bottom: new Element('link', {
                rel: 'stylesheet',
                href: Maarch.jsPath + Maarch.cssPath + stylesheet + '.css',
                type: "text/css"
            })});
        }
    };

    /**
     * Maarch.injectInlineCSS(style) -> null
     * - style (String): the css rules to inject
     *
     * Dynamically insert inline CSS in the current page.
     * The CSS will be inserted in `<style>` tags.
     **/
    var injectInlineCSS = function(style){
        var styleTag = new Element('style', {'type': 'text/css'});
        if(styleTag.styleSheet){
            var rule = document.createTextNode(style);
            styleTag.styleSheet.cssText = rule.nodeValue;
        } else {
            styleTag.insert(style);
        }
        $$('head')[0].insert(styleTag);
    };

    /**
     * Maarch.injectJS(jsFile) -> null
     * - jsFile (String): the name of the javascript file to load.
     *
     * Dynamically loads a javascript file in the current page.
     *
     * The url of the javascript file that will be loaded is
     *     Maarch.jsPath + jsFile + '.js'
     *
     * i.e: with following settings :
     *
     *     Maarch.jsPath = "http://my-server.com/maarchjs/js/"
     *     jsFile = "maarch-treeview"
     *
     * the url called will be :
     *     http://my-server.com/maarchjs/js/maarch-treeview.js
     **/
    var injectJS = function(jsFile){
        if ($$('script[src$=' + jsFile + '.js]').size() == 0){
            var scriptTag = new Element('script', {
                src: Maarch.jsPath + jsFile + '.js',
                type: "text/javascript"
            });
            //$(document.body).insert({bottom: scriptTag});
            $$("head")[0].insert({bottom: scriptTag});
            return scriptTag;
        }
        return null;
    };


    /**
     * Maarch.addIEDiv(element) -> null
     * - element (String | Element): the element to wrap or its id.
     *
     * Only works in Internet Explorer
     *
     * Wraps the given `element` in a div with `IEx` as id where x is the
     * version of Internet Explorer the scripts runs in.
     *
     *     // in the HTML:
     *     // <div id="content">my content</div>
     *
     *     Maarch.addIEDiv("content");
     *
     *     // if run in IE7, HTML becomes:
     *     // <div id="IE7"><div id="content"></div></div>
     *
     * This ease the use of Internet Explorer specific CSS hacks.
     **/
    var addIEDiv =  function(element){
        if (Prototype.Browser.IE){
            $(element).wrap(new Element("div", {id: "IE"+navigator.appVersion.replace(/.*MSIE ([6-9]).*/, '$1')}));
        }
    };

    ///////////////
    /// Loading ///
    ///////////////

    if ((typeof Prototype == 'undefined') ||
            (typeof Element == 'undefined') ||
            (typeof Element.Methods == 'undefined') ||
            (convertVersionString(Prototype.Version) <
                    convertVersionString(requiredPrototype))) {
        throw ("Maarch.js requires the Prototype JavaScript framework >= " +
                requiredPrototype);
    }

    fixPrototype();

    return {
        Version: Version,
        libs: libs,
        jsPath: jsPath,
        cssPath: cssPath,
        require: require,
        injectCSSFile: injectCSSFile,
        injectInlineCSS: injectInlineCSS,
        injectJS: injectJS,
        addIEDiv: addIEDiv
    };
})();