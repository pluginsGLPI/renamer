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

class PluginRenamerInstall extends CommonDBTM {

   /**
    * Function to check if 'locales' folder of glpi is writable
    * @return bool
    */
   static function checkRightAccessOnGlpiLocalesFiles() {
      $locale_path = GLPI_ROOT . "/locales/";

      if ($dossier = opendir($locale_path)) {
         while (false !== ($fichier = readdir($dossier))) {
            if ($fichier != '.' && $fichier != '..' && $fichier != '.htaccess') {
               if (!is_writable($locale_path . $fichier)) {
                  return false;
               }
            }
         }
      } else {
         return false;
      }
      return true;
   }


   /**
    * Function to check if renamer plugin is writable
    * @return bool
    */
   static function checkRightAccesOnRenamerPlugin() {
      $locale_path = GLPI_ROOT . "/plugins/renamer/backup/";

      if ($dossier = opendir($locale_path)) {
         while (false !== ($fichier = readdir($dossier))) {
            if ($fichier != '.' && $fichier != '..' && $fichier != '.htaccess') {
               if (!is_writable($locale_path . $fichier)) {
                  return false;
               }
            }
         }
         return true;
      }
      return false;
   }

   /**
    * Function to backup locale files of glpi into backup folder of renamer plugin
    * @return bool
    */
   static function backupLocaleFiles() {
      $source_path      = GLPI_ROOT . "/locales/";
      $destination_path = GLPI_ROOT . "/plugins/renamer/backup/";

      if ($dossier = opendir($source_path)) {
         while (false !== ($fichier = readdir($dossier))) {
            if ($fichier != '.' && $fichier != '..' && $fichier != '.htaccess') {
               if (!copy($source_path . $fichier, $destination_path . $fichier)) {
                  Toolbox::logInFile('renamer', sprintf(__('Error when saving files  \'%1$s\' ', 'renamer'), $source_path . $fichier) . "\n");
                  return false;
               }
            }
         }
         return true;
      }
      return false;
   }

   /**
    * Function to clean backup folder
    * @return bool
    */
   static function cleanBackupFolder() {
      $source_path = GLPI_ROOT . "/plugins/renamer/backup/";

      if ($dossier = opendir($source_path)) {
         while (false !== ($fichier = readdir($dossier))) {
            if ($fichier != '.' && $fichier != '..' && $fichier != '.htaccess' && $fichier != 'test.txt') {
               if (!unlink($source_path . $fichier)) {
                  Toolbox::logInFile('renamer', sprintf(__('Error while deleting backup file  \'%1$s\' ', 'renamer'), $source_path . $fichier) . "\n");
                  return false;
               }
            }
         }
         return true;
      }
      return false;
   }


   /**
    * Function to clean locales folder of glpi
    * @return bool
    */
   static function cleanLocalesFilesOfGlpi() {
      $source_path = GLPI_ROOT . "/locales/";

      if ($dossier = opendir($source_path)) {
         while (false !== ($fichier = readdir($dossier))) {
            if ($fichier != '.' && $fichier != '..' && $fichier != '.htaccess' && $fichier != 'test.txt') {
               if (!unlink($source_path . $fichier)) {
                  Toolbox::logInFile('renamer', sprintf(__('Error while cleaning local glpi file  \'%1$s\' ', 'renamer'), $source_path . $fichier) . "\n");
                  return false;
               }
            }
         }
         return true;
      }
      return false;
   }


   /**
    * Function to restore locales file of renamer plugin into locales folder of glpi
    * @return bool
    */
   static function restoreLocalesFielsOfGlpi() {
      $destination_path = GLPI_ROOT . "/locales/";
      $source_path      = GLPI_ROOT . "/plugins/renamer/backup/";

      if ($dossier = opendir($source_path)) {
         while (false !== ($fichier = readdir($dossier))) {
            if ($fichier != '.' && $fichier != '..' && $fichier != '.htaccess') {
               if (!copy($source_path . $fichier, $destination_path . $fichier)) {
                  Toolbox::logInFile('renamer', sprintf(__('Error during the restoration of local file \'%1$s\' ', 'renamer'), $source_path . $fichier) . "\n");
                  return false;
               }
            }
         }
         return true;
      }
      return false;
   }


   public static function cleanLocalesFileOfGlpi($file) {
      $source_path = GLPI_ROOT . "/locales/";

      if ($dossier = opendir($source_path)) {
         while (false !== ($fichier = readdir($dossier))) {
            if ($fichier != '.' && $fichier != '..' && $fichier != '.htaccess' && $fichier != 'test.txt' && $fichier == $file) {
               if (!unlink($source_path . $fichier)) {
                  Toolbox::logInFile('renamer', sprintf(__('Error while cleaning local glpi file  \'%1$s\' ', 'renamer'), $source_path . $fichier) . "\n");
                  return false;
               }
            }
         }
         return true;
      }
      return false;
   }



   public static function restoreLocalesFielOfGlpi($file) {
      $destination_path = GLPI_ROOT . "/locales/";
      $source_path      = GLPI_ROOT . "/plugins/renamer/backup/";

      if ($dossier = opendir($source_path)) {
         while (false !== ($fichier = readdir($dossier))) {
            if ($fichier != '.' && $fichier != '..' && $fichier != '.htaccess' && $fichier == $file) {
               if (!copy($source_path . $fichier, $destination_path . $fichier)) {
                  Toolbox::logInFile('renamer', sprintf(__('Error during the restoration of local file \'%1$s\' ', 'renamer'), $source_path . $fichier) . "\n");
                  return false;
               }
            }
         }
         return true;
      }
      return false;
   }
}
