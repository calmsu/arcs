# Makefile
# --------
# Builds assets and documentation for ARCS.
#
# To use it, you'll need NodeJS and the following NPM packages:
#   - coffee-script
#   - uglify-js
#   - clean-css
#   - less
#   - markdown

# Asset dirs
COFFEE=app/webroot/coffee
JS=app/webroot/js
CSS=app/webroot/css
LESS=app/webroot/css/lib

# Need to outsource parsing the INI file
SCRIPTS=$(shell bin/get-assets --js)
STYLESHEETS=$(shell bin/get-assets --css)

DOCS=$(wildcard docs/user/*)

# ARCS version and license header
VERSION=$(shell cat VERSION)
HEADER="/**\n\
  * ARCS\n\
  * $(VERSION)\n\
  *\n\
  * Copyright 2012, Michigan State University Board of Trustees\n\
  */\n"

# Compile coffee in webroot/coffee to webroot/js
coffee:
	coffee --compile --bare --output $(JS) $(COFFEE)

# Compile less in css/lib to css/arcs-skin.css
less:
	lessc $(LESS)/arcs-skin.less > $(CSS)/arcs-skin.css

# Concatenate and minify javascript.
js: coffee
	echo $(HEADER) > $(JS)/arcs.min.js
	$(foreach script, $(SCRIPTS), uglifyjs $(JS)/$(script) >> $(JS)/arcs.min.js;)

# Concatenate and minify css.
css: less
	echo $(HEADER) > $(CSS)/arcs.min.css
	$(foreach style, $(STYLESHEETS), cleancss $(CSS)/$(style) >> $(CSS)/arcs.min.css;)

# Convert user documentation from Markdown and put it in the View directory.
doc: 
	$(foreach doc, $(DOCS), markdown_py -x tables $(doc) > \
		app/View/Docs/$(notdir $(basename $(doc))).ctp;)

all: js css doc
