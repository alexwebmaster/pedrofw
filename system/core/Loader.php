<?php
class Loader
{    
    public static function registerAutoload()
    {
        return spl_autoload_register(array(__CLASS__, 'includeClass'));
    }
    
    public static function unregisterAutoload()
    {
        return spl_autoload_unregister(array(__CLASS__, 'includeClass'));
    }
    
    public static function includeClass($class)
    {
		static $loaded = array();
		
		$rootDirs = array(SYS_ROOT);
		if (defined('SRV_ROOT') && (SRV_ROOT != SYS_ROOT))  $rootDirs[] = SRV_ROOT;
		
		if (!isset($loaded[$class])) {
			$dirs = array(
				CLASSES_DIR, LIBRARY_DIR, HANDLERS_DIR,
				VIEWS_DIR, VIEWS_DIR.'admin/'
			);
			$filename = "$class.php";
			foreach ($rootDirs as $root) {
				foreach ($dirs as $dir) {
					$path = $root.$dir.$filename;
					if (file_exists($path)) {
						require_once $path;
						$loaded[$class] = true;
						return;
					}
				}
			}
		}
        // require(PATH . '/' . strtr($class, '_\\', '//') . '.php');
    }
}

Loader::registerAutoload();
?>