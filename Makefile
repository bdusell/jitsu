SHELL=/bin/bash

all: build build/dev/.htaccess build/prod/.htaccess
build: .make/build
.PHONY: all build

include bin/functions.makefile
include bin/gulp.makefile

SRC_EXTENSIONS=js coffee css scss sass less php
SRC:=$(shell find src $(call TAIL,2,$(foreach i,$(SRC_EXTENSIONS),-o -name '*.$(i)')))

.make/build: .make/packages/gulp .make/node-modules $(SRC)
	gulp build && $(MKFILE)

build/dev/.htaccess: build/dev/config.php src/php/config.htaccess.php
	./bin/process.php -c $^ > $@

build/prod/.htaccess: build/prod/config.php src/php/config.htaccess.php
	./bin/process.php -c $^ > $@

