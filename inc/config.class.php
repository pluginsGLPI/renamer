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

class PluginRenamerConfig extends CommonDBTM {



    /**
     * Function to define if the user have right to create
     * @return bool|booleen
     */
    static function canCreate() {
        return Session::haveRight('config', 'w');
    }


    /**
     * Function to define if the user have right to view
     * @return bool|booleen
     */
    static function canView() {
        return Session::haveRight('config', 'r');
    }





    function defineTabs($options=array()){

        $ong = array();
        $this->addStandardTab("PluginRenamerRenamer", $ong, $options);
        $this->addStandardTab("PluginRenamerHistory", $ong, $options);

        return $ong;
    }




    /**
     * Display tab
     *
     * @param CommonGLPI $item
     * @param integer $withtemplate
     *
     * @return varchar name of the tab(s) to display
     */
     function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

        $ong = array();
        $ong[1] = __('General setup');
        $ong[2] = __('History');
        return $ong;
    }



    /**
     * Display content of tab
     *
     * @param CommonGLPI $item
     * @param integer $tabnum
     * @param interger $withtemplate
     *
     * @return boolean TRUE
     */
    static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

        switch ($tabnum) {
            case 1 : // mon premier onglet
                $history = new PluginRenamerHistory();
                $history->showForm();
                break;

            case 2 : // mon second onglet
                $renamer = new PluginRenamerRenamer();
                $renamer->showForm();
                break;
        }
        return true;
    }



}