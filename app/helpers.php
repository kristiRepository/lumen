<?php


if (! function_exists('config_path')) {
    /**
     * Get the path to the configuration files.
     *
     * @return string
     */
    function config_path()
    {
        return app()->basePath().'/config';
    }
}