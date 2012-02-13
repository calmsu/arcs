# Makefile
# --------
# Builds assets for ARCS.

# Asset dirs
COFFEE=app/webroot/coffee
JS=app/webroot/js
CSS=app/webroot/css
LESS=app/webroot/css/lib

# Need to outsource parsing the INI file
SCRIPTS=$(shell bin/get-assets --js)
STYLESHEETS=$(shell bin/get-assets --css)

# ARCS version and license header
VERSION=$(shell cat VERSION)
HEADER="/**\n\
  * ARCS\n\
  * $(VERSION)\n\
  *\n\
  * Copyright 2012, Michigan State University Board of Trustees\n\
  */\n"

coffee:
	coffee --compile --bare --output $(JS) $(COFFEE)

less:
	lessc $(LESS)/arcs-skin.less > $(CSS)/arcs-skin.css

js: coffee
	echo $(HEADER) > $(JS)/arcs.min.js
	$(foreach script, $(SCRIPTS), uglifyjs $(JS)/$(script) >> $(JS)/arcs.min.js)
	
css: less
	echo $(HEADER) > $(CSS)/arcs.min.css
	$(foreach style, $(STYLESHEETS), cleancss $(CSS)/$(style) >> $(CSS)/arcs.min.css)

all: js css
