<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  mod_menu
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\Module\Phocatopmenu\Administrator\Phocatopmenu\CssPhocatopmenu;

$app		= Factory::getApplication();
$doc       	= $app->getDocument();
$lang 		= Factory::getLanguage();
$lang->load('mod_menu.sys');
$lang->load('mod_menu');

$open_menu = $params->get('open_menu', 1);

$doc->addStyleSheet(JURI::root(true) . '/media/mod_phocatopmenu/css/main.css');
if ($open_menu == 2) {
	$doc->addStyleSheet(JURI::root(true) . '/media/mod_phocatopmenu/css/main-hover.css');
}
$doc->addScript(JURI::root(true) . '/media/mod_phocatopmenu/js/main.js');

 
$enabled 		= !$app->input->getBool('hidemainmenu');
$menu			= new CssPhocatopmenu($app);
$root 			= $menu->load($params, $enabled);
$root->level 	= 0;

require ModuleHelper::getLayoutPath('mod_phocatopmenu', $params->get('layout', 'default'));
