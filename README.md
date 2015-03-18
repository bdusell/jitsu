Phrame
======

Phrame is a PHP framework for building modern web applications and REST APIs.
It features a library of PHP classes and functions which help overcome the many
shortcomings and idiosyncracies of the language and its standard library.

Projects are designed to be trivial to install and configure on any system
which has Apache set up to process `.php` and `.htaccess` files &ndash; a
ubiquitous setup, especially in shared hosting environments.

## Motivation ##

PHP comes with a rudimentary web development API out of the box: "superglobal"
variables such as `$_GET` and `$_SERVER`, a special file stream `php://input`,
a host of built-in functions, and so on. Although workable, this API suffers
from numerous problems. Not only does it horribly break encapsulation, it is
insufficient to support a full suite of REST API functions without some extra
effort. What's worse is that many of these built-in features have awkward
interfaces or use names which are just plain difficult to remember. <q>How do
I access the request URI? Is it a function call? A superglobal? I already have
variables `$_GET` and `$_COOKIE`, so whatever happened to `$_HEADER`? Surely
`headers_list` returns the headers received in the request.</q>
<i>Ad nauseam</i>.

Phrame offers a normalized, more mnemonic API which irons out these quirks.
String and array functions have reasonable behavior on edge cases (no more
"empty needle" errors). The HTTP request and response APIs are unified under
an object-oriented interface. You can focus on developing your application
rather than coping with PHP's unhelpful default behavior.

