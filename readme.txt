=== ExpiryManager ===
Contributors: (your WordPress.org username)
Tags: expiration, post management, draft
Requires at least: 5.0
Tested up to: 6.6
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

== Short Description ==
Set an expiration date for any public post type, automatically changing its status to "draft" or "trash" upon expiration.

== Description ==
ExpiryManager is a lightweight WordPress plugin that enables you to set an expiration date for any public post type. Once a post reaches its expiration date, the plugin automatically changes the post status to "draft" (by default) or "trash" if configured.

### Features

- Set an expiration date for any public post type.
- Automatically changes the post status to "draft" upon expiration.
- Customizable behavior to move expired posts to "trash" instead of "draft".

== Installation ==
1. Upload the `ExpiryManager` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. After activation, a new meta box will appear on all public post types, allowing you to set an expiration date.

== Usage ==
1. After activating the plugin, a new "Expiration Date" field will appear on the post editor for all public post types.
2. Set the desired expiration date for your post.
3. The post will automatically change to "draft" status when the expiration date is reached.

== Customization ==
- By default, expired posts are moved to "draft" status. To move them to "trash" instead, modify the following line in the `cep_check_expired_posts` function within the plugin's code:
  ```php
  'post_status' => 'trash' // Change this from 'draft' to 'trash'
