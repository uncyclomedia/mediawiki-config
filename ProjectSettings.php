<?php
# This file contains all the local site settings not in the
# LocalSettings.php (which despite the name, is more of a common
# settings file).
#
# General documentation for configuration settings may be found at:
# http://www.mediawiki.org/wiki/Manual:Configuration_settings
#
# Is this properly? No bloody idea, but it made sense at four in the morning.
#
# Configuration variables are defined and projects listed for each definition
# in arrays. At the end, the arrays are then 'parsed' - the relevant
# configuration for the current project is pulled out.
#
# Simple. Sort of.

# Protect against web entry
if ( !defined( 'MEDIAWIKI' ) ) {
	exit;
}

# SVG converter; default is librsvg
$p_SVG_handler = array(
	'en.uncyclopedia' => 'inkscape',
	'test.uncyclopedia' => 'inkscape',
	'cabal.uncyclomedia' => 'inkscape',
	'commons.uncyclomedia' => 'inkscape',
);

# Patrolling options to enable
$p_patrolling = array(
	'en.uncyclopedia' => array( 'recentchanges', 'newpages' ),
	'test.uncyclopedia' => array( 'recentchanges', 'newpages' ),
	# 'test1.uncyclopedia' => array( 'newpages' ),
);

# Default skin; unlisted are Vector
$p_skin = array(
	'en.uncyclopedia' => 'vector'
);

# Private projects - restricted access, read and write
$p_private = array(
	'en.illogicopedia',
	'test1.uncyclopedia',
	'test2.uncyclopedia',
	'test3.uncyclopedia',
	'cabal.uncyclomedia'
);

# Projects using a clone of carlb's Commons as a file repository
$p_repo_uncommons = array(
	'en.illogicopedia',
	'cabal.uncyclomedia'
);

# Projects using Wikimedia Commons as a file repository
$p_repo_commons = array(
	'cabal.uncyclomedia',
	'en.uncyclopedia',
	'test.uncyclopedia',
);

# Projects using MediaWikiAuth
# Arrays contain urls to api and preferences pages.
$p_mediawikiauth = array(
	'en.illogicopedia' => array(
		'http://illogicopedia.com/api.php',
		'http://illogicopedia.com/wiki/Special:Preferences'
	),
	'en.uncyclopedia' => array(
		'http://uncyclopedia.wikia.com/api.php',
		'http://uncyclopedia.wikia.com/wiki/Special:Preferences'
	),
	'test.uncyclopedia' => array(
		'http://uncyclopedia.wikia.com/api.php',
		'http://uncyclopedia.wikia.com/wiki/Special:Preferences'
	),
	'test1.uncyclopedia' => array(
		'http://uncyclopedia.wikia.com/api.php',
		'http://uncyclopedia.wikia.com/wiki/Special:Preferences'
	)
);

# Projects that allow blocked users to appeal on their talkpages
$p_block_UTEdit = array(
	'en.uncyclopedia',
);



# PARSING
# Dude, this looks slow.
# FIXME: Is this completely stupid? Seriously, is it? I have no idea. -I

# SVG converter?
$wgSVGConverters['rsvg'] = '/usr/bin/rsvg-convert -w $width -h $height -o $output $input';
$wgSVGConverter = 'rsvg';
if ( in_array( "$code.$project", array_keys( $p_SVG_handler ) ) ) {
	if ( $p_SVG_handler["$code.$project"] == 'inkscape' ) {
		$wgSVGConverter = 'inkscape';
		# Because inkscape is fat
		$wgMaxShellMemory = 517000;
	} else {
		# No specific handling for others as none are actually set up
		$wgSVGConverter = $p_SVG_handler["$code.$project"];
	}
}

# Patrolling?
$wgUseRCPatrol = false;
$wgUseNPPatrol = false;
if ( in_array( "$code.$project", array_keys( $p_patrolling ) ) ) {
	if ( in_array( 'newpages', $p_patrolling["$code.$project"] ) ) {
		$wgUseNPPatrol = true;
	}
	if ( in_array( 'recentchanges', $p_patrolling["$code.$project"] ) ) {
		$wgUseRCPatrol = true;
	}
}

