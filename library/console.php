<?php
/**
 * Created by PhpStorm.
 * User: danielpfeffer
 * Date: 22.03.18
 * Time: 07:27
 */

class console
{
    /**
     * @param $var
     */
    function log($var)
    {
        $backtrace = debug_backtrace();
        $line = $backtrace['0']['line'];
        $file = $backtrace['0']['file'];
        //var_dump($file =$backtrace['0']);
        $file = basename($file);
        $debugOutput = "'".$file." ".$line."'";
        if (is_object($var)) {
            console::log(get_object_vars($var));
        } elseif (is_array($var)) {
            foreach ($var as $boi){
                echo "<script>console.log($debugOutput+': '+'$boi')</script>";
            }
        } else {
                echo "<script>console.log($debugOutput+ ': '+'$var')</script>";
        }
    }
}