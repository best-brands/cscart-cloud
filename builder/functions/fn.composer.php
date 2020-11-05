<?php

/**
 * Install composer packages
 * @param $params
 */
function fn_rb_install_composer_packages($params) {
    echo "Installing composer packages...\n";

    $working_dirs = [
        $params['destination'] . '/app/lib'
    ];

    $composers = glob($params['destination'] . '/app/addons/*/lib/composer.json');
    foreach ($composers as $composer_json) {
        $working_dirs[] = dirname($composer_json);
    }

    foreach ($working_dirs as $working_dir) {
        echo "Installing composer package '$working_dir'\n";

        fn_rb_exec(
            "composer install --working-dir={$working_dir} --no-interaction --no-dev --optimize-autoloader --ignore-platform-reqs",
            $output
        );
    }
}
