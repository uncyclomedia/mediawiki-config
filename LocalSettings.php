<?php
# This file was automatically generated by the MediaWiki 1.20alpha
# installer. If you make manual changes, please keep track in case you
# need to recreate them later.
#
# See includes/DefaultSettings.php for all configurable settings
# and their default values, but don't forget to make changes in _this_
# file, not there.
#
# Further documentation for configuration settings may be found at:
# http://www.mediawiki.org/wiki/Manual:Configuration_settings

# Protect against web entry
if ( !defined( 'MEDIAWIKI' ) ) {
	exit;
}

$IDIOT_BRIGADE = false;

# For the Idiot Brigade
if ( $IDIOT_BRIGADE ) {
	error_reporting( -1 );
	ini_set( 'display_errors', 1 );
}

## Shared memory settings
$wgMainCacheType = CACHE_MEMCACHED;
$wgMemCachedServers = array( "127.0.0.1:11211" );

require_once("$IP/extensions/ConfirmEdit/ConfirmEdit.php");
require_once("$IP/extensions/ConfirmEdit/FancyCaptcha.php");
$wgCaptchaClass = 'FancyCaptcha';
$wgGroupPermissions['*']['skipcaptcha'] = false;
$wgGroupPermissions['user']['skipcaptcha'] = false;
$wgGroupPermissions['autoconfirmed']['skipcaptcha'] = true;
$wgGroupPermissions['bot']['skipcaptcha'] = true; // registered bots
$wgGroupPermissions['sysop']['skipcaptcha'] = true;
$wgCaptchaTriggers['edit']          = false;
$wgCaptchaTriggers['create']        = false;
$wgCaptchaTriggers['addurl']        = true;
$wgCaptchaTriggers['createaccount'] = true;
$wgCaptchaTriggers['badlogin']      = true;



# Should this be somewhere else?
# Parse domain and set project variables

require_once( "/var/mwconfig/projects.php" );
$projectDomain = strtolower( $_SERVER['SERVER_NAME'] );
if ( in_array( $projectDomain, array_keys( $projectList ) ) ) {

	$prefix = $projectList[$projectDomain][3];
	$code = $projectList[$projectDomain][0];
	$project = $projectList[$projectDomain][1];
	$projectName = $projectList[$projectDomain][2];
} else {
	die( 'Invalid project.<br/><br/>Default handling has not been enabled.' );
}

# Set language
$validCodes = array_keys( Language::getLanguageNames() );
if ( in_array( $code, $validCodes ) ) {
	$wgLanguageCode = "$code";
} else {
	$wgLanguageCode = 'en';
}
$wgSitename = $projectName;


## Uncomment this to disable output compression
# $wgDisableOutputCompression = true;


## The URL base path to the directory containing the wiki;
## defaults for all runtime URL paths are based off of this.
$wgFullPath         = "/var/www/mediawiki";
$wgScriptPath	= "/w";
$wgScriptExtension	= ".php";
$wgArticlePath 	= "/wiki/$1";
# Links to images 'server', something about putting them
# somewhere else to make it easier to move/back stuff up.
$wgUploadPath 	= "http://images.uncyclomedia.co/$project/$code";
$wgUploadDirectory	= "/var/www/images/$project/$code";

## The protocol and server name to use in fully-qualified URLs
$wgServer = "http://$projectDomain";

## The relative URL path to the skins directory
$wgStylePath = "$wgScriptPath/skins";

## Paths to the logo and favicon; either File:Wiki.png and
## File:Favicon.ico or fallbacks
if ( file_exists( "$wgUploadDirectory/b/bc/Wiki.png" ) ) {
	$wgLogo = "$wgUploadPath/b/bc/Wiki.png";
} else $wgLogo = "$wgScriptPath/Uncyclomedia_black_logo.png";
if ( file_exists( "$wgUploadDirectory/6/64/Favicon.ico" ) ) {
	$wgFavicon = "$wgUploadPath/6/64/Favicon.ico";
} else $wgFavicon = "$wgScriptPath/Favicon.ico";
if ( file_exists( "$wgUploadDirectory/4/43/Apple-touch-icon.png" ) ) {
        $wgAppleTouchIcon = "$wgUploadPath/4/43/Apple-touch-icon.png";
} else $wgAppleTouchIcon = "$wgScriptPath/Apple-touch-icon.png";

