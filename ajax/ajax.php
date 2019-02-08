<?php
/*
------------------------------------------------------------------------
GLPI Plugin Renamer
Copyright (C) 2014 by the GLPI Plugin Renamer Development Team.

https://forge.indepnet.net/projects/rennamer
------------------------------------------------------------------------

LICENSE

This file is part of GLPI Plugin Renamer project.

GLPI Plugin Renamer is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

GLPI Plugin Renamer is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with GLPI Plugin Renamer. If not, see <http://www.gnu.org/licenses/>.

------------------------------------------------------------------------

@package   GLPI Plugin Renamer
@author    Stanislas Kita (teclib')
@copyright Copyright (c) 2014 GLPI Plugin Renamer Development team
@license   GPLv3 or (at your option) any later version
http://www.gnu.org/licenses/gpl.html
@link      https://github.com/pluginsGLPI/renamer
@since     2014

------------------------------------------------------------------------
*/
include('../../../inc/includes.php');

if (isset($_POST['action'])) {
   switch ($_POST['action']) {
      case 'restoreALanguage':
         echo PluginRenamerRenamer::restoreLanguage($_POST['lang']);
         break;

      case 'updateOverload':
         echo PluginRenamerRenamer::updateOverload($_POST['id'], $_POST['newWord']);
         break;

      case 'getWords':
         echo PluginRenamerRenamer::getWords($_POST['word'], $_POST['lang']);
         break;

      case 'overloadWord':
         echo PluginRenamerRenamer::overloadWord($_POST);
         break;

      case 'restoreWord':
         echo PluginRenamerRenamer::restoreWord($_POST['id']);
         break;

      case 'restore':
         echo PluginRenamerRenamer::restoreAll();
         break;

      default:
         echo 0;
   }
   exit;
}

echo 0;
