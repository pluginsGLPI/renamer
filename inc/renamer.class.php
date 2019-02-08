<?php
/*
------------------------------------------------------------------------
GLPI Plugin renamer
Copyright (C) 2014 by the GLPI Plugin renamer Development Team.

https://github.com/pluginsGLPI/renamer
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
@copyright Copyright (c) 2014 GLPI Plugin renamer Development team
@license   GPLv3 or (at your option) any later version
http://www.gnu.org/licenses/gpl.html
@link      https://github.com/pluginsGLPI/renamer
@since     2014

------------------------------------------------------------------------
*/

use Gettext\Translations;

class PluginRenamerRenamer extends CommonDBTM {

   static function canCreate() {
      return Session::haveRight('config', UPDATE);
   }

   static function canView() {
      return Session::haveRight('config', READ);
   }

   function showForm() {

      echo "<div>";
      $this->showBtnToRestoreLanguage();
      echo "<br>";
      $this->showHistory();
      echo "<br>";
      $this->showFormToOverload();
      echo "</div>";

      /*require_once('../lib/PoParser.php');
      global $CFG_GLPI;
      $file = $this->getLanguageFile('Français');
      $poParser = new PoParser();
      $entries = $poParser->parse($_SERVER['DOCUMENT_ROOT'].$CFG_GLPI["root_doc"].'/locales/'.$file);

      foreach($entries as $entry){
      var_dump($entry);
      }*/

   }


   function showFormToOverload() {
      $conf = new PluginRenamerConfig();
      $conf->getFromDB(1);
      $lang      = $conf->getelectedLanguage();
      $lang_user = $this->getLanguage($_SESSION['glpilanguage']);

      $content = "<table class='tab_cadre'cellpadding='5'>";
      $content .= "<th colspan='2'>".__('Overload Language', 'renamer')."</th>";

      $content .= "<tr class='headerRow'>";
      $content .= "<th>".__('Language', 'renamer')."</th>";
      $content .= "<th>".__('Search Word', 'renamer')."</th>";
      $content .= "</tr>";

      $content .= "<tr class='tab_bg_1'>";
      $content .= "<td class='center'>";

      if ($conf->fields['lang_selected'] == null || count($lang) === 0) {
         $content .= Dropdown::showLanguages("language", [
            'display' => false,
            'value'   => $_SESSION['glpilanguage'],
            'rand'    => ''
         ]);
      } else {

         $content .= '<select id="dropdown_language" name="dropdown_language" selected="selected"  >';
         foreach ($lang as $l) {
            if ($l == $lang_user) {
               $content .= ' <option value="'.$l.'" selected="selected">'.$l.'</option>';
            } else {
               $content .= ' <option value="'.$l.'">'.$l.'</option>';
            }
         }
         $content .= '</select>';
      }

      $content .= "</td>";

      $content .= "<td class='center'>";
      $content .= "<input type='text' id='searchword' />";
      $content .= "<div style='width:24px; float:right; padding-left:10px;' id='infoSearchWord'><img id='waitLoading' style='width:24px; display:none;' src='../pics/loading.gif'></div>";
      $content .= "</td>";
      $content .= "</tr>";

      $content .= "<table class='tab_cadre'cellpadding='5' id='tableOverloadWord'>";
      $content .= "<th colspan='6'>".__("List of words found", "renamer")."</th>";
      $content .= "<tr class='headerRow'>";
      $content .= "<th>".__("ID", "renamer")."</th>";
      $content .= "<th>".__("msgctx", "renamer")."</th>";
      $content .= "<th>".__("plural", "renamer")."</th>";
      $content .= "<th>".__("String", "renamer")."</th>";
      $content .= "<th colspan='2'>"._x("field", "Overload", "renamer")."</th>";
      //$content .= "<th></th>";
      $content .= "</tr>";

      $content .= "<br>";
      $content .= "<tbody id='tbody'>";

      $content .= "</td>";
      $content .= "</tbody>";
      $content .= "</table>";

      $content .= "<input type='hidden' name='users_id'      id='users_id'      value=".Session::getLoginUserID().">";
      $content .= "<input type='hidden' name='date_overload' id='date_overload' value=".date("Y-m-d").">";

      $content .= "</table>";

      echo $content;
   }




