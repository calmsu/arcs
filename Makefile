# Makefile
# --------
# Builds assets and documentation for ARCS.
#
# To use it, you'll need NodeJS and the following NPM packages:
#   - coffee-script
#   - uglify-js
#   - clean-css
#   - less
#
# To build the docs, we're using markdown_py, from the Python
# Markdown module. You can substitute it with another Markdown
# converter that supports tables.

# Asset dirs
COFFEE=app/webroot/coffee
JS=app/webroot/js
CSS=app/webroot/css
ASSETS=app/webroot/assets

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
	coffee --compile --output $(JS) $(COFFEE)

# Compile less in (and included in) css/app.less to css/app.css
less:
	cd $(CSS); lessc app.less > app.css

# Concatenate and minify javascript.
js: coffee
	echo $(HEADER) > $(ASSETS)/arcs.js
	$(foreach script, $(SCRIPTS), uglifyjs -nc $(JS)/$(script) >> $(ASSETS)/arcs.js;)
	cat $(ASSETS)/templates.js >> $(ASSETS)/arcs.js

# Concatenate and minify css.
css: less
	echo $(HEADER) > $(ASSETS)/arcs.css
	$(foreach style, $(STYLESHEETS), cleancss $(CSS)/$(style) >> $(ASSETS)/arcs.css;)

# Convert user documentation from Markdown and put it in the View directory.
doc: 
	$(foreach doc, $(DOCS), markdown_py -x tables $(doc) > \
		app/View/Help/$(notdir $(basename $(doc))).ctp;)

# Make everything.
all: js css doc
