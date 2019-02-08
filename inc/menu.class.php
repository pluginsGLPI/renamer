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
@author    Stanislas Kita (teclib')
@co-author François Legastelois (teclib')
@co-author Le Conseil d'Etat
@copyright Copyright (c) 2014 GLPI Plugin Renamer Development team
@license   GPLv3 or (at your option) any later version
http://www.gnu.org/licenses/gpl.html
@link      https://github.com/pluginsGLPI/renamer
@since     2014

------------------------------------------------------------------------
*/

class PluginRenamerMenu extends CommonGLPI {
   static function getTypeName($nb = 0) {
      return __('Overload Language', 'renamer');
   }

   static function getMenuName() {
      return __('Renamer', 'renamer');
   }

   static function getMenuContent() {
      $search_url = PluginRenamerRenamer::getFormUrl(false);

      $menu = [
         'title' => self::getMenuName(),
         'page'  => $search_url,
      ];

      $links = [
         'search' => $search_url,
      ];

      if (Session::haveRight('config', UPDATE)) {
         $links['config'] = PluginRenamerConfig::getFormUrl(false);
      }

      if (Session::haveRight('config', READ)) {
         $menu['options'] = [
            'overload' => [
               'title' => self::getTypeName(),
               'page'  => $search_url,
               'links' => $links,
            ],
            'config' => [
               'title' => PluginRenamerConfig::getTypeName(),
               'page'  => PluginRenamerConfig::getFormUrl(false),
               'links' => $links,
            ]
         ];

      }

      return $menu;
   }
}
