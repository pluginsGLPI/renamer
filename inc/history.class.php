<?php

/*
   ------------------------------------------------------------------------
   GLPI Plugin renamer
   Copyright (C) 2014 by the GLPI Plugin renamer Development Team.

   https://forge.indepnet.net/projects/renamer
   ------------------------------------------------------------------------

   LICENSE

   This file is part of GLPI Plugin renamer project.

   GLPI Plugin renamer is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; either version 3 of the License, or
   (at your option) any later version.

   GLPI Plugin renamer is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with GLPI Plugin renamer. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   GLPI Plugin renamer
   @author    Stanislas Kita (teclib)
   @co-author François Legastelois (teclib)
   @copyright Copyright (c) 2014 GLPI Plugin renamer Development team
   @license   GPLv3 or (at your option) any later version
              http://www.gnu.org/licenses/gpl.html
   @link      https://forge.indepnet.net/projects/renamer
   @since     2014

   ------------------------------------------------------------------------
 */

class PluginRenamerHistory extends CommonDBTM {




    function showForm() {
        return "history";
    }

}

?>