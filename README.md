Not A Blog
----------
Not A Blog is a WordPress plugin to use when you're using WordPress solely as a CMS to power a custom plugin.

It disables the front-end (redirecting to a chosen wp-admin page) and removes the blogging menu items from wp-admin.

Before/After Not A Blog:

!["A side-by-side view of the wp-admin menu bar before and after installing Not A Blog. In the After view, all of the blogging-related menu items have been removed."](screenshots/before-after.png)

To define which page should be the default landing page, return the URL from the `not_a_blog_default_page` filter, like so:

```
add_filter( 'not_a_blog_default_page', function ( $url ) {
	return 'wp-admin/admin.php?page=my_custom_page';
} );
```