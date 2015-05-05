<?php

class PluginRenamerMenu extends CommonGLPI {

   static function getTypeName($nb = 0) {
      return __('getTypeName', 'renamer'); //__('Link ItilCategory - Groups', 'itilcategorygroups');
   }
   
   static function getMenuName() {
      return __('Renamer', 'renamer');
   }

   static function getMenuContent() {
      global $CFG_GLPI;
      $menu          = array();
      $menu['title'] = self::getMenuName();
      $menu['page']  = '/plugins/renamer/front/renamer.form.php';
      
      if (Session::haveRight('config', READ)) {
         
         $menu['options']['model']['title'] = self::getTypeName();
         $menu['options']['model']['page'] = Toolbox::getItemTypeSearchUrl('PluginRenamerRenamer', false);
         $menu['options']['model']['links']['search'] = Toolbox::getItemTypeSearchUrl('PluginRenamerRenamer', false);

         if (Session::haveRight('config', UPDATE)) {
            $menu['options']['model']['links']['add'] = Toolbox::getItemTypeFormUrl('PluginRenamerRenamer', false);
         }
      
      }

      return $menu;
   }

}