# Skin?
$wgDefaultSkin = "vector";
if ( in_array( "$code.$project", array_keys( $p_skin ) ) ) {
	$wgDefaultSkin = $p_skin["$code.$project"];
}

# Private?
if ( in_array( "$code.$project", $p_private ) ) {
	$wgGroupPermissions['*']['createaccount'] = false;
	$wgGroupPermissions['*']['read'] = false;
	$wgGroupPermissions['*']['edit'] = false;
	$wgBlockDisablesLogin = true;
}

# UnCommons?
if ( in_array( "$code.$project", $p_repo_uncommons ) ) {
	/*
	$wgForeignFileRepos[] = array(
		'class'            => 'ForeignDBRepo',
		'name'             => 'uncyclomediacommons',
		'url'              => "http://commons.uncyclomedia.co/w/images/uncyclomedia/commons",
		'directory'        => "$IP/images/uncyclomedia/commons",
		'hashLevels'       => 2,
		'dbType'           => $wgDBtype,
		'dbServer'         => $wgDBserver,
		'dbUser'           => $wgDBuser,
		'dbPassword'       => $wgDBpassword,
		'dbFlags'          => DBO_DEFAULT,
		'dbName'           => 'uncm_commons',
		'tablePrefix'      => '',
		'hasSharedCache'   => false,
		'descBaseUrl'      => 'http://commons.uncyclomedia.co/wiki/File:',
		'fetchDescription' => true
	);
	*/
	// Use foreignAPIrepo for now because inkscape doesn't seem to like the foreignDBrepo.
	$wgForeignFileRepos[] = array(
		'class'                   => 'ForeignAPIRepo',
		'name'                    => 'uncyclomediacommons',
		'apibase'                 => 'http://commons.uncyclomedia.co/w/api.php',
		'hashLevels'              => 2,
		'fetchDescription'        => true,
		'descriptionCacheExpiry'  => 604800,
		'apiThumbCacheExpiry'     => 604800,
	);
}



# Commons?
if ( in_array( "$code.$project", $p_repo_commons ) ) {
	$wgForeignFileRepos[] = array(
		'class'                   => 'ForeignAPIRepo',
		'name'                    => 'wikimediacommons',
		'apibase'                 => 'http://commons.wikimedia.org/w/api.php',
		'hashLevels'              => 2,
		'fetchDescription'        => true,
		'descriptionCacheExpiry'  => 604800,
		'apiThumbCacheExpiry'     => 604800,
	);
}

# MediaWikiAuth?
if ( in_array( "$code.$project", array_keys( $p_mediawikiauth ) ) ) {
	$wgMediaWikiAuthAPIURL = $p_mediawikiauth["$code.$project"][0];
	$wgMediaWikiAuthPrefsURL = $p_mediawikiauth["$code.$project"][1];
	require_once( "$IP/extensions/MediaWikiAuth/MediaWikiAuth.php" );
	$wgAuth = new MediaWikiAuthPlugin();
}

# Talkpage access for blocked users?
if ( in_array( "$code.$project", $p_block_UTEdit ) ) {
	$wgBlockAllowsUTEdit = true;
}




# TODO: This properly. Whatever that is.
$wgFilterLogTypes = array( 'patrol' => true );

# Common permissions
$wgGroupPermissions['*']['createaccount'] = false;
$wgGroupPermissions['*']['edit'] = false;

$wgGroupPermissions['user']['upload'] = true;
// Uplaod requested by RAHB, matches Wikia
$wgGroupPermissions['user']['move'] = false;
$wgGroupPermissions['user']['movefile'] = false;
$wgGroupPermissions['user']['move-subpages'] = false;
$wgGroupPermissions['user']['reupload-shared'] = false;
$wgGroupPermissions['user']['reupload'] = false;

