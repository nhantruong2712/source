<?php
/**
 * SA Framework View Class
 *
 * @Author Sans_Amour
 * @Copyright 2011
 */

class view
{
    private static $extensions = array();
    private static $sections = array();
    private static $current_section = false;
    private static $current_new_section = false;

    // Render
    // ---------------------------------------------------------------------------
    public static function render($view, $data = array(), $return=false)
    {
        //Load::view($view, $data);
        $res = core::$theme->view($view, $data, $return);
        // Load extensions
        foreach (self::$extensions as $e) {
            //Load::view($e['view'], $e['data']);
            $res .= core::$theme->view($e['view'], $e['data'], $return);
        }
        //print_r(self::$sections);
        return $res;
    }
 
    // Extend
    // ---------------------------------------------------------------------------
    public static function extend($view, $data = array())
    {
        self::$extensions[] = array('view' => $view, 'data' => $data);
    }
 
    // Section
    // ---------------------------------------------------------------------------
    public static function section($name)
    {
        ob_clean();
        self::$current_section = $name;
        ob_end_flush();
        ob_start();
    }
 
    // End Section
    // ---------------------------------------------------------------------------
    public static function end_section()
    {
        $data = ob_get_clean();
        self::$sections[self::$current_section] = $data;
        self::$current_section = false;
        ob_start();
    }
 
    // New Section
    // ---------------------------------------------------------------------------
    public static function new_section($name, $default = false)
    {
        if (!$default) {
            echo self::$sections[$name];
        } else {
            self::$current_new_section = $name;
            ob_end_flush();
            ob_start();
        }
    }
 
    // End New Section
    // ---------------------------------------------------------------------------
    public static function end_new_section()
    {
        if (isset(self::$sections[self::$current_new_section])) {
            ob_clean();
            echo self::$sections[self::$current_new_section];
        }
        self::$current_new_section = false;
    }
}
?>