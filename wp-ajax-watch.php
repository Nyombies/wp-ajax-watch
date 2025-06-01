<?php

add_action('admin_init', function () {
	if (!defined('DOING_AJAX') || !DOING_AJAX) {
		return;
	}

	$action = isset($_REQUEST['action']) ? sanitize_text_field($_REQUEST['action']) : 'undefined';
	$start = microtime(true);

	if ($action === 'heartbeat' && !empty($GLOBALS['wp_filter']['heartbeat_send'])) {
		foreach ($GLOBALS['wp_filter']['heartbeat_send'] as $priority => $callbacks) {
			foreach ($callbacks as $callback) {
				$func = is_array($callback['function'])
					? implode('::', $callback['function'])
					: $callback['function'];

				// NOTE: This path is hardcoded and may need to be changed based on your environment.
				file_put_contents(
					'/var/www/html/ajax.log',
					sprintf("[%s] HEARTBEAT Hooked: %s\n", date('Y-m-d H:i:s'), $func),
					FILE_APPEND
				);
			}
		}
	}

	add_action('shutdown', function () use ($action, $start) {
		$duration = round(microtime(true) - $start, 4);

		// NOTE: This logs the current user's username (if logged in). Be aware of privacy implications.
		$user = is_user_logged_in() ? wp_get_current_user()->user_login : 'guest';

		// NOTE: This path is hardcoded and may need to be changed based on your environment.
		file_put_contents(
			'/var/www/html/ajax.log',
			sprintf("[%s] AJAX Action: %s | Time: %ss | User: %s\n", date('Y-m-d H:i:s'), $action, $duration, $user),
			FILE_APPEND
		);
	});
});


// OPTIONAL: Throttle the frequency of the WordPress Heartbeat API.
// Uncomment the following block to increase the interval (in seconds).

// add_filter('heartbeat_settings', function ($settings) {
//     $settings['interval'] = 60;
//     return $settings;
// });
