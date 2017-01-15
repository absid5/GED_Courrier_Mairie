#!/usr/bin/env python
from __future__ import print_function
import os
import sys
import subprocess
import shutil
import StringIO

SOURCE_DIR = "src"
DIST_DIR = "dist"

LINT_CMD = "java -jar lib/build/rhino.jar %(filename)s"
CLOSURE_CMD = "java -jar lib/build/compiler.jar --js=%(original)s > %(compressed)s"
YUI_CMD = "java -jar lib/build/yuicompressor-2.4.2.jar %(original)s > %(compressed)s"
SASS_CMD = "sass {original} {compressed} --style=compressed"





def setup_lint():
    os.mkdir('tmp')

def teardown_lint():
    shutil.rmtree('tmp')


def run_task(task):
    try:
        tasks[task]()
    except:
        print("\n***** Error while executing task %s" % (task,))
        sys.exit(3)

def sh(cmd, stdout=sys.stdout, stderr=sys.stderr):
    t = subprocess.Popen(cmd, shell=True, stdout=subprocess.PIPE, stderr=subprocess.PIPE)
    stdout, stderr = t.communicate()
    return_code = t.wait()
    if return_code != 0:
        print(stdout)
        print(stderr)
        raise Exception
    return (return_code, stdout, stderr)

def depends(*before, **kw):
    def wrapped(func):
        def wrapper(*args):
            if before:
                for task in before:
                    task()
            elif "before" in kw:
                if callable(kw["before"]):
                    kw["before"]()
                else:
                    for task in kw["before"]:
                        task()
            try:
                tmp = func(*args)
            except:
                pass
            finally:
                if "after" in kw:
                    if callable(kw["after"]):
                        kw["after"]()
                    else:
                        for task in kw["after"]:
                            task()
            if "tmp" in locals():
                return locals()['tmp']
            else:
                raise Exception
        return wrapper
    return wrapped

def main(args):
    if not len(args):
        if callable(default_task):
            run_task(default_task)
        else:
            print("Default task not set!")
            print("Available tasks: " + ", ".join(tasks))
        sys.exit(1)
    for task in args:
        if task not in tasks.keys():
            print("task %s not found" % (args[0],))
            print("Available tasks: " + ", ".join(tasks))
            sys.exit(2)
    for task in args:
        run_task(task)


def _do_lint(filename):
    with open(os.path.join('tmp', filename.split('/')[-1].replace('.js', '-lint.js')), "w") as lint_file:
        lint_file.write("""
        load('lib/build/jslint.js');
        load('lib/build/maarch-lint.js');
        maarch_check("%s");
        """ % (filename))
    with open(os.path.join('tmp', "lint-result.log"), 'a') as lint_log:
        rc, stdout, stderr = sh(LINT_CMD % {'filename': os.path.join('tmp', filename.split('/')[-1].replace('.js', '-lint.js'))})
        if len(stdout) + len(stderr):
            lint_log.write(filename+"\n"+"="*len(filename)+"\n\n")
            lint_log.write(stdout)
            lint_log.write("\n\n")
            lint_log.write(stderr)
            lint_log.write("\n\n\n\n")
        return 0
    return 1

def _compress_closure():
    for source_file in os.listdir(os.path.join(SOURCE_DIR, "js")):
        print("compressing %s" % (source_file,), end='... ')
        sh(CLOSURE_CMD % {'original': os.path.join(SOURCE_DIR, "js", source_file),
                          'compressed': os.path.join(DIST_DIR, source_file.replace('.js', '.clo.js'))})
        sh("gzip -c {0} > {1}".format(os.path.join(DIST_DIR, source_file.replace('.js', '.clo.js')),
                                    os.path.join(DIST_DIR, source_file.replace('.js', '.clo.js.gz'))))
        orig_size = os.path.getsize(os.path.join(SOURCE_DIR, "js", source_file))
        comp_size = os.path.getsize(os.path.join(DIST_DIR, source_file.replace('.js', '.clo.js')))
        gz_size = os.path.getsize(os.path.join(DIST_DIR, source_file.replace('.js', '.clo.js.gz')))
        print("compressed {0:.2f}% ({1} -> {2} -> {3})".format(gz_size*100./orig_size,
                                                    orig_size,
                                                    comp_size,
                                                    gz_size))