## UPO means: this is also a user preference option

$wgEnableEmail      = true;
$wgEnableUserEmail  = true; # UPO

$wgEmergencyContact = "support@uncyclomedia.co";
$wgPasswordSender   = "noreply@uncyclomedia.co";

$wgEnotifUserTalk      = true; # UPO
$wgEnotifWatchlist     = true; # UPO
$wgEmailAuthentication = true;

## Database settings
$wgDBtype           = "mysql";
$wgDBserver         = "localhost";
#$wgDBuser           = "username"; # in private.php
#$wgDBpassword       = "password"; # in private.php
$wgDBname           = $prefix."_$code";

$wgDBprefix         = "";
$wgDBTableOptions   = "ENGINE=InnoDB, DEFAULT CHARSET=binary";

# Experimental charset support for MySQL 5.0.
$wgDBmysql5 = false;

# Performance settings
$wgMiserMode = true;
$wgDisableCounters = true;
$wgShowIPinHeader = false;
$wgJobRunRate = 0;	# Lower probability of running through queue; replace later with cronjob?


# Lets add our private config!
require_once("/var/mwconfig/private.php");

## Shared memory settings
#$wgMainCacheType = CACHE_MEMCACHED;
#$wgMemCachedServers = array( "127.0.0.1:11211" );

## Set $wgCacheDirectory to a writable directory on the web server
## to make your wiki go slightly faster. The directory should not
## be publically accessible from the web.
$wgCacheDirectory = "/var/mwcache/$project/$code";
$wgUseFileCache = true;
$wgFileCacheDirectory = "$wgCacheDirectory/files";

## To enable image uploads, make sure the 'images' directory
## is writable, then set this to true:
$wgEnableUploads  = true;
$wgUseImageMagick = true;
$wgFileExtensions = array( 'png', 'gif', 'jpg', 'jpeg', 'svg', 'ico', 'ogg', 'mp3', 'swf', 'xcf', 'psd', 'pdf', 'tiff' );
$wgImageMagickConvertCommand = "/usr/bin/convert";
#$wgImageMagickConvertCommand = "/usr/local/bin/convert";
# Limit image sizes
$wgMaxAnimatedGifArea = 1.25e7;
$wgMaxImageArea = 1.25e7;
# Allow uploading from URLs
# $wgAllowCopyUploads = true;
$wgAllowExternalImages = true;

$wgAllowUserCss = true;
$wgAllowUserJs = true;

## If you use ImageMagick (or any other shell command) on a
## Linux server, this will need to be set to the name of an
## available UTF-8 locale
$wgShellLocale = "en_US.utf8";
$wgLocaltimezone = 'UTC';

# Site upgrade key. Must be set to a string (default provided) to turn on the
# web installer while LocalSettings.php is in place
#$wgUpgradeKey = "thingy"; # in private.php
#$wgSecretKey = "thingy"; # in private.php

## Skip skins without removing them
$wgSkipSkins = array( 'chick', 'myskin', 'nostalgia', 'simple', 'standard' );

## For attaching licensing metadata to pages, and displaying an
## appropriate copyright notice / icon. GNU Free Documentation
## License and Creative Commons licenses are supported so far.
$wgRightsPage = ""; # Set to the title of a wiki page that describes your license/copyright
$wgRightsUrl  = "http://creativecommons.org/licenses/by-nc-sa/3.0/";
$wgRightsText = "Creative Commons Attribution Non-Commercial Share Alike";
# $wgRightsIcon = "$wgStylePath/common/images/cc-by-nc-sa.png";
# $wgRightsCode = ""; # Not yet used

