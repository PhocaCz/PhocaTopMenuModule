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
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

$app		= Factory::getApplication();
$doc       	= $app->getDocument();

/**
 * =========================================================================================================
 * IMPORTANT: The scope of this layout file is the `var  \Joomla\Module\Menu\Administrator\Menu\CssMenu` object
 * and NOT the module context.
 * =========================================================================================================
 */
/** @var  \Joomla\Module\Menu\Administrator\Menu\CssMenu  $this */
$class         = 'item '.$doc->direction;
$currentParams = $current->getParams();

// Build the CSS class suffix
if (!$this->enabled)
{
	$class .= ' disabled';
}
elseif ($current->type == 'separator')
{
	$class = $current->title ? ' menuitem-group' : ' main-nav divider';
}
elseif ($current->hasChildren())
{
	//$class .= ' class="dropdown-submenu"';
	$class .= ' dropdown parent';

	if ($current->level == 1)
	{
		$class .= ' dropdown parent text-nowrap';
	}
	else if ($current->level > 1)
	{
		if($doc->direction === 'rtl') {
			$class.= ' dropdown parent text-nowrap dropstart"';
		} else {
			$class.= ' dropdown parent text-nowrap dropend"';
		}
	}
	elseif ($current->class === 'scrollable-menu')
	{
		$class .= ' dropdown scrollable-menu';
	}
}

// Set the correct aria role and print the item
if ($current->type == 'separator')
{
	echo '<li class="' . $class . ' ph-topmenu-sep" role="presentation">';
}
else
{
	echo '<li class="' . $class . '">';
}

// Print a link if it exists
$linkClass  = [];
$dataToggle = '';
$iconClass  = '';
$itemIconClass = '';
$itemImage  = '';

if ($current->hasChildren())
{
	//$linkClass[] = 'nav-link dropdown has-arrow';
	//$linkClass[] = 'nav-link-sub dropdown-toggle has-arrow';

	if ($current->level > 1)
	{
		$linkClass[] = 'nav-link sub dropdown-toggle has-arrow';
		if($doc->direction === 'rtl') {
			//$linkClass[] = 'dropright';
		} else {
			//$linkClass[] = 'dropright';
		}
	} else {
		$linkClass[] = 'nav-link dropdown-toggle has-arrow';
	}

	$dataToggle  = ' data-bs-toggle="dropdown"';
}
else
{
	//$linkClass[] = 'nav-link dropdown';//'no-dropdown';

	if ($current->level > 1)
	{
		$linkClass[] = 'nav-link sub';
	} else {
			$linkClass[] = 'nav-link';
	}
}

// Implode out $linkClass for rendering
$linkClass = ' class="' . implode(' ', $linkClass) . '" ';

// Get the menu link
$link = $current->link;

// Get the menu image class
$itemIconClass = $currentParams->get('menu_icon');

// Get the menu image
$itemImage = $currentParams->get('menu_image');

// Get the menu icon
$icon      = $this->getIconClass($current);
$iconClass = ($icon != '' && $current->level == 1) ? '<span class="' . $icon . '" aria-hidden="true"></span>' : '';
$ajax      = $current->ajaxbadge ? '<span class="menu-badge"><span class="fas fa-spin fa-spinner mt-1 system-counter" data-url="' . $current->ajaxbadge . '"></span></span>' : '';
$iconImage = $current->icon;
$homeImage = '';

if ($iconClass === '' && $itemIconClass)
{
	$iconClass = '<span class="' . $itemIconClass . '" aria-hidden="true"></span>';
}

if ($iconImage)
{
	if (substr($iconImage, 0, 6) == 'class:' && substr($iconImage, 6) == 'icon-home')
	{
		$iconImage = '<span class="home-image icon-featured fas fa-star" aria-hidden="true"></span>';
		$iconImage .= '<span class="sr-only">' . Text::_('JDEFAULT') . '</span>';
	}
	elseif (substr($iconImage, 0, 6) == 'image:')
	{
		$iconImage = '&nbsp;<span class="badge badge-secondary">' . substr($iconImage, 6) . '</span>';
	}
	else
	{
		$iconImage = '';
	}
}

$itemImage = (empty($itemIconClass) && $itemImage) ? '&nbsp;<img src="' . Uri::root() . $itemImage . '" alt="">&nbsp;' : '';

