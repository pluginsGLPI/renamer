<?php
/*
   ------------------------------------------------------------------------
   GLPI Plugin MantisBT
   Copyright (C) 2014 by the GLPI Plugin MantisBT Development Team.

   https://forge.indepnet.net/projects/rennamer
   ------------------------------------------------------------------------

   LICENSE

   This file is part of GLPI Plugin MantisBT project.

   GLPI Plugin MantisBT is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; either version 3 of the License, or
   (at your option) any later version.

   GLPI Plugin MantisBT is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with GLPI Plugin MantisBT. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   GLPI Plugin Renamer
   @author    Stanislas Kita (teclib')
   @copyright Copyright (c) 2014 GLPI Plugin MantisBT Development team
   @license   GPLv3 or (at your option) any later version
              http://www.gnu.org/licenses/gpl.html
   @link      https://forge.indepnet.net/projects/renamer
   @since     2014

   ------------------------------------------------------------------------
 */
include('../../../inc/includes.php');

if (isset($_POST['action'])) {

    global $CFG_GLPI,$DB;

    switch ($_POST['action']) {

        case 'searchOriginalWord':

            if(isset($_POST['original']) && $_POST['original'] != ""
                  && isset($_POST['lang']) && $_POST['lang'] != ""){
                $renamer = new PluginRenamerRenamer();

                if ($renamer->isAlreadyOverload($_POST)){
                    echo  sprintf(__('The word \'%1$s\' is already an overload, please restore this word and try again', "renamer"),$_POST['original']);
                }else{
                    $res = $renamer->searchWord($_POST['original'] , $_POST['lang']);

                    if ($res)  returnSuccess();
                    else  returnError();
                }

            }else  returnError();

            break;

        case 'overloadWord':

            if(isset($_POST['original']) && $_POST['original'] == ""){
                echo __("Please complete the field 'word'","rename");
            }else if (isset($_POST['lang']) && $_POST['lang'] == ""){
                echo __("Please complete the field 'language'","rename");
            }else if (isset($_POST['overload']) && $_POST['overload'] == ""){
                echo __("Please complete the field 'Substitut'","rename");
            }else{

                $renamer = new PluginRenamerRenamer();
                $word = stripslashes($_POST['original']);

                if ($renamer->isAlreadyOverload($_POST)){
                    echo  sprintf(__('The word \'%1$s\' is already an overload, please restore this word and try again', "renamer"),$word);
                }else{
                    $res = $renamer->searchWord($word , $_POST['lang']);

                    if (!$res)  echo sprintf(__('The word \'%1$s\' does not exist.',"renamer"),$word);
                    else {

                        if(PluginRenamerInstall::checkRightAccessOnGlpiLocalesFiles()){
                            $res =  $renamer->overloadWord($_POST);
                            echo $res;
                        }else{
                            echo  __("Please give write permission to the 'locales' folder of Glpi", "renamer");

                        }
                    }
                }
            }

            break;

        case 'restoreWord':

            if(PluginRenamerInstall::checkRightAccessOnGlpiLocalesFiles()){
                $renamer = new PluginRenamerRenamer();
                echo $renamer->restoreWord($_POST['id']);
            }else{
                echo  __("Please give write permission to the 'locales' folder of Glpi", "renamer");
            }

            break;


        case 'restore':

            if(!PluginRenamerInstall::checkRightAccessOnGlpiLocalesFiles()){
                Session::addMessageAfterRedirect(__("Please give write permission to the 'locales' folder of Glpi", "renamer"), false, ERROR);
                return false;
            }

            if(!PluginRenamerInstall::cleanLocalesFilesOfGlpi()){
                Session::addMessageAfterRedirect(__("Error while cleaning glpi locale files", "renamer"), false, ERROR);
                return false;
            }

            if(!PluginRenamerInstall::restoreLocalesFielsOfGlpi()){
                Session::addMessageAfterRedirect(__("Error while restore glpi locale files", "renamer"), false, ERROR);
                return false;
            }

            $DB->query("TRUNCATE TABLE `glpi_plugin_renamer_renamers`", "renamer");

            Session::addMessageAfterRedirect(__("Restoration Complete", "renamer"), false, INFO);

            echo true;

            break;

        case 'updateWord':

            if(isset($_POST['new_word']) && $_POST['new_word'] == ""){
                echo __("Please complete the field 'new Substitut'","rename");
            }else{

                $renamer = new PluginRenamerRenamer();
                $res = $renamer->updateOverloadWord($_POST['id'],$_POST['new_word']);

                echo $res;

            }

            break;

        default:
            echo 0;
    }

} else {
    echo 0;
}


function returnSuccess(){
    global $CFG_GLPI;
    echo "<img src='" . $CFG_GLPI['root_doc'] . "/plugins/renamer/pics/check16.png'/>";
}

function returnError(){
    global $CFG_GLPI;
    echo "<img src='" . $CFG_GLPI['root_doc'] . "/plugins/renamer/pics/cross16.png'/>";
}

