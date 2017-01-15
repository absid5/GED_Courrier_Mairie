loader.loadMaarchLib("treeview", ["activateToolTip"]);
loader.depends("maarchTreeview");

describe("Maarch Treeview with tooltips", function(){
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
    it("should have title attributes", function(){
        expect(tree.getBranchById("1").down("span")).toHaveAttr("title");
        expect(tree.getBranchById("1").down("span").title).toEqual("test 1");
    });
});