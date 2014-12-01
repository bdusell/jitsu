# $(call TAIL,n,words)
#   Return all but the first `n - 1` words.
TAIL=$(wordlist $1,$(words $2),$2)

# $(call JOIN,sep,words)
#   Return `words` with the words `sep` in between each element.
JOIN=$(call TAIL,$(words $1 x),$(foreach w,$2,$1 $(w)))

# $(RM) arg1 arg2 ...
#   Delete files and directories.
RM=rm -rf --

# $(DOWNLOAD) url # output is target
# $(call DOWNLOAD,url,output)
#   Download the file located at a URL.
DOWNLOAD=wget -O $(if $2,$2,$@)$(if $1, $1,)

# $(MKDIRS) # use target
# $(call MKDIRS,path)
#   Ensure that the directories named in path have been created.
MKDIRS=mkdir -p $(dir $(if $1,$1,$@))

# $(MKFILE) # use target
# $(call MKFILE,path)
#   Ensure that the file specified by path has been created, creating parent
#   directories as needed.
MKFILE={ $(call MKDIRS,$1) && touch $(if $1,$1,$@); }

# $(INSTALL) # use basename of target
# $(call INSTALL,command) # use command name for package name
# $(call INSTALL,command,package)
#   Ensure that a command has been installed on the system.
INSTALL={ { which $(if $1,$1,$(@F)) || $(call INSTALL__PACKAGE,$(if $2,$2,$(if $(1),$(1),$(@F)))); } && $(MKFILE); }
INSTALL__PACKAGE=sudo apt-get install $1

# $(FILETYPE)
#   Get the extension of the target path, without the dot.
FILETYPE=$(FILETYPE__SUFFIX:.%=%)
FILETYPE__SUFFIX=$(suffix $@)

# $(UNZIP) # use basename of target
# $(call UNZIP,filename)
#   Extract a particular file from a zip file.
UNZIP=unzip -DD -d $(@D) -j $< "**/$(if $(1),$(1),$(@F))"

# $(GUNZIP) # use first dependency
# $(call GUNZIP,input)
#   Uncompress a .tar.gz file.
UNGZIP=gunzip -k -c $(if $1,$1,$<) > $@

# $(INSTALL_NODE_CLI_PACKAGE) # use basename of target and add -cli for package name
# $(call INSTALL_NODE_CLI_PACKAGE,command,package)
#   Install a NodeJS CLI package globally.
INSTALL_NODE_CLI_PACKAGE={ { which $(if $1,$1,$(@F)) || npm install -g $(if $2,$2,$(@F)-cli); } && $(MKFILE); }

# $(INSTALL_NODE_MODULE) # use basename of target
# $(call INSTALL_NODE_MODULE,command,package)
#   Install a NodeJS package locally.
INSTALL_NODE_MODULE={ { which $(if $1,$1,$(@F)) || npm install $(if $2,$2,$(@F)); } && $(MKFILE); }

