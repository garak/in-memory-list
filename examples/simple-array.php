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

include __DIR__ . '/../app/bootstrap.php';

$simpleArray = json_encode([
    [
        'userId' => 1,
        'id' => 1,
        'title' => 'sunt aut facere repellat provident occaecati excepturi optio reprehenderit',
        'body' => "quia et suscipit\nsuscipit recusandae consequuntur expedita et cum\nreprehenderit molestiae ut ut quas totam\nnostrum rerum est autem sunt rem eveniet architecto",
    ],
    [
        'userId' => 1,
        'id' => 2,
        'title' => 'qui est esse',
        'body' => "est rerum tempore vitae\nsequi sint nihil reprehenderit dolor beatae ea dolores neque\nfugiat blanditiis voluptate porro vel nihil molestiae ut reiciendis\nqui aperiam non debitis possimus qui neque nisi nulla",
    ],
]);

$client = new Client('redis', $config['redis_parameters']);
$collection = $client->findListByUuid('simple-list') ?: $client->create(json_decode($simpleArray), ['uuid' => 'simple-list', 'ttl' => 1000]);

// loop items
echo '<h3>Loop items</h3>';
foreach ($collection as $element) {
    $item = $client->item($element);

    echo '<p>';
    echo '<strong>userId</strong>: '.$item->userId.'<br>';
    echo '<strong>Id</strong>: '.$item->id.'<br>';
    echo '<strong>title</strong>: '.$item->title.'<br>';
    echo '<strong>body</strong>: '.$item->body.'<br>';
    echo '</p>';
}
