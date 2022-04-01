<?php
/*
 * @package   plg_system_jbzoooverride
 * @version   1.0.0
 * @author    Dmitriy Vasyukov - https://fictionlabs.ru
 * @copyright Copyright (c) 2022 Fictionlabs. All rights reserved.
 * @license   GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 * @link      https://fictionlabs.ru/
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Filesystem\Folder;

class plgSystemJBZooOverrideInstallerScript
{
	// Post Install Function

	function postflight($type, $parent)
	{
		// Create overrider folder

		Folder::create(JPATH_ROOT . '/media/zoo/applications/jbuniversal/framework-override');
	}
}
