SHELL=/bin/bash

all: dev prod

include bin/functions.makefile
include bin/gulp.makefile

build: .make/build
.PHONY: all dev prod

.make/build: gulp gulp-plugins
	gulp build && $(MKFILE)