   function showHistory() {
      global $CFG_GLPI;
      $res = $this->getHistory();

      if ($res->num_rows > 0) {

         $content = "<table id='table2' class='tab_cadre_fixe'>";
         $content .= "<th colspan='10'>".__("History of overload", "renamer")."</th>";

         $content .= "<tr class='headerRow'>";
         $content .= "<th>".__("ID", "renamer")."</th>";
         $content .= "<th>".__("msgid", "renamer")."</th>";
         $content .= "<th>".__("msgctxt", "renamer")."</th>";
         $content .= "<th>".__("Date", "renamer")."</th>";
         $content .= "<th>".__("Language", "renamer")."</th>";
         $content .= "<th>".__("Original", "renamer")."</th>";
         $content .= "<th>"._x("field", "Overload", "renamer")."</th>";
         $content .= "<th>".__("User")."</th>";
         $content .= "<th>".__("Restore")."</th>";
         $content .= "<th>".__("Update", "renamer")."</th>";
         $content .= "</tr>";

         $user = new User();

         while ($row = $res->fetch_assoc()) {

            $user->getFromDB($row["users_id"]);

            $content .= "<tr class='center'>";
            $content .= "<td>".$row["id"]."</td>";
            $content .= "<td lang='en' dir='ltr'>";
            $content .= implode('<br>', unserialize(stripslashes(stripslashes($row['msgid']))));
            $content .= "</td>";

            $content .= "<td>";
            if ($row['context'] == null) {
               $content .= __('No', 'renamer');
            } else {
               $content .= implode('<br>', unserialize(stripslashes(stripslashes(str_replace("]", "'", $row['context'])))));
            }
            $content .= "</td>";

            $content .= "<td>".Html::convDate($row["date_overload"])."</td>";
            $content .= "<td>".$row["lang"]."</td>";
            $content .= "<td>";
            $original = unserialize(stripslashes(stripslashes(str_replace("]", "'", $row['original']))));
            $content .= $original;
            $content .= "</td>";
            $content .= "<td>".$row["overload"]."</td>";
            $content .= "<td>".$user->getName()."</td>";

            $content .= "<td><img src='".$CFG_GLPI['root_doc']."/plugins/renamer/pics/bin16.png' onclick='restoreWord(".$row['id'].")'"."style='cursor: pointer;' title='".__("Delete overload", "renamer")."'/></td>";

            $content .= "<td><input type='text' id='updateWord".$row["id"]."' value='$original' /> ";
            $content .= "<img src='".$CFG_GLPI['root_doc']."/plugins/renamer/pics/update16.png' onclick='updateOverload(".$row['id'].")'"."style='cursor: pointer;' title='".__("Update overload", "renamer")."'/>
                    <img id='waitLoadingOnUpdate' style='min-width:24px; display:none;' class='center' src='../pics/please_wait.gif'></td>";

         }
         $content .= "</table>";

      } else {
         $content = "<table id='table1' class='tab_cadre_fixe'>";
         $content .= "<th colspan='10'>".__("History of overload", "renamer")."</th>";

         $content .= "<tr class='tab_bg_1'>";

         $content .= "<td class='center'>";
         $content .= __("No history to show for the moment", "renamer");
         $content .= "</td>";

         $content .= "</tr>";

         $content .= "</table>";
      }

      echo $content;
   }

