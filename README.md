# WP AJAX Watch

A lightweight must-use plugin for monitoring all incoming AJAX requests in WordPress, including Heartbeat API traffic. It logs each request to a file with action name, execution time, and user info — useful for debugging, performance analysis, or tracking unexpected admin-ajax activity.

## Features

- Logs all AJAX (`admin-ajax.php`) requests
- Tracks Heartbeat API hook callbacks
- Records execution time and logged-in user
- Optional filter to throttle Heartbeat frequency

## Installation

1. Place the plugin file in your `mu-plugins` directory:

   ```
   wp-content/mu-plugins/wp-ajax-watch.php
   ```

   > Must-use plugins run automatically and don’t require activation.

2. Ensure the path defined for logging is writable. By default, logs are written to:

   ```
   /var/www/html/ajax.log
   ```

   You may want to update this path based on your environment.

## Usage

To actively monitor AJAX traffic:

```bash
tail -f /var/www/html/ajax.log
```

You’ll see output like:

```
[2025-06-01 13:02:10] HEARTBEAT Hooked: WP_Heartbeat::send
[2025-06-01 13:02:10] AJAX Action: heartbeat | Time: 0.1423s | User: admin
[2025-06-01 13:03:45] AJAX Action: custom_action_name | Time: 0.0671s | User: editor
```

## Optional: Throttle Heartbeat API

To reduce the frequency of Heartbeat requests, you can uncomment the following block at the bottom of the plugin:

```php
add_filter('heartbeat_settings', function ($settings) {
    $settings['interval'] = 60;
    return $settings;
});
```

This increases the polling interval to 60 seconds.

## Notes

- This plugin logs usernames. Be mindful of privacy if sharing logs or deploying to production.
- File logging is simple and synchronous. For high-traffic sites, consider integrating with a logging service or system logger instead.

## License

MIT
