<?php
$base = basename( __DIR__ );
rename( 'plugin.php', $base . '.php' );
$underscore = str_replace( '-', '_', $base );
$uc_words = ucwords( str_replace( '-', ' ', $base . '-' ) );
$namespace_for_composer = str_replace( ' ', '\\\\', $uc_words );
$namespace_for_file = str_replace( ' ', '\\', trim( $uc_words ) );
$composer = file_get_contents( './composer.json' );
$composer = str_replace( 'PluginPackage\\\\', $namespace_for_composer, $composer );
file_put_contents( './composer.json', $composer );
/**
 * @param $dir
 */
function seek_and_destroy( $dir ): void {
	global $namespace_for_file, $uc_words, $underscore, $base;
	if ( is_dir( $dir ) ) {
		$files = glob( $dir . '/*' );
		foreach ( $files as $file ) {
			seek_and_destroy( $file );
		}
	} else {
		$contents = file_get_contents( $dir );
		$search = array(
			'your-plugin-name',
			'your_plugin_name',
			'PluginPackage',
			'Your Plugin Name',
			'@package    PluginPackage',

		);
		$replace = array(
			$base,
			$underscore,
			$namespace_for_file,
			trim( $uc_words ),
			"@package    $namespace_for_file",
		);
		$replaced = str_replace( $search, $replace, $contents );
		file_put_contents( $dir, $replaced );
	}

}

seek_and_destroy( __DIR__ . '/src/' );
seek_and_destroy( $base . '.php' );
seek_and_destroy( __DIR__ . '/deploy.sh' );
seek_and_destroy( __DIR__ . '/build.sh' );
seek_and_destroy( __DIR__ . '/readme.txt' );
seek_and_destroy( __DIR__ . '/assets/' );
exec( 'composer install' );
chdir( __DIR__ . '/assets/admin' );
exec( 'npm install' );
exec( 'npm run build' );
chdir( __DIR__ . '/assets/website' );
exec( 'npm install' );
exec( 'npm run build' );
unlink( __DIR__ . '/post-install.php' );
