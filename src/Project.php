<?php

namespace Jitsu;

class Project {

	private static function createDirectory($dir_name) {
		echo "creating directory $dir_name\n";
		mkdir($dir_name);
	}

	private static function processTemplate($fin_name, $fout_name, $vars) {
		echo "creating file      $fout_name\n";
		ob_start();
		try {
			Util::template($fin_name, $vars);
		} catch(\Exception $e1) {
			ob_end_clean();
			throw $e1;
		}
		$content = ob_get_clean();
		$fout = fopen($fout_name, 'w');
		try {
			fwrite($fout, $content);
		} catch(\Exception $e2) {
		}
		fclose($fout);
		if(isset($e2)) throw $e2;
	}

	private static function copyFile($src_path, $dest_path) {
		echo "creating file      $dest_path\n";
		copy($src_path, $dest_path);
	}

	private static function copy($src_dirname, $src_basename, $dest_dirname, $vars) {
		$src_path = "$src_dirname/$src_basename";
		if(is_dir($src_path)) {
			$dest_path = "$dest_dirname/$src_basename";
			self::createDirectory($dest_path);
			self::copyDirectoryContents($src_path, $dest_path, $vars);
		} elseif(($dest_basename = StringUtil::removeSuffix($src_basename, '.in.php')) !== null) {
			self::processTemplate($src_path, "$dest_dirname/$dest_basename", $vars);
		} else {
			self::copyFile($src_path, "$dest_dirname/$src_basename");
		}
	}

	private static function copyDirectoryContents($src_path, $dest_path, $vars) {
		$dir = opendir($src_path);
		while(($entry = readdir($dir)) !== false) {
			if($entry !== '.' && $entry !== '..') {
				self::copy($src_path, $entry, $dest_path, $vars);
			}
		}
	}

	private static function runCommand($command, $cwd = null) {
		echo "running command    $command\n";
		$p = proc_open($command, [['pipe', 'r'], STDOUT, STDERR], $pipes, $cwd);
		if(!$p) throw new \RuntimeException('unable to open sub-process');
		try {
			fclose($pipes[0]);
		} catch(\Exception $e) {
		}
		$rc = proc_close($p);
		if(isset($e)) throw $e;
		if($rc < 0) throw new \RuntimeException('unable to close sub-process');
		if($rc > 0) throw new \RuntimeException('command failed');
	}

	private static function create($project_name, $project_directory) {
		$TEMPLATE_DIR_NAME = dirname(__DIR__) . '/template';
		if($project_directory === null) $project_directory = $project_name;
		$chunks = (new XString($project_name))->splitCamelCase();
		$dashes = $chunks->join('-')->lower()->value;
		$upper = ucfirst($project_name);
		$underscores = $chunks->join('_')->lower()->value;
		$vars = array(
			'project_name' => $project_name,
			'project_directory' => $project_directory,
			'package_name' => $dashes,
			'namespace' => $upper,
			'database_name' => $underscores
		);
		echo "creating project   $project_name\n";
		self::createDirectory($project_directory);
		self::copyDirectoryContents($TEMPLATE_DIR_NAME, $project_directory, $vars);
		$absolute_project_directory = realpath($project_directory);
		self::runCommand('composer update', $absolute_project_directory . '/src/php');
		self::runCommand('npm install', $absolute_project_directory);
		echo "done\n";
	}

	private static function usage($prog_name) {
		echo <<<TXT
Usage: $prog_name create <project-name> <project-dir>

  Create a new Jitsu project.

TXT
		;
	}

	public static function cli($argv) {
		$prog_name = array_shift($argv);
		$project_name = null;
		$project_directory = null;
		$debug = false;
		while(($arg = array_shift($argv)) !== null) {
			switch($arg) {
			case 'create':
				$project_name = array_shift($argv);
				$project_directory = array_shift($argv);
				break;
			case '--debug':
				$debug = true;
				break;
			case 'help':
			case '--help':
			case '-h':
				self::usage($prog_name);
				exit(0);
			default:
				self::usage($prog_name);
				exit(1);
			}
		}
		if($project_name === null) {
			self::usage($prog_name);
			exit(1);
		}
		if(!(new XRegex('^[a-z_][a-z_0-9]*$', 'i'))->match($project_name)) {
			echo "error: invalid project name\n";
			exit(1);
		}
		try {
			self::create($project_name, $project_directory);
		} catch(\Exception $e) {
			if($debug) {
				throw $e;
			} else {
				echo 'error: ', $e->getMessage(), "\n";
				exit(1);
			}
		}
	}

	public static function jsonString($s) {
		return json_encode((string) $s, JSON_UNESCAPED_SLASHES);
	}
}