$wgAutoConfirmAge = 86400 * .5; # Two days
$wgAutoConfirmCount = 10;
$wgGroupPermissions['autoconfirmed']['skipcaptcha'] = true;
$wgGroupPermissions['autoconfirmed']['move'] = true;
$wgGroupPermissions['autoconfirmed']['movefile'] = true;
//$wgGroupPermissions['autoconfirmed']['upload'] = true;
$wgGroupPermissions['autoconfirmed']['reupload'] = true;


$wgGroupPermissions['sysop']['deleterevision']  = true; #revdel

/*
#oversight
$wgGroupPermissions['oversight']['hideuser'] = true;
$wgGroupPermissions['oversight']['suppressrevision'] = true;
$wgGroupPermissions['oversight']['suppressionlog'] = true;
*/

#steward like role which suddenly turned into our equivalent of staff! Erp.
$wgGroupPermissions['sysadmin']['hideuser'] = true;
$wgGroupPermissions['sysadmin']['suppressrevision'] = true;
$wgGroupPermissions['sysadmin']['suppressionlog'] = true;
$wgGroupPermissions['sysadmin']['deleterevision'] = true;
$wgGroupPermissions['sysadmin']['deletelogentry'] = true;
$wgGroupPermissions['sysadmin']['userrights'] = true;
$wgGroupPermissions['sysadmin']['interwiki'] = true;
$wgGroupPermissions['sysadmin']['checkuser'] = true;
$wgGroupPermissions['sysadmin']['checkuser-log'] = true;
$wgGroupPermissions['sysadmin']['noratelimit'] = true;
$wgGroupPermissions['sysadmin']['renameuser'] = true;
$wgGroupPermissions['sysadmin']['editusercss'] = true;
$wgGroupPermissions['sysadmin']['edituserjs'] = true;
$wgGroupPermissions['sysadmin']['editinterface'] = true;
$wgGroupPermissions['sysadmin']['importupload'] = true;
$wgGroupPermissions['sysadmin']['import'] = true;
$wgGroupPermissions['sysadmin']['abusefilter-modify'] = true;
$wgGroupPermissions['sysadmin']['abusefilter-modify-restricted'] = true;
$wgGroupPermissions['sysadmin']['skipcaptcha'] = true;
$wgGroupPermissions['sysadmin']['abusefilter-revert'] = true;
$wgGroupPermissions['sysadmin']['browsearchive'] = true;
$wgGroupPermissions['sysadmin']['undelete'] = true;
$wgGroupPermissions['sysadmin']['deletedhistory'] = true;
$wgGroupPermissions['sysadmin']['deletedtext'] = true;
$wgGroupPermissions['sysadmin']['abusefilter-private'] = true;
$wgGroupPermissions['sysadmin']['delete'] = true;
$wgGroupPermissions['sysadmin']['bigdelete'] = true;
$wgGroupPermissions['sysadmin']['autoconfirmed'] = true;
$wgGroupPermissions['sysadmin']['override-antispoof'] = true;
$wgGroupPermissions['sysadmin']['nuke'] = true;
$wgGroupPermissions['sysadmin']['protect'] = true;
$wgGroupPermissions['sysadmin']['block'] = true;
$wgGroupPermissions['sysadmin']['mergehistory'] = true;

$wgGroupPermissions['sysop']['bigdelete'] = false;

$wgGroupPermissions['bureaucrat']['userrights'] = false; #prevent crats from giving out CU
$wgAddGroups['bureaucrat'] = array( 'bot', 'sysop', 'bureaucrat' );
$wgRemoveGroups['bureaucrat'] = array( 'bot', 'sysop' );
$wgGroupsRemoveFromSelf['bureaucrat'] = array( 'bureaucrat' );
$wgGroupsRemoveFromSelf['sysop'] = array( 'sysop' );

# $wgGroupPermissions['suppressredirect']['suppressredirect'] = true;
# $wgGroupPermissions['suppressredirect']['markbotedits'] = true;
# $wgGroupPermissions['suppressredirect']['deletedhistory'] = true;

