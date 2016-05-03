Jitsu: PHP on easy mode
-----------------------

**jitsu 実（じつ）** n. truth, reality; fidelity

Jitsu is a suite of PHP libraries for building modern web applications and REST
APIs. Its purpose is to help you cut straight to the task at hand instead of
struggling with the many shortcomings and idiosyncracies of the PHP language
and its standard library.

Projects based on Jitsu are designed to be trivial to install and configure on
any system which has Apache set up to process `.php` and `.htaccess` files
&ndash; a ubiquitous setup, especially in shared hosting environments. This
repository comes with a project generator which can be used to set up new
Jitsu projects quickly and easily.

## Motivation

PHP is a nice tool for novice web developers. For starters, most web servers
come pre-configured to run PHP without any additional effort. You write "hello
world" to a file named `index.php` and _boom_, you have a web page. Put some
more code in some more files with some other names, and _boom_, you have a
whole website. For the casual user, PHP looks and feels like magic HTML.
Because PHP is a templating language, the panoply of languages essential for
web development can coexist in the same source file. This reduces the
complexity of HTML form submission to 1) printing the appropriate HTML markup,
and 2) retrieving the submitted values through `$_REQUEST`. Play it right, and
you can always have all of your HTML, CSS, JavaScript, backend logic, SQL
queries, etc. in one big ball-o'-code `.php` file.

Obviously, this language-fiesta approach is not suitable for larger projects.
As soon as your aspirations evolve from "how do I make a web page which asks
for the user's name?" to "how do I make a REST API service for my web app?",
the built-in APIs tend to hinder more than help. What might be their worst
aspect is that many of these built-in features have unusual interfaces or use
names which are just plain difficult to remember. Some features, like setting
cookies, are function calls. Others things, like reading cookies, are
done through superglobal array variables. Still other things come in the form
of special file stream names like `php://input`. _Ad nauseam._

It doesn't help that many of the built-in string and array functions behave
poorly on edge cases. A lot of functions give you the error "empty needle" if
you, say, try to test whether a string contains the empty string. String and
array slicing gets really messy whenever you can't ensure that all the indices
end up within the boundaries. And let's _not even get started_ on error handling
in PHP.

Jitsu offers a saner PHP development experience, providing memorable function
names, well-behaved string and array functions, disciplined error handling,
object-oriented HTTP requests and responses, request routing, and more. A few
highlights include:

* Auto-loading for all `Jitsu` classes. Jitsu is composed of a number of
  [Composer](https://getcomposer.org/) packages, all of which support
  [PSR-4](http://www.php-fig.org/psr/psr-4/)-compliant auto-loading.
* An `Application` class which serves as an extensible HTTP request router.
* Request and response interfaces which decouple clients from superglobals
  like `$_GET`, `$_SERVER`, etc.
* A much more succinct API for SQL databases built upon PHP's PDO library.
* Many, many static utility functions for string and array manipulation,
  regular expression matching, introspection, functional programming, etc.
* Wrapper classes `XArray`, `XString`, and `XRegex` which offer a rich,
  object-oriented interface to native PHP data types that supports method
  chaining.
* Other features in the works, like a SQL syntax abstraction layer and an ORM.

## Component Libraries

* [jitsu/app](https://github.com/bdusell/jitsu-app): Routing and configuration
  for web apps
* [jitsu/http](https://github.com/bdusell/jitsu-http): HTTP request and
  response interfaces
* [jitsu/sqldb](https://github.com/bdusell/jitsu-sqldb): Awesome OOP interface
  to SQL databases
* [jitsu/error](https://github.com/bdusell/jitsu-error): Error handling done
  right
* [jitsu/wrap](https://github.com/bdusell/jitsu-wrap): OOP wrappers for native
  data types with chainable methods
* [jitsu/util](https://github.com/bdusell/jitsu-util): Indispensable helper
  functions
* [jitsu/array](https://github.com/bdusell/jitsu-array): Re-designed array
  library
* [jitsu/string](https://github.com/bdusell/jitsu-string): Re-designed string
  library
* [jitsu/regex](https://github.com/bdusell/jitsu-regex): Re-designed regular
  expression library

## Quickstart

Clone this repo and create a new project:

```sh
git clone https://github.com/bdusell/jitsu.git
./jitsu/bin/jitsu create MyBlog
cd MyBlog
```

## Project Structure

## HTML Templates

Something PHP is actually good at! By convention, HTML templates are kept
separate from application logic in files ending in `.html.php`. They reference
dynamic values using simple local variables &ndash; these are sent to the
templates using `Jitsu\Util::template`. Global shorthand functions `html` and
`htmlattr` can be used to escape values properly.

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

実
