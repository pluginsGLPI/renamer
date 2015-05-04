<?php

include ("../../../inc/includes.php");

Session::checkRight("profile", READ);

if (isset($_POST['update_user_profile'])) {
	$prof = new PluginRenamerProfile();
	$prof->update($_POST);
	Html::back();
}
