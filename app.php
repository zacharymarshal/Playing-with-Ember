<?php

require 'vendor/autoload.php';

use Silex\Application;

use Lstr\Silex\Asset\AssetServiceProvider;
use Lstr\Silex\Template\TemplateServiceProvider;

use Assetic\Filter\EmberPrecompileFilter;

$app = new Application();
$app['config'] = new ArrayObject;
$app['config']['debug'] = true;
$app['config']['base_url'] = '/~zacharyrankin/ember_play/public';
$app['config']['node_modules_path'] = __DIR__ . '/node_modules';
$app['config']['lstr.asset.url_prefix'] = $app['config']['base_url'] . '/assets';
$app['config']['lstr.asset.assetrinc'] = [
    'node_modules' => [
        'binaries' => [
            'ember-precompile' => $app['config']['node_modules_path']
                . '/ember-precompile/bin/ember-precompile',
        ]
    ],
    'filters' => [
        'node_modules' => [
            'path' => $app['config']['node_modules_path']
        ],
        'by_extension' => [
            'hbs' => [
                'ember-precompile',
                '?uglify_js'
            ],
        ],
    ],
    'filter_factories' => [
        'ember-precompile' => function ($options) {
            return new EmberPrecompileFilter(
                $options['node_modules']['binaries']['ember-precompile']
            );
        }
    ],
];

$app['debug'] = $app['config']['debug'];
$app->register(new AssetServiceProvider);
$app['lstr.asset.path'] = [
    'app'    => __DIR__ . '/assets/app',
    'lib'    => __DIR__ . '/assets/lib',
    'vendor' => __DIR__ . '/assets/vendor',
];
$app->register(new TemplateServiceProvider);
$app['lstr.template.path'][] = __DIR__ . '/templates';

$app->get('/', function (Application $app) {
    return $app['lstr.template']->render('index.phtml');
});

$app->get('/assets/{name}', function ($name, Application $app) {
    return $app['lstr.asset.responder']->getResponse($name);
})->assert('name', '.*');

return $app;
