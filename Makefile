JS=app/webroot/js
CSS=app/webroot/css
MINJS=$(JS)/arcs.min.js
MINCSS=$(CSS)/arcs.min.css
JS_DOCS=docs/js

MODELS=$(wildcard $(JS)/models/*.js)
COLLECTIONS=$(wildcard $(JS)/collections/*.js)
VIEWS=$(wildcard $(JS)/views/*.js)
UTILS=$(wildcard $(JS)/utils/*.js)
CSS_VENDORS=$(wildcard $(CSS)/vendor/*.css)

VERSION=v0.1
ARCS="/**\n\
  * ARCS\n\
  * $(VERSION)\n\
  *\n\
  * MSU College of Arts and Letters\n\
  */\n"

js:
	echo $(ARCS) > $(MINJS)
	# Order matters here, so unfortunately we can't just use wildcards
	uglifyjs $(JS)/vendor/jquery.min.js >> $(MINJS)
	uglifyjs $(JS)/vendor/underscore-min.js >> $(MINJS)
	uglifyjs $(JS)/vendor/backbone-0.5.0.js >> $(MINJS)
	uglifyjs $(JS)/vendor/mustache.js >> $(MINJS)
	# jQuery UI & friends
	uglifyjs $(JS)/vendor/jquery.ui.core.js >> $(MINJS)
	uglifyjs $(JS)/vendor/jquery.ui.widget.js >> $(MINJS)
	uglifyjs $(JS)/vendor/jquery.ui.mouse.js >> $(MINJS)
	uglifyjs $(JS)/vendor/jquery.ui.position.js >> $(MINJS)
	uglifyjs $(JS)/vendor/jquery.ui.draggable.js >> $(MINJS)
	uglifyjs $(JS)/vendor/jquery.ui.selectable.js >> $(MINJS)
	uglifyjs $(JS)/vendor/jquery.ui.autocomplete.js >> $(MINJS)
	# Bootstrap
	uglifyjs $(JS)/vendor/bootstrap-modal.js >> $(MINJS)
	uglifyjs $(JS)/vendor/bootstrap-alert.js >> $(MINJS)
	uglifyjs $(JS)/vendor/bootstrap-twipsy.js >> $(MINJS)
	uglifyjs $(JS)/vendor/bootstrap-tooltip.js >> $(MINJS)
	uglifyjs $(JS)/vendor/bootstrap-popover.js >> $(MINJS)
	uglifyjs $(JS)/vendor/bootstrap-dropdown.js >> $(MINJS)
	uglifyjs $(JS)/vendor/bootstrap-tab.js >> $(MINJS)
	# Misc Plugins
	uglifyjs $(JS)/vendor/jquery.elastislide.js >> $(MINJS)
	uglifyjs $(JS)/vendor/jquery.imgareaselect.min.js >> $(MINJS)
	uglifyjs $(JS)/vendor/relative-date.js >> $(MINJS)
	# ARCS stuff
	uglifyjs $(JS)/app.js >> $(MINJS)
	uglifyjs $(JS)/utils.js >> $(MINJS)
	uglifyjs $(JS)/templates.js >> $(MINJS)
	# Rest is ok to wildcard now.
	$(foreach util, $(UTILS), uglifyjs $(util) >> $(MINJS);)
	$(foreach model, $(MODELS), uglifyjs $(model) >> $(MINJS);)
	$(foreach col, $(COLLECTIONS), uglifyjs $(col) >> $(MINJS);)
	$(foreach view, $(VIEWS), uglifyjs $(view) >> $(MINJS);)

css:
	echo $(ARCS) > $(MINCSS)
	# Vendors first
	$(foreach vendor, $(CSS_VENDORS), cleancss $(vendor) >> $(MINCSS);)
	cleancss $(CSS)/arcs-skin.css >> $(MINCSS)
	cleancss $(CSS)/style.css >> $(MINCSS)

doc:
	cd $(JS_DOCS); \
	docco ../../$(JS)/*.coffee; \
	docco ../../$(JS)/collections/*.coffee; \
	docco ../../$(JS)/models/*.coffee; \
	docco ../../$(JS)/views/*.coffee;
	mv $(JS_DOCS)/docs/* $(JS_DOCS)/
	rm -r $(JS_DOCS)/docs
