SHELL=/bin/bash

all: build/dev/.htaccess build/prod/.htaccess build/dev/php.ini build/prod/php.ini
build: .make/build
.PHONY: all build

include lib/make/functions.makefile
include lib/make/commands.makefile
include lib/make/npm.makefile
include lib/make/gulp.makefile
include lib/make/bower.makefile

SRC_EXTENSIONS=js coffee css scss sass less php
SRC:=$(shell find src $(call TAIL,2,$(foreach i,$(SRC_EXTENSIONS),-o -name '*.$(i)')))

.make/build: .make/packages/gulp .make/node-modules .make/bower-packages $(SRC)
	gulp build && $(MKFILE)

build/dev/.htaccess: build/dev/config.php src/php/config.htaccess.php
	./bin/process.php -c $^ > $@

build/prod/.htaccess: build/prod/config.php src/php/config.htaccess.php
	./bin/process.php -c $^ > $@

build/dev/php.ini: build/dev/config.php src/php/php.ini.php
	./bin/process.php -c $^ > $@

build/prod/php.ini: build/prod/config.php src/php/php.ini.php
	./bin/process.php -c $^ > $@

