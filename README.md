# Mural
Multiple "resource" autoloader

Mural is a small package containing a custom autoloader to sanely serve _and_
test multiple "apps" (websites, normally) from the same repo, while keeping your
shared code base clean.

## The problem
Your average small site will have code somewhere (say, `src`)
