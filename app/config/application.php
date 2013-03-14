<?php
#####################################################################
### this is the application configuration file, please configure  ###
### and customize your application before starting to use it      ###
#####################################################################
/**
 * Identity
 * 
 * Define your jFramework powered web application here. Set at least a version, a Name and a
 * title for your application. Name would better follow identifier rules.
 */
const jf_Application_Version=jf\version; //put version of your application here, as a string. For jframework, version is the same as core.
const jf_Application_Name="jFramework" ; //follow identifier rules for this name
const jf_Application_Title="jFramework Website" ; //title of your application

/**
 * Mode detection
 * here jframework tries to determine what mode its running at,
 * Deploy, Develop or Command Line. Provide necessary logic for it to determine correctly
 */
if (HttpRequest::Host()=="localhost")
	jf::$RunMode->Add(RunModes::Develop);
elseif (strpos(HttpRequest::Host(),"jframework.info")!==false) #TODO:replace this with your site
	jf::$RunMode->Add(RunModes::Develop);
elseif (php_sapi_name()=="cli")
	jf::$RunMode->Add(RunModes::CLI);
else 
	throw new Exception("No running state determined, please provide rules in app/config/application.php.");

/**
 * Siteroot
 * 
 * jFramework requires to know where your site root is, e.g http://jframework.info
 * or http://tld.com/myfolder/myjf/deploy
 * automatically determines this, so change it and define it manually only when necessary
 * you can use this constant in your views for absolute urls
 */
define ( "SiteRoot", HttpRequest::Root () );
/**
 * Database Setup
 * 
 * jFramework requires at least a database for its core functionality. 
 * You can also use "no database-setup" if you do not need jFramework libraries and want a semi-static 
 * web application, in that case, comment or remove the database username definition
 */
if (jf::$RunMode->IsDevelop() or jf::$RunMode->IsCLI())
{
	\jf\DatabaseManager::$TablePrefix="jf_";
 	\jf\DatabaseManager::AddConnection(new \jf\DatabaseSetting("mysqli", "jf4", "root", ""));
}
elseif (jf::$RunMode->IsDeploy())
{
 	\jf\DatabaseManager::AddConnection(new \jf\DatabaseSetting("mysqli", "dbname", "username", "password"));
}
/**
 * Error Handling
 * 
 * jFramework has an advanced error handler built-in. 
 * Errors should not be presented to the end user on a release environment,
 * so keep in mind to set this to false when releasing your software.
 * You can view errors in logs anytime.
 */
jf\ErrorHandler::$Enabled=true; //Enables jFramework's built-in error handler
jf::$ErrorHandler->SetErrorHandler();

if (jf::$RunMode->IsDevelop())
	jf\ErrorHandler::$PresentErrors=true;
else
	jf\ErrorHandler::$PresentErrors=false;

/**
 * Bandwidth Management
 * 
 * jFramework handles all file feeds and downloads manually. 
 * Its FileManager has the ability to limit download speed of files larger than a specific size.
 * Set both the initial size and the limit here.
 */
	jf\FileManager::$BandwidthLimitInitialSize=-1;  # negative number disables it
	jf\FileManager::$BandwidthLimitSpeed=1024*1024;

/**
 * Iterative Templates
 * 
 * If this is set, jFramework viewer would look into the view folder and all its ancestor folders
 * to find a template folder, and would display the first template it finds.
 * Otherwise only the same folder is checked for templates. 
 */
	jf\View::$IterativeTemplates=true;

	
jf::import("config/more");