if ($link != '' && $current->target != '')
{
    $dataToggleCurrent = $dataToggle;
    if ($current->target == "_blank") {
        $dataToggleCurrent = '';
    }
	echo "<a" . $linkClass . $dataToggleCurrent . " href=\"" . $link . "\"   target=\"" . $current->target . "\">"
		. $iconClass
		. '<span class="sidebar-item-title">' . $itemImage . Text::_($current->title) . '</span>' . $ajax . '</a>';
}
elseif ($link != '' && $current->type !== 'separator')
{
    // If it has children, the link must be deactivated because of toggle
    // there cannot be two functions on click:  1) open the submenu 2) go to the link
    if ($current->hasChildren()){
        $link = '#';
    }

    // BS toggle errors - skip toggle for links
     $dataToggleCurrent = $dataToggle;
    if ($link != '#') {
        $dataToggleCurrent = '';
    }

	echo "<a" . $linkClass . $dataToggleCurrent . "  href=\"" . $link . "\">"
		. $iconClass
		. '<span class="sidebar-item-title">' . $itemImage . Text::_($current->title) . '</span>' . $iconImage . '</a>';
}
elseif ($current->title != '' && $current->type !== 'separator')
{
	echo "<a" . $linkClass . $dataToggle . ">"
		. $iconClass
		. '<span class="sidebar-item-title">'. $itemImage . Text::_($current->title) . '</span>' . $ajax . '</a>';
}
elseif ($current->title != '' && $current->type === 'separator')
{
	echo '<span class="sidebar-item-title">' . Text::_($current->title) . '</span>' . $ajax;
}
else
{
	echo '<span>' . Text::_($current->title) . '</span>' . $ajax;
}

// todo if ($currentParams->get('menu-quicktask', false))
if ($currentParams->get('menu-quicktask') && (int) $this->params->get('shownew', 1) === 1)
{
	$params = $current->getParams();
	$user = $this->application->getIdentity();
	// todo $link = $params->get('menu-quicktask-link');
	$link = $params->get('menu-quicktask');
	$icon = $params->get('menu-quicktask-icon', 'plus');
	$title = $params->get('menu-quicktask-title', 'MOD_MENU_QUICKTASK_NEW');
	$permission = $params->get('menu-quicktask-permission');
	$scope = $current->scope !== 'default' ? $current->scope : null;

	if (!$permission || $user->authorise($permission, $scope))
	{

		// todo
		/*echo '</li><li class="pl-5">';

		echo '<a href="' . $link . '">' . htmlentities(Text::_($title)) . '</a>';
		echo ' <span class="fas fa-' . $icon . '" title="' . htmlentities(Text::_($title)) . '" aria-hidden="true"></span>';
echo '<span class="menu-quicktask"><a href="' . $link . '">';

		*/
		echo '<span class="menu-quicktask"><a href="' . $link . '">';
		echo '<span class="fas fa-' . $icon . ' '.$doc->direction.'" title="' . htmlentities(Text::_($title)) . '" aria-hidden="true"></span>';
		echo '<span class="sr-only">' . Text::_($title) . '</span>';
		echo '</a></span>';
	}
}

// for topmenu it is better to put the dasboard link as the first item in the list
// so save it here
$dashboard = '';
if ($current->dashboard)
{
	$titleDashboard = Text::sprintf('MOD_MENU_DASHBOARD_LINK', Text::_($current->title));

	//$titleDashboard = Text::_($current->title);
	$dashboard = '<li>'
		.'<a class=nav-link dropdown" href="' . "\n"
		. Route::_('index.php?option=com_cpanel&view=cpanel&dashboard=' . $current->dashboard) . '">'
		. '<span class="fas fa-th-large" title="' . $titleDashboard . '" aria-hidden="true"></span>'
		. '<span class="sr-only">' . $titleDashboard . '</span>'
		. '</a>' . "\n"
		. '</li>' . "\n";
}

// Recurse through children if they exist
if ($this->enabled && $current->hasChildren())
{
	if ($current->level > 1)
	{
		$id = $current->id ? ' id="menu-' . strtolower($current->id) . '"' : '';

		echo '<ul' . $id . ' class="dropdown-menu">';//mm-collapse collapse-level-' . $current->level . '">' . "\n";
	}
	else
	{
		echo '<ul id="collapse' . $this->getCounter() . '" class="dropdown-menu">'; //collapse-level-1 mm-collapse">' . "\n";
	}

	if (!empty($dashboard)) {
		echo $dashboard;
	}
	// WARNING: Do not use direct 'include' or 'require' as it is important to isolate the scope for each call
	$this->renderSubmenu(__FILE__, $current);

	echo "</ul>\n";
}

echo "</li>\n";