def _compress_yui():
    for source_file in os.listdir(os.path.join(SOURCE_DIR, "js")):
        print("compressing %s" % (source_file,), end='... ')
        sh(YUI_CMD % {'original': os.path.join(SOURCE_DIR, "js", source_file),
                          'compressed': os.path.join(DIST_DIR, source_file.replace('.js', '.yui.js'))})
        sh("gzip -c {0} > {1}".format(os.path.join(DIST_DIR, source_file.replace('.js', '.yui.js')),
                                    os.path.join(DIST_DIR, source_file.replace('.js', '.yui.js.gz'))))
        orig_size = os.path.getsize(os.path.join(SOURCE_DIR, "js", source_file))
        comp_size = os.path.getsize(os.path.join(DIST_DIR, source_file.replace('.js', '.yui.js')))
        gz_size = os.path.getsize(os.path.join(DIST_DIR, source_file.replace('.js', '.yui.js.gz')))
        print("compressed: {0:.2f}% ({1} -> {2} -> {3})".format(gz_size*100./orig_size,
                                                    orig_size,
                                                    comp_size,
                                                    gz_size))

########################################################################
##                         Define tasks here                          ##
########################################################################

@depends(before=setup_lint, after=teardown_lint)
def check():
    print("* Running jslint on js source files")
    for filename in os.listdir(os.path.join(SOURCE_DIR, "js")):
        res = _do_lint(os.path.join(SOURCE_DIR, "js", filename))
        if not res:
            print("***** error found in " + filename)
        else:
            print(filename + "... OK")
    if os.path.getsize(os.path.join('tmp', "lint-result.log")):
        os.rename(os.path.join('tmp', "lint-result.log"), "lint-result.log")
        print("See information about errors in lint-result.log")
        raise Exception

def compress():
    print("** Compressing js source files")
    _compress_closure()
    for filename in os.listdir(DIST_DIR):
        if filename.endswith(".gz"):
            os.remove(os.path.join(DIST_DIR, filename))
        elif filename.endswith(".clo.js"):
            os.rename(os.path.join(DIST_DIR, filename),
                      os.path.join(DIST_DIR, filename.replace('.clo.', '.')))
    #print("* trying with yui compressor")
    #_compress_yui()

def css():
    print("** Generating css")
    if not os.path.exists(os.path.join(DIST_DIR, 'css')):
        os.mkdir(os.path.join(DIST_DIR, 'css'))
    for filename in os.listdir(os.path.join(SOURCE_DIR, 'scss')):
        print("compiling {0}...".format(filename))
    sh(SASS_CMD.format(original=os.path.join(SOURCE_DIR, 'scss', filename),
                   compressed=os.path.join(DIST_DIR, 'css', filename.replace('.scss','.css'))))

def doc():
    print("** Generating documentation")
    if os.path.exists("doc"):
        shutil.rmtree("doc")
    sh("./lib/build/pdoc/bin/pdoc -o doc -d %s/js/ --params maarch.yml" % (SOURCE_DIR,))

def clean():
    print("** Cleaning directory")
    if os.path.exists(DIST_DIR):
        for path in os.listdir(DIST_DIR):
            shutil.rmtree(os.path.join(DIST_DIR, path))
    if os.path.exists("lint-result.log"):
        os.remove("lint-result.log")

@depends(check, clean, css, compress, setup_lint, doc, after=teardown_lint)
def dist():
    for filename in os.listdir(DIST_DIR):
        if filename.endswith(".js"):
            _do_lint(os.path.join(DIST_DIR, filename))
    if os.path.exists(os.path.join(DIST_DIR, 'img')):
        shutil.rmtree(os.path.join(DIST_DIR, 'img'))
    shutil.copytree(os.path.join(SOURCE_DIR, 'img'),
                    os.path.join(DIST_DIR, 'img'))

def diagrams():
    print("** Generating diagrams")
    sh("java -jar lib/build/plantuml.jar design design")



tasks = {
    'check': check,
    'compress': compress,
    'clean': clean,
    'css': css,
    'doc': doc,
    'dist': dist,
    'diagrams': diagrams,
}
default_task = None
########################################################################
########################################################################

if __name__=='__main__':
    main(sys.argv[1:])