# $wgGroupPermissions['staff'] = array_merge(
# 	$wgGroupPermissions['bureaucrat'],
# 	$wgGroupPermissions['sysop']
# );


/*
require_once( "$IP/extensions/CSS/CSS.php" );
require_once( "$IP/extensions/CSS/pageCSS.php" );
require_once( "$IP/extensions/intersection/DynamicPageList.php" );
require_once( "$IP/extensions/DynamicPageList/DynamicPageList2.php" );
	ExtDynamicPageList::setFunctionalRichness(2);
require_once( "$IP/extensions/DPLforum/DPLforum.php" );

require_once( "$IP/extensions/InputBox/InputBox.php" );
require_once( "$IP/extensions/CreateBox/CreateBox.php" );
require_once( "$IP/extensions/Variables/Variables.php" );
require_once( "$IP/extensions/CharInsert/CharInsert" );
require_once( "$IP/extensions/Cite/Cite" );
require_once( "$IP/extensions/RSS/RSS" );
require_once( "$IP/extensions/WikiHiero/WikiHiero" );
*/

# For en.Uncyclopedia

if ( "$code.$project" == "en.uncyclopedia" || "$code.$project" == "test.uncyclopedia" ) {
	## per wiki user-rights
	$wgGroupPermissions['confirmed']['autoconfirmed'] = true;
	$wgGroupPermissions['confirmed']['skipcaptcha'] = true;
	$wgGroupPermissions['confirmed']['move'] = true;
	$wgGroupPermissions['confirmed']['upload'] = true;
	$wgGroupPermissions['confirmed']['reupload'] = true;

	$wgGroupPermissions['rollback']['rollback'] = true;
	$wgGroupPermissions['rollback']['autopatrol'] = true;
	$wgGroupPermissions['rollback']['patrol'] = true;
	$wgGroupPermissions['rollback']['suppressredirect'] = true;

	$wgGroupPermissions['autopatrolled']['autopatrol'] = true;
	$wgAddGroups['bureaucrat'][] = 'rollback';
	$wgRemoveGroups['bureaucrat'][] = 'rollback';
	$wgAddGroups['bureaucrat'][] = 'flood';
	$wgRemoveGroups['bureaucrat'][] = 'flood';
	$wgAddGroups['sysop'] = array( 'autopatrolled', 'confirmed', 'rollback' );
	$wgRemoveGroups['sysop'] = array( 'autopatrolled', 'confirmed', 'rollback' );

	$wgGroupPermissions['*']['createaccount'] = true;
	$wgGroupPermissions['*']['edit'] = true;
	$wgGroupPermissions['flood']['bot'] = true;
	$wgGroupsRemoveFromSelf['flood'] = array( 'flood' );

	# For test
	$wgUploadPath = "http://images.uncyclomedia.co/$project/en";
	$wgUploadDirectory = "/var/www/images/$project/en";
	$wgMetaNamespace = "Uncyclopedia";

	if( !isset( $wgMaintenanceScriptHurr ) ) {
		require_once( "$IP/extensions/DPLforum/DPLforum.php" );
		require_once( "$IP/extensions/InputBox/InputBox.php" );
		require_once( "$IP/extensions/MobileFrontend/MobileFrontend.php" );
		require_once( "$IP/extensions/CSS/CSS.php" );
		require_once( "$IP/extensions/AJAXPoll/AJAXPoll.php" );
		require_once( "$IP/extensions/PostEdit/PostEdit.php" );
		require_once( "$IP/extensions/DynamicPageList/DynamicPageList.php" );
		ExtDynamicPageList::setFunctionalRichness( 3 );
	}
	# createbox
	# randomincategory

	$wgExtraNamespaces[102] = "UnNews";
	$wgExtraNamespaces[103] = "UnNews_talk";
	$wgExtraNamespaces[104] = "Undictionary";
	$wgExtraNamespaces[105] = "Undictionary_talk";
	$wgExtraNamespaces[106] = "Game";
	$wgExtraNamespaces[107] = "Game_talk";
	$wgExtraNamespaces[108] = "Babel";
	$wgExtraNamespaces[109] = "Babel_talk";
	$wgExtraNamespaces[110] = "Forum";
	$wgExtraNamespaces[111] = "Forum_talk";
	$wgExtraNamespaces[112] = "UnTunes";
	$wgExtraNamespaces[113] = "UnTunes_talk";
	$wgExtraNamespaces[114] = "HowTo";
	$wgExtraNamespaces[115] = "HowTo_talk";
	$wgExtraNamespaces[116] = "Why?";
	$wgExtraNamespaces[117] = "Why?_talk";
	$wgExtraNamespaces[118] = "UnBooks";
	$wgExtraNamespaces[119] = "UnBooks_talk";
	$wgExtraNamespaces[120] = "UnScripts";
	$wgExtraNamespaces[121] = "UnScripts_talk";
	$wgExtraNamespaces[122] = "UnPoetia";
	$wgExtraNamespaces[123] = "UnPoetia_talk";
	$wgExtraNamespaces[124] = "Unquotable";
	$wgExtraNamespaces[125] = "Unquotable_talk";
	$wgExtraNamespaces[126] = "UnDebate";
	$wgExtraNamespaces[127] = "UnDebate_talk";
	$wgExtraNamespaces[128] = "UnReviews";
	$wgExtraNamespaces[129] = "UnReviews_talk";
	$wgExtraNamespaces[130] = "UnVoyage";
	$wgExtraNamespaces[131] = "UnVoyage_talk";

	$wgNamespaceLogos = array(
		NS_FILE => 'http://images.uncyclomedia.co/uncyclopedia/en/d/d5/Uncyclomedia_Commons.png',
		NS_FILE_TALK => 'http://images.uncyclomedia.co/uncyclopedia/en/d/d5/Uncyclomedia_Commons.png',
		102 => 'http://images.uncyclomedia.co/uncyclopedia/en/0/02/UnNews_Logo_Potato.png',
		103 => 'http://images.uncyclomedia.co/uncyclopedia/en/0/02/UnNews_Logo_Potato.png',
		104 => 'http://images.uncyclomedia.co/uncyclopedia/en/4/47/Undictionary_Logo_Text.png',
		105 => 'http://images.uncyclomedia.co/uncyclopedia/en/4/47/Undictionary_Logo_Text.png',
		106 => 'http://images.uncyclomedia.co/uncyclopedia/en/9/92/Game-Logo.png',
		107 => 'http://images.uncyclomedia.co/uncyclopedia/en/9/92/Game-Logo.png',
		110 => 'http://images.uncyclomedia.co/uncyclopedia/en/0/04/Forum_torches3.png',
		111 => 'http://images.uncyclomedia.co/uncyclopedia/en/d/db/Forum_talk.png',
		112 => 'http://images.uncyclomedia.co/uncyclopedia/en/b/bf/UnTunes.png',
		113 => 'http://images.uncyclomedia.co/uncyclopedia/en/b/bf/UnTunes.png',
		114 => 'http://images.uncyclomedia.co/uncyclopedia/en/3/36/Howto-logo.png',
		115 => 'http://images.uncyclomedia.co/uncyclopedia/en/3/36/Howto-logo.png',
		118 => 'http://images.uncyclomedia.co/uncyclopedia/en/3/37/Unbooks-logo-en.png',
		119 => 'http://images.uncyclomedia.co/uncyclopedia/en/3/37/Unbooks-logo-en.png',
		124 => 'http://images.uncyclomedia.co/uncyclopedia/en/b/bb/Unquotable-logo-en.png',
		125 => 'http://images.uncyclomedia.co/uncyclopedia/en/b/bb/Unquotable-logo-en.png',
		122 => 'http://images.uncyclomedia.co/uncyclopedia/en/2/2e/Unpoetia_logo.png',
		123 => 'http://images.uncyclomedia.co/uncyclopedia/en/2/2e/Unpoetia_logo.png',
		128 => 'http://images.uncyclomedia.co/uncyclopedia/en/7/75/UnReviews_small.png',
		129 => 'http://images.uncyclomedia.co/uncyclopedia/en/7/75/UnReviews_small.png',
		120 => 'http://images.uncyclomedia.co/uncyclopedia/en/d/d6/UnScripts.png',
		121 => 'http://images.uncyclomedia.co/uncyclopedia/en/d/d6/UnScripts.png',
		116 => 'http://images.uncyclomedia.co/uncyclopedia/en/2/25/Why_logo.png',
		117 => 'http://images.uncyclomedia.co/uncyclopedia/en/2/25/Why_logo.png'
	);

	$wgContentNamespaces = array_merge(
		$wgContentNamespaces,
		array( 102, 104, 112, 114, 116, 118, 120, 122, 124, 126, 128, 130 )
	);

	# CentralNotice stuff, based out of en.uncy for now
	# Sort out which stuff to move to global later
	require_once( "$IP/extensions/CentralNotice/CentralNotice.php" );
	$wgNoticeProject = 'uncyclopedia';
	$wgNoticeProjects = array(
		'uncyclopedia',
		'test'
	);
	$wgNoticeEnableFundraising = false;
	$wgNoticeReporterDomains = '';
	$wgNoticeNumberOfBuckets = 2;
	$wgCentralBannerDispatcher = "/wiki/Special:BannerRandom";
	$wgCentralBannerRecorder = "/wiki/Special:RecordImpression";
}

# For test wiki

if ( "$code.$project" == 'test.uncyclopedia' ) {
	$wgGroupPermissions['*']['createaccount'] = false;
	$wgGroupPermissions['*']['edit'] = false;

	$wgDefaultRobotPolicy = 'noindex,nofollow';

	/* Share login and blocks and stuff from main uncyclopedia */
	$wgSharedDB = 'uncy_en';
	$wgSharedTables = array(
		'user',
		'user_properties',
		'ipblocks',
		'interwiki',
		'user_groups'
	);

	/* Use files from main uncyclopedia (commons and uncommons also enabled further up) */
	$wgForeignFileRepos[] = array(
		'class'            => 'ForeignDBRepo',
		'name'             => 'uncyclopedia-en',
		'url'              => "http://en.uncyclomedia.co/w/images/uncyclopedia/en",
		'directory'        => "$IP/images/uncyclopedia/en",
		'hashLevels'       => 2,
		'dbType'           => $wgDBtype,
		'dbServer'         => $wgDBserver,
		'dbUser'           => $wgDBuser,
		'dbPassword'       => $wgDBpassword,
		'dbFlags'          => DBO_DEFAULT,
		'dbName'           => 'uncy_en',
		'tablePrefix'      => '',
		'hasSharedCache'   => false,
		'descBaseUrl'      => 'http://en.uncyclopedia.co/wiki/File:',
		'fetchDescription' => true
	);

	/* CentralNotice stuff */
	require_once( "$IP/extensions/CentralNotice/CentralNotice.php" );
	$wgNoticeProject = 'test';
	$wgNoticeInfrastructure = false;
	$wgCentralDBname = 'uncy_en';
}

# For test2

if ( "$code.$project" == 'test2.uncyclopedia' ) {
	$wgGroupPermissions['*']['createaccount'] = true;

}

# For Illogicopedia

else if ( "$code.$project" == 'en.illogicopedia' ) {

	require_once( "$IP/extensions/RandomSelection/RandomSelection.php" );

	require_once( "$IP/extensions/DynamicPageList/DynamicPageList2.php" );
		ExtDynamicPageList::setFunctionalRichness(2);
	require_once( "$IP/extensions/CSS/CSS.php" );
	require_once( "$IP/extensions/PageCSS/PageCSS.php" );
	#require_once( "$IP/extensions/RSSold/RSS.php" );
	#	$wgRSSAllowLinkTag = true;
	#	$wgAllowImageTag = true;

	require_once( "$IP/extensions/CharInsert/CharInsert.php" );
	# require_once( "$IP/extensions/RawMsg/RawMsg.php" );
	require_once( "$IP/extensions/Variables/Variables.php" );


	$wgExtraNamespaces[100] = "IllogiBooks";
	$wgExtraNamespaces[101] = "IllogiBooks_talk";
	$wgExtraNamespaces[102] = "IllogiCountry";
	$wgExtraNamespaces[103] = "IllogiCountry_talk";
	$wgExtraNamespaces[104] = "IllogiDictionary";
	$wgExtraNamespaces[105] = "IllogiDictionary_talk";
	$wgExtraNamespaces[106] = "IllogiGames";
	$wgExtraNamespaces[107] = "IllogiGames_talk";
	$wgExtraNamespaces[110] = "Forum";
	$wgExtraNamespaces[111] = "Forum_talk";
	$wgExtraNamespaces[112] = "IllogiMusic";
	$wgExtraNamespaces[113] = "IllogiMusic_talk";
	$wgExtraNamespaces[114] = "IllogiNews";
	$wgExtraNamespaces[115] = "IllogiNews_talk";
	$wgExtraNamespaces[116] = "IllogiToons";
	$wgExtraNamespaces[117] = "IllogiToons_talk";
	$wgExtraNamespaces[118] = "IllogiZoo";
	$wgExtraNamespaces[119] = "IllogiZoo_talk";
	$wgExtraNamespaces[120] = "HowTo";
	$wgExtraNamespaces[121] = "HowTo_talk";
	$wgExtraNamespaces[150] = "tlh";
	$wgExtraNamespaces[151] = "tlh_talk";
}

