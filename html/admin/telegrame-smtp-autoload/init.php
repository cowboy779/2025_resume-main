<?php

include 'config.php';
include 'lib/Function.php';

spl_autoload_register(function($class_name) {
    //class directories
    $directories = array(
        'lib/',
    );

    //for each directory
    foreach($directories as $directory)
    {
        //see if the file exsists
        if(is_readable($directory.$class_name . '.php'))
        {
            require_once($directory.$class_name . '.php');
            //only require the class once, so quit after to save effort (if you got more, then name them something else 
            return;
        }            
    }
    
    $filename = 'mailer/'.'class.'.strtolower($class_name).'.php';
    if (is_readable($filename)) {
        require_once $filename;
        return;
    }
});

// IP가 내부 IP면 통과
function IsAllowedIP() {
    $remoteAddr = GetClientIP();
    
    if ((substr($remoteAddr, 0, 10) == '192.168.1.')) {
        return true;
    }
    return in_array($remoteAddr, Config::$ALLOWED_IPLIST);
}
