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
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;
use Joomla\Module\Phocatopmenu\Administrator\Phocatopmenu\CssPhocatopmenu;

$app		= Factory::getApplication();
$doc       	= $app->getDocument();
$lang 		= Factory::getLanguage();
$lang->load('mod_menu.sys');
$lang->load('mod_menu');

$open_menu 	        = $params->get('open_menu', 2);
$theme_style 		= $params->get('theme_style', 'black');
$custom_css 		= $params->get('custom_css', '');
$sticky_navbar 		= $params->get('sticky_navbar', 0);

HTMLHelper::_('behavior.core');
//HTMLHelper::_('jquery.framework');
$doc->addStyleSheet(Uri::root(true) . '/media/mod_phocatopmenu/css/main.css');
if ($open_menu == 2) {
	$doc->addStyleSheet(Uri::root(true) . '/media/mod_phocatopmenu/css/main-hover.css');
}
$doc->addStyleSheet(Uri::root(true) . '/media/mod_phocatopmenu/css/theme.css');
//$doc->addScript(Uri::root(true) . '/media/mod_phocatopmenu/js/main.js');

$cssOutput = '';
if ($sticky_navbar == 1) {

    $cssOutput .= '
    .ph-topmenu-navbar {
       position: sticky;
       top: 0;
       height: 3rem;
    }
    
    .subhead {
       top: 3rem;
    }
    ';
}


$enabled 		= !$app->input->getBool('hidemainmenu');
$menu			= new CssPhocatopmenu($app);
$root 			= $menu->load($params, $enabled);
$root->level 	= 0;

if ($custom_css != '') {
    $cssOutput .= htmlspecialchars(strip_tags($custom_css));
}

if ($cssOutput != '') {
	$doc->addCustomTag( "<style type=\"text/css\"> \n"
	. $cssOutput
	. "</style>\n");
}

require ModuleHelper::getLayoutPath('mod_phocatopmenu', $params->get('layout', 'default'));
