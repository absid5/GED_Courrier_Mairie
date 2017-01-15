var currentPath = document.location.pathname.split("/").slice(0,-1).join('/');

loader.loadMaarchLib("treeview", [["activateAjaxLoad", currentPath+'/ajax.json']]);
loader.depends("maarchTreeview");

describe("Maarch Treeview with Ajax loading", function(){
    var initialStructure = [
        {'id' : '1', 'label' :'blah1', 'toolTip' : 'test 1', 'children' : [
            {'id' : '1_1', 'label' :'1_1', 'children' : [
                {'id' : '1_1_1', 'label' : '1_1_1'}]},
            {'id' : '1_2', 'label' :'1_2', 'children' : []},
            {'id' : '1_3', 'label' :'1_3',  'children' : []}]},
        {'id' : '2', 'label' :'2', 'children' :  [
            {'id' : '2_1', 'label' :'2_1'}]}
    ];
    var tree;
    beforeEach(function(){
        setFixtures('<div id="treeWrapper"></div>');
        tree = new Maarch.treeview.Tree("treeWrapper", {'initial_structure': initialStructure});
    });
    afterEach(function(){
        delete tree;
        $('treeWrapper').remove();
    });

    describe("when it does not have any children and is not a leaf", function(){
        it("should launch an Ajax Request", function(){
            spyOn(Ajax.Request.prototype, "initialize");
            var origBranch = tree.getBranchById("1_1_1");
            var returnedBranch = tree.expand(origBranch);
            runs(function(){
                expect(Ajax.Request.prototype.initialize).toHaveBeenCalled();
                expect(tree.hasChild(origBranch)).toBeTruthy();
                expect(returnedBranch).toBe(origBranch);
            });
        });
    });
    describe("when it is a leaf", function(){
        it("shouldn't launch any Ajax Request", function(){
            spyOn(Ajax.Request.prototype, "initialize");
            var origBranch = tree.getBranchById("1_1_1");
            origBranch.insert({bottom: new Element("ul")});
            var returnedBranch = tree.expand(origBranch);
            expect(Ajax.Request.prototype.initialize).not.toHaveBeenCalled();
            expect(tree.hasChild("1_1_1")).toBeFalsy();
            expect(returnedBranch).toBe(origBranch);
        });
    });
    describe("when it already has children", function(){
        it("shouldn't launch any Ajax Request", function(){
            spyOn(Ajax.Request.prototype, "initialize");
            var origBranch = tree.getBranchById("1_1");
            var returnedBranch = tree.expand(origBranch);
            expect(Ajax.Request.prototype.initialize).not.toHaveBeenCalled();
            expect(returnedBranch).toBe(origBranch);
        });
    });
});