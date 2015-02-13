Phrame
======

Phrame is a PHP framework for building modern web applications and REST APIs.
It features a library of PHP classes and functions which help overcome the many
shortcomings and idiosyncracies of the language and its standard library.

Projects are designed to be trivial to install and configure on any system
which has Apache set up to process `.php` and `.htaccess` files &ndash; a
ubiquitous setup, especially in shared hosting environments.

## Motivation ##

PHP comes with a rudimentary web development API out of the box in the form of
"superglobal" variables such as `$_GET` and `$_SERVER`, a special file stream
`php://input`, a host of built-in functions, and so on. Although workable, this
API suffers from numerous problems. Not only does it horribly break
encapsulation, it is insufficient to support a full suite of REST API functions
without significant extra effort. What's worse is that many of these built-in
features have awkward interfaces or use names which are just plain difficult to
remember. How do I access the request URI? Is it a function call? A superglobal?
I already have variables `$_GET` and `$_COOKIE`, so whatever happened to
`$_HEADER`? Surely `headers_list` returns the headers received in the request.
<i>Ad nauseam</i>.

Phrame offers a normalized, more mnemonic API which irons out these quirks.
Much of this API comes in the form of static functions which can be accessed
through auto-loading. A few global functions are included as well.

* `Util` includes some must-have helper functions such as `Util::get` (array
  access with a default value), `Util::p` (debug printing), and
  `Util::template` (encapsulated PHP file inclusion)
* Wrapper classes `XArray`, `XString`, and `XRegex` provide a rich,
  object-oriented interface to their built-in PHP counterparts
* `Request` and `Response` unify PHP's various methods for accessing data from
  the current HTTP request and building the HTTP response, respectively
* `phrame\sql\Database` and `phrame\sql\Statement` are useful wrappers around
  PHP's PDO library, offering iterator syntax for SQL query results and greatly
  simplified parameter binding
* The `html` function escapes a string for interpolation inside HTML text;
  `htmlattr` does the same for a string inside a double-quoted HTML attribute
* The `repr` function returns a string of the PHP representation of a value

Refer to the inline documentation for details. The library code is found under
`src/`.

The Phrame bootstrapping code does some additional configuring for a saner
development experience.

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
`src/bin/process.php`, to create them dynamically using the project's
configuration settings. The `Makefile` included under `demo/` defines rules for
creating these files from their sources, which reside under
`demo/src/app/templates/`.

The example project also includes configuation files for the build tools
Bower and GulpJS. The example gulpfile is used to concatenate and minify
CSS and JavaScript assets.

For projects that use a SQL database, the scripts `src/bin/makedb-sqlite` and
`src/bin/makedb-mysql` can be used to clobber the database. The files under
`demo/src/sql/` define the schema of the example project.

## Usage ##

Phrame is designed so that any system which has Apache configured to run PHP
(a reasonably recent version of it, anyway) and read `.htaccess` files can
serve a Phrame app simply by exposing one of the directories under `build/`.
On a Linux system, this could be as simple as generating one of the builds
and creating a symlink under `/var/www/` to its directory.

The build process requires the use of `gulp`, `bower`, and `make`. Follow these
steps to install these build tools:

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

A phrame project, as shown under `demo/`, should consist of the following:

<dl>
  <dt><code>build/</code></dt>
  <dd>Contains the <code>dev</code> and <code>prod/</code> builds of the site,
  each of which contains bootstrapping PHP code which points to the Phrame
  library, as well as pre-compiled assets and configuration files specific to
  the build.</dd>

  <dt><code>db/</code></dt>
  <dd>For apps using SQLite, contains the SQLite database file.</dd>

  <dt><code>src/</code></dt>
  <dd>Your application code. PHP, JavaScript, CSS, SQL, and the rest all live
  here.</dd>

  <dt><code>assets/</code></dt>
  <dd>Optionally, some static assets such as images, fonts, etc.</dd>
</dl>

## Configuring ##

Phrame's configuration system is fairly straightforward. A configuration file
shared by both environments exists at `src/app/config.php`. Configuration files
specific to each environment exist at `build/dev/config.php` and
`build/prod/config.php`.

The configuation files themselves are simply snippets of PHP code which use the
`config` class. Configuration variables are set using
`config::set($name, $value)` or, for pre-defined or previously set variables,
<code>config::<var>name</var>($value)</code>. They can be accessed from
anywhere using <code>config::<var>name</var>()</code> or the more explicit
`config::get($name)`.

Some of the essential pre-defined variables are:
<dl>
  <dt><code>dir</code></dt>
  <dd>Absolute path to the current build's directory. Use <code>__DIR__</code>
  in the local <code>config.php</code> file to set this.</dd>

  <dt><code>document_root</code></dt>
  <dd>The build's root directory from the point of view of the server. If the
  directory is symlinked, this will need to be different from <code>dir</code>.
  </dd>

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

  <dt><code>is_production</code></dt>
  <dd>Whether the current build is the production build. Default is false.</dd>

  <dt><code>show_errors</code></dt>
  <dd>Whether to display errors in responses. Default is true when
  <code>is_production</code> is false, false otherwise.</dd>

  <dt><code>helper</code></dt>
  <dd>Name of the application's helper class. Default is <code>Pages</code>.
  </dd>
