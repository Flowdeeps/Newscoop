<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/classes/common.php');
load_common_include_files("$ADMIN_DIR/issues");
require_once($_SERVER['DOCUMENT_ROOT']."/$ADMIN_DIR/CampsiteInterface.php");
require_once($_SERVER['DOCUMENT_ROOT'].'/classes/Input.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/classes/Template.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/classes/Log.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/classes/Publication.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/classes/Issue.php');

// Check permissions
list($access, $User) = check_basic_access($_REQUEST);
if (!$access) {
	header("Location: /$ADMIN/logout.php");
	exit;
}

if (!$User->hasPermission('ManageIssue')) {
	CampsiteInterface::DisplayError(getGS('You do not have the right to change issue details.'));
	exit;
}
$Pub = Input::Get('Pub', 'int');
$Issue = Input::Get('Issue', 'int');
$Language = Input::Get('Language', 'int');
$cName = trim(Input::Get('cName'));
$cLang = Input::Get('cLang', 'int');
$cPublicationDate = Input::Get('cPublicationDate', 'string', '', true);
$cIssueTplId = Input::Get('cIssueTplId', 'int');
$cSectionTplId = Input::Get('cSectionTplId', 'int');
$cArticleTplId = Input::Get('cArticleTplId', 'int');
$cShortName = trim(Input::Get('cShortName'));

if (!Input::IsValid()) {
	CampsiteInterface::DisplayError(getGS('Invalid input: $1', Input::GetErrorString()));	
	exit;
}
$publicationObj =& new Publication($Pub);
$issueObj =& new Issue($Pub, $Language, $Issue);

$backLink = "/$ADMIN/issues/edit.php?Pub=$Pub&Issue=$Issue&Language=$Language";
if ($cLang == 0) {
	CampsiteInterface::DisplayError(getGS('You must select a language.'), $backLink);
	exit;
}
if ($cName == '') {
	CampsiteInterface::DisplayError(getGS('You must complete the $1 field.', "'".getGS('Name')."'"),
		$backLink);
	exit;
}
if ($cShortName == '') {
	CampsiteInterface::DisplayError(getGS('You must complete the $1 field.', "'".getGS('URL Name')."'"),
		$backLink);
	exit;
}
if (!valid_short_name($cShortName)) {
	CampsiteInterface::DisplayError(getGS('The $1 field may only contain letters, digits and underscore (_) character.', "'" . getGS('URL Name') . "'"), $backLink);
	exit;
}
$issueObj->setProperty('Name', $cName, false);
$issueObj->setProperty('IdLanguage', $cLang, false);
if ($issueObj->getPublished() == 'Y') {
	$issueObj->setProperty('PublicationDate', $cPublicationDate, false);
}
$issueObj->setProperty('IssueTplId', $cIssueTplId, false);
$issueObj->setProperty('SectionTplId', $cSectionTplId, false);
$issueObj->setProperty('ArticleTplId', $cArticleTplId, false);
$issueObj->setProperty('ShortName', $cShortName, false);
if ($issueObj->commit()) {
	$logtext = getGS('Issue $1 updated in publication $2', $cName, $publicationObj->getName());
	Log::Message($logtext, $User->getUserName(), 11);
}

header("Location: /$ADMIN/issues/?Pub=" . $publicationObj->getPublicationId());

?>