# Uncyclomedia footer icon
$wgFooterIcons['copyright']['uncyclomedia'] = array(
	"src" => "$wgStylePath/common/images/icon_uncyclomedia.png",
	"url" => "http://uncyclomedia.co/",
	"alt" => "An Uncyclomedia project"
);

# Path to the GNU diff3 utility. Used for conflict resolution.
$wgDiff3 = "/usr/bin/diff3";

# Query string length limit for ResourceLoader. You should only set this if
# your web server has a query string length limit (then set it to that limit),
# or if you have suhosin.get.max_value_length set in php.ini (then set it to
# that value)
$wgResourceLoaderMaxQueryLength = -1;

$wgUseAjax = true;
$wgEnableMWSuggest = true;
$wgAllowDisplayTitle = true;
$wgRestrictDisplayTitle = false;

# HTML tidy for idiot-enabling and cross-compatibility
$wgUseTidy = true;

# Add some other 'languages'
$wgExtraLanguageNames = array(
	'tlh' => 'tlhIngan Hol'
);

# Prevent interwiki redirects
$wgDisableHardRedirects = true;

# Allow subpages for everything; pretty sure that was Wikia's default, so
# keeping it like that should be safest for now.
#should be array_fill(0, 200, true); to include Mainspace and talk pages, you jackasses.
#$wgNamespacesWithSubpages = array_fill(2, 200, true);
$wgNamespacesWithSubpages = array_fill(0, 200, true);

# Common extensions
require_once( "$IP/extensions/AbuseFilter/AbuseFilter.php" );
	$wgGroupPermissions['*']['abusefilter-log-detail'] = true;
	$wgGroupPermissions['*']['abusefilter-view'] = true;
	$wgGroupPermissions['*']['abusefilter-log'] = true;
	$wgGroupPermissions['sysop']['abusefilter-modify'] = true;
	$wgGroupPermissions['sysop']['abusefilter-private'] = true;
	$wgGroupPermissions['sysop']['abusefilter-modify-restricted'] = true;
	$wgGroupPermissions['sysop']['abusefilter-revert'] = true;
require_once( "$IP/extensions/AntiSpoof/AntiSpoof.php" );
require_once( "$IP/extensions/CategoryTree/CategoryTree.php" );
require_once( "$IP/extensions/CheckUser/CheckUser.php" );
require_once( "$IP/extensions/DismissableSiteNotice/DismissableSiteNotice.php" );
#require_once( "$IP/extensions/Editcount/Editcount.php" );
require_once( "$IP/extensions/ExpandTemplates/ExpandTemplates.php" );
require_once( "$IP/extensions/Gadgets/Gadgets.php" );
require_once( "$IP/extensions/ImageMap/ImageMap.php" );
require_once( "$IP/extensions/Interwiki/Interwiki.php" );
	$wgGroupPermissions['bureaucrat']['interwiki'] = true;
require_once( "$IP/extensions/LinkSuggest/LinkSuggest.php" );
require_once( "$IP/extensions/LogoFunctions/LogoFunctions.php" );
require_once( "$IP/extensions/Math/Math.php" );
	$wgTexvc = '/usr/bin/texvc';
require_once( "$IP/extensions/Nuke/Nuke.php" );
require_once( "$IP/extensions/ParserFunctions/ParserFunctions.php" );
	$wgPFEnableStringFunctions = true;
require_once( "$IP/extensions/Poem/Poem.php" );
#require_once( "$IP/extensions/RandomImage/RandomImage.php" );
require_once( "$IP/extensions_angry/RandomSelection/RandomSelection.php" );
require_once( "$IP/extensions/Renameuser/Renameuser.php" );
require_once( "$IP/extensions/SyntaxHighlight_GeSHi/SyntaxHighlight_GeSHi.php" );
	$wgSyntaxHighlightDefaultLang = "javascript";
require_once( "$IP/extensions/CodeEditor/CodeEditor.php" );
require_once( "$IP/extensions/Vector/Vector.php" );
 	$wgDefaultUserOptions['useeditwarning'] = 1;
 	$wgVectorUseSimpleSearch = true;
 	$wgVectorUseIconWatch = true;
