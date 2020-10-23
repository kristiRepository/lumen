<?php


if (!function_exists('config_path')) {
    /**
     * Get the path to the configuration files.
     *
     * @return string
     */
    function config_path()
    {
        return app()->basePath() . '/config';
    }
}


if (!function_exists('public_path')) {
    /**
     * Return the path to public dir
     *
     * @param null $path
     *
     * @return string
     */
    function public_path($path = null)
    {
        return rtrim(app()->basePath('public/' . $path), '/');
    }
}