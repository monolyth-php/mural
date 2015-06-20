# Mural
MUltiple "Resource" AutoLoader

Mural is a small package containing a custom autoloader to sanely serve _and_
test multiple "apps" (websites, normally) from the same repo, while keeping your
shared code base clean.

## Installation

### Composer (recommended)
```composer require monomelodies/mural```

### Manual
Clone or download the repo, and make sure the `Mural` namespace maps to the
`src` directory of Mural in your autoloader. Since it's only one file, you
could also `require` it manually.

## The problem
Your average small site will have code somewhere (say, `src`) and a public
directory with your `index.php` front controller (say, `httpdocs`). Since
normally `httpdocs` will only contain static files, you can set your autoloader
(e.g. in `composer.json`) to look at `src` and be done with it. The same goes
for any external packages.

However, more complicated projects will have multiple public facing paths, with
a few project-specific overrides but mostly a bunch of shared code.

One could tailor PHP's `include_path` to automatically include the right code,
but this is problematic during testing: you would need a test suite per project
(site), since class names would be identical and would trigger a fatal error.

## The solution
The Mural autoloader allows you to specifically namespace these overrides,
whilst still exposing the "original" names to consuming classes. During testing,
simply don't include it and test to your heart's content with the original,
fully namespaced (and thus unique) classes. In your application use the
"normalised" classnames instead.

## WHY $DEITY??? An example
Okay, a bit more clarification. This is actually a real-world example.

Say you have a group of dating sites. Lots of functionality (messaging,
profile editing etc.) is shared, but there are some specifics. `straight.com` is
aimed at straight people, but `gay.com` is aimed at gay people (duh). Apart from
the logo and some other CSS they're identical, except in one important respect:
when searching, `straight.com` should only match male <> female results, whereas
`gay.com` should only match male <> male or female <> female results. A
fictional directory layout might be:

```
/path/to/sites
    /vendor <- packages
    /src <- shared source code
    /straight.com
        /Search.php
    /gay.com
        /Search.php
```

Normally, either both `Search` classes would simply be called `\Search`, _or_
they'd be in their site's namespace (e.g. `Straight\Search` etc) and you'd have
to remember to load the correct one in your (shared) controller, which is
clunky:

```
<?php

class SearchController
{
    public function search()
    {
        if ($_SERVER['SERVER_NAME'] == 'straight.com') {
            $search = new Straight\Search;
        } else {
            $search = new Gay\Search;
        }
        // ...
    }
}

```

You get the idea. This kind of code makes me cringe, and it quickly becomes
unmaintainable. What *I* want to do is this:

```
<?php

class SearchController
{
    public function search()
    {
        $search = new Search;
        // ...
    }
}

```

...and handle the override of the `Search` component elsewhere. That's when
Mural was born!

## How it works
Mural prepends itself to the autoloader stack, and rewrites class names you
specify it to:

```
<?php

$mural = new Mural\Autoloader;
$mural->rewrite('\\', 'Straight\\');

```

The above example essentially says "for every class, check if there's a version
under `Straight\\` first. If so, use that instead".

A lot can be said about PHP, but its autoloading mechanism is pretty well
thought-out. If there's no "aliased" class, Mural will simply pass on the
autoloading logic to the next autoloader in the chain.

## Wrapping it up
In `straight.com/index.php`:

```
<?php

$mural = new Mural\Autoloader;
$mural->rewrite('\\', 'Straight\\`);

```

...and in `gay.com/index.php`:

```
<?php

$mural = new Mural\Autoloader;
$mural->rewrite('\\', 'Gay\\');

```

...and finally in your tests, just test `Straight\Search` and `Gay\Search`.

Mural blindly checks a string match and kicks into action if `strpos === 0`. So
you can just as well only override subnamespaces.

