<?php

require __DIR__ . "./../vendor/autoload.php";

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'test',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();


$tree = (new W\CsvParser(__DIR__ . "/../dati2.csv"))->parseIntoTree()->getTree();

/**
 * No more import needed
 */
// try {
//     if ((new W\Importer($tree))->setCapsule($capsule)->import()) {
//         dump("Data successfully imported");
//     } else {
//         dump("Something went wrong");
//     }
// } catch (\Exception $ex) {
//     dump($ex->getMessage());
// }

// $tree = (new W\CsvParser(__DIR__ . "/../dati3.csv"))->parseIntoLatLons()->getTree();

// try {
//     if ((new W\Importer($tree))->setCapsule($capsule)->updateCountryCoords()) {
//         dump("Data successfully imported");
//     } else {
//         dump("Something went wrong");
//     }
// } catch (\Exception $ex) {
//     dump($ex->getMessage());
// }

// (new W\Importer($tree))->setCapsule($capsule)
//     ->calculateDistances();