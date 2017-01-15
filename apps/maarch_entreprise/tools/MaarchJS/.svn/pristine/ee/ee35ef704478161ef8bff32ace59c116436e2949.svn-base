/**
 * Maarch.treeview
 *
 * Maarch.treeview is a library that allows the representation of
 * hierarchical information as a tree (think the left pane of Windows
 * Explorer)
 *
 * ![Example of tree](../../images/treeview.png)
 *
 * The preferred way to load this library is to use
 * [[Maarch.require Maarch.require()]]:
 *
 *     Maarch.require("treeview", function(){
 *         //do stuff with the library, call modifiers, etc.
 *     });
 *
 * `Maarch.treeview` mainly contains the [[Maarch.treeview.Tree Tree]] class
 * and a few modifiers of the class (for more information about modifiers
 * and core concepts of maarch.js, see [[Maarch]]).
 *
 **/
Maarch.treeview = {

    /**
     * class Maarch.treeview.Tree
     *
     * A class that represents a tree.
     *
     * This class contains all methods to work with the tree.
     *
     * The internal structure of the Tree is a simple `<ul>` list, where
     * every `<li>` is a node and every `<ul>` contains children of a branch:
     *
     *     lang: html
     *     <ul>
     *       <li> Branch 1 (with child)
     *         <ul>
     *           <li> Branch 1.1</li>
     *           <li> Branch 1.2</li>
     *         </ul>
     *       </li>
     *       <li> Branch 2 (without child)</li>
     *     </ul>
     *
     * Each `<li>` must have an id. During the tree construction, the id of the
     * branches will be prefixed with a tree-specific string. You can safely
     * ignore this "internal id", as all methods of a Tree instance understand
     * the original id that you gave (in this documentation, we call them
     * "external id".
     *
     * #### Create a new tree
     *
     * A new tree is created by instantiating the class `Tree`:
     *
     *     var tree = new Maarch.treeview.Tree(containerElement, params);
     *
     * `rootElement` can either be the element that will contain
     * the tree or the Element itself.
     *
     * `params` is an object that contains options of the tree. available
     * options are :
     *
     * <table>
     *   <tr>
     *     <th>Property</th>
     *     <th>Default value</th>
     *     <th>Comment</th>
     *   </tr>
     *   <tr>
     *     <td>treeId</td>
     *     <td>MaarchTree</td>
     *     <td>Only used if the tree container has no id attribute: treeID
     *     will be set as its id</td>
     *   </tr>
     *   <tr>
     *     <td>img.path</td>
     *     <td>img/</td>
     *     <td>The path, relative to the current page, used to load the
     *     images needed to display the tree</td>
     *   </tr>
     *   <tr>
     *     <td>img.mt_fopened</td>
     *     <td>folderopen.gif</td>
     *     <td>The name of the image used to identify opened branches and
     *     selected leaves</td>
     *   </tr>
     *   <tr>
     *     <td>img.mt_fclosed</td>
     *     <td>folder.gif</td>
     *     <td>The name of the image used to identify closed branches on
     *     leaves that are not selected</td>
     *   </tr>
     *   <tr>
     *     <td>img.mt_plus</td>
     *     <td>plus0.gif</td>
     *     <td>The name of the image for the plus sign in front of closed
     *     branches</td>
     *   </tr>
     *   <tr>
     *     <td>img.mt_minus</td>
     *     <td>minus0.gif</td>
     *     <td>The name of the image for the minus sign in front of opened
     *     branches</td>
     *   </tr>
     *   <tr>
     *     <td>img.mt_none</td>
     *     <td>none.gif</td>
     *     <td>The name of the empty image to replace the plus or minus signs
     *     in front of leaves</td>
     *   </tr>
     *   <tr>
     *     <td>initial_structure</td>
     *     <td>null</td>
     *     <td>see "initial structure" below</td>
     *   </tr>
     * </table>
     *
     * #### Initial Structure
     *
     * If given, the parameter `initial_structure` of the constructor takes
     * a serialization of the tree, either as a string or as a list of objects
     * (see "Branches serialization" below).
     *
     * It is optional. If not given, the constructor will lok for a ul/li
     * structure in `containerElement` starting with a `<ul>` tag.
     *
     * #### Branches serialization
     *
     * When inserting a branch in the tree, whether at initialization time
     * or using [[Maarch.treeview.Tree#insertBranches Tree.insertBranches()]],
     * new branches can have two serialization :
     * 
     *   - a string containing li:
     *
     *        newBranches = '<li id="new1">new branch 1<ul><li id="new1_1">new branch 1.1</li></ul></li>';
     *
     *   - a list of hash (each hash represent a branch):
     * 
     *        newBranches = [{id:"new1", label: "new branch 1", children: [{id: "new1_1", label: "new branch 1.1"}]}];
     *
     * When using the hash form, following properties are available to describe
     * a branch :
     *
     * <table>
     *   <tr>
     *     <th>Property</th>
     *     <th></th>
     *     <th>Comment</th>
     *   </tr>
     *   <tr>
     *     <td>id</td>
     *     <td>mandatory</td>
     *     <td>The external id of the branch</td>
     *   </tr>
     *   <tr>
     *     <td>label</td>
     *     <td>mandatory</td>
     *     <td>The name of the branch</td>
     *   </tr>
     *   <tr>
     *     <td>children</td>
     *     <td>optional</td>
     *     <td>If any the children of the branch. It must be a hash
     *     serialization of a branch</td>
     *   </tr>
     *   <tr>
     *     <td>tooltip</td>
     *     <td>optional</td>
     *     <td>The tooltip for the branch. Only used if the modifier
     *     [[Maarch.treeview.activateToolTip activateToolTip()]]has been
     *     executed.</td>
     *   </tr>
     * </table>
     *
     * #### Modifiers
     *
     * The class Tree currently has two modifiers:
     * 
     *   - [[Maarch.treeview.activateToolTip activateToolTip]]: Displays
     *   tooltips on branches
     *   - [[Maarch.treeview.activateAjaxLoad activateAjaxLoad]]: If active,
     *   the children of a branch will be fetched with an Ajax request when
     *   the branch is opened.
     *
     * (for more information about modifiers and core concepts of
     * maarch.js, see [[Maarch]])
     **/
    Tree: Class.create({
        _DEFAULT_OPTIONS: {
            'treeId' : 'MaarchTree',
            'img.path': 'img/',
            'img.mt_fopened': 'folderopen.gif',
            'img.mt_fclosed': 'folder.gif',
            'img.mt_plus': 'plus0.gif',
            'img.mt_minus': 'minus0.gif',
            'img.mt_none': 'none.gif',
            'initial_structure': null
        },

        _setOptions: function(params){
            var options = this._DEFAULT_OPTIONS;
            var listKeys = $H(options).keys().intersect($H(params).keys());
            listKeys.each(function(el){
                options[el] = params[el];
            });
            this.options = options;
        },

        /**
         *  new Maarch.treeview.Tree(element, params)
         *  - element (String | Element): the id of the element where
         *  the tree must be built or the root Element itself
         *  - params (Object): a list of optional parameters needed
         *  to build the tree
         *
         *  Creates a new Tree.
         *
         *  See [[Maarch.treeview.Tree]] for more information and the
         *  possible values of the `params` hash. 
         **/
        initialize : function(element, params){

            this.root = $(element).addClassName('MaarchTreeRoot');
            this._setOptions(params);
            Maarch.addIEDiv(this.root);
            Maarch.injectInlineCSS(
                 ".MaarchTreeRoot li:last-child {"
                + "    background-image: url(" + this.options['img.path'] + "line2.gif);"
                + "}"
                + ".MaarchTreeRoot li {"
                + "    background-image: url(" + this.options['img.path'] + "line3.gif);"
                + "}"
                + "#IE6 .MaarchTreeRoot li, #IE7 .MaarchTreeRoot li {"
                + "    background-color: inherit;"
                + "    background-image: expression((this.nextSibling==null) ? 'url(" + this.options['img.path'] + "line2.gif)' : 'url(" + this.options['img.path'] + "line3.gif)');"
                + "}"
            );

            if (!!!this.root.id){
                this.root.id = this.options.treeId;
            }
            this.prefix = "mt_" + this.root.id + "_";
            
            // construction de l'arbre
            // Structure de l'arbre présente dans les paramètres
             if (this.options.initial_structure)
             {
                this.insertBranches(this.options.initial_structure, this.root);
                this.options.initial_structure = null;
            }
            else {
                // Aucune structure passée en paramètre, la structure attendue est déjà présente dans le html
                this._formatUL(this.root);
            }

            //Déclaration des événements à observer
            this._setEventObserver();

        },

        /**
         *  Maarch.treeview.Tree.getBranchById(branchId) -> branch
         *  - branchId (String | Element): the internal or external id
         *  of the branch to fetch, or the Element object corresponding to
         *  the branch.
         *
         *  Returns a branch given its id. If the branch itself is passed as
         *  argument, returns the branch without modifying it.
         **/
        getBranchById : function(branchId){
            if (Object.isString(branchId) && !branchId.startsWith(this.prefix)){
                return $(this.prefix + branchId);
            }
            return $(branchId);
        },

        /**
         * Maarch.treeview.Tree#hasChild(branch) -> Bool
         * - branch (String | Element): the internal or external id
         * of the branch or the Element object corresponding to the branch.
         *
         * Returns True if the branch has children, else False
         **/
        hasChild: function(branch)
        {
            return this.getBranchById(branch).down('li') ? true : false;
        },

        /**
         * Maarch.treeview.Tree#expand(branch) -> branch
         * - branch (String | Element):the internal or external id
         * of the branch or the Element object corresponding to the branch.
         *
         * Opens a branch.
         *
         * Visually, the children of the branch will be
         * shown and a minus will replace the plus sign in front of the
         * branch.
         * It will also remove any plus or minus sign if the branch has no
         * child.
         **/
        expand : function(branch){
            branch = this.getBranchById(branch);
            branch.removeClassName('mt_closed');
            branch.addClassName('mt_opened');
            if(!this.hasChild(branch)) {
                branch.addClassName('mt_leaf');
            }
            return branch;
        },

        /**
         * Maarch.treeview.Tree#collapse(branch) -> branch
         * - branch (String | Element):the internal or external id
         * of the branch or the Element object corresponding to the branch.
         *
         * Closes a branch.
         *
         * Visually, the children of the branch will be
         * hidden and a plus will replace the minus sign in front of the
         * branch.
         **/
        collapse : function(branch){
            branch = this.getBranchById(branch);
            branch.removeClassName('mt_opened');
            branch.addClassName('mt_closed');
            return branch;
        },

        /**
         * Maarch.treeview.Tree#toggle(branch) -> branch
         * - branch (String | Element):the internal or external id
         * of the branch or the Element object corresponding to the branch.
         *
         * Switch a branch opened or closed by calling [[Tree.expand]] or
         * [[Tree.collapse]] on the branch.
         **/
        toggle : function(branch){
            var branch = this.getBranchById(branch);
            this[branch.hasClassName('mt_opened') ? 'collapse' : 'expand'](branch);
            return branch;
        },

        /**
         * Maarch.treeview.Tree#insertBranches(newBranches, parentId) -> branch
         * - newBranches (Hash | String): The structure of the branch(es)
         * to insert in the tree.
         * - parentId (String | Element): the internal or external id
         * of the parent branch or the Element object corresponding to the
         * parent branch.
         *
         * Create new branches from the given structure and insert the in
         * the parent branch.
         *
         * The structure newBranches can either be a string containing
         * a ul/li structure or a hash with the same structure as in
         * `Maarch.treeview.Tree` constructor `initial_structure` parameter.
         * See [[Maarch.treeview.Tree]] for more information.
         **/
        insertBranches : function(newBranches, parent){
            parent = this.getBranchById(parent);
            if(Object.isString(newBranches)) {
                parent.innerHTML = newBranches;
                this._formatUL(parent);
            } else {
                var node = this._createNode(parent);
                newBranches.each(function(el){
                    this._insertBranch(el, node);
                    if (el.children && el.children.length > 0){
                        this.insertBranches(el.children, el.id);
                    }

                }.bind(this));
            }
        },

         _insertBranch : function(branch, parentId){
            var newBranch = this._createBranch(branch);
            parentId.insert(newBranch);
            return newBranch;
        },

        _formatUL : function(parent)
        {

            var ul = this.getBranchById(parent).down('ul');
            if(ul)
            {
                var children =  ul.childElements();
                children.each(function(el){
                    // Préfixage de l'id
                    el.id = this.prefix + el.id;
                    // Ajout de la classe mt_closed
                    el.addClassName('mt_closed');
                    // On recupere le texte du li
                    var liTxt = el.firstChild.data;
                    // puis on efface le node texte
                    el.removeChild(el.firstChild);
                    //Ajout du span et des images
                   var span =  this._createSpan(liTxt);
                    // Si il y a des li en dessous on relance la méthode de façon récursive
                    //FIXME: there must be a more simple way to do this
                    if (typeof el.down('ul') == "undefined")
                    {
                         el.insert({top: span});
                    }
                    else
                    {
                        el.down('ul').insert({before : span});
                        this._formatUL(el);
                    }

                    }.bind(this));
            }

        },

        _createNode : function(parent)
        {
            parent = this.getBranchById(parent);
            var node = parent.down('ul');
            if (!node)
            {
                node = new Element('ul').wrap(parent);
                node = node.down('ul');
            }
            return node;
        },

        _createBranch : function(el)
        {
            //creation du li
            var branch = new Element('li', {'id' : this.prefix + el.id, 'class': 'mt_closed'});
            var span = this._createSpan(el.label);
            branch.insert({top: span});
            return branch;
        },

        _createSpan : function(label)
        {
          var span = new Element("span").insert(label);
            // Img dossier
            span.insert({
                top: new Element("img",
                                 {'src': this.options['img.path'] + this.options['img.mt_fopened'],
                                  'class' : 'mt_fopened',
                                  'alt' : ''})});
            span.insert({
                top: new Element("img",
                                 {'src': this.options['img.path'] + this.options['img.mt_fclosed'],
                                  'class' : 'mt_fclosed',
                                  'alt' : ''})});
            span.insert({
                top: new Element("img",
                                 {'src': this.options['img.path'] + this.options['img.mt_plus'],
                                  'class' : 'mt_plus',
                                   'alt' : ''})});
            span.insert({
                top: new Element("img",
                                 {'src': this.options['img.path'] + this.options['img.mt_minus'],
                                  'class' : 'mt_minus',
                                  'alt' : ''})});
            span.insert({
                top: new Element("img",
                                 {'src': this.options['img.path'] + this.options['img.mt_none'],
                                  'class' : 'mt_none',
                                  'alt' : ''})});
            return span;
        },

        /**
         * Maarch.treeview.Tree#select(branch) ->  branch
         * - branch (String | Element): the internal or external id
         * of the branch or the Element object corresponding to the
         * branch to be marked as selected.
         * fires maarch:tree:branchselect
         *
         * This methods marks an branch as selected by adding it the
         * `mt_selected` CSS class.
         * It returns the selected branch.
         *
         * By default, this method is called when an user clicks on the
         * branch.
         *
         * Any selected branch will be deselected when this method is
         * called (only one branch can be selected at once)
         *
         * You can customise the appearance
         * of a selected branch by adding rules to the `mt_selected` CSS
         * class :
         *
         *     <style type="text/css">
         *         .mt_selected {
         *           background-color: red;
         *         }
         *     </style>
         *
         * In addition, it fires, the custom element
         * `maarch:tree:branchselect` which you can catch to launch custom
         * actions on the event. the memo attribute of the event has a
         * `branch_id` property which contains the external id of the
         * selected branch. Example:
         *
         *     $('TreeRoot').observe('maarch:tree:branchselect', function(event){
         *         alert('branch ' + event.memo.branch_id + ' has been selected!');
         *     });
         **/
        select : function(branch)
        {
            branch = this.getBranchById(branch);
            $$('.mt_selected').each(this.deselect.bind(this));
            branch.addClassName('mt_selected');
            if (branch.hasClassName('mt_leaf')){
                branch.removeClassName('mt_closed');
                branch.addClassName('mt_opened');
            }
            branch.fire("maarch:tree:branchselect",
                        {"branch_id": branch.id.replace(new RegExp(this.prefix + "(.*)"), "$1")});
            return branch;
        },

        /**
         * Maarch.treeview.Tree#deselect(branch) ->  branch
         * - branch (String | Element): the internal or external id
         * of the branch or the Element object corresponding to the
         * branch to be deselected.
         *
         * This methods deselects a branch by removing the
         * `mt_selected` CSS class.
         *
         * It returns the deselected branch.
         **/
        deselect : function(branch)
        {
            branch = this.getBranchById(branch);
            branch.removeClassName('mt_selected');
            if (branch.hasClassName('mt_leaf')){
                branch.removeClassName('mt_opened');
                branch.addClassName('mt_closed');
            }
            return branch;
        },

    /*
     * Others Methods
     */

        //Déclaration d'événements
        _setEventObserver: function()
        {
            this.root.on("click", ".mt_plus, .mt_minus, span", function(evt, elt){
                var clickedElt = elt.up('li');
                if (elt.tagName == "SPAN"){
                    this.select(clickedElt);
                } else {
                    this.toggle(clickedElt);
                }
            }.bind(this));
        }
    }),

    /*
     * Modifiers (activate features on demand)
     */

    /**
     * Maarch.treeview.activateToolTip() -> null
     *
     * Modifier for the [[Maarch.treeview.Tree Tree]] class.
     *
     * It allows the addition of tooltips to the branches when a branch is
     * constructed with a hash structure.
     * It adds the new parameter `toolTip` to the hash structure of a branch
     * (see [[Maarch.treeview.Tree Tree]] for a full list of parameters)
     **/
    activateToolTip: function()
    {
        var _old_createBranch = Maarch.treeview.Tree.prototype._createBranch;
        Maarch.treeview.Tree.addMethods({
            _createBranch : function(branchInfos){
                var branch = _old_createBranch.bind(this)(branchInfos);
                var spanTag = branch.down("span");
                spanTag.writeAttribute("title", branchInfos.toolTip || '');
                return branch;
            }
        });
    },

    /**
     * Maarch.treeview.activateAjaxLoad(url) -> null
     * - url (String): the url that is called each time a branch is expanded
     *
     * Modifier for the [[Maarch.treeview.Tree Tree]] class.
     *
     * It loads dynamically (Ajax) the children of a branch when it is
     * opened (when [[Maarch.treeview.Tree.expand Tree.expand()]] is called
     * on the branch).
     *
     * The Ajax request uses the POST method, and has the following parameters:
     *   - parent_id: the id of the branch we want to fetch children.
     *
     * It keeps in memory whether a branch children have been loaded or not
     * in order not to load them more than once.
     **/
    activateAjaxLoad: function(url)
    {
        var _old_expand = Maarch.treeview.Tree.prototype.expand;

        Maarch.treeview.Tree.addMethods({
            expand : function(el){
                var branch = _old_expand.bind(this)(el);
                if(!branch.down('ul'))
                {
                    new Ajax.Request(url, {
                        method: 'post',
                        parameters: {
                            parent_id: branch.id.replace(new RegExp(this.prefix + "(\.*)"), "$1")
                        },
                        onSuccess: function(response){
                            eval('struct='+response.responseText+';');
                            if(struct.length > 0)
                            {
                                this.insertBranches(struct, branch.id);
                                branch.removeClassName('mt_leaf');
                            }
                        }.bind(this),
                        onFailure: function(response)
                        {
                            var errorMsg = "La branche n'a pas pu se charger correctement.<br/><small>(HTTP error "+response.status+" : "+url+")</small>";
                            var span = new Element("span", {'id' : 'mt_error'}).update(errorMsg);
                            this.root.insert({top: span});
                            window.setTimeout(function() { $('mt_error').remove();}, 4000);
                        }.bind(this)
                    });
                }
                return branch;
            }
        });

    }
};