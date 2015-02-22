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
PROCESS_PHP=cd $(@D) && ../../vendor/phrame/bin/run.php -i ../../src/php/autoload.php ../../$< > $(@F)

.make/build: .make/packages/gulp .make/node-modules .make/bower-packages $(SRC)
	gulp build && $(MKFILE)

build/dev/.htaccess: src/php/templates/htaccess.php src/php/config.php build/dev/config.php
	$(PROCESS_PHP)
build/prod/.htaccess: src/php/templates/htaccess.php src/php/config.php build/prod/config.php
	$(PROCESS_PHP)

build/dev/php.ini: src/php/templates/php.ini.php src/php/config.php build/dev/config.php
	$(PROCESS_PHP)
build/prod/php.ini: src/php/templates/php.ini.php src/php/config.php build/prod/config.php
	$(PROCESS_PHP)

build/dev/robots.txt: src/php/templates/robots.txt.php src/php/config.php build/dev/config.php
	$(PROCESS_PHP)
build/prod/robots.txt: src/php/templates/robots.txt.php src/php/config.php build/prod/config.php
	$(PROCESS_PHP)
