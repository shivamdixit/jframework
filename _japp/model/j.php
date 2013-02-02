<?php
namespace jf;
class ImportException extends \Exception 
{}
/**
 * jf helper class
 * entry point to all jframework modules
 * @author abiusx
 * @version 4.0
 */
class jf 
{
	static $Request=null;
	
	/**
	 * returns root url of jframework
	 */
	static function url()
	{
		return HttpRequest::Root();
	}
	static private $root=null;
	/**
	 * Returns jframeworks root folder on filesystem (the one containing app and _japp)
	 * @return string
	 */
	static function root()
	{
		if (self::$root===null)
			self::$root=dirname(dirname(__DIR__));
		return self::$root;
	}
	static function moduleFile($module)
	{
		if (strlen($module)>3 && substr($module,0,3)=="jf/")
			$module="_japp/".substr($module,3);
		else
			$module="app/".$module;
		
		$file=jf::root()."/".$module.".php";
		return $file;
	}
	/**
	 * 
	 * Loads a module into the context
	 * @param string $module
	 * @param array $scopeVars
	 * @throws ImportException
	 */
	static function import($module,$scopeVars=null)
	{
		
		$file=jf::moduleFile($module);
		if (!file_exists($file))
		 	throw new ImportException("File not found : {$file}");
		if (is_array($scopeVars)) foreach ( $scopeVars as $ArgName => $ArgValue )
			${$ArgName} = $ArgValue;
		require_once($file);
			
	}
	/**
	 * Returns a database connection which is already established
	 * If no index provided, first (default) connection will be returned
	 * @param integer $Index
	 * @return BaseDatabase
	 */
	static function db($Index=null)
	{
		return \jf\DatabaseManager::Database($Index);		
	}
	/**
	 * 
	 * @var FrontController
	 */
	public static $App;
    /**
     * Session Management object. Session Handling, User management and session management are here.
     * @var UserManager
     */
    public static $User;
    /**
     * Session Management object. Session Handling, User management and session management are here.
     * @var SessionManager
     */
    public static $Session;
    /**
     * Service Management. This object handles all service calls and service consumptions allowed by jFramework.
     * @var ServiceCore 
     */
    public static $Services;
    /**
     * Web Tracker. Tracks user activities on specific pages of this system.
     * @var Profiler
     */
    public static $Profiler;
    /**
     * Options Interface. This object allows you to save options for current session, current user and even current application
     * and retrieve them when needed.
     * @var SettingManager
     */
    public static $Options;
    /**
     * Log Management. Logs system events, Analyses logs and etc.
     * @var LogManager
     */
    public static $Log;
    /**
     * Security Interface
     *
     * @var SecurityManager
     */
    public static $Security;

    /**
     * Role Based Access Control
     *
     * @var RBAC
     */
    public static $RBAC;
    /**
     * ErrorHandler
     *
     * @var ErrorHandler
     */
    public static $ErrorHandler;
    
    
    /**
     * 
     * @var RunModes
     */
    public static $RunMode;
	/**
	 * convenient wrapper for php time() function which allows
	 * time manipulation for testing purposes
	 * always use this instead of the php one
	 */
	static function time()
	{
		if (self::$time===null)
			self::$time=time();
		return self::$time;
	}
	/**
	 * Use this to move time from real time for testing purposes
	 * @param integer $difference
	 */
	static function _movetime($difference)
	{
		self::$time=time()+$difference;
	}
	private static $time=null;



	/**
	 * Internationalization Interface
	 * @var I18nPlugin
	 */
	public static $i18n;
    
    
    #### Options Section ####
    static function SaveGeneralSetting($Name, $Value, $Timeout = null)
	{
		if ($Timeout===null)
			$Timeout=reg("jf/session/timeout/General");
		$a=func_get_args();
        return call_user_func_array(array(jf::$Settings,"SaveGeneral"),$a);	    
	}
	static function LoadGeneralSetting($Name)
	{
		$a=func_get_args();
		return call_user_func_array(array(jf::$Settings,"LoadGeneral"),$a);	    
	}
	static function SaveUserSetting($Name, $Value,$UserID=null,$Timeout = null)
	{
		if ($Timeout===null)
			$Timeout=jfSessionManager::$NoAccessTimeout;
		$a=func_get_args();
		return call_user_func_array(array(jf::$Settings,"Save"),$a);	    
	}
	static function DeleteGeneralSetting($Name,$UserID=null)
	{
		$a=func_get_args();
		return call_user_func_array(array(jf::$Options,"DeleteGeneral"),$a);	    
	}
	
    static function LoadUserSetting($Name)
	{
		$a=func_get_args();
		return call_user_func_array(array(jf::$Options,"Load"),$a);	    
	}
	static function SaveSessionSetting($Name,$Value,$Timeout = null)
	{
		if ($Timeout===null)
			$Timeout=reg("jf/session/timeout/General");
		$a=func_get_args();
		return call_user_func_array(array(jf::$Options,"SaveSession"),$a);	    
	}
	static function DeleteUserSetting($Name,$ID=null)
	{
		$a=func_get_args();
		return call_user_func_array(array(jf::$Options,"Delete"),$a);	    
	}
	
	static function LoadSessionSetting($Name)
	{
		$a=func_get_args();
		return call_user_func_array(array(jf::$Options,"LoadSession"),$a);	    
	}
	static function DeleteSessionSetting($Name)
	{
		$a=func_get_args();
		return call_user_func_array(array(jf::$Options,"DeleteSession"),$a);	    
	}
    
    ###### Log Section ######
    static function Log ($Subject, $LogData, $Severity = 0)
    {
        return jf::$Log->Log($Subject, $LogData, $Severity);
    }
    ###### DBAL Section ######
    /**
     * Runs a SQL query in the database and retrieves result (via DBAL)
     *
     * @param String $Query
     * @param optional $Param1 (could be an array)
     * @return mixed
     */
    static function SQL ($Query, $Param1 = null)
    {
    	$args=func_get_args();
    	if (is_array($Param1))
    	{
			$args=$Param1;
			array_unshift($args,$Query);
    	}
        return call_user_func_array(array(self::db(), "SQL"), $args);
    }
    
    
    ##### User Session Section #####
    static function CurrentUser ()
    {
        return jf::$Session->CurrentUser();
    }
    static function Username ()
    {
        return jf::$User->Username();
    }
    static function Login ($Username, $Password,$Force=null)
    {
        return jf::$User->Login($Username, $Password,$Force);
    }
    static function Logout ()
    {
        jf::$User->Logout();
    }
    
    ##### RBAC ######
    static function Check($Permission,$UserID=null)
    {
    	return jf::$RBAC->Check($Permission,$UserID);
    }
    static function Enforce($Permission)
    {
    	return jf::$RBAC->Enforce($Permission);
    }
    
    static public function __Initialize (jfBaseFrontController $App)
    {
        self::$App = &$App;
        self::$DB = &$App->DB;
        self::$Session = &$App->Session;
        self::$User = &$App->User;
        self::$Services = &$App->Services;
        self::$Tracker = &$App->Profiler;
        self::$Profiler = &$App->Profiler;
        self::$Settings = &$App->Settings ;
        self::$Log = &$App->Log;
        self::$RBAC = &$App->RBAC;
        self::$Security=&$App->Security;
    }
    }
class j extends jf
{
    
}
?>