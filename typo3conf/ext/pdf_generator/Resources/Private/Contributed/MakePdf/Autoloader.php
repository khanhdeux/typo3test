<?php

MakePdf_Autoloader::Register();

/**
 * MakePdf_Autoloader
 *
 * @category    MakePdf
 * @package     MakePdf
 */
class MakePdf_Autoloader
{
    /**
     * Register the Autoloader with SPL
     *
     */
    public static function Register() {
        if (function_exists('__autoload')) {
            //    Register any existing autoloader function with SPL, so we don't get any clashes
            spl_autoload_register('__autoload');
        }
        //    Register ourselves with SPL
        return spl_autoload_register(array('MakePdf_Autoloader', 'Load'));
    }   //    function Register()


    /**
     * Autoload a class identified by name
     *
     * @param    string    $pClassName        Name of the object to load
     */
    public static function Load($pClassName){
        if ((class_exists($pClassName,FALSE)) || (strpos($pClassName, 'MakePdf') === FALSE)) {
            //    Either already loaded, or not a MakePdf class request
            return FALSE;
        }

        $pClassFilePath = PDFGENERATOR_ROOT .
            str_replace('\\',DIRECTORY_SEPARATOR,$pClassName) .
            '.php';

        if ((file_exists($pClassFilePath) === FALSE) || (is_readable($pClassFilePath) === FALSE)) {
            //    Can't load
            return FALSE;
        }

        require($pClassFilePath);
    }   //    function Load()

}
