<?php

/*
   ------------------------------------------------------------------------
   GLPI Plugin Renamer
   Copyright (C) 2014 by the GLPI Plugin Renamer Development Team.

   https://github.com/pluginsGLPI/renamer
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
   @author    Stanislas Kita (Teclib')
   @copyright Copyright (c) 2014 GLPI Plugin Renamer Development team
   @license   GPLv3 or (at your option) any later version
              http://www.gnu.org/licenses/gpl.html
   @link      https://github.com/pluginsGLPI/renamer
   @since     2014

   ------------------------------------------------------------------------
 */

define('PLUGIN_RENAMER_VERSION', '2.0');

// Minimal GLPI version, inclusive
define("PLUGIN_RENAMER_MIN_GLPI", "9.4");
// Maximum GLPI version, exclusive
define("PLUGIN_RENAMER_MAX_GLPI", "9.5");

function plugin_init_renamer() {
   global $PLUGIN_HOOKS;

   $plugin = new Plugin();

   $PLUGIN_HOOKS['csrf_compliant']['renamer'] = true;
   $PLUGIN_HOOKS['change_profile']['renamer'] = [
      'PluginRenamerProfile',
      'changeProfile'
   ];

   Plugin::registerClass('PluginRenamerProfile', [
      'addtabon' => ['Profile']
   ]);

   if (Session::getLoginUserID()) {
      if (Session::haveRight("config", UPDATE)) {

         // Add link in GLPI plugins list :
         $PLUGIN_HOOKS['config_page']['renamer'] = "front/config.form.php";

         // add to 'Admin' menu :
         $PLUGIN_HOOKS["menu_toadd"]['renamer'] = [
            'admin' => 'PluginRenamerMenu'
         ];
      }
   }

   if (Session::getLoginUserID() && $plugin->isActivated('renamer')) {
      $PLUGIN_HOOKS['add_javascript']['renamer'] = [
         'scripts/jquery-picklist.min.js',
         'scripts/renamer.js.php'
      ];
      $PLUGIN_HOOKS['add_css']['renamer'] = [
         'css/renamer.css',
         'css/jquery-picklist.css',
         'jquery-picklist-ie7.css'
      ];

      include_once GLPI_ROOT . "/plugins/renamer/vendor/autoload.php";
   }
}

function plugin_version_renamer() {
   return [
      'name'            => "Renamer",
      'version'         => PLUGIN_RENAMER_VERSION,
      'author'          => 'TECLIB\'',
      'license'         => 'GPLv3',
      'homepage'        => 'https://github.com/TECLIB/renamer',
      'requirements'   => [
         'glpi' => [
            'min' => PLUGIN_RENAMER_MIN_GLPI,
            'max' => PLUGIN_RENAMER_MAX_GLPI,
         ]
      ]
   ];
}


function plugin_renamer_check_config($verbose = false) {

   return true;
}