   /**
    * function to show boutton to overload Language or restore
    */
   function showBtnToRestoreLanguage() {
      $content = "<table id='table1'  class='tab_cadre_fixe'>";
      $content .= "<th colspan='3'>".__("Restore")."</th>";

      $content .= "<tr class='headerRow'>";
      $content .= "<th>".__('Restore all languages', 'renamer')."</th>";
      $content .= "<th colspan='2'>".__("Restore a language ", "renamer")."</th>";
      $content .= "</tr>";

      $content .= "<tr class='tab_bg_1'>";

      $content .= "<td style='text-align: center;'>";
      $content .= "<input  onclick='restoreAllLocaleFiles();'  value='".__('Restore')."' class='submit'    style='width : 200px;'>";
      $content .= "</td>";

      $content .= "<td style='text-align: center;'>";
      $content .= Dropdown::showLanguages("languageToRestore", [
         'display' => false,
         'value' => $_SESSION['glpilanguage'],
         'rand' => ''
      ]);
      $content .= "</td>";
      $content .= "<td style='text-align: center;'>";
      $content .= "<input onclick='restoreLocaleFiles();' value='".__('Restore')."' class='submit' style='width: 200px;'>";
      $content .= "</td>";

      $content .= "</tr>";
      $content .= "</table>";

      echo $content;
   }



   /**
    * Function to retrieve language file with name of language
    * @param $lang
    * @return mixed|string
    */
   public function getLanguageFile($lang) {
      global $CFG_GLPI;
      $file = "";

      foreach ($CFG_GLPI["languages"] as &$local) {
         if ($lang == $local[0]) {
            $file = $local[1];
         }
      }
      $file = str_replace('mo', 'po', $file);

      return $file;
   }


