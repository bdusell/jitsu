.make/packages/npm:
	$(INSTALL)

# $(INSTALL_NODE_CLI_PACKAGE) # use basename of target and add -cli for package name
# $(call INSTALL_NODE_CLI_PACKAGE,command,package)
#   Install a NodeJS CLI package globally.
INSTALL_NODE_CLI_PACKAGE={ { which $(if $1,$1,$(@F)) || npm install -g $(if $2,$2,$(@F)-cli); } && $(call MKFILE); }

# $(INSTALL_NODE_MODULE) # use basename of target
# $(call INSTALL_NODE_MODULE,command,package)
#   Install a NodeJS package locally.
INSTALL_NODE_MODULE={ { which $(if $1,$1,$(@F)) || npm install $(if $2,$2,$(@F)); } && $(call MKFILE); }

.make/node-modules: package.json .make/packages/npm
	npm install && $(MKFILE)
