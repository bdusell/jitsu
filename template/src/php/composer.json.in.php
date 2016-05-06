<?php use Jitsu\Project; ?>
{
  "name": <?= Project::jsonString(get_current_user() . '/' . $package_name) ?>,
  "description": <?= Project::jsonString('PHP source for ' . $project_name) ?>,
  "autoload": {
    "psr-4": {
      <?= Project::jsonString($namespace . '\\') ?>: "app/"
    }
  },
  "require": {
    "jitsu/app": "^0.2.0",
    "jitsu/array": "^0.1.1",
    "jitsu/error": "^0.2.0",
    "jitsu/http": "^0.2.0",
    "jitsu/regex": "^0.1.1",
    "jitsu/sqldb": "^0.2.1",
    "jitsu/string": "^0.1.1",
    "jitsu/util": "^0.1.2",
    "jitsu/wrap": "^0.1.2"
  }
}
