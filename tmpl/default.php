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
use Joomla\CMS\Language\Text;

$app		= Factory::getApplication();
$doc       	= $app->getDocument();

$direction = $doc->direction === 'rtl' ? 'float-right' : '';
$class     = $enabled ? 'nav flex-column main-nav ' . $direction : 'nav flex-column main-nav disabled ' . $direction;

// Recurse through children of root node if they exist
if ($root->hasChildren()) {

	echo '<nav class="navbar navbar-expand-md navbar-inverse ph-topmenu-navbar ph-topmenu-'.$theme_style.'">';
	echo '<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#phtopmenu" aria-controls="topmenu" aria-expanded="false" aria-label="'.Text::_('MOD_PHOCATOPMENU_TOGGLE_NAVIGATION').'">';
	echo '<span class="fas fa-bars"></span>';
	echo '</button>';
	echo '<div class="collapse navbar-collapse" id="phtopmenu">';
	echo '<ul class="nav navbar-nav mr-auto">';
	$menu->renderSubmenu(ModuleHelper::getLayoutPath('mod_phocatopmenu', 'default_submenu'), $root);// WARNING: Do not use direct 'include' or 'require' as it is important to isolate the scope for each call
	echo '</ul>';
	echo '</div>';
	echo '</nav>'."\n";
}
?>
