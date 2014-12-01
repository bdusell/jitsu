.make/packages/bower: .make/packages/npm
	$(call INSTALL_NODE_CLI_PACKAGE,bower,bower)

.make/bower-packages: bower.json .make/packages/bower
	bower update && $(MKFILE)