require_once( "$IP/extensions/WikiEditor/WikiEditor.php" );
	$wgDefaultUserOptions['usebetatoolbar'] = 1;
	$wgDefaultUserOptions['usebetatoolbar-cgd'] = 1;
#require_once( "$IP/extensions/TagsReport/SpecialTagsReport.php" ); # requires fixes
require_once( "$IP/extensions/RandomInCategory/RandomInCategory.php" );
require_once( "$IP/extensions/Editcount/Editcount.php");
	$wgGroupPermissions['*']['lookupuser'] = false;
	$wgGroupPermissions['sysadmin']['lookupuser'] = true;
require_once( "$IP/extensions/RandomImage/RandomImage.php" );
require_once( "$IP/extensions/CharInsert/CharInsert.php" );
require_once( "$IP/extensions/Cite/Cite.php" );
require_once( "$IP/extensions/CreateBox/CreateBox.php" );
require_once( "$IP/extensions/OpenGraphMeta/OpenGraphMeta.php" );
require_once( "$IP/extensions/wikihiero/wikihiero.php" );
require_once( "$IP/extensions/YouTube/YouTube.php" );
require_once( "$IP/extensions/SpamBlacklist/SpamBlacklist.php" );
require_once( "$IP/extensions/ApiSandbox/ApiSandbox.php" );
require_once( "$IP/extensions/AssertEdit/AssertEdit.php" );
require_once( "$IP/extensions/Disambiguator/Disambiguator.php" );
#require_once( "$IP/extensions/MultiUpload/MultiUpload.php" );

/*
Others to install:

ContactPage
Contributors
LookupUser
MediawikiPlayer
OggHandler
PagedTiffHandler
PdfHandler
Phalanx
RandomInCategory
TorBlock

*/

# Specific settings for different projects
require_once( "/var/mwconfig/ProjectSettings.php" );

# For the Idiot Brigade
if( $IDIOT_BRIGADE ) {
	$wgShowExceptionDetails = true;
	$wgShowHostnames = true;
	$wgShowSQLErrors = true;
	$wgShowDBErrorBacktrace = true;
}

# Tell robots to go away.
# $wgDefaultRobotPolicy = 'noindex,nofollow';

#Anti-spam extensions
#Nuke,AbuseFilter,AntiSpoof installed above
require_once( "$IP/extensions/SimpleAntiSpam/SimpleAntiSpam.php" );

#captcha shit
$wgCaptchaDirectory = "/var/captcha";
$wgCaptchaDirectoryLevels = 0;
#$wgCaptchaSecret = "thingy"; # in private.php

#irc is set in ProjectSettings.php
#$wgRC2UDPAddress = '127.0.0.1'; # the host where the IRC client is running
#$wgRC2UDPPort = 9390; # or whatever
#$wgRC2UDPPrefix = '';

#$wgRateLimits['edit']['anon'] = array( 8, 30 ); //careful with this one
$wgRateLimits['edit']['newbie'] = array( 10, 30 );
$wgRateLimits['edit']['user'] = array( 12, 30 );
$wgRateLimits['edit']['ip'] = array( 10, 30 );

$wgRateLimits['move']['user'] = array( 6, 60 );
$wgRateLimits['move']['newbie'] = array( 4, 60 );
$wgRateLimits['move']['ip'] = array( 4, 60 );

$wgRateLimits['mailpassword']['anon'] = array( 8, 30 );
$wgRateLimits['emailuser']['user'] = array( 3, 60 );

$wgRateLimitLog = '/var/ratelimit.log';

# Unfortunate 1.20.0 thing
$wgCleanupPresentationalAttributes = false;
//$wgDebugLogFile ="wtf.log";

//$wgCustomConvertCommand = "/usr/loca/bin/convert -resize %wx%h %s %d";

#$wgJobRunRate = 0;


#$wgReadOnly = 'We are currently upgrading to a new version of MediaWiki. Join us in #uncyclopedia for updates.';

