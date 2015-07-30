Jitsu 実 ~ RESTful PHP on easy mode
===================================

Jitsu is a suite of PHP libraries for building modern web applications and REST
APIs. Its purpose is to help you cut straight to the task at hand instead of
struggling with the many shortcomings and idiosyncracies of the PHP language
and its standard library.

Projects based on Jitsu are designed to be trivial to install and configure on
any system which has Apache set up to process `.php` and `.htaccess` files
&ndash; a ubiquitous setup, especially in shared hosting environments.

## Motivation ##

PHP is a nice tool for novice web developers. For starters, most web servers
come pre-configured to run PHP without any additional effort. You write "hello
world" to a file named `index.php` and _boom_, you have a web page. Put some
more code in some more files with some other names, and _bam_, you have a whole
website. By all rights PHP looks and feels like magic HTML. Because PHP is a
templating language, the several languages essential for web development can
coexist in the same source file. This reduces the complexity of HTML form
submission to printing the appropriate HTML markup and retrieving the submitted
values through a variable named `$_REQUEST`. Play it right, and you can always
have all of your HTML, CSS, JavaScript, backend logic, SQL code, etc. in one
big `.php` file.

Obviously, this big-ball-of-code approach is not acceptable for larger projects.
As soon as your aspirations evolve from "how do I make a web page which asks
for a user's name?" to "how do I make a REST API service for my web app?", the
built-in APIs tend to hinder more than help. What might be their worst aspect
is that many of these built-in features have unusual interfaces or use names
which are just plain difficult to remember. Some features, like setting
cookies, are function calls. Others things, like reading cookies, are
superglobal array variables. Still other things come in the form of special
file stream names like `php://input`.

It doesn't help that many of the built-in string and array functions behave
poorly on edge cases. A lot of functions give you the error "empty needle" if
you, say, try to test whether a string contains the empty string. String and
array slicing gets really messy whenever you can't ensure that all the indices
end up within the boundaries. And let's not even get started on error handling
in PHP.

Jistu offers a saner PHP development experience, providing memorable function
names, well-defined string and array functions, object-oriented HTTP requests
and responses, request routing, and more. A few highlights include:

