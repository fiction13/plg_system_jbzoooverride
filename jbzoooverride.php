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

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Filesystem\File;

class plgSystemJBZooOverride extends CMSPlugin
{

	/**
	 * @var
	 * @since 1.0.0
	 */
	protected $app;

	/**
	 * @var
	 * @since 1.0.0
	 */
	protected $zoo;

	/**
	 * onAfterInitialise handler
	 *
	 * Adds ZOO event listeners
	 *
	 * @access public
	 * @return null
	 */
	public function onAfterInitialise()
	{

		// make sure ZOO exists
		if (!ComponentHelper::getComponent('com_zoo', true)->enabled)
		{
			return;
		}

		// load ZOO config
		if (!File::exists(JPATH_ADMINISTRATOR . '/components/com_zoo/config.php') || !ComponentHelper::getComponent('com_zoo', true)->enabled)
		{
			return;
		}

		require_once(JPATH_ADMINISTRATOR . '/components/com_zoo/config.php');

		// make sure App class exists
		if (!class_exists('App'))
		{
			return;
		}

		// Get the ZOO App instance
		$this->zoo = App::getInstance('zoo');

		$this->zoo->event->dispatcher->connect('application:init', array($this, 'initOverride'));
	}

	/**
	 * Init all
	 * @return void
	 */

	public function initOverride()
	{
		$this->_initTables();
		$this->_initPaths();
	}

	/**
	 * Add directory path
	 */
	private function _initPaths()
	{
		$this->_addPath('jbapp:framework-override', 'jbzoo');
		$this->_addPath('jbzoo:assets', 'jbassets');
		$this->_addPath('jbassets:zoo', 'assets');
		$this->_addPath('jbzoo:config', 'jbconfig');
		$this->_addPath('jbzoo:elements', 'jbelements');
		$this->_addPath('jbzoo:cart-elements', 'cart-elements');
		$this->_addPath('jbzoo:types', 'jbtypes');

		$this->_addPath('jbzoo:helpers', 'helpers');
		$this->_addPath('jbzoo:helpers-std', 'helpers');
		$this->_addPath('helpers:fields', 'fields');

		$this->_addPath('jbzoo:tables', 'tables');
		$this->_addPath('jbzoo:classes-std', 'classes');
		$this->_addPath('jbzoo:render', 'renderer');
		$this->_addPath('jbzoo:views', 'jbviews');
		$this->_addPath('jbzoo:config', 'jbxml');
		$this->_addPath('jbviews:', 'partials');
		$this->_addPath('jbzoo:joomla/elements', 'fields');
		$this->_addPath('jbzoo:templates', 'jbtmpl');

		$this->_addPath('modules:mod_jbzoo_search', 'mod_jbzoo_search');
		$this->_addPath('modules:mod_jbzoo_props', 'mod_jbzoo_props');
		$this->_addPath('modules:mod_jbzoo_basket', 'mod_jbzoo_basket');
		$this->_addPath('modules:mod_jbzoo_category', 'mod_jbzoo_category');
		$this->_addPath('modules:mod_jbzoo_item', 'mod_jbzoo_item');
		$this->_addPath('modules:mod_jbzoo_currency', 'mod_jbzoo_currency');

		$this->_addPath('plugins:/system/jbzoo', 'plugin_jbzoo');

		// Add new elements folder

		$this->_addPath('jbzoo:zoo-elements', 'elements');

		// Add controllers

		if ($this->app->isClient('site'))
		{
			$this->_addPath('jbzoo:controllers', 'controllers');
		}

		// Paths

		$frameworkPath = JPATH_SITE . '/media/zoo/applications/jbuniversal/framework-override';
		$clsPath       = $frameworkPath . '/classes';
		$modPath       = $frameworkPath . '/models';
		$filPath       = $frameworkPath . '/render';
		$evtPath       = $frameworkPath . '/events';

		// Add class list

		$classList = [
			// Models example

			// 'JBModelCustom'          => $modPath . '/jbmodel.custom.php',

			// Filter example

			// 'JBFilterElementCustom'  => $filPath . '/element.custom.php',

			// Classes example

			// 'JBClass'                => $clsPath . '/jbcustom.php',
		];

		foreach ($classList as $className => $path)
		{
			JLoader::register($className, $path, true);
		}
	}

	/**
	 * Add directory path
	 */
	private function _initTables()
	{
		// Define your tables
		// define('ZOO_TABLE_CUSTOM', '#__zoo_custom');
	}

	/**
	 * Register new path in system
	 *
	 * @param   string  $path
	 * @param   string  $pathName
	 *
	 * @return mixed
	 */
	private function _addPath($path, $pathName)
	{
		if ($fullPath = $this->zoo->path->path($path))
		{
			return $this->zoo->path->register($fullPath, $pathName);
		}

		return null;
	}

}
