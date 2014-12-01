# $(call TAIL,n,words)
#   Return all but the first `n - 1` words.
TAIL=$(wordlist $1,$(words $2),$2)

# $(call JOIN,sep,words)
#   Return `words` with the words `sep` in between each element.
JOIN=$(call TAIL,$(words $1 x),$(foreach w,$2,$1 $(w)))
