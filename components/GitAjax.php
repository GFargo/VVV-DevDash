<?php
/**
 * @Author: Griffen Fargo
 * @Date:   2016-01-26 00:20:13
 * @Last Modified by:   Griffen Fargo
 * @Last Modified time: 2016-01-26 12:29:31
 */

$path = $_POST['path'];

require_once('Git.php');

$repo = Git::open($path);  // -or- Git::create('/path/to/repo')



echo 'Current script owner: ' . get_current_user();
echo '<br> Current path: ' . $path;


echo $repo->run('pull');


// return $repo->pull('origin', 'master');