   /**
    * Function to find all links record for an item
    * @param $item
    * @return Query
    */
   public function getHistory() {
      global $DB;
      return $DB->query("SELECT `glpi_plugin_renamer_renamers`.*
                        FROM `glpi_plugin_renamer_renamers`");
   }

   /**
    * Function to check if the word to overload is an overload of another word
    * @param $_post
    * @return true
    */
   public function isAlreadyOverload(Array $params) {
      $context = [];
      if ($context != 'null') {
         $context = [
            'context' => $params['msgctxt'],
         ];
      }

      return $this->getFromDBByCrit([
         'msgid'    => (int) $params['id'],
         'original' => $params['wordToOverload'],
         'lang'     => $params['lang'],
      ] + $context);
   }


   /**
    * function to update translation
    * $file_patch -> file to update
    * @param $file_patch
    */
   public function updateTranslation($po_file) {
      $mo_file = substr($po_file, 0, -3).".mo";
      //exec("msgcat ".$po_file." | msgfmt -o ".$mo_file." - ");

      include_once "../lib/gettext/gettext/src/autoloader.php";
      include_once "../lib/gettext/languages/src/autoloader.php";

      $translations = Translations::fromPoFile($po_file);
      $translations->toMoFile($mo_file);
   }


   /**
    * save file into tmp directorie
    * @param $file
    * @return bool
    */
   public function saveFileIntoTmp($file) {
      if (!copy(GLPI_ROOT. '/locales/'.$file, GLPI_ROOT.'/plugins/renamer/tmp/'.$file)) {
         Toolbox::logInFile('renamer', sprintf(__('Can\'t save file  \'%1$s\' in tmp folder', 'renamer'), $file)."\n");
         return false;
      }
      return true;
   }

   /**
    * remove file in tmp directories
    * @param $file
    * @return bool
    */
   public function removeFileIntoTmp($file) {
      if (!unlink(GLPI_ROOT.'/plugins/renamer/tmp/'.$file)) {
         Toolbox::logInFile('renamer', sprintf(__('Can\'t remove file  \'%1$s\' in tmp folder', 'renamer'), $file)."\n");
         return false;
      }
      return true;
   }

   /**
    * restore a file in tmp directorie to locale directories of glpi
    * @param $file
    * @return bool
    */
   public function restoreFileFromTmp($file) {
      if ($this->removeFileFromLocaleOfGlpi($file)) {
         if (!copy(GLPI_ROOT.'/plugins/renamer/tmp/'.$file, GLPI_ROOT.'/locales/'.$file)) {
            Toolbox::logInFile('renamer', sprintf(__('Can\'t restore file  \'%1$s\' in locale folder of Glpi', 'renamer'), $file)."\n");
            return false;
         } else {
            return true;
         }
      }
      return false;
   }

   /**
    * Remove file in local directorie of glpi
    * @param $file
    * @return bool
    */
   public function removeFileFromLocaleOfGlpi($file) {
      if (!unlink(GLPI_ROOT.'/locales/'.$file)) {
         Toolbox::logInFile('renamer', sprintf(__('Can\'t remove file  \'%1$s\' in locale folder of Glpi', 'renamer'), $file)."\n");
         return false;
      }
      return true;
   }

   private function getLanguage($glpilanguage) {
      global $CFG_GLPI;
      $lang = '';

      foreach ($CFG_GLPI["languages"] as $key => $value) {
         if ($glpilanguage == $key) {
            $lang = $value[0];
            break;
         }
      }
      return $lang;
   }

   static function returnSuccess() {
      global $CFG_GLPI;
      return "<img src='".$CFG_GLPI['root_doc']."/plugins/renamer/pics/check16.png'/>";
   }

   static function returnError($error) {
      global $CFG_GLPI;
      return "<div>
         <img src='".$CFG_GLPI['root_doc']."/plugins/renamer/pics/cross16.png'/>
         $error
      </div>";
   }

   static function returnWarning() {
      global $CFG_GLPI;
      return "<td colspan='6'><img src='".$CFG_GLPI['root_doc']."/plugins/renamer/pics/warning.png'/> ".__("Please give write permission to the 'locales' folder of Glpi", "renamer")."
               <img src='".$CFG_GLPI['root_doc']."/plugins/renamer/pics/warning.png'/></td>";
   }

   /**
    * Function to search on msgid
    * @param $word
    * @param $msgid
    * @param bool $exact
    * @return bool
    */
   static function searchOnMsgid($word, $msgid, $exact = false) {

      if ($exact) {
         foreach ($msgid as $id) {
            if (strtolower($id) === strtolower($word)) {
               return true;
            }
         }
      } else {
         foreach ($msgid as $id) {
            if (preg_match_all("#".preg_quote($word)."#i", $id)) {
               return true;
            }
         }
      }

   }


   /**
    * function to search word on msgstr
    * @param $word
    * @param $msgstr
    * @param bool $exact
    * @return bool
    */
   static function searchOnMsgstr($word, $msgstr, $exact = false) {

      if ($exact) {
         foreach ($msgstr as $str) {
            if (strtolower($str) === stripslashes(strtolower($word))) {
               return true;
            }
         }
      } else {
         foreach ($msgstr as $str) {
            if (preg_match_all("#".preg_quote($word)."#i", $str)) {
               return true;
            }
         }
      }

   }


   /**
    *  Search if string exist (==) on another string
    * @param $word
    * @param $id
    * @param $string
    * @return bool
    */
   static function existExactWord($word, $id, $string) {
      $resultId     = self::searchOnMsgid($word, $id, true);
      $resultString = self::searchOnMsgstr($word, $string, true);
      if ($resultString || $resultId) {
         return true;
      }

   }

   /**
    * Search if string exist (stripos) on another string
    * @param $word search
    * @param $string pattern
    * @return bool
    */
   static function existword($word, $string, $id) {
      $resultId     = self::searchOnMsgid($word, $id, false);
      $resultString = self::searchOnMsgstr($word, $string, false);
      if ($resultString || $resultId) {
         return true;
      }

   }

   /**
    * Create table row for an entry
    * @param $entry
    * @return string
    */
   static function createTableRow($entry, $word) {
      $i       = 0;
      $content = "";
      $index   = $entry['index'];

      foreach ($entry['msgstr'] as $str) {

         $nb = rand();

         $content .= "<tr class='tab_bg_1'>";

         if ($i != 1) {
            $content .= "<td rowspan=".count($entry['msgstr']).">";
            $content .= self::addHighlightingWord(implode('<br>', $entry['msgid']), $word);
            $content .= "</td>";

            $content .= "<td rowspan=".count($entry['msgstr']).">";
            if (isset($entry['msgctxt'])) {
               $content .= implode('<br>', $entry['msgctxt']);
            } else {
               $content .= __('No', 'renamer');
            }

            $content .= "</td>";

            $content .= "<td rowspan=".count($entry['msgstr']).">";
            if (isset($entry['msgid_plural'])) {
               $content .= self::addHighlightingWord(implode('<br>', $entry['msgid_plural']), $word);
            } else {
               $content .= __('No', 'renamer');
            }

            $content .= "</td>";
            $i++;
         }

         $content .= "<td>".self::addHighlightingWord($str, $word)."</td>";
         $content .= "<td>";
         $content .= "<input type='text' id='newWord$index$nb' /> ";
         $content .= "<input onclick='overloadWord($index$nb);' value='"._x('action', 'Overload', 'renamer')."' class='submit' style='width: 80px;'>";
         $content .= "</td>";

         $content .= "<td>";
         $content .= "<div style='min-width:24px; float:right; padding-left:10px;' id='info$index$nb'></div><img id='waitLoadingOverload$index$nb' style='width:24px; display:none;' src='../pics/loading.gif'>";
         $content .= "</td>";

         $content .= "<input type='hidden' name='msgid' id='msgid$index$nb' value='".addslashes(serialize($entry['msgid']))."'>";
         $content .= "<input type='hidden' name='msgstr' id='msgstr$index$nb' value='".addslashes(serialize(str_replace("'", "]", $str)))."'>";

         if (isset($entry['msgctxt'])) {
            $content .= "<input type='hidden' name='msgctxt' id='msgctxt$index$nb' value='".addslashes(serialize(str_replace("'", "]", $entry['msgctxt'])))."'>";
         } else {
            $content .= "<input type='hidden' name='msgctxt' id='msgctxt$index$nb' value='null'>";
         }

         $content .= "</tr>";
      }

      return $content;
   }

   /**
    * add Highlightning to a word in string
    * @param $str
    * @param $word
    * @return mixed
    */
   static function addHighlightingWord($str, $word) {
      $motif  = '`(.*?)('.preg_quote($word).')(.*?)`si';
      $sortie = '$1<span class="highlighting">$2</span>$3';
      return preg_replace($motif, $sortie, $str);
   }

   static function restoreLanguage($lang = "") {
      global $CFG_GLPI;

      if (PluginRenamerInstall::checkRightAccessOnGlpiLocalesFiles()) {
         $renamer  = new self();
         $file = $renamer->getLanguageFile($lang);

         //remove locale file of glpi
         if (!PluginRenamerInstall::cleanLocalesFileOfGlpi($file)) {
            Session::addMessageAfterRedirect(__("Error while cleaning glpi locale files", "renamer"), false, ERROR);
            return false;
         }

         //restore local file of glpi with back og plugin renamer
         if (!PluginRenamerInstall::restoreLocalesFielOfGlpi($file)) {
            Session::addMessageAfterRedirect(__("Error while restore glpi locale files", "renamer"), false, ERROR);
            return false;
         }

         //clean table
         $renamer->deleteByCriteria([
            'lang' => $lang,
         ]);
         Session::addMessageAfterRedirect(__("Restoration Complete", "renamer"), false, INFO);

         $renamer->updateTranslation($_SERVER['DOCUMENT_ROOT'].$CFG_GLPI["root_doc"].'/locales/'.$file);
         return true;

      } else {
         Session::addMessageAfterRedirect(__("Please give write permission to the 'locales' folder of Glpi", "renamer"), false, INFO);
         return false;
      }
   }

   static function updateOverload($id = "", $newWord = "") {
      global $CFG_GLPI;

      $renamer  = new self();

      //checl if right access on glpi locales file
      if (PluginRenamerInstall::checkRightAccessOnGlpiLocalesFiles()) {

         //get record to update on bdd
         $renamer->getFromDB($id);

         $lang     = $renamer->fields['lang'];
         $overload = $renamer->fields['overload'];
         $msgid    = $renamer->fields['msgid'];
         $msgctxt  = $renamer->fields['context'];

         $id      = unserialize(stripslashes(stripslashes($msgid)));
         $context = unserialize(stripslashes(stripslashes($msgctxt)));

         $file     = $renamer->getLanguageFile($lang);
         $entries  = $poParser->parse(GLPI_ROOT.'/locales/'.$file);
         $header   = $poParser->getHeaders();
         $newEntry = [];

         if (isset($entry['msgctxt'])) {

            foreach ($entries as $entry) {
               if ($entry['msgid'] == $id) {

                  if ($entry['msgctxt'] == $context) {
                     for ($i = 0; $i < count($entry['msgstr']); ++$i) {
                        if ($entry['msgstr'][$i] == $overload) {
                           $entry['msgstr'][$i] = $newWord;
                           $find                = true;
                        }
                     }
                  }
               }
               $newEntry[] = $entry;
            }

         } else {

            foreach ($entries as $entry) {
               if ($entry['msgid'] == $id) {
                  for ($i = 0; $i < count($entry['msgstr']); ++$i) {
                     if ($entry['msgstr'][$i] == $overload) {
                        $entry['msgstr'][$i] = $newWord;
                        $find                = true;
                     }
                  }
               }
               $newEntry[] = $entry;
            }

         }

         //sauvegarde temporaire du fichier à updaté
         if ($renamer->saveFileIntoTmp($file)) {

            $poParser = new PoParser();
            $poParser->setEntries($newEntry);
            $poParser->setHeaders($header);
            $res = $poParser->write($_SERVER['DOCUMENT_ROOT'].$CFG_GLPI["root_doc"].'/locales/'.$file);

            //si write ok
            if ($res) {

               //update translate
               $renamer->updateTranslation(GLPI_ROOT. '/locales/'.$file);
               //delete tmp file
               $renamer->removeFileIntoTmp($file);

               //update bdd entry
               $input                  = [];
               $input['id']            = $id;
               $input['overload']      = $newWord;
               $input['date_overload'] = date("Y-m-d");

               $renamer->update($input);
               Session::addMessageAfterRedirect(sprintf(__('\'%1$s\' replaced by \'%2$s\'', "renamer"), $overload, $newWord), false, INFO);

               return true;
            } else {
               //restore locales file from tmp
               $renamer->restoreFileFromTmp($file);
               //remove tmp file
               $renamer->removeFileIntoTmp($file);
               Session::addMessageAfterRedirect(sprintf(__('Can\'t access to file \'%1$s\'', 'renamer').$file), false, INFO);
            }

         } else {
            Session::addMessageAfterRedirect(sprintf(__('Can\'t save file  \'%1$s\' in tmp folder', 'renamer'), $file), false, INFO);
            return false;
         }

      } else {
         Session::addMessageAfterRedirect(__("Please give write permission to the 'locales' folder of Glpi", "renamer"), false, INFO);
         return false;
      }
   }

   static function getWords($word = "", $lang = "") {
      $renamer  = new self();
      require_once('../lib/PoParser.php');
      $poParser = new PoParser();
      $saved_entries = null;
      $saved_lang   = null;

      if (PluginRenamerInstall::checkRightAccessOnGlpiLocalesFiles()) {
         if ($word != ""
             && $lang != "") {
            $file = $renamer->getLanguageFile($lang);

            if ($saved_lang == null
                || $saved_lang != null
                    && $saved_lang != $lang) {
               $saved_entries = $entries = $poParser->parse(GLPI_ROOT.'/locales/'.$file);
               $saved_lang = $lang;
            } else {
               $entries = $saved_entries;
            }

            $content = "";

            $find = false;
            foreach ($entries AS $entry) {
               if (self::existExactWord($word, $entry['msgid'], $entry['msgstr'])) {
                  $content .= self::createTablerow($entry, $word);
                  $find = true;

                  if (!isset($entry['msgctxt'])) {
                     break;
                  }
               }
            }

            if (!$find) {
               foreach ($entries AS $entry) {
                  if (self::existWord($word, $entry['msgid'], $entry['msgstr'])) {
                     $content .= self::createTablerow($entry, $word);
                  }
               }
            }

            return $content;
         }

      } else {
         return self::returnWarning();
      }
   }

   static function overloadWord($params = []) {
      global $CFG_GLPI;
      $renamer  = new self();
      require_once('../lib/PoParser.php');
      $poParser = new PoParser();

      $wordToOverload = unserialize(stripslashes(stripslashes(str_replace("]", "'", $params['wordToOverload']))));
      $context        = '';

      if ($params['msgctxt'] == 'null') {
         $context = null;
      } else {
         $context = unserialize(stripslashes(stripslashes(str_replace("]", "'", $params['msgctxt']))));
      }

      if ($renamer->isAlreadyOverload($params)) {
         echo self::returnError(__('This Word is already overload ', 'renamer').$file);
      } else {

         $newword  = $params['word'];
         $lang     = $params['lang'];
         $id       = unserialize(stripslashes(stripslashes($params['id'])));
         $file     = $renamer->getLanguageFile($lang);
         $entries  = $poParser->parse(GLPI_ROOT.'/locales/'.$file);
         $header   = $poParser->getHeaders();
         $newEntry = [];

         if ($context == null) {
            //on parcours chaque entry
            foreach ($entries as $entry) {
               //quand on à  l'id
               if ($entry['msgid'] == $id) {
                  //on compare chaque msgstr
                  for ($i = 0; $i < count($entry['msgstr']); ++$i) {
                     //quand on le trouve on le modifie
                     if ($entry['msgstr'][$i] == $wordToOverload) {
                        $entry['msgstr'][$i] = $newword;
                     }
                  }
               }
               //toute les entry vont dans un nouveau tableau
               $newEntry[] = $entry;
            }
         } else {
            //on parcours chaque entry
            foreach ($entries as $entry) {
               //quand on à  l'id
               if ($entry['msgid'] == $id) {

                  if (isset($entry['msgctxt']) && $entry['msgctxt'] == $context) {
                     //on compare chaque msgstr
                     for ($i = 0; $i < count($entry['msgstr']); ++$i) {
                        //quand on le trouve on le modifie
                        if ($entry['msgstr'][$i] == $wordToOverload) {
                           $entry['msgstr'][$i] = $newword;
                        }
                     }
                  }
               }
               //toute les entry vont dans un nouveau tableau
               $newEntry[] = $entry;
            }
         }

         // clean empty entries (without msgcat crash)
         foreach ($newEntry as &$current) {
            for ($i = 0; $i < count($current['msgstr']); ++$i) {
               if (strlen(trim($current['msgstr'][$i])) === 0) {
                  $current['msgstr'][$i] = $current['msgid'][$i];
               }
            }
         }

         //sauvegarde temporaire du fichier à updaté
         if ($renamer->saveFileIntoTmp($file)) {
            $poParser->setEntries($newEntry);
            $poParser->setHeaders($header);
            $res = $poParser->write($_SERVER['DOCUMENT_ROOT'].$CFG_GLPI["root_doc"].'/locales/'.$file);

            //si write ok
            if ($res) {

               //update tranlate
               $renamer->updateTranslation(GLPI_ROOT.'/locales/'.$file);
               //del tmp file
               $renamer->removeFileIntoTmp($file);

               $input                  = [];
               $input['msgid']         = $params['id'];
               $input['users_id']      = Session::getLoginUserID();
               $input['date_overload'] = date("Y-m-d");
               $input['lang']          = $params['lang'];

               if ($context == null) {
                  $input['context'] = $context;
               } else {
                  $input['context'] = $params['msgctxt'];
               }

               $input['original'] = $params['wordToOverload'];
               $input['overload'] = $newword;
               //add bdd entry
               $renamer->add($input);

               return self::returnSuccess();
            } else {
               $renamer->restoreFileFromTmp($file);
               $renamer->removeFileIntoTmp($file);
               return self::returnError(sprintf(__('Can\'t access to file \'%1$s\'', 'renamer').$file));
            }

         } else {
            return self::returnError(sprintf(__('Can\'t save file  \'%1$s\' in tmp folder', 'renamer'), $file));
         }

      }
   }

   static function restoreWord($id = "") {
      global $CFG_GLPI;

      if (PluginRenamerInstall::checkRightAccessOnGlpiLocalesFiles()) {

         $renamer  = new self();
         $renamer->getFromDB($id);

         $lang     = $renamer->fields['lang'];
         $original = $renamer->fields['original'];
         $overload = $renamer->fields['overload'];
         $msgid    = $renamer->fields['msgid'];

         $raw_id   = $id;
         $id       = unserialize(stripslashes(stripslashes($msgid)));
         $newWord  = unserialize(stripslashes(stripslashes(str_replace("]", "'", $original))));

         $file     = $renamer->getLanguageFile($lang);

         require_once('../lib/PoParser.php');
         $poParser = new PoParser();
         $entries  = $poParser->parse(GLPI_ROOT.'/locales/'.$file);
         $header   = $poParser->getHeaders();
         $newEntry = [];

         foreach ($entries as $entry) {
            if ($entry['msgid'] == $id) {
               for ($i = 0; $i < count($entry['msgstr']); ++$i) {
                  if ($entry['msgstr'][$i] == $overload) {
                     $entry['msgstr'][$i] = $newWord;
                  }
               }
            }
            $newEntry[] = $entry;
         }

         //sauvegarde temporaire du fichier à updaté
         if ($renamer->saveFileIntoTmp($file)) {
            $poParser->setEntries($newEntry);
            $poParser->setHeaders($header);
            $res = $poParser->write($_SERVER['DOCUMENT_ROOT'].$CFG_GLPI["root_doc"].'/locales/'.$file);

            //si write ok
            if ($res) {

               //update tranlate
               $renamer->updateTranslation(GLPI_ROOT.'/locales/'.$file);
               //del tmp file
               $renamer->removeFileIntoTmp($file);

               //del bdd entry
               $renamer->delete([
                  'id' => $raw_id,
               ]);

               Session::addMessageAfterRedirect(sprintf(__('\'%1$s\' replaced by \'%2$s\'', "renamer"), $overload, $newWord), false, INFO);
               return true;

            } else {

               $renamer->restoreFileFromTmp($file);
               $renamer->removeFileIntoTmp($file);
               Session::addMessageAfterRedirect(sprintf(__('Can\'t access to file ', 'renamer').$file), false, INFO);
               return false;
            }

         } else {
            Session::addMessageAfterRedirect(sprintf(__('Can\'t save file  \'%1$s\' in tmp folder', 'renamer'), $file), false, INFO);
            return false;
         }

      } else {
         Session::addMessageAfterRedirect(__("Please give write permission to the 'locales' folder of Glpi", "renamer"), false, INFO);
         return false;
      }
   }

   static function restoreAll() {
      global $DB;

      //check if right access
      if (!PluginRenamerInstall::checkRightAccessOnGlpiLocalesFiles()) {
         Session::addMessageAfterRedirect(__("Please give write permission to the 'locales' folder of Glpi", "renamer"), false, ERROR);
         return false;
      }

      //remove locale file of glpi
      if (!PluginRenamerInstall::cleanLocalesFilesOfGlpi()) {
         Session::addMessageAfterRedirect(__("Error while cleaning glpi locale files", "renamer"), false, ERROR);
         return false;
      }

      //restore local file of glpi with back og plugin renamer
      if (!PluginRenamerInstall::restoreLocalesFielsOfGlpi()) {
         Session::addMessageAfterRedirect(__("Error while restore glpi locale files", "renamer"), false, ERROR);
         return false;
      }

      //clean table
      $DB->query("TRUNCATE TABLE `glpi_plugin_renamer_renamers`", "renamer");
      Session::addMessageAfterRedirect(__("Restoration Complete", "renamer"), false, INFO);

      return true;
   }

}
