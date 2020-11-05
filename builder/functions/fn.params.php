<?php

/**
 * Parse the arguments according to a previously declared rule
 * @param $params
 * @param $argv
 * @param $params_rules
 * @return mixed
 */
function fn_rb_parse_params(&$params, $argv, $params_rules)
{
    echo("Processing params...\n");

    $params['dir_builder'] = dirname(__FILE__) . '/';
    $params['dir_current'] = getcwd() . '/';

    array_shift($argv);
    $required = array_keys($params_rules['required_params']);
    $missing_required_params = array_diff($required, $argv);

    if ($missing_required_params) {
        fn_rb_die('Error: some params are missing: '. implode(', ', $missing_required_params) . ". Use --help key for help.\n");
    }

    foreach ($params_rules['possible_params'] as $possible_param) {
        if (!empty($params_rules['default_params'][$possible_param])) {
            $params[$possible_param] = $params_rules['default_params'][$possible_param];
        } else {
            $params[$possible_param] = '';
        }
    }

    $params_rules['params'] = array_merge($params_rules['required_params'], $params_rules['possible_params']);

    $key = '';
    foreach ($argv as $param) {
        if ($key) { // saving value for prev parameter
            if (substr($key, 0, 4) == 'dir_') {
                $param = rtrim($param, '/') . '/';
            }
            $params[$key] = $param;
            $key = '';
        } else {
            if (array_key_exists($param, $params_rules['params'])) {
                $key = $params_rules['params'][$param]; // save key for future usage
            } elseif (array_key_exists($param, $params_rules['possible_keys'])) {
                $params[$params_rules['possible_keys'][$param]] = true;
            }
        }
    }

    return $params;
}
