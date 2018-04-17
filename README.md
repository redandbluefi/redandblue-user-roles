# redandblue user roles
A Lightweight WordPress plugin that adds a Content Manager role to wp-admin. Restricts the role of installing plugins and themes. Adds common capabilities to admin.

## Capabilities:
- Edit/Add posts
- Edit/Add media
- Edit/Add pages
- Edit/Add custom post types
- Edit options pages created by ACF
- Edit/Add Menus in Layouts
- Manage comments
- Manage languages
- Manage SEO

## Filters
**'redandblue-user-roles/rnb_urc_comments'**

_(true|false)_ Toggle Comment editing. _Default false (hide)_

**'redandblue-user-roles/rnb_urc_users'**

_(true|false)_ Toggle User editing. _Default false (hide)_

**'redandblue-user-roles/rnb_urc_comments'**

_(array)_ Overwrite capabilities array. See documentation of [Roles and Capabilities](https://codex.wordpress.org/Roles_and_Capabilities)

## Example usage of filters
```php
// Allow editing of users
add_filter('redandblue-user-roles/rnb_urc_users', function(){return true;});

// Overwriting default capabilities by passing a inline function into filter which returns an array
add_filter('redandblue-user-roles/rnb_urc_caps', function(){
  return [
    'edit_posts' => false
  ];
});
// refresh_redandblue_role function must be used after updating capabilities. (Better method will be implemented in upcoming versions)
refresh_redandblue_role(); // this function needs to be run only ones to update
```

## Todo
- [X] Filters to disable/enable caps in theme functions
- [ ] Add multisite capabilities
- [ ] More filters?
- [ ] Better way to refresh caps