* Auto-loading for all `\jitsu\` classes
* A `Util` class with some must-have helper functions such as:
  * `get` (get an array element or a default value)
  * `p` (debug printing)
  * `template` (include a PHP file within a private function scope)
* An `ArrayUtil` class with array helper functions
* A `StringUtil` class with string helper functions
* A `RegexUtil` class with a more succinct regular expression API
* Wrapper classes `XArray`, `XString`, and `XRegex` built upon the
  aforementioned classes to provide a rich, object-oriented interface to
  their built-in PHP counterparts
* A much more developer-friendly SQL database API built upon PHP's PDO library
* An object-oriented SQL syntax abstraction layer, allowing you to switch
  freely between different SQL dialects like SQLite and MySQL

## Getting Started ##

Include the file `src/autoload.php` in your main script in order to set up
[PSR-4](http://www.php-fig.org/psr/psr-4/)-compliant auto-loading for the
Jitsu library (namespace `jitsu`). This means that merely referencing any
class under the `jitsu` namespace will implicitly include its definition, if
it exists. Many library components which are actually functions are available
in the form of static methods so that they can be accessed through
auto-loading.

Currently, the best source for documentation is the inline comments above each
class and function definition. The Jitsu library code is found under `src/`.
All classes under the `jitsu` namespace are found under `src/classes`. Some
files which define functions are found under `src/functions`.

The example project under `demo/` shows how to bootstrap and configure the Jitsu
library. It does the following:

* Sets up class auto-loading
* Uses a flexible, general-purpose configuration system which allows an
  application to be served from an arbitrary mounting point and document root
* Activates intelligible error reporting in development mode and silences
  errors in production mode
* Uses a robust routing mechanism which uses local `.htaccess` files and
  pattern matching logic implemented in PHP, while still serving static assets
  directly through Apache
* As a consequence of the above, is able to hide PHP from the outside world
  entirely; adding `.php` to a page URL results in a 404

In order to generate certain configuration files which are external to PHP
(`.htaccess`, `ini.php`, `robots.txt`), Jitsu leverages an executable script,
`run.php`, to create them dynamically using the project's configuration
settings. The example `Makefile` contains rules to create these files from
templates which reside under `demo/src/php/templates/`.

The example project also includes configuation files for the build tools
Bower and Gulp. The example gulpfile is used to concatenate and minify
CSS and JavaScript assets.

## Usage ##

Jitsu is designed so that any system which has Apache configured to run a
fairly recent version of PHP and process `.htaccess` files can serve an
application simply by including a few of its bootstrapping files into a file
named, say, `index.php`, generating an appropriate `.htaccess` file, and
serving its directory to the web. On a Linux system, this could be as simple
as generating a build and creating a symlink under `/var/www/` to its
directory.

In the example project, the build process requires the use of `gulp`, `bower`,
and `make`. Follow these steps to install these build tools:

* Install NodeJS and `npm`
* Run `npm install`, which installs the necessary NodeJS packages for Gulp
* Install the `gulp` CLI tool
* Install `bower`
* Run `bower install`, which fetches third-party frontend JavaScript and CSS
  libraries
* Run `gulp build`, which compiles whatever sources for the site's JavaScript
  and CSS are found under `src/` and places the results under `build/dev/` and
  `build/prod/`
* Run `make`, which generates local `.htaccess`, `robots.txt`, and `php.ini`
  files for each environment
* If the project requires a database to be set up, run the appropriate setup
  script under `bin/`

## Configuring ##

Jitsu configuration settings include:

<dl>
  <dt><code>scheme</code></dt>
  <dd>The HTTP protocol to expect, either <code>'http'</code> or
  <code>'https'</code>. Default is <code>'http'</code>.</dd>

  <dt><code>host</code></dt>
  <dd>Expected hostname of the server. Default is
  <code>'localhost'</code>.</dd>

  <dt><code>path</code></dt>
  <dd>External mounting point of the application. Default is empty, so the site
  is served from directly under the domain.</dd>

  <dt><code>base_url</code></dt>
  <dd>Sets or gets the full base URL of the application. If used to set the
  base URL, automatically parses the value and sets <code>scheme</code>,
  <code>host</code>, and <code>path</code> accordingly.</dd>

  <dt><code>show_errors</code></dt>
  <dd>Whether to display errors in responses.</dd>

  <dt><code>document_root</code></dt>
  <dd>The build's root directory from the point of view of the server. If the
  directory is symlinked, this will need to be different from <code>dir</code>.
  </dd>
</dl>

## Routing ##

Jitsu provides a better routing mechanism.

See [here](demo/src/php/Application.php) for an example of a router definition.

Patterns may include `:variables`, `*globs`, and `(optional)` parts. Variables
correspond to path components and do not match slashes. Globs, on the other
hand, match all characters. The captured values of variables and globs are
URL-decoded and passed as positional parameters to callbacks.

Patterns are tested in order, so they should be listed in decreasing order of
specificity.

Jitsu itself allows forward slashes to be encoded in path components, but some
server configurations may disallow this. For example, it is common for Apache
to re-encode encoded forward slashes in incoming URLs, or to refuse such
requests with 404 Not Found. This is a security measure to prevent crafty
individuals from gaining access to paths on the filesystem through unsanitized
inputs. You can allow encoded slashes by adding back the following directive in
the virtual host in your Apache configuration file:

```apache
AllowEncodedSlashes Off
```

## Wrapper Classes ##

Jitsu defines the wrapper classes `jitsu\XArray` for native PHP `array`s,
`jitsu\XString` for PHP `string`s, and `jitsu\XRegex` for PHP's `preg`
functions. These are object-oriented interfaces built on top of `ArrayUtil`,
`StringUtil`, and `RegexUtil`, respectively, which serve as the
non-object-oriented version of essentially the same API. When you want to use
the normalized API without creating wrapped objects, use the -`Util` classes
instead. In general, when a -`Util` function takes the native PHP equivalent as
its first argument, it is also a method on the corresponding wrapper class. The
wrapper class methods automatically unbox wrapped arguments and wrap return
values where appropriate; the -`Util` functions do not.

As an alternative to invoking `new \jitsu\XArray($array)`, etc. you can use
the global functions `xarray`, `xstring`, and `xregex` to wrap values in these
classes. These functions are defined in `src/functions/common.php`.

Refer to the detailed inline documentation in `ArrayUtil`, `StringUtil`, and
`RegexUtil`.

## Request Access ##

The class `jitsu\http\AbstractRequest` defines an object-oriented interface
for HTTP requests. The class `jitsu\http\CurrentRequest` implements this
interface for the current request being handled by the script, unifying the
various API quirks for accessing the URI, headers, etc. The class
`jitsu\Request` is the static version of the same thing.

## Response Building ##

Like the current HTTP request, the HTTP response is abstracted behind an
interface, `jitsu\http\AbstractResponse`, and implemented in
`jitsu\http\CurrentResponse`. Static methods are accessible through
`jitsu\ResponseUtil`.

## Application Code ##

See the example project under `demo/`. In this demo project, the `src/js/` and
`src/css/` directories house source code for the JavaScript and stylesheets,
respectively. The `src/php/` directory contains backend PHP code. It contains
the following directories.

<dl>
  <dt><code>controllers/</code></dt>
  <dd>Controllers which define methods for handling the various requests. The
  router definition maps HTTP requests to these handlers.</dd>

  <dt><code>helpers/</code></dt>
  <dd>Helper functions.</dd>

  <dt><code>templates/</code></dt>
  <dd>Miscellaneous configuration files, namely the source for
  <code>.htaccess</code>, <code>robots.txt</code>, and <code>php.ini</code>.
  These are actually PHP files which are passed through the script
  <code>run.php</code>, which processes files with the Jitsu library
  loaded. This allows parts of these configuration files to be generated
  dynamically based on settings in <code>config.php</code>.</dd>

  <dt><code>views/</code></dt>
  <dd>HTML views. Since PHP is at heart a templating language, the files
  contained here are simply HTML code interspersed with PHP tags. Dynamic
  values are referenced as local variables; they are expected to be set from
  within a call to <code>\jitsu\Util::template</code>.</dd>
</dl>

## SQL Databases ##

The class `\jitsu\sql\Database` is a object-oriented abstraction of a SQL
database. It is essentially a much more succint API built on top of PHP's
PDO library.

```php
$query = $db->query(
  'select "name", "email" from "users" where "age" between ? and ?',
  $min_age, $max_age
);
foreach($query as $row) {
  do_something($row->name, $row->email);
}

