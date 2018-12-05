<?php
/**
 * Created by PhpStorm.
 * User: raphe
 * Date: 20/11/2018
 * Time: 3:14 PM
 */

namespace App\System;


class xTemplate
{
    const TEMPLATE_NOT_FOUND = "404";

    private $folder;

    function __construct( $folder = null ){
        if ( $folder ) {
            $this->set_folder( $folder );
        }
    }
    function set_folder( $folder ){
        $this->folder = $this->folder = rtrim( $folder, '/' );
    }
    function render($template_name, $variables = array() ){
        $template = stream_resolve_include_path($this->folder . "/{$template_name}.phtml");

        return ($template) ? $this->render_template( $template, $variables ) : xTemplate::TEMPLATE_NOT_FOUND;
    }
    function render_template( /*$template, $variables*/ ){
        ob_start();

        foreach ( func_get_args()[1] as $key => $value)
            ${$key} = $value;

        /** @noinspection PhpIncludeInspection */
        include func_get_args()[0];

        return ob_get_clean();
    }
}
