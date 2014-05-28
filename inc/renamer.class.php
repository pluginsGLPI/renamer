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
   @copyright Copyright (c) 2014 GLPI Plugin renamer Development team
   @license   GPLv3 or (at your option) any later version
              http://www.gnu.org/licenses/gpl.html
   @link      https://forge.indepnet.net/projects/renamer
   @since     2014

   ------------------------------------------------------------------------
 */

class PluginRenamerRenamer extends CommonDBTM
{

    /**
     * Function to define if the user have right to create
     * @return bool
     */
    static function canCreate(){
        return Session::haveRight('config', 'w');
    }

    /**
     * Function to define if the user have right to view
     * @return bool
     */
    static function canView(){
        return Session::haveRight('config', 'r');
    }


    function showForm(){

        echo "<div>";
        $this->showBtnToOverloadRestoreLanguage();
        $this->showHistory();
        echo "</div>";
    }


    function showHistory(){
        global $CFG_GLPI;
        $res = $this->getHistory();
        $content = "";

        if ($res->num_rows > 0) {

            $content .= "<table id='table2'  class='tab_cadre_fixe' >";
            $content .= "<th colspan='8'>" . __("History of overload", "renamer") . "</th>";

            $content .= "<tr class='headerRow'>";
            $content .= "<th>" . __("ID", "renamer") . "</th>";
            $content .= "<th>" . __("Date", "renamer") . "</th>";
            $content .= "<th>" . __("Language", "renamer") . "</th>";
            $content .= "<th>" . __("Original", "renamer") . "</th>";
            $content .= "<th>" . __("Overload", "renamer") . "</th>";
            $content .= "<th>" . __("User", "renamer") . "</th>";
            $content .= "<th>" . __("Delete", "renamer") . "</th>";
            $content .= "<th>" . __("Update", "renamer") . "</th>";
            $content .= "</tr>";

            $user = new User();

            while ($row = $res->fetch_assoc()) {

                $user->getFromDB($row["users_id"]);

                Ajax::createModalWindow('popupToUpdate' . $row['id'],
                    $CFG_GLPI['root_doc'].'/plugins/renamer/front/renamer.form.php?action=updateWord&id=' . $row['id'],
                    array('title'  => __("Update", "renamer"),
                        'width'  => 550,
                        'height' => 190));

                $content .= "<tr>";
                $content .= "<td class='center'>" . $row["id"] . "</td>";
                $content .= "<td class='center'>" . $row["date_overload"] . "</td>";
                $content .= "<td class='center'>" . $row["lang"] . "</td>";
                $content .= "<td class='center'>" . $row["original"] . "</td>";
                $content .= "<td class='center'>" . $row["overload"] . "</td>";
                $content .= "<td class='center'>" . $user->getName() . "</td>";
                $content .= "<td class='center'><img src='" . $CFG_GLPI['root_doc'] .
                    "/plugins/renamer/pics/bin16.png'  onclick='restoreWord(" . $row['id'] . ")';
                    style='cursor: pointer;' title='" . __("Delete overload", "renamer") . "'/></td>";
                $content .= "<td class = 'center'> <img src='".$CFG_GLPI['root_doc'].
                    "/plugins/renamer/pics/update16.png'  onclick='popupToUpdate" . $row['id'] .".show()';
                    style='cursor: pointer;' title='" . __("Update Overload", "renamer") ."'/></td>";
                $content .= "</tr>";

            }

        } else {
            $content = "";
            $content .= "<table id='table1'  class='tab_cadre_fixe' >";
            $content .= "<th colspan='6'>" . __("History of overload", "renamer") . "</th>";

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
    function showBtnToOverloadRestoreLanguage()
    {

        global $CFG_GLPI;

        $content = "<div id='popupOverloadLanguage' ></div>";

        Ajax::createModalWindow('popupOverloadLanguage',
            $CFG_GLPI["root_doc"] . '/plugins/renamer/front/renamer.form.php?action=overloadlanguage',
            array('title' => __("Overload Language", "renamer"),
                'width' => 550,
                'height' => 230));

        $content .= "<table id='table1'  class='tab_cadre_fixe' >";
        $content .= "<th colspan='2'>" . __("Overload language", "renamer") . "</th>";

        $content .= "<tr class='tab_bg_1'>";

        $content .= "<td style='text-align: center;'>";
        $content .= "<input  onclick='popupOverloadLanguage.show();'  value='" .
            __('Overload Language', 'renamer') . "' class='submit'    style='width : 200px;'></td>";

        $content .= "<td style='text-align: center;'>";
        $content .= "<input  onclick='restoreLocaleFiles();'  value='" .
            __('Restore Language', 'renamer') . "' class='submit'    style='width : 200px;'></td>";

        $content .= "</tr>";
        $content .= "</table>";

        echo $content;

    }

    public function showFormToUpdateOverloadLanguage($id){

        $this->getFromDB($id);

        global $CFG_GLPI;

        $content = "<form action='#' >";
        $content .= "<table class='tab_cadre'cellpadding='5'>";
        $content .= "<th colspan='2'>" . __('Update overload Language', 'renamer') . "</th>";

        $content .= "<tr class='tab_bg_1'>";
        $content .= "<th width='100'>" . __('Language', 'renamer') . "</th>";
        $content .= "<td >";
        $content .= "<input size=35 id='lang' type='text' name='lang' value='".$this->fields['lang']."' readonly = 'readonly' disabled/>";
        $content .= "</td>";
        $content .= "</tr>";

        $content .= "<tr class='tab_bg_1'>";
        $content .= "<th width='100'>" . __('Word', 'renamer') . "</th>";
        $content .= "<td >";
        $content .= "<input size=35 id='original' type='text' name='original' value='".$this->fields['original']."' readonly = 'readonly' disabled/>";
        $content .= "</td>";
        $content .= "</tr>";

        $content .= "<tr class='tab_bg_1'>";
        $content .= "<th width='100'>" . __('Substitut', 'renamer') . "</th>";
        $content .= "<td >";
        $content .= "<input size=35 id='overload' type='text' name='overload' value='".$this->fields['overload']."' readonly = 'readonly' disabled/>";
        $content .= "</td>";
        $content .= "</tr>";

        $content .= "<tr class='tab_bg_1'>";
        $content .= "<th width='100'>" . __('New substitut', 'renamer') . "</th>";
        $content .= "<td >";
        $content .= "<input size=35 id='newoverload".$id."' type='text' name='newoverload".$id."' />";
        $content .= "</td>";
        $content .= "</tr>";

        $content .= "<tr class='tab_bg_1'>";
        $content .= "<td >";
        $content .= "<input  id='overload' onclick='updateOverloadWord(".$id.");
         'name='overload' value='" . __("Update Overload", "renamer") . "' class='submit'></td>";
        $content .= "</td>";
        $content .= "<td >";
        $content .= "<div id='infoUpdateOverloadWord' ></div>";
        $content .= "<img id='waitUpdateOverload'
         src='" . $CFG_GLPI['root_doc'] . "/plugins/renamer/pics/please_wait.gif' style='display:none;'/>";
        $content .= "</td>";
        $content .= "</tr>";

        $content .= "<input type='hidden' name='users_id'      id='users_id'      value=" . Session::getLoginUserID() . " >";
        $content .= "<input type='hidden' name='date_overload' id='date_overload' value=" . date("Y-m-d") . " >";

        $content .= "</table>";

        $content .= Html::closeForm(false);

        echo $content;

    }


    function showFormToOverloadLanguage(){

        $this->searchWord("config","Français");

        global $CFG_GLPI;

        $content = "<form action='#' >";
        $content .= "<table class='tab_cadre'cellpadding='5'>";
        $content .= "<th colspan='6'>" . __('Overload Language', 'renamer') . "</th>";

        $content .= "<tr class='tab_bg_1'>";
        $content .= "<th width='100'>" . __('Language', 'renamer') . "</th>";
        $content .= "<td >";
        $content .= Dropdown::showLanguages("language", array('display' => false, 'value' => $_SESSION['glpilanguage'], 'rand' => ''));
        $content .= "</td>";
        $content .= "<td width='14'>";
        $content .= "</td>";
        $content .= "</tr>";

        $content .= "<tr class='tab_bg_1'>";
        $content .= "<th width='100'>" . __('Word', 'renamer') . "</th>";
        $content .= "<td >";
        $content .= "<input size=35 id='original' type='text' name='original' onkeypress=\"if(event.keyCode==13)searchOriginalWord();\" />";
        $content .= "<img id='searchImg' alt='rechercher' src='" . $CFG_GLPI['root_doc'] . "/pics/aide.png'
        onclick='searchOriginalWord();'
        style='cursor: pointer;padding-left:5px; padding-top:5px;'/></td>";
        $content .= "</td>";
        $content .= "<td width='14'>";
        $content .= "<div id='infoSearchWord' ></div>";
        $content .= "</td>";
        $content .= "</tr>";

        $content .= "<tr class='tab_bg_1'>";
        $content .= "<th width='100'>" . __('Substitut', 'renamer') . "</th>";
        $content .= "<td >";
        $content .= "<input size=35 id='overload' type='text' name='overload' onkeypress=\"if(event.keyCode==13)searchOriginalWord();\" />";
        $content .= "</td>";
        $content .= "<td width='14'>";
        $content .= "</td>";
        $content .= "</tr>";

        $content .= "<tr class='tab_bg_1'>";
        $content .= "<td >";
        $content .= "<input  id='overload' onclick='overloadWord();
         'name='overload' value='" . __("Overload", "renamer") . "' class='submit'></td>";
        $content .= "</td>";
        $content .= "<td >";
        $content .= "<div id='infoOverloadWord' ></div>";
        $content .= "<img id='wait'
         src='" . $CFG_GLPI['root_doc'] . "/plugins/renamer/pics/please_wait.gif' style='display:none;'/>";
        $content .= "</td>";
        $content .= "<td >";
        $content .= "</td>";
        $content .= "</tr>";

        $content .= "<input type='hidden' name='users_id'      id='users_id'      value=" . Session::getLoginUserID() . " >";
        $content .= "<input type='hidden' name='date_overload' id='date_overload' value=" . date("Y-m-d") . " >";

        $content .= "</table>";

        $content .= Html::closeForm(false);

        echo $content;
    }




    /**
     * Function to retrieve language file with name of language
     * @param $lang
     * @return mixed|string
     */
    public function getLanguageFile($lang)
    {
        global $CFG_GLPI;
        $file = "";

        foreach ($CFG_GLPI["languages"] as &$local) {
            if ($lang == $local[0]) $file = $local[1];
        }
        $file = str_replace('mo', 'po', $file);

        return $file;
    }


    /**
     * Function to find all links record for an item
     * @param $item
     * @return Query
     */
    public function getHistory()
    {
        global $DB;
        return $DB->query("SELECT `glpi_plugin_renamer_renamers`.*
                        FROM `glpi_plugin_renamer_renamers`");
    }


    /**
     * Function tu overload a word
     * @param $_post
     * @return bool|translated
     */
    public function overloadWord($_post)
    {
        global $CFG_GLPI;

        $lang = $_post['lang'];
        $original = $_post['original'];
        $overload = $_post['overload'];

        $file = $this->getLanguageFile($lang);
        $file_patch = $_SERVER['DOCUMENT_ROOT'] . $CFG_GLPI["root_doc"] . "/locales/" . $file;

        $line_original = $this->getLineForOriginal($original , $file_patch, $overload);
        $line_overload = $this->getLineForOverload($original , $file_patch, $overload);


        $text = fopen($file_patch, 'r');
        if ($text != false) {
            $contenu = file_get_contents($file_patch);
            $contenuMod = str_replace($line_original, $line_overload, $contenu);
            fclose($text);
        } else {
            return  sprintf(__('Error when access to the file \'%1$s\' Please give write permission to the \'locales\' folder of Glpi', "renamer"),$file);
        }


        $text2 = fopen($file_patch, 'w+');
        if ($text2 != false) {
            fwrite($text2, $contenuMod);
            fclose($text2);
        } else {
            return  sprintf(__('Error when access to the file \'%1$s\' Please give write permission to the \'locales\' folder of Glpi', "renamer"),$file);
        }

        if ($this->add($_post) == false) {
            return __("Error when adding history in the database", "renamer");
        }

        $this->updateTranslation($file_patch);

        Session::addMessageAfterRedirect(sprintf( __('\'%1$s\' replaced by \'%2$s\'', "renamer"),$this->fields['original'],$this->fields['overload'] ), false, INFO);
        return true;

    }


    /**
     * Function to restore a word overload
     * @param $id
     * @return bool
     */
    public function restoreWord($id)
    {

        $this->getFromDB($id);
        global $CFG_GLPI;

        $lang = $this->fields['lang'];
        $original = $this->fields['original'];
        $overload = $this->fields['overload'];

        $file = $this->getLanguageFile($lang);
        $file_patch = $_SERVER['DOCUMENT_ROOT'] . $CFG_GLPI["root_doc"] . "/locales/" . $file;

        $line_original = $this->getLineForOriginal($overload , $file_patch, $original);
        $line_overload = $this->getLineForOverload($overload , $file_patch, $original);

        $text = fopen($file_patch, 'r') or die("Fichier manquant");
        if ($text != false) {
            $contenu = file_get_contents($file_patch);
            $contenuMod = str_replace($line_original, $line_overload, $contenu);
            fclose($text);
        } else {
            Session::addMessageAfterRedirect(sprintf(__('Error when access to the file \'%1$s\'', "renamer"),$file_patch), false, ERROR);
            return false;
        }

        $text2 = fopen($file_patch, 'w+');
        if ($text2 != false) {
            fwrite($text2, $contenuMod);
            fclose($text2);
        } else {
            Session::addMessageAfterRedirect(sprintf(__('Error when access to the file \'%1$s\'', "renamer"),$file_patch), false, ERROR);
            return false;
        }

        if ($this->delete($this->fields) == false) {
            Session::addMessageAfterRedirect(__("Error when deleting history in the database", "renamer"), false, ERROR);
            return false;
        }

        $this->updateTranslation($file_patch);
        
        Session::addMessageAfterRedirect(sprintf( __('\'%1$s\' replaced by \'%2$s\'', "renamer"),$this->fields['overload'],$this->fields['original']), false, INFO);
        return true;

    }

    /**
     * Function to check if the word to overload is an overload of another word
     * @param $_post
     * @return true
     */
    public function isAlreadyOverload($_post)
    {
        $original = $_post['original'];
        return $this->getFromDBByQuery($this->getTable() . " WHERE `" . "`.`overload` = '" . $original . "'");

    }


    /**
     * function to update overload of a word
     * @param $id
     * @param $new_word
     * @return bool|translated
     */
    public function updateOverloadWord($id, $new_word)
    {

        $this->getFromDB($id);

        global $CFG_GLPI;

        $lang = $this->fields['lang'];
        $overload = $this->fields['overload'];

        $file = $this->getLanguageFile($lang);
        $file_patch = $_SERVER['DOCUMENT_ROOT'] . $CFG_GLPI["root_doc"] . "/locales/" . $file;

        $line_original = $this->getLineForOriginal($overload , $file_patch, $new_word);
        $line_overload = $this->getLineForOverload($overload , $file_patch, $new_word);

        $text = fopen($file_patch, 'r');
        if ($text != false) {
            $contenu = file_get_contents($file_patch);
            $contenuMod = str_replace($line_original, $line_overload, $contenu);
            fclose($text);
        } else {
           return sprintf(__('Error when access to the file \'%1$s\' Please give write permission to the \'locales\' folder of Glpi', "renamer"),$file);
        }

        $text2 = fopen($file_patch, 'w+');
        if ($text2 != false) {
            fwrite($text2, $contenuMod);
            fclose($text2);
        } else {
            return  sprintf(__('Error when access to the file \'%1$s\' Please give write permission to the \'locales\' folder of Glpi', "renamer"),$file);
        }

        $old_world = $this->fields['overload'];
        $this->fields['overload'] = $new_word;
        if ($this->update($this->fields) == false) {

            return __("Error when updating history in the database", "renamer");
        }

        $this->updateTranslation($file_patch);
        Session::addMessageAfterRedirect(sprintf( __('\'%1$s\' updated by \'%2$s\'', "renamer"),$old_world,$new_word), false, INFO);
        return true;

    }

    /**
     * Function to create String for original world
     * @param $original
     * @param $file_patch
     * @param $overload
     * @return string
     */
    private function getLineForOriginal($original, $file_patch, $overload)
    {

      $fh = fopen($file_patch,"r");

        while (!feof($fh)){

            $lineContent = fgets($fh);
            $pos  = stripos($lineContent,'msgstr "' . $original . '"');
            $pos1 = stripos($lineContent,'msgstr[0] "' . $original . '"');
            $pos2 = stripos($lineContent,'msgstr[1] "' . $original . '"');

            if($pos !== false){
                fclose($fh);
                return 'msgstr "' . $original . '"';
            }else if($pos1 !== false){
                fclose($fh);
                return 'msgstr[0] "' . $original . '"';
            }else if($pos2 !== false){
                fclose($fh);
                return 'msgstr[1] "' . $original . '"';
            }

        }
        fclose($fh);
        return "";
    }


    /**
     * Function to create String for overload world
     * @param $original
     * @param $file_patch
     * @param $overload
     * @return string
     */
    private function getLineForOverload($original, $file_patch, $overload)
    {

        $fh = fopen($file_patch,"r");

        while (!feof($fh)){

            $lineContent = fgets($fh);
            $pos  = stripos($lineContent,'msgstr "' . $original . '"');
            $pos1 = stripos($lineContent,'msgstr[0] "' . $original . '"');
            $pos2 = stripos($lineContent,'msgstr[1] "' . $original . '"');


            if($pos !== false){
                fclose($fh);
                return 'msgstr "' . $overload . '"';
            }else if($pos1 !== false){
                fclose($fh);
                return 'msgstr[0] "' . $overload . '"';
            }else if($pos2 !== false){
                fclose($fh);
                return 'msgstr[1] "' . $overload . '"';
            }

        }
        fclose($fh);
        return "";
    }


    /**
     * Function to search the word to replace in locale files determineted by $lang
     * @param $word
     * @param $lang
     * @return bool
     */
    public function searchWord($word , $lang)
    {

        global $CFG_GLPI;

        $file = $this->getLanguageFile($lang);
        $file_patch = $_SERVER['DOCUMENT_ROOT'] . $CFG_GLPI["root_doc"] . "/locales/" . $file;

        if($fh = fopen($file_patch,"r")){

            while (!feof($fh)){

                $lineContent = fgets($fh);
                $pos  = stripos($lineContent,'msgstr "' . $word . '"');
                $pos1 = stripos($lineContent,'msgstr[0] "' . $word . '"');
                $pos2 = stripos($lineContent,'msgstr[1] "' . $word . '"');

                if ($pos !== false || $pos1 !== false || $pos2 !== false) {
                    return true;
                }

            }
            fclose($fh);
        }else{
            return false;
        }

        return false;

    }


    public function updateTranslation($file_patch){
        // Convert XXX.po to XXX.mo
        global $CFG_GLPI;
        require($_SERVER['DOCUMENT_ROOT'].$CFG_GLPI["root_doc"].'/plugins/renamer/lib/php-mo.php');
        @phpmo_convert($file_patch,substr($file_patch,0,-3).".mo");
    }

}