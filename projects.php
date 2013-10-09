<?php
# Domains of sites hosted, with database suffixes for the php to figure out what site to serve and project names.
# domain => code, project, projectname[, prefix]

$projectList = array (

	'en.uncyclopedia.co' => array ( 'en', 'uncyclopedia', 'Uncyclopedia', 'uncy' ),
//	'en.uncyclomedia.co' => array ( 'en', 'uncyclopedia', 'Uncyclopedia', 'uncy' ),  //Redirected to en.uncyclopedia.co via Apache --Emu
	'test.uncyclopedia.co' => array ( 'test', 'uncyclopedia', 'Uncyclopedia test wiki', 'uncy' ),
	'test1.uncyclopedia.co' => array ( 'test1', 'uncyclopedia', 'Uncyclopedia test I', 'uncy' ),
	'test2.uncyclopedia.co' => array ( 'test2', 'uncyclopedia', 'Uncyclopedia test II', 'uncy' ),
	'test3.uncyclopedia.co' => array ( 'test3', 'uncyclopedia', 'Uncyclopedia test III', 'uncy' ),

	'illogia.uncyclomedia.co' => array ( 'en', 'illogicopedia', 'Illogicopedia', 'illg' ),	# Remove after move is verified
	'en.illogicopedia.org' => array ( 'en', 'illogicopedia', 'Illogicopedia', 'illg' ),

	'cabal.uncyclomedia.co' => array ( 'cabal', 'uncyclomedia', 'The Cabal', 'uncm' ),
	'commons.uncyclomedia.co' => array ( 'commons', 'uncyclomedia', 'Uncyclomedia Commons', 'uncm' ),
	'uncyclomedia.co' => array ( 'meta', 'uncyclomedia', 'Uncyclomedia', 'uncm' ),

	# Empty wiki for a template for maintenance and upgrades; not a reachable project
	# base.sql/template.sql dumps are generated from this
	'template.uncyclomedia.co' => array ( 'template', 'uncyclomedia', 'Uncyclomedia template', 'uncm' )
);

# Need to automatically fill this.
$projectDatabases = array(
	'uncy_en',
	'uncy_test',
	'uncy_test1',
	'uncy_test2',
	'uncy_test3',

	'illg_en',

	'uncm_cabal',
	'uncm_commons',
	'uncm_meta',

	'uncm_template'
);

# Not used.
$projectCodes = array(
	'en.illogicopedia',
	'en.uncyclopedia',
	'test.uncyclopedia',
	'test1.uncyclopedia',
	'test2.uncyclopedia',
	'test3.uncyclopedia',

	'cabal.uncyclomedia',
	'commons.uncyclomedia',

	'template.uncyclomedia'
);
