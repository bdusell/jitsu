# $(RM) arg1 arg2 ...
#   Delete files and directories.
RM=rm -rf --

# $(call DOWNLOAD,url) # output is target
# $(call DOWNLOAD,url,output)
#   Download the file located at a URL.
DOWNLOAD={ wget -O $(if $2,$2,$@) $1 || { $(RM) $(if $2,$2,$@); false; }; }

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
INSTALL={ { which $(if $1,$1,$(@F)) || $(call INSTALL__PACKAGE,$(if $2,$2,$(if $(1),$(1),$(@F)))); } && $(call MKFILE); }
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