Including the file `lib/autoload.php` sets up
[PSR-4](http://www.php-fig.org/psr/psr-4/)-compliant auto-loading for the
Phrame library (namespace `phrame`). This means that merely referencing any
class under the `phrame` namespace will implicitly include its definition, if
it exists. Many library components which are actually functions are available
in the form of static methods so that they can be accessed through
auto-loading.

A few highlights from the class/function library:

* `phrame\Util` includes some must-have helper functions such as:
  * `get` (array access with a default value)
  * `p` (debug printing for expressions)
  * `template` (encapsulated PHP file inclusion)
* Function collections `phrame\ArrayUtil`, `phrame\StringUtil`, and
  `phrame\RegexUtil` provide a nicer interface for dealing with PHP arrays,
  strings, and regular expressions
* Wrapper classes `phrame\XArray`, `phrame\XString`, and `phrame\XRegex`
  build upon the above classes to provide a rich, object-oriented interface to
  their built-in PHP counterparts
* The trait `phrame\Singleton` makes it easy to define singleton classes, which
  is useful for creating lazy-loaded objects which can be referenced from
  anywhere
* `phrame\RequestUtil` and `phrame\ResponseUtil` unify PHP's various methods
  for accessing data from the current HTTP request and building the HTTP
  response, respectively
* `phrame\sql\Database` and `phrame\sql\Statement` are useful wrappers around
  PHP's PDO library, offering iterator syntax for SQL query results and greatly
  simplified parameter binding
* `phrame\sql\Ast` provides a SQL syntax abstraction layer, allowing you to
  switch freely between different SQL dialects like SQLite and MySQL

The best source for documentation is the inline comments above each class and
function. The Phrame library code is found under `lib/`. All classes under the
`phrame` namespace are found under `lib/classes`. Some files which define
functions are found under `lib/functions`.

The example project under `demo` shows how to bootstrap the Phrame library and
do some extra configuring for a saner development experience. It does the
following:

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
(`.htaccess`, `ini.php`, `robots.txt`), Phrame leverages an executable script,
`run.php`, to create them dynamically using the project's configuration
settings. The example `Makefile` contains rules to create these files from
templates which reside under `demo/src/php/templates/`.

The example project also includes configuation files for the build tools
Bower and Gulp. The example gulpfile is used to concatenate and minify
CSS and JavaScript assets.

The scripts `lib/bin/makedb-sqlite` and `lib/bin/makedb-mysql` can be used to
clobber a project's database. The files under `demo/src/sql/` define the schema
of the example project.

## Usage ##

Phrame is designed so that any system which has Apache configured to run (a
recent version of) PHP and process `.htaccess` files can serve a Phrame app
simply by including a few of its bootstrapping files into a file named, say,
`index.php`, generating an appropriate `.htaccess` file, and serving its
directory to the web. On a Linux system, this could be as simple as generating
a build and creating a symlink under `/var/www/` to its directory.

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

## Project Structure ##

The example project under `demo/` consists of the following:

<dl>
  <dt><code>build/</code></dt>
  <dd>Contains the <code>dev</code> and <code>prod/</code> builds of the site,
  each of which contains bootstrapping PHP code which points to the Phrame
  library, as well as pre-compiled assets and configuration files specific to
  the build.</dd>

  <dt><code>db/</code></dt>
  <dd>For apps using SQLite, contains the SQLite database file.</dd>

  <dt><code>src/</code></dt>
  <dd>The application code. PHP, JavaScript, CSS, SQL, and the rest all live
  here.</dd>
</dl>

## Configuring ##

Phrame uses a class called `phrame\SiteConfig` to manage configuration
settings. Its base class, `phrame\Config`, can read settings from any PHP file
written as a series of property assignments on a variable named `$config`. Some
properties have getter and setter hooks which process the settings.

There is a common configuration file at `demo/src/php/config.php` which is
merged with a build-specific configuration at
`demo/build/{dev,prod}/config.php`.

Some of the variables used are:
<dl>
  <dt><code>scheme</code></dt>
  <dd>The HTTP protocol to use, either <code>'http'</code> or
  <code>'https'</code>. Default is <code>'http'</code>.</dd>

  <dt><code>host</code></dt>
  <dd>Hostname of the server. Default is <code>'localhost'</code>.</dd>

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

Routing is configured in the file `demo/src/php/routes.php`. Like the config
files, this routing file operates on a variable `$router` to define the
application's routing behavior. URL patterns are mapped to actions using
`$router->map($pattern, $callback)`. The first argument is a Rails-style URL
pattern. The second argument is a PHP callable object.

Patterns may include `:variables`, `*globs`, and `(optional)` parts. Variables
correspond to path components and do not match slashes. Globs, on the other
hand, match all characters. The captured values of variables and globs are
passed as positional parameters to callbacks.

If a pattern ends with a slash, a request to an equivalent URL without the slash
will issue a permanent redirect to the version with the slash.

Patterns are tested in order, so they should be listed in decreasing order of
specificity.

## Wrapper Classes ##

Phrame defines the wrapper classes `phrame\XArray` for native PHP `array`s,
`phrame\XString` for PHP `string`s, and `phrame\XRegex` for PHP's `preg`
functions. These are object-oriented interfaces built on top of `ArrayUtil`,
`StringUtil`, and `RegexUtil`, respectively, which serve as the
non-object-oriented version of essentially the same API. When you want to use
the normalized API without creating wrapped objects, use the -`Util` classes
instead. In general, when a -`Util` function takes the native PHP equivalent as
its first argument, it is also a method on the corresponding wrapper class. The
wrapper class methods automatically unbox wrapped arguments and wrap return
values where appropriate; the -`Util` functions do not.

As an alternative to invoking `new \phrame\XArray($array)`, etc. you can use
the global functions `xarray`, `xstring`, and `xregex` to wrap values in these
classes. These functions are defined in `lib/functions/common.php`.

Refer to the detailed inline documentation in `ArrayUtil`, `StringUtil`, and
`RegexUtil` for method details.

Unit tests are being written to verify edge case behavior in `StringUtil`.

## Request Access ##

The class `phrame\http\AbstractRequest` defines an object-oriented interface
for HTTP requests. The class `phrame\http\CurrentRequest` implements this
interface for the current request being handled by the script, unifying the
various API quirks for accessing the URI, headers, etc. The class
`phrame\RequestUtil` is a singleton class which provides access to an instance
of this class. Of course, instantiating `phrame\CurrentRequest` more than once
will still result in the same data &ndash; it's merely a shim around PHP's
superglobals, etc.

## Response Building ##

Like the current HTTP request, the HTTP response is abstracted behind an
interface, `phrame\http\AbstractResponse`, and implemented in
`phrame\http\CurrentResponse`. An instance is accessible through the singleton
`phrame\ResponseUtil`.

## Application Code ##

See the example project under `demo/`. The `src/js/` and `src/css/`
directories house source code for the JavaScript and stylesheets, respectively.
The `src/php/` directory contains backend PHP code. It contains the following
directories.

<dl>
  <dt><code>controllers/</code></dt>
  <dd>Controllers for the application which tie other components together to
  respond to requests. Typically the <code>routes.php</code> file will map REST
  functions to static methods in the controller classes.</dd>

  <dt><code>helpers/</code></dt>
  <dd>Singleton classes live here. One, <code>Database</code>, is a singleton
  class which provides access to the app's database. Another,
  <code>Pages</code>, defines a few site-wide functions, like how error pages
  (404, 403, 500, etc.) are served, how redirects are performed, etc.</dd>

  <dt><code>templates/</code></dt>
  <dd>Miscellaneous configuration files, namely the source for
  <code>.htaccess</code>, <code>robots.txt</code>, and <code>php.ini</code>.
  These are actually PHP files which are passed through the script
  <code>run.php</code>, which processes files with the Phrame library
  and the app's configuration settings loaded. This allows parts of these
  configuration files to be generated dynamically based on settings in
  <code>config.php</code>.</dd>

  <dt><code>views/</code></dt>
  <dd>HTML views. Since PHP is at heart a templating language, the files
  contained here are simply HTML code interspersed with PHP tags. Dynamic
  values are referenced as local variables; they are expected to be set from
  within a call to <code>phrame\Util::template</code>.</dd>
</dl>

## SQL Databases ##

A very convenient practice is to use a singleton `Database` class to access
the SQL database through the magic of auto-loading. The example `Database`
class simply makes all of the methods of the `phrame\sql\Database` class
available as static methods. The database connection is established the first
time the `Database` class is referenced in a context that requires a
connection.

Here are some examples to illustrate the convenience of this module:
```php
$query = Database::query(
  'select "name", "email" from "users" where "age" between ? and ?',
  $min_age, $max_age
);
foreach($query as $row) {
  do_something($row->name, $row->email);
}

$row = Database::row('select "name" from "users" where "id" = ?', $id);
do_something($row->name);

$highest_age = Database::evaluate('select max("age") from "users"');

Database::execute(
  'insert into "users"("name", "email") values (?, ?)',
  'Joe', 'joe@example.com'
);
do_something(Database::last_insert_id());
```

Refer to the inline documentation in `phrame\sql\Database` and
`phrame\sql\Statement` for more details about this API.

Phrame also includes a SQL syntax abstraction layer so that an app may be
configured to use a different SQL driver (SQLite, MySQL, etc.) with seamless
transition. This is provided in `phrame\sql\Ast`.

## HTML Templates ##

Something PHP is actually good for! The demo HTML templates are kept separate
from application logic in files ending in `.html.php`. They reference dynamic
values using simple local variables &ndash; these are sent to the templates
using `phrame\Util::template`. The global functions `html` and `htmlattr`,
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
Util::template('views/users/index.html.php', array(
  'users' => Database::query('select "id", "name" from "users"')
));
```

## Unit Testing ##

The class `phrame\test\UnitTest` can be used as a base class to define unit
tests. Methods starting with `test` are treated as test cases. The script
`lib/bin/test.php` can be used as a test case driver. Its argument should be
a PHP file which defines a unit test class, and nothing else; the driver will
automatically instantiate it, run it, and report results.

## Stylesheets ##

The example gulpfile is set up to compile SCSS files under `demo/src/css/` into
minified CSS.

## JavaScript ##

The example gulpfile is set up to concatenate and minify all JavaScript files
under `demo/src/js/` using Browserify.

