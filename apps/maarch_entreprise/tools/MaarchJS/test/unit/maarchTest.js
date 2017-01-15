describe("Maarch", function(){
    
    it("should exist", function(){
        expect('Maarch').not.toBe(undefined);
        expect(typeof(Maarch)).toBe("object");
    });

    describe("if the version of Prototype is not supported", function(){
        var oldProtoVersion;
        beforeEach(function(){
            oldProtoVersion = Prototype.Version;
        });
        afterEach(function(){
            Prototype.Version = oldProtoVersion;
        });
        xit("should throw an error", function(){
            Prototype.Version = "1.0.0.0";
            expect(Maarch.load).toThrow();
        });
        it("should not exist", function(){
            Prototype.Version = "1.0.0.0";
            expect(Maarch).not.toExist();
        })
    });

    
    it("should have a version", function(){
        expect(Maarch.Version).toBeTruthy();
    });
    
    it("should have a cssPath property", function(){
        expect(Maarch.cssPath).toBeTruthy();
    });
    it("should have a jsPath property", function(){
        expect(Maarch.jsPath).toBeTruthy();
    });
    

    describe("should add new features to older versions of prototype", function(){
        xit("should execute Maarch._fixPrototype", function(){
            spyOn(Maarch, "_fixPrototype");
            Maarch.load();
            expect(Maarch._fixPrototype).toHaveBeenCalled();
        });
        xit("should add the .on method to Element objects", function(){

        });
    });
    
    describe("when Maarch.js injects CSS files", function(){
        afterEach(function(){
            $$("link[href$=maarch-treeview.css]").invoke("remove");
        });
        it("should load CSS Files", function(){
            Maarch.cssPath = "../../dist/css/";
            Maarch.injectCSSFile("maarch-treeview");
            expect($$("link[href$=maarch-treeview.css]")).not.toEqual([]);
        });
        it("should not load the same CSS file twice", function(){
            Maarch.cssPath = "../../dist/css/";
            Maarch.injectCSSFile("maarch-treeview");
            Maarch.injectCSSFile("maarch-treeview");
            expect($$("link[href$=maarch-treeview.css]").size()).toEqual(1);
        });
    });

    describe("when Maarch.js injects inline CSS", function(){
        it("should add inline CSS", function(){
            this.after(function(){
                $$("style").invoke("remove");
            });
            setFixtures(sandbox({id: 'my-id'}));
            Maarch.injectInlineCSS("#my-id{display:none;}");
            expect(document.getElementById('my-id')).toExist();
            expect(document.getElementById('my-id')).toBeHidden();
        });
    });
    
    describe("when Maarch.js inject JS", function(){
        afterEach(function(){
            this.after(function(){
                $$("script[src$=maarch-treeview.js]").invoke("remove");
            });
        });
        it("should load JS Files", function(){
            Maarch.injectJS("maarch-treeview");
            expect($$("script[src$=maarch-treeview.js]")).not.toEqual([]);
        });
        it("should not load the same JS file twice", function(){
            Maarch.injectJS("maarch-treeview");
            Maarch.injectJS("maarch-treeview");
            expect($$("script[src$=maarch-treeview.js]").size()).toEqual(1);
        });
    });

    describe("when it loads a library", function(){
        afterEach(function(){
            $$("script[src$=maarch-treeview.js]").invoke("remove");
            $$("style").invoke("remove");
            $$("link[href$=maarch-treeview.css]").invoke("remove");
        });
        it("should inject the javascript file", function(){
            spyOn(Maarch, "injectJS");
            Maarch.require("treeview");
            expect(Maarch.injectJS).toHaveBeenCalled();
        });
        it("should inject the CSS file", function(){
            spyOn(Maarch, "injectCSSFile");
            Maarch.require("treeview");
            expect(Maarch.injectCSSFile).toHaveBeenCalled();
        });
        it("must load the callback when the library is loaded", function(){
            var i = 10;
            var myCallback = function(){
                i+=1;
            };
            Maarch.require("treeview", myCallback);
            waits(1000);
            runs(function(){
                expect(i).not.toEqual(10);
            });
        });
    });

    if(Prototype.Browser.IE){
    describe("when it runs on IE", function(){
        it("should be able to wrap an element in a special div", function(){
            setFixtures(sandbox('<div id="wrapper"><div id="wrapped"></div></div>'));
            Maarch.addIEDiv("my-id");
            expect($('wrapped').up('div').id).not.toEqual("wrapper");
        });
    });
    }
});