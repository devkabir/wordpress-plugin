<?php // phpcs:ignore


/*
|--------------------------------------------------------------------------
| If this file is called directly, abort.
|--------------------------------------------------------------------------
*/
if ( ! defined( 'PluginPackage\SLUG' ) ) {
	exit;
}


return array(
	'dashboard' => array(
		'title'       => __( 'Starter Page: Dashboard', 'your-plugin-name' ),
		'description' => __( 'This is the dashboard content. Add some stats, charts, etc.', 'your-plugin-name' ),
		'links'       => array(
			'github' => __( 'Get the source code', 'your-plugin-name' ),
		),
	),
	'settings'  => array(
		'title'       => __( 'Plugin Settings', 'your-plugin-name' ),
		'description' => __( 'This is where you can create and manage your plugin settings.', 'your-plugin-name' ),
		'form'        => array(
			'inputs' => array(
				'enable' => array(
					'label'       => __( 'Enable Plugin', 'your-plugin-name' ),
					'description' => __( 'Enable or disable the plugin.', 'your-plugin-name' ),
				),
			),
			'submit' => __( 'Save Settings', 'your-plugin-name' ),
			'reset'  => __( 'Reset Settings', 'your-plugin-name' ),
		),
	),
);
