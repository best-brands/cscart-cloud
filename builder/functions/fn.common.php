<?php

/**
 * Command execution wrapper
 * @param $command
 * @param null $output
 * @param null $retval
 * @param bool $die_on_error
 * @return false
 */
function fn_rb_exec($command, &$output = null, &$retval = null, $die_on_error = true)
{
    if (empty($command)) {
        return false;
    }

    exec("$command 2>&1", $output, $retval);

    $str_output = implode(' ', $output);
    if ($retval == 0 && strpos($str_output, 'ERROR ') !== false) {
        $retval = 1;
    }

    if ($retval !== 0 && $die_on_error) {
        echo implode("\n", $output);
        // phpcs:disable
        @debug_print_backtrace(defined('DEBUG') ? 0 : DEBUG_BACKTRACE_IGNORE_ARGS);
        // phpcs:enable
        fn_rb_die("Error: command '$command' failed.\n");
    }
}

/**
 * @param $message
 */
function fn_rb_die($message)
{
    echo($message);
    exit(1);
}
