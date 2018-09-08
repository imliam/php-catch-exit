<?php

if (!function_exists('catch_exit')) {
    /**
     * Catch when a part of a script exits and execute a custom function at
     * that point. Note that if exiting, the script does not continue.
     * You can use this to, for example, flash a message and
     * redirect the user instead of showing the default
     * black text on white background "exit" view.
     *
     * @param Closure $run
     * @param mixed   $fallback
     * @return mixed
     */
    function catch_exit($run, $fallback)
    {
        $shouldExit = true;

        register_shutdown_function(function () use ($fallback, &$shouldExit) {
            if (!$shouldExit) {
                return;
            }

            $exitMessage = ob_get_contents();

            if ($exitMessage) {
                ob_end_clean();
            }

            if (is_object($fallback) && $fallback instanceof Closure) {
                $result = $fallback($exitMessage);
            } else {
                $result = $fallback;
            }

            echo $result;
        });

        ob_start();
        $result = $run();
        ob_end_clean();

        $shouldExit = false;

        return $result;
    }
}

if (!function_exists('catch_die')) {
    /**
     * @see catch_die()
     */
    function catch_die(...$args)
    {
        return catch_exit(...$args);
    }
}
