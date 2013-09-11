<?php
if(!defined("in_admin"))exit;

if(empty($lang) || !is_array($lang)){
	$lang = array();
}

$lang = array_merge($lang,array(
		'title'          => 'Admin forside',
		'FrontPageTitle' => 'Forside',
		'ErrorLog'       => 'Error.. der findes en error log i "Root/log/" Se dem venligst og slet den!',
		'Update'         => 'Der er en update til denne forum. gå ind på http://battelkamp.dk for at hente updaten!',
		'UpdateError'    => 'Forsøg på at kontrollere om der opdateringer resulteret i error fra serverens side!',
		'UserCount'      => 'Antal bruger:',
		'GeustCount'     => 'Antal gæster',
		'ForumCount'     => 'Antal forumer',
		'KatCount'       => 'Antal Katolorier',
		'DeleteU'        => 'Slet alt unødvendigt',
		'DeleteOne'      => 'Alt unødvendigt er nu slettet.',
));