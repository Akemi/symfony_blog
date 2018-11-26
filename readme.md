# Symfony Blog

## What works
- login/logout
- user authentication
- paged Home page
- creating comments (anonymous, registered)
- creating blog posts (registered)
- deleting comments (admin)

## TODO
- Split Blog Controller into one per page controller
- validate comment and blog post data (html tags strip?, javascript)
- register page
- OneToMany relation does't work in all cases, Post `getComments()`function
- impressum
- delete blog posts (cascade)