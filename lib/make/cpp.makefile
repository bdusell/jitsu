# $(call compile_cpp,input,output)
# $(COMPILE_CPP)
#   Compile a C++ file.
compile_cpp=g++ -Wall -O3 -s -o $2 -c $1
COMPILE_CPP=$(call compile_cpp,$<,$@)

# $(call link_cpp,inputs,output)
# $(LINK_CPP)
#   Link C++ object files into an executable.
link_cpp=g++ -Wall -o $2 $1
LINK_CPP=$(call link_cpp,$^,$@)

# $(call update_cpp_deps,sources,output)
# $(UPDATE_CPP_DEPS)
#   Write Makefile rules for the files listed in sources.
update_cpp_deps=$(call mkdirs,$(dir $2)) && { $(foreach x,$1,g++ $3 -MT $(x:.cpp=.o) -MM $(x); )true; } > $2
UPDATE_CPP_DEPS=$(call update_cpp_deps,$(filter *.cpp,$^),$@)

