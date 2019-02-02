# Stratum
Avant-garde development for WordPress




            

# A reading experience

Stratum has been design with readability over writability, Stratum makes you write redabale, mantainable and extendible applications and websites.

## Control

Stratum provides a readable, fully-featured routing system to handle both native WordPress and HTTP requests. It comes with a validation layer for a cleaner architecture.

Dedicated validation Lay

4 Response types

```php
GET::request()->to('/dashboard')
              ->validateWith('AuthorizationsController.userIsLoggedIn')
              ->use('DashboardController.home');
```
## Model

Powerful, full featured, low and high level APIs for locating and managing data from any storage system and the logic associated with it. MYSQL implementations built in.

```php
(object) $posts = Posts::with(1)->orMoreComments()->find();

(object) $posts = Posts::by()->users()
                             ->withName('Rafael')
                             ->find();
```
## Present

Super clean, mantainable and reusable views. Building HTML documents has never been so fun.
```php
<<Header>>
    <ul class="menu">
        <li create-for-each="menuItem in menu">
            <a href="(menuItem:url)">(menuItem:name)</a>
        </li>
    </ul>
<<Footer>>
```
### Fast

Stratum's templating engine is compiled to native PHP for a faster execution. Performance optimization features are also available to help dealing with unnecessary bottlenecks.


### Open Source

Stratum is an open source project released under the MIT license

