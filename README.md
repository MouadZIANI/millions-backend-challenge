## Millions backend challenge
My own implementation of the backend challenge. 

## Implemented features
* [x] Show posts paginated, and ordered by creation date with their information (Image, description, date, author) including
  total likes and the last 5 usernames who liked the post.
* [x] A user can register and login.
* [x] An authenticated user can create a new post
* [x] An authenticated user can remove his posts, with the image file
* [x] An authenticated user can like/unlike other posts.
* [x] An authenticated user can see all likes of a specific post.
* [x] Send a notification to other users when a new post is added using (Database channel)
* [x] Automatically delete posts 15 days old.
* [x] [BONUS] Created 6 feature tests including success & failure scenarios

![](doc/tests.png)

## Used technologies

- PHP 7.4
- Laravel 8
- PHPUnit
- Redis (for queue)
- Tymon/jwt-auth package (for authentication)
- Docker

## Installation Steps

> prerequisite: PHP > 7.4

* Clone repository
* `composer install`
* Create DB eg: `backend_api_challenge`
* `composer install`
* `composer setup` (copies `env` file, generates key, and migrates DB)
* Then run ``` php artisan serve ```

Nb: In this project I used redis to store the queued notifications, so you have to configure it locally then run this command 
``php artisan queue:work`` 
to run dispatched notifications.

## Testing
In this file [doc/postman_collection.json](doc/postman_collection.json) you will find the postman collection that you can import into your local postman app and test the api.