$row = $db->row('select "name" from "users" where "id" = ?', $id);
do_something($row->name);

$highest_age = $db->evaluate('select max("age") from "users"');

$db->execute(
  'insert into "users"("name", "email") values (?, ?)',
  'Joe', 'joe@example.com'
);
do_something($db->last_insert_id());
```

Refer to the inline documentation in `\jitsu\sql\Database` and
`\jitsu\sql\Statement` for more details about this API.

Jitsu also includes a SQL syntax abstraction layer so that an app may be
configured to use a different SQL driver (SQLite, MySQL, etc.) with seamless
transition. This is provided in `\jitsu\sql\Ast`.

## HTML Templates ##

Something PHP is actually good for! The demo HTML templates are kept separate
from application logic in files ending in `.html.php`. They reference dynamic
values using simple local variables &ndash; these are sent to the templates
using `\jitsu\Util::template`. The global functions `html` and `htmlattr`,
proved in `lib/functions/common.php`, escape values interpolated in HTML
properly.

Example:

In a file `views/users/index.html.php`:
```html
<section>
  <h1>Users</h1>
  <ul>
  <?php foreach($users as $user): ?>
    <li id="user-<?= htmlattr($user->id) ?>"><?= html($user->name) ?></li>
  <?php endforeach; ?>
  </ul>
</section>
```

Elsewhere:
```php
\jitsu\Util::template('views/users/index.html.php', array(
  'users' => $db->query('select "id", "name" from "users"')
));
```

## Unit Testing ##

The class `\jitsu\test\UnitTest` can be used as a base class to define unit
tests. Methods starting with `test` are treated as test cases. The script
`src/bin/test.php` can be used as a test case driver. Its argument should be
a PHP file which defines a unit test class, and nothing else; the driver will
automatically detect it, instantiate it, run it, and report results.

## Stylesheets ##

The example gulpfile is set up to compile SCSS files under `demo/src/css/` into
minified CSS.

## JavaScript ##

The example gulpfile is set up to concatenate and minify all JavaScript files
under `demo/src/js/` using Browserify.
