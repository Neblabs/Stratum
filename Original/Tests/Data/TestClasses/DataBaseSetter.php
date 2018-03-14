<?php

namespace Stratum\Original\Test\Data\TestClass;

use Stratum\Original\Establish\Established;

Class DataBaseSetter
{
    public static function setInitialData()
    {
        (object) $database = Established::database();
        
        $pdo = new \PDO("mysql:host={$database->host};dbname={$database->name}", $database->username, $database->password);

        $pdo->query('TRUNCATE TABLE wp_posts');

        $pdo->query(
            "INSERT INTO wp_posts (ID, post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt, post_status, comment_status, ping_status, post_password, post_name, to_ping, pinged, post_modified, post_modified_gmt, post_content_filtered, post_parent, guid, menu_order, post_type, post_mime_type, comment_count)
            VALUES
    (1,1,'2016-11-13 21:30:57','2016-11-13 21:30:57','Welcome to WordPress. This is your first post. Edit or delete it, then start writing!','Hello world!','','publish','open','open','','hello-world','','','2016-11-13 21:30:57','2016-11-13 21:30:57','',0,'http://localhost/wordpress/?p=1',0,'post','',1),
    (2,1,'2016-11-13 21:30:57','2016-11-13 21:30:57','This is an example page. It\'s different from a blog post because it will stay in one place and will show up in your site navigation (in most themes). Most people start with an About page that introduces them to potential site visitors. It might say something like this:\n\n<blockquote>Hi there! I\'m a bike messenger by day, aspiring actor by night, and this is my website. I live in Los Angeles, have a great dog named Jack, and I like pi&#241;a coladas. (And gettin\' caught in the rain.)</blockquote>\n\n...or something like this:\n\n<blockquote>The XYZ Doohickey Company was founded in 1971, and has been providing quality doohickeys to the public ever since. Located in Gotham City, XYZ employs over 2,000 people and does all kinds of awesome things for the Gotham community.</blockquote>\n\nAs a new WordPress user, you should go to <a href=\"http://localhost/wordpress/wp-admin/\">your dashboard</a> to delete this page and create new pages for your content. Have fun!','Sample Page','','publish','closed','open','','sample-page','','','2016-11-13 21:30:57','2016-11-13 21:30:57','',0,'http://localhost/wordpress/?page_id=2',0,'page','',0),
    (3,2,'0000-00-00 00:00:00','0000-00-00 00:00:00','','Third post','','publish','open','open','','','','','0000-00-00 00:00:00','0000-00-00 00:00:00','',0,'',0,'post','',0),
    (4,2,'0000-00-00 00:00:00','0000-00-00 00:00:00','','Fourth post','','publish','open','open','','','','','0000-00-00 00:00:00','0000-00-00 00:00:00','',0,'',0,'post','',0),
    (5,1,'2016-11-14 03:19:24','0000-00-00 00:00:00','','Auto Draft','','auto-draft','open','open','','','','','2016-11-14 03:19:24','0000-00-00 00:00:00','',0,'http://localhost/wordpress/?p=5',0,'post','',0),
    (6,1,'2016-11-14 03:22:42','2016-11-14 03:22:42','','Sixth Post','','publish','open','open','','2-post','','','2016-11-14 05:11:47','2016-11-14 05:11:47','',0,'http://localhost/wordpress/?p=6',0,'post','',6),
    (8,1,'2016-11-14 03:23:59','2016-11-14 03:23:59','','Seventh post','excerpt','publish','open','open','pass','3-post','','','2016-11-14 03:40:57','2016-11-14 03:40:57','',0,'http://localhost/wordpress/?p=8',0,'post','',0);"
        );


        $pdo->query('TRUNCATE TABLE wp_comments');

        $pdo->query(
            "INSERT INTO `wp_comments` (`comment_ID`, `comment_post_ID`, `comment_author`, `comment_author_email`, `comment_author_url`, `comment_author_IP`, `comment_date`, `comment_date_gmt`, `comment_content`, `comment_karma`, `comment_approved`, `comment_agent`, `comment_type`, `comment_parent`, `user_id`)
VALUES
    (1,1,'A WordPress Commenter','wapuu@wordpress.example','https://wordpress.org/','','2016-11-13 21:30:57','2016-11-13 21:30:57','Hi, this is a comment.\nTo get started with moderating, editing, and deleting comments, please visit the Comments screen in the dashboard.\nCommenter avatars come from <a href=\"https://gravatar.com\">Gravatar</a>.',0,'1','','',0,0),
    (23,6,'Commenter','mail@mail.com','','','0000-00-00 00:00:00','0000-00-00 00:00:00','Thanks for the post!',0,'1','','',0,1),
    (24,6,'Commenter','mail@mail.com','','','0000-00-00 00:00:00','0000-00-00 00:00:00','Thanks for the post!',0,'1','','',0,1),
    (25,8,'Commenter','mail@mail.com','','','0000-00-00 00:00:00','0000-00-00 00:00:00','Thanks for the post!',0,'1','','',0,1),
    (26,6,'Commenter','mail@mail.com','','','0000-00-00 00:00:00','0000-00-00 00:00:00','Thanks for the post!',0,'1','','',0,1),
    (27,6,'Commenter','mail@mail.com','','','0000-00-00 00:00:00','0000-00-00 00:00:00','Thanks for the post!',0,'1','','',0,1),
    (28,8,'Commenter','mail@mail.com','','','2016-11-15 00:56:00','2016-11-15 00:56:00','Thanks for the post!',0,'1','','',0,1)"
        );

        $pdo->query('TRUNCATE TABLE wp_users');

        $pdo->query(
            "INSERT INTO `wp_users` (`ID`, `user_login`, `user_pass`, `user_nicename`, `user_email`, `user_url`, `user_registered`, `user_activation_key`, `user_status`, `display_name`)
VALUES
    (1,'rafark','\$P\$Bujs8rgJ7KDXyQQVODL8ELo81OztAG/','rafark','jhwdfiyeg@lkhgio.co','','2016-11-13 21:30:57','',0,'rafark')"
        );

        $pdo->query('TRUNCATE TABLE wp_postmeta');

        $pdo->query(
            "INSERT INTO `wp_postmeta` (`meta_id`, `post_id`, `meta_key`, `meta_value`)
VALUES
    (1,2,'_wp_page_template','default'),
    (2,6,'_edit_last','1'),
    (3,6,'_edit_lock','1479100478:1'),
    (6,8,'_edit_last','1'),
    (7,8,'_edit_lock','1479098666:1')"
        );

        $pdo->query('TRUNCATE TABLE wp_term_relationships');

        $pdo->query(
            "INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`)
VALUES
    (1,1,0),
    (6,1,0),
    (6,3,0),
    (6,6,0),
    (8,1,0),
    (8,4,0)"
        );


$pdo->query('TRUNCATE TABLE wp_term_taxonomy');

        $pdo->query(
            "INSERT INTO `wp_term_taxonomy` (`term_taxonomy_id`, `term_id`, `taxonomy`, `description`, `parent`, `count`)
VALUES
    (1,1,'category','',0,3),
    (2,3,'post_tag','',0,0),
    (3,4,'category','',0,1),
    (4,5,'post_tag','',0,1),
    (5,6,'post_tag','',0,0),
    (6,7,'post_tag','',0,1)"
        );

        $pdo->query('TRUNCATE TABLE wp_terms');

        $pdo->query(
            "INSERT INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`)
VALUES
    (1,'Uncategorized','uncategorized',0),
    (4,'News','news',0),
    (5,'#comment','comment',0),
    (6,'#favorite','favorite',0),
    (7,'#brandNew','brandnew',0)"
        );

    }
}