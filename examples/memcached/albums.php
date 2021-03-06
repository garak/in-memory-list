<?php
/**
 * This file is part of the InMemoryList package.
 *
 * (c) Mauro Cassani<https://github.com/mauretto78>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */
use InMemoryList\Application\Client;

include __DIR__ . '/../../app/bootstrap.php';

$start = microtime(true);
$apiUrl = 'https://jsonplaceholder.typicode.com/albums';
$apiArray = json_decode(file_get_contents($apiUrl));

$client = new Client('memcached', $config['memcached_parameters']);
$collection = $client->findListByUuid('albums-list') ?: $client->create($apiArray, ['uuid' => 'albums-list', 'element-uuid' => 'id']);

// loop items
echo '<h3>Loop items</h3>';
foreach ($collection as $element) {
    $item = $client->item($element);

    echo '<p>';
    echo '<strong>userId</strong>: '.$item->userId.'<br>';
    echo '<strong>Id</strong>: '.$item->id.'<br>';
    echo '<strong>title</strong>: '.$item->title.'<br>';
    echo '</p>';
}

echo ' ELAPSED TIME: '.$time_elapsed_secs = microtime(true) - $start;
