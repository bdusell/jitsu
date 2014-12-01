npm: .make/packages/npm
gulp: .make/packages/gulp
gulp-plugins: .make/node-packages
.PHONY: npm gulp gulp-plugins

.make/packages/npm:
	$(INSTALL)

.make/node-packages: package.json npm
	npm install && $(MKFILE)

.make/packages/gulp: npm
	$(INSTALL_NODE_CLI_PACKAGE)