# For the Cabal

else if ( "$code.$project" == 'cabal.uncyclomedia' ) {
	require_once( "$IP/extensions/CSS/CSS.php" );

	$wgSyntaxHighlightDefaultLang = "php";
	$wgFixDoubleRedirects = true;

	# Uncyclopedia
	$wgForeignFileRepos[] = array(
		'class'                   => 'ForeignAPIRepo',
		'name'                    => 'uncyclopedia',
		'apibase'                 => 'http://en.uncyclopedia.co/w/api.php',
		'fetchDescription'        => true, // Optional
		'descriptionCacheExpiry'  => 43200, // 12 hours, optional (values are seconds)
		'apiThumbCacheExpiry'     => 43200, // 12 hours, optional, but required for local thumb caching
	);
}


#IRC feed

$p_irc_feed = array(
	// Project name => channel name
	"en.uncyclopedia" => "#uncyclopedia-rc-en",
	"test.uncyclopedia" => "#uncyclopedia-rc-test"
);

if ( in_array( "$code.$project", array_keys( $p_irc_feed ) ) ) {
	$wgRC2UDPAddress = '127.0.0.1';
	$wgRC2UDPPort = '33333';
	$wgRC2UDPPrefix = $p_irc_feed["$code.$project"] . "\t";
}



if ( "$code.$project" == "test.uncyclopedia" ) {
        error_reporting( -1 );
        ini_set( 'display_errors', 1 );
        $IDIOT_BRIGADE = true;
}

#Google Analytics

$p_use_ga = array(
	"en.uncyclopedia" => "UA-38042228-1",
	"test.uncyclopedia" => "UA-38042228-2",
);

if ( in_array( "$code.$project", array_keys( $p_use_ga ) ) ) {
	require_once( "$IP/extensions/googleAnalytics/googleAnalytics.php" );
	$wgGoogleAnalyticsAccount = $p_use_ga["$code.$project"];
	$wgGoogleAnalyticsIgnoreSysops = false;
	$wgGroupPermissions['bot']['noanalytics'] = true;
}

if ( "$code.$project" == "en.uncyclopedia" || "$code.$project" == "test.uncyclopedia" ) {
	require_once("$IP/extensions/GlobalBlocking/GlobalBlocking.php");
	unset($wgGroupPermissions['steward']); // Silly WMF
	$wgGroupPermissions['sysadmin']['globalblock'] = true;
	$wgGroupPermissions['sysadmin']['globalunblock'] = true;
}

