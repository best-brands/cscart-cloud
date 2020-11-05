<?php

$init_stack = [
    'fn.common.php',
    'fn.composer.php',
    'fn.params.php',
    'fn.preloader.php',
];

foreach ($init_stack as $item) {
    require(__DIR__ . '/functions/' . $item);
}

$params = [];
$params_rules = [
    'required_params' => [],
    'possible_params' => [
        '-d' => 'destination',
    ],
    'default_params' => [
        'destination' => getcwd(),
    ],
    'possible_keys' => []
];

fn_rb_parse_params($params, $argv, $params_rules);
fn_rb_install_composer_packages($params);
fn_rb_install_preloader($params);
