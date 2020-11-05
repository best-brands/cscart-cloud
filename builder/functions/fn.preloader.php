<?php

function fn_rb_install_preloader($params) {
    $working_dir = dirname(dirname(__DIR__)) . '/preload/';
    fn_rb_exec("composer install --working-dir={$working_dir} --no-interaction --no-dev --optimize-autoloader --ignore-platform-reqs");
}
