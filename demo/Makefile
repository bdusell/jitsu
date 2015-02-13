SHELL=/bin/bash

all: \
	build/dev/.htaccess build/prod/.htaccess \
	build/dev/php.ini build/prod/php.ini \
	build/dev/robots.txt build/prod/robots.txt
build: .make/build
.PHONY: all build

include vendor/phrame/make/functions.makefile
include vendor/phrame/make/commands.makefile
include vendor/phrame/make/npm.makefile
include vendor/phrame/make/gulp.makefile
include vendor/phrame/make/bower.makefile

SRC_EXTENSIONS=js coffee css scss sass less
SRC=$(shell find src $(call TAIL,2,$(foreach i,$(SRC_EXTENSIONS),-o -name '*.$(i)')))
# TODO
PROCESS_PHP=(cd $(@D) && ../../vendor/phrame/bin/run.php > $@)

.make/build: .make/packages/gulp .make/node-modules .make/bower-packages $(SRC)
	gulp build && $(MKFILE)

build/dev/.htaccess: build/dev/config.php src/php/templates/config.htaccess.php
	$(PROCESS_PHP)
build/prod/.htaccess: build/prod/config.php src/php/templates/config.htaccess.php
	$(PROCESS_PHP)

build/dev/php.ini: build/dev/config.php src/php/templates/php.ini.php
	$(PROCESS_PHP)
build/prod/php.ini: build/prod/config.php src/php/templates/php.ini.php
	$(PROCESS_PHP)

build/dev/robots.txt: build/dev/config.php src/php/templates/robots.txt.php
	$(PROCESS_PHP)
build/prod/robots.txt: build/prod/config.php src/php/templates/robots.txt.php
	$(PROCESS_PHP)