</dl>

## Routing ##

Routing is configured in the file `src/app/routes.php`. Map URL patterns to
actions using `router::map($pattern, $callback)`. The first argument is a
Rails-style URL pattern. The second argument is a PHP callable object.

Patterns may include `:variables`, `*globs`, and `(optional)` parts. Variables
correspond to path components and do not match slashes, unlike globs, which
match all characters. The captured values of variables and globs are passed as
positional parameters to callbacks.

If a pattern ends with a slash, a request to an equivalent URL without the slash
will issue a permanent redirect to the version with the slash.

Patterns are tested in order, so they should be listed in decreasing order of
specificity.

## Wrapper Classes ##

These are `XArray` for native PHP `array`s, `XString` for PHP `string`s, and
`XRegex` for PHP's `preg` functions. These are object-oriented interfaces built
on top of `ArrayUtil`, `StringUtil`, and `RegexUtil`, respectively. When you
want to use the normalized API without creating wrapped objects, use the
`*Util` classes instead. In general, when a `*Util` function takes the native
PHP equivalent as its first argument, it is also a method on the corresponding
`X*` class. The `X*` class methods automatically unbox `X*` class arguments and
wrap return values in `X*` classes where appropriate; the `*Util` functions do
not.

As an alternative to invoking `new XArray($array)`, etc. you can use the global
functions `xarray`, `xstring`, and `xregex` to wrap values in these classes.

Refer to the detailed inline documentation in `ArrayUtil`, `StringUtil`, and
`RegexUtil` for method details.

## Request Access ##

Refer to the inline documentation in `Request`. This module gives you easy
access to the HTTP method, URL, header fields, content body, and more.

## Response Building ##

Refer to the inline documentation in `Response`. This module gives you easy
access to the response code, response headers, content type, and so on.

## Application Code ##

All of the code under `src/` is yours to modify. The `src/js/` and `src/css/`
directories house source code for the JavaScript and stylesheets, respectively.

The `src/app/` directory contains backend PHP code. It contains the following
directories.

<dl>
  <dt><code>controllers/</code></dt>
  <dd>Controllers for the application which tie models and views together to
  respond to requests. Typically the <code>routes.php</code> file will map REST
  functions to static methods in the controller classes.</dd>

  <dt><code>helpers/</code></dt>
  <dd>A couple of special classes live here. One, <code>Database</code>, is a
  singleton class which provides access to the app's database. The other,
  <code>Pages</code>, defines a few site-wide functions, like how error pages
  (404, 403, 500, etc.) are served, how redirects are performed, etc.</dd>

  <dt><code>models/</code></dt>
  <dd>Model classes go here. Phrame does include a rudimentary active record
  class called <code>Model</code>, but since it is rather under-developed at
  this point, it would be recommended to define model classes from scratch.
  </dd>

  <dt><code>templates/</code></dt>
  <dd>Miscellaneous configuration files, namely the source for
  <code>.htaccess</code>, <code>robots.txt</code>, and <code>php.ini</code>.
  These are actually PHP files which are passed through the script
  <code>bin/process.php</code>, which processes files with the Phrame library
  and your configuration settings loaded. This allows parts of these important
  configuration files to be generated dynamically based on your settings in
  <code>config.php</code>.</dd>

  <dt><code>views/</code></dt>
  <dd>HTML views. Since PHP is at heart a templating language, the files
  contained here are simply HTML code interspersed with PHP tags. Dynamic
  values should be referenced as local variables; they can be extracted into
  the current symbol table with a call to <code>Util::template</code>.</dd>
</dl>

## SQL Databases ##

A very convenient practice is to use a singleton `Database` class to access
your SQL database through the magic of auto-loading. The example `Database`
class simply makes all of the methods of the `phrame\sql\Database` class
available as static methods. The database connection is established the first
time the `Database` class is referenced in a context that requires a
connection. Currently supports SQLite or MySQL.

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
`phrame\sql\Statement` for more details about the API.

Phrame also includes a SQL syntax abstraction layer so that an app may be
configured to use a different SQL driver (SQLite, MySQL, etc.) with a seamless
transition. This is implemented in `phrame\sql\Ast`.

## HTML Templates ##

Something PHP is actually good for! Write HTML templates separately from your
application logic in files ending in `.html.php`. Reference dynamic values
using local variables &ndash; these can be sent to the template using
`Util::template`. Use `html` and `htmlattr` to escape values properly. 

Example:

In `src/app/views/users/index.html.php`:
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
Util::template('users/index.html.php', array(
  'users' => Database::query('select "id", "name" from "users"')
));
```

## Stylesheets ##

The example gulpfile is set up to compile SCSS files under `src/css/` into
minified CSS.

## JavaScript ##

The example gulpfile is set up to concatenate and minify all JavaScript files
under `src/js/` using Browserify.

