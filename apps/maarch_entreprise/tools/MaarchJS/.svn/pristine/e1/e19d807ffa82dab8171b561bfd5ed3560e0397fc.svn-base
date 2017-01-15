Maarch.cssPath = "../../dist/css/";
loader.loadMaarchLib("treeview");

describe("Maarch Treeview", function(){
    it("should exist", function(){
        expect(Maarch.treeview).not.toBe(undefined);
        expect(typeof(Maarch.treeview)).toBe("object");
    });

    it("should have the class Tree", function(){
        expect(Maarch.treeview.Tree).not.toBe(undefined);
    });
});

describe("Maarch.treeview.Tree", function(){
    var initialStructure = [
        {'id' : '1', 'label' :'blah1', 'toolTip' : 'test 1', 'children' : [
            {'id' : '1_1', 'label' :'1_1', 'children' : [
                {'id' : '1_1_1', 'label' : '1_1_1'}]},
            {'id' : '1_2', 'label' :'1_2', 'children' : []},
            {'id' : '1_3', 'label' :'1_3',  'children' : []}]},
        {'id' : '2', 'label' :'2', 'children' :  [
            {'id' : '2_1', 'label' :'2_1'}]}
    ];
    
    describe("when it is created from an existing li structure", function(){
        var tree;
        beforeEach(function(){
            setFixtures('<div id="treeWrapper"><ul><li id="1" title="test tooltip">blah1<ul><li id="1_1">1_1<ul><li id="1_1_1">1.1.1</li></ul></li><li id="1_2">1_2</li><li id="1_3">1_3</li></ul></li><li id="2">2<ul><li id="2_1">2.1</li></ul></li></ul></div>');
            tree = new Maarch.treeview.Tree("treeWrapper");
        });
        afterEach(function(){
            delete tree;
            $('treeWrapper').remove();
        });

        it("should have a prefix", function(){
            expect(tree.prefix).toBeTruthy();
        });

        it("this structure should now be a tree", function(){
            expect($$(".MaarchTreeRoot").size()).toEqual(1);
        });
        it("should have branches", function(){
            $('treeWrapper').descendants().filter(function(el){
                return (el.tagName == "LI");
            }).each(function(branch){
                expect(branch).toExist();
                expect(branch.down("span")).toExist();
            });
        });
    });

    describe("when a new Tree is created from a li string", function(){
        var tree;
        beforeEach(function(){
            setFixtures('<div id="treeWrapper"></div>');
            tree = new Maarch.treeview.Tree("treeWrapper", {'initial_structure': '<div id="treeWrapper"><ul><li id="1" title="test tooltip">blah1<ul><li id="1_1">1_1<ul><li id="1_1_1">1.1.1</li></ul></li><li id="1_2">1_2</li><li id="1_3">1_3</li></ul></li><li id="2">2<ul><li id="2_1">2.1</li></ul></li></ul></div>'});
        });
        afterEach(function(){
            delete tree;
            $('treeWrapper').remove();
        });

        it("should have a prefix", function(){
            expect(tree.prefix).toBeTruthy();
        });

        it("this structure should now be a tree", function(){
            expect($$(".MaarchTreeRoot").size()).toEqual(1);
        });
        it("should have branches", function(){
            $('treeWrapper').descendants().filter(function(el){
                return (el.tagName == "LI");
            }).each(function(branch){
                expect(branch).toExist();
                expect(branch.down("span")).toExist();
            });
        });
    });

    describe("when a new Tree is created from a hash", function(){
        var tree;
        beforeEach(function(){
            setFixtures('<div id="treeWrapper"></div>');
            tree = new Maarch.treeview.Tree("treeWrapper", {'initial_structure': initialStructure});
        });
        afterEach(function(){
            delete tree;
        });

        it("should have a prefix", function(){
            expect(tree.prefix).toBeTruthy();
        });

        it("this structure should now be a tree", function(){
            expect($$(".MaarchTreeRoot").size()).toEqual(1);
        });
        
        it("should have branches", function(){
            $('treeWrapper').descendants().filter(function(el){
                return (el.tagName == "LI");
            }).each(function(branch){
                expect(branch).toExist();
                expect(branch.down("span")).toExist();
            });
        });
    });

    describe("when a tree has been initialised", function(){
        var tree;
        beforeEach(function(){
            setFixtures('<div id="treeWrapper"></div>');
            tree = new Maarch.treeview.Tree("treeWrapper", {'initial_structure': initialStructure});
        });
        afterEach(function(){
            delete tree;
        });

        describe("should be possible to select a branch", function(){
            it(" with an internal id", function(){
                expect(tree.getBranchById(tree.prefix + '1_1')).toExist();
            });

            it("with an external id", function(){
                expect(tree.getBranchById('1_1')).toExist();
            });

            it("with a branch", function(){
                expect(tree.getBranchById(tree.getBranchById('1_1'))).toExist();
            });
        });
        
        describe("should be possible to know if a branch has children", function(){
            it("with an internal id", function(){
                expect(tree.hasChild(tree.prefix + '1')).toBeTruthy();
                expect(tree.hasChild(tree.prefix + '1_1_1')).toBeFalsy();
            });

            it("with an external id", function(){
                expect(tree.hasChild('1')).toBeTruthy();
                expect(tree.hasChild('1_1_1')).toBeFalsy();
            });

            it("with a branch", function(){
                expect(tree.hasChild(tree.getBranchById('1'))).toBeTruthy();
                expect(tree.hasChild(tree.getBranchById('1_1_1'))).toBeFalsy();
            });

            xit("as branch method", function(){
                expect(tree.getBranchById('1').hasChild()).toBeTruthy();
                expect(tree.getBranchById('1_1_1').hasChild()).toBeFalsy();
            });
        });

        describe("A branch should be expand-able", function(){
            describe("When a branch is opened", function(){
                describe("with an internal id", function(){
                    it("should have visible children", function(){
                        tree.expand(tree.prefix + "1");
                        expect($(tree.prefix + "1_1")).toBeVisible();
                    });
                    it("shouldn't have visible sub-children", function(){
                        tree.expand(tree.prefix + "1");
                        expect($(tree.prefix + "1_1_1")).not.toBeVisible();
                    });
                    it("should have a minus sign and no plus sign", function(){
                        tree.expand(tree.prefix + "1");
                        expect($(tree.prefix + "1").down(".mt_minus")).toBeVisible();
                        expect($(tree.prefix + "1").down(".mt_plus")).not.toBeVisible();
                    });
                });
                describe("with an external id", function(){
                    it("should have visible children", function(){
                        tree.expand("1");
                        expect($(tree.prefix + "1_1")).toBeVisible();
                    });
                    it("shouldn't have visible sub-children", function(){
                        tree.expand("1");
                        expect($(tree.prefix + "1_1_1")).not.toBeVisible();
                    });
                    it("should have a minus sign and no plus sign", function(){
                        tree.expand(tree.prefix + "1");
                        expect($(tree.prefix + "1").down(".mt_minus")).toBeVisible();
                        expect($(tree.prefix + "1").down(".mt_plus")).not.toBeVisible();
                    });
                });
                describe("with a branch", function(){
                    it("should have visible children", function(){
                        tree.expand(tree.getBranchById("1"));
                        expect($(tree.prefix + "1_1")).toBeVisible();
                    });
                    it("shouldn't have visible sub-children", function(){
                        tree.expand(tree.getBranchById("1"));
                        expect($(tree.prefix + "1_1_1")).not.toBeVisible();
                    });
                    it("should have a minus sign and no plus sign", function(){
                        tree.expand(tree.prefix + "1");
                        expect($(tree.prefix + "1").down(".mt_minus")).toBeVisible();
                        expect($(tree.prefix + "1").down(".mt_plus")).not.toBeVisible();
                    });
                });
                xdescribe("as branch method", function(){
                    it("should have visible children", function(){
                        tree.getBranchById("1").expand();
                        expect($(tree.prefix + "1_1")).toBeVisible();
                    });
                    it("shouldn't have visible sub-children", function(){
                        tree.getBranchById("1").expand();
                        expect($(tree.prefix + "1_1_1")).not.toBeVisible();
                    });
                    it("should have a minus sign and no plus sign", function(){
                        tree.expand(tree.prefix + "1");
                        expect($(tree.prefix + "1").down(".mt_minus")).toBeVisible();
                        expect($(tree.prefix + "1").down(".mt_plus")).not.toBeVisible();
                    });
                });
                describe("if it has no child", function(){
                    it("should have no plus sign", function(){
                        tree.expand("1");
                        tree.expand("1_1");
                        tree.expand("1_1_1");
                        expect(tree.getBranchById("1_1_1").down('.mt_plus')).not.toBeVisible();
                    });
                    it("should have no minus sign", function(){
                        tree.expand("1");
                        tree.expand("1_1");
                        tree.expand("1_1_1");
                        expect(tree.getBranchById("1_1_1").down('.mt_minus')).not.toBeVisible();
                    });
                });
                it("must return the opened branch", function(){
                    var branch = tree.getBranchById("1");
                    expect(tree.expand(branch)).toBe(branch);
                });
            });
        });

        describe("A branch should be collapse-able", function(){
            describe("When a branch is closed", function(){
                describe("with an internal id", function(){
                    it("should have no visible children", function(){
                        tree.expand(tree.prefix + "1");
                        tree.collapse(tree.prefix + "1");
                        expect($(tree.prefix + "1_1")).not.toBeVisible();
                    });
                    it("should have a plus sign and no minus sign", function(){
                        tree.expand(tree.prefix + "1");
                        tree.collapse(tree.prefix + "1");
                        expect($(tree.prefix + "1").down(".mt_minus")).not.toBeVisible();
                        expect($(tree.prefix + "1").down(".mt_plus")).toBeVisible();
                    });
                });
                describe("with an external id", function(){
                    it("should have no visible children", function(){
                        tree.expand("1");
                        tree.collapse("1");
                        expect($(tree.prefix + "1_1")).not.toBeVisible();
                    });
                    it("should have a plus sign and no minus sign", function(){
                        tree.expand(tree.prefix + "1");
                        tree.collapse(tree.prefix + "1");
                        expect($(tree.prefix + "1").down(".mt_minus")).not.toBeVisible();
                        expect($(tree.prefix + "1").down(".mt_plus")).toBeVisible();
                    });
                });
                describe("with a branch", function(){
                    it("should have no visible children", function(){
                        tree.expand(tree.getBranchById("1"));
                        tree.collapse(tree.getBranchById("1"));
                        expect($(tree.prefix + "1_1")).not.toBeVisible();
                    });
                    it("should have a plus sign and no minus sign", function(){
                        tree.expand(tree.prefix + "1");
                        tree.collapse(tree.prefix + "1");
                        expect($(tree.prefix + "1").down(".mt_minus")).not.toBeVisible();
                        expect($(tree.prefix + "1").down(".mt_plus")).toBeVisible();
                    });
                });
                xdescribe("as branch method", function(){
                    it("should have visible children", function(){
                        tree.getBranchById("1").expand();
                        tree.getBranchById("1").collapse();
                        expect($(tree.prefix + "1_1")).not.toBeVisible();
                    });
                    it("should have a plus sign and no minus sign", function(){
                        tree.expand(tree.prefix + "1");
                        tree.collapse(tree.prefix + "1");
                        expect($(tree.prefix + "1").down(".mt_minus")).not.toBeVisible();
                        expect($(tree.prefix + "1").down(".mt_plus")).toBeVisible();
                    });
                });
                it("must return the closed branch", function(){
                    var branch = tree.getBranchById("1");
                    expect(tree.collapse(branch)).toBe(branch);
                });
            });
        });

        describe("A branch should be toggle-able", function(){
            it("with an internal id", function(){
                expect(tree.toggle(tree.prefix + "1")).toExist();
            });
            it("with an external id", function(){
                expect(tree.toggle("1")).toExist();
            });
            it("with a branch", function(){
                expect(tree.toggle($(tree.prefix + "1"))).toExist();
            });
            xit("as a branch method", function(){
                expect(tree.getBranchById("1").toggle()).toExist();
            });
            it("a closed branch must be opened", function(){
                spyOn(tree, "expand");
                tree.toggle("1");
                expect(tree.expand).toHaveBeenCalled();
            });
            it("a opened branch must be closed", function(){
                spyOn(tree, "collapse");
                tree.expand("1");
                tree.toggle("1");
                expect(tree.collapse).toHaveBeenCalled();
            });
            it("must return the toggled branch", function(){
                var branch = tree.getBranchById("1")
                expect(branch.toggle()).toBe(branch);
            });
        });

        describe("new branches should be inserted", function(){
            //FIXME: That is buggy in Maarch Treeview
            xdescribe("as a li string structure", function(){
                var liStruct = '<li id="1_4">branch 1.4</li>';
                it("with an internal parent id", function(){
                    tree.insertBranches(liStruct, tree.prefix + "1");
                    console.log(tree);
                    expect($(tree.prefix + "1_4")).toExist();
                    expect($(tree.prefix + "1_4")).toContain("span");
                });
                it("with an external parent id", function(){
                    tree.insertBranches(liStruct, "1");
                    expect($(tree.prefix + "1_4")).toExist();
                    expect($(tree.prefix + "1_4")).toContain("span");
                });
                it("with a parent branch", function(){
                    tree.insertBranches(liStruct, tree.getBranchById(tree.prefix + "1"));
                    expect($(tree.prefix + "1_4")).toExist();
                    expect($(tree.prefix + "1_4")).toContain("span");
                });
                xit("as a parent method", function(){
                    tree.getBranchById(tree.prefix + "1").insertBranches(liStruct);
                    expect($(tree.prefix + "1_4")).toExist();
                    expect($(tree.prefix + "1_4")).toContain("span");
                });
            });
            describe("as a hash structure", function(){
                var hashStruct = [{id: "1_4", label:"branch 1.4"}];
                it("with an internal parent id", function(){
                    tree.insertBranches(hashStruct, tree.prefix + "1");
                    expect($(tree.prefix + "1_4")).toExist();
                    expect($(tree.prefix + "1_4")).toContain("span");
                });
                it("with an external parent id", function(){
                    tree.insertBranches(hashStruct, "1");
                    expect($(tree.prefix + "1_4")).toExist();
                    expect($(tree.prefix + "1_4")).toContain("span");
                });
                it("with a parent branch", function(){
                    tree.insertBranches(hashStruct, tree.getBranchById(tree.prefix + "1"));
                    expect($(tree.prefix + "1_4")).toExist();
                    expect($(tree.prefix + "1_4")).toContain("span");
                });
                xit("as a parent method", function(){
                    tree.getBranchById(tree.prefix + "1").insertBranches(hashStruct);
                    expect($(tree.prefix + "1_4")).toExist();
                    expect($(tree.prefix + "1_4")).toContain("span");
                });
            });
        });

        describe("a branch can be selected", function(){
            it("with an internal id", function(){
                tree.select(tree.prefix + "1_2");
                expect($(tree.prefix + "1_2")).toHaveClass("mt_selected");
            });
            it("with an external id", function(){
                tree.select("1_2");
                expect($(tree.prefix + "1_2")).toHaveClass("mt_selected");
            });
            it("with a branch element", function(){
                tree.select($(tree.prefix + "1_2"));
                expect($(tree.prefix + "1_2")).toHaveClass("mt_selected");
            });
            xit("as a branch method", function(){
                tree.getBranchById("1_2").select();
                expect($(tree.prefix + "1_2")).toHaveClass("mt_selected");
            });
            describe("when a branch is selected", function(){
                it("should deselect previously selected branches", function(){
                    tree.select("1_2");
                    tree.select("1_3");
                    expect($(tree.prefix + "1_2")).not.toHaveClass("mt_selected");
                    expect($(tree.prefix + "1_3")).toHaveClass("mt_selected");
                });
                it("should fire a maarch:tree:branchselect event", function(){
                    this.after(function(){
                        tree.root.stopObserving("maarch:tree:branchselect");
                    });
                    var probe = false;
                    var myCallback = function(){
                        probe = true;
                    };
                    tree.root.observe("maarch:tree:branchselect", myCallback);
                    tree.select("1_3");
                    waitsFor(function(){
                        return probe;
                    }, "callback has not been called", 10000);
                    runs(function(){
                        expect(probe).toBeTruthy();
                    });
                });
                it("should fire a maarch:tree:branchselect event with the branch id", function(){
                    this.after(function(){
                        $(document).stopObserving("maarch:tree:branchselect");
                    });
                    var myCallback = function(evt){
                        expect(evt.memo.branch_id).toEqual("1_3");
                    };
                    $(document).observe("maarch:tree:branchselect", myCallback);
                    tree.select("1_3");
                });
            });
            it("must return the selected branch", function(){
                var branch = tree.getBranchById("1")
                expect(tree.select(branch)).toBe(branch);
            });
        });

        describe("a branch can be deselected", function(){
            it("with an internal id", function(){
                tree.select(tree.prefix + "1_2");
                tree.deselect(tree.prefix + "1_2");
                expect($(tree.prefix + "1_2")).not.toHaveClass("mt_selected");
            });
            it("with an external id", function(){
                tree.select("1_2");
                tree.deselect("1_2");
                expect($(tree.prefix + "1_2")).not.toHaveClass("mt_selected");
            });
            it("with a branch element", function(){
                tree.select($(tree.prefix + "1_2"));
                tree.deselect($(tree.prefix + "1_2"));
                expect($(tree.prefix + "1_2")).not.toHaveClass("mt_selected");
            });
            xit("as a branch method", function(){
                tree.getBranchById("1_2").select();
                tree.getBranchById("1_2").deselect();
                expect($(tree.prefix + "1_2")).not.toHaveClass("mt_selected");
            });
            it("must return the deselected branch", function(){
                var branch = tree.getBranchById("1")
                expect(tree.deselect(branch)).toBe(branch);
            });
        });


    });
});
