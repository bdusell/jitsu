<?php use Jitsu\Util; ?>
<?= '<?php' ?>


namespace <?= $namespace ?>;

use Jitsu\Util;
use Jitsu\ArrayUtil;

class Tools {

	public static function page($data, $name, $variables = []) {
		if(!ArrayUtil::hasKey($variables, 'title')) {
			$variables['title'] = $variables['page_name'] . <?= Util::repr(' | ' . $project_name) ?>;
		}
		$base_dir = dirname(__DIR__);
		Util::template("$base_dir/views/common/main.html.php", $variables + [
			'route' => Util::getProp($data, 'route'),
			'config' => Util::getProp($data, 'config'),
			'db' => Util::getProp($data, 'database'),
			'body' => "$base_dir/views/$name.html.php"
		]);
	}

	public static function text($data, $name, $variables = []) {
		Util::template(dirname(__DIR__) . "/views/$name.txt.php", $variables + [
			'route' => Util::getProp($data, 'route'),
			'config' => Util::getProp($data, 'config'),
			'db' => Util::getProp($data, 'database')
		]);
	}
}
