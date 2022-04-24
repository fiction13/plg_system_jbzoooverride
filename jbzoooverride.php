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
            'JBDatabaseQuery'                    => $clsPath . '/database/JBDatabaseQuery.php',
            'JBDatabaseQueryElement'             => $clsPath . '/database/JBDatabaseQueryElement.php',
            'JBCartOrder'                        => $clsPath . '/cart/jborder.php',
            'JBCart'                             => $clsPath . '/cart/jbcart.php',
            'JBCartValue'                        => $clsPath . '/cart/jbvalue.php',
            'JBCartVariant'                      => $clsPath . '/cart/jbvariant.php',
            'JBCartVariantList'                  => $clsPath . '/cart/jbvariantlist.php',
            'JBTemplate'                         => $clsPath . '/jbtemplate.php',
            'JBUpload'                           => $clsPath . '/jbupload.php',

            // models
            'JBModel'                            => $modPath . '/jbmodel.php',
            'JBModelConfig'                      => $modPath . '/jbmodel.config.php',
            'JBModelElement'                     => $modPath . '/jbmodel.element.php',
            'JBModelAutoComplete'                => $modPath . '/jbmodel.autocomplete.php',
            'JBModelElementCountry'              => $modPath . '/jbmodel.element.country.php',
            'JBModelElementDate'                 => $modPath . '/jbmodel.element.date.php',
            'JBModelElementItemDate'             => $modPath . '/jbmodel.element.itemdate.php',
            'JBModelElementItemauthor'           => $modPath . '/jbmodel.element.itemauthor.php',
            'JBModelElementItemCategory'         => $modPath . '/jbmodel.element.itemcategory.php',
            'JBModelElementItemCreated'          => $modPath . '/jbmodel.element.itemcreated.php',
            'JBModelElementItemFrontpage'        => $modPath . '/jbmodel.element.itemfrontpage.php',
            'JBModelElementItemModified'         => $modPath . '/jbmodel.element.itemmodified.php',
            'JBModelElementItemName'             => $modPath . '/jbmodel.element.itemname.php',
            'JBModelElementItemPublish_down'     => $modPath . '/jbmodel.element.itempublish_down.php',
            'JBModelElementItemPublish_up'       => $modPath . '/jbmodel.element.itempublish_up.php',
            'JBModelElementItemTag'              => $modPath . '/jbmodel.element.itemtag.php',
            'JBModelElementJBImage'              => $modPath . '/jbmodel.element.jbimage.php',
            'JBModelElementJBSelectCascade'      => $modPath . '/jbmodel.element.jbselectcascade.php',
            'JBModelElementRange'                => $modPath . '/jbmodel.element.range.php',
            'JBModelElementRating'               => $modPath . '/jbmodel.element.rating.php',
            'JBModelElementJBPrice'              => $modPath . '/jbmodel.element.jbprice.php',
            'JBModelElementJBPricePlain'         => $modPath . '/jbmodel.element.jbprice.plain.php',
            'JBModelElementJBPriceCalc'          => $modPath . '/jbmodel.element.jbprice.calc.php',
            'JBModelElementJBComments'           => $modPath . '/jbmodel.element.jbcomments.php',
            'JBModelElementTextarea'             => $modPath . '/jbmodel.element.textarea.php',
            'JBModelFavorite'                    => $modPath . '/jbmodel.favorite.php',
            'JBModelFilter'                      => $modPath . '/jbmodel.filter.php',
            'JBModelItem'                        => $modPath . '/jbmodel.item.php',
            'JBModelApp'                         => $modPath . '/jbmodel.app.php',
            'JBModelCategory'                    => $modPath . '/jbmodel.category.php',
            'JBModelOrder'                       => $modPath . '/jbmodel.order.php',
            'JBModelRelated'                     => $modPath . '/jbmodel.related.php',
            'JBModelSearchindex'                 => $modPath . '/jbmodel.searchindex.php',
            'JBModelValues'                      => $modPath . '/jbmodel.values.php',
            'JBModelSku'                         => $modPath . '/jbmodel.sku.php',

            // filter
            'JBFilterElement'                    => $filPath . '/element.php',
            'JBFilterElementAuthor'              => $filPath . '/element.author.php',
            'JBFilterElementAuthorCheckbox'      => $filPath . '/element.author.checkbox.php',
            'JBFilterElementAuthorRadio'         => $filPath . '/element.author.radio.php',
            'JBFilterElementAuthorSelect'        => $filPath . '/element.author.select.php',
            'JBFilterElementAuthorChosen'        => $filPath . '/element.author.select.chosen.php',
            'JBFilterElementAuthorText'          => $filPath . '/element.author.text.php',
            'JBFilterElementCategory'            => $filPath . '/element.category.php',
            'JBFilterElementCategoryCheckbox'    => $filPath . '/element.category.checkbox.php',
            'JBFilterElementCategoryChosen'      => $filPath . '/element.category.chosen.php',
            'JBFilterElementCategoryHidden'      => $filPath . '/element.category.hidden.php',
            'JBFilterElementCheckbox'            => $filPath . '/element.checkbox.php',
            'JBFilterElementCountry'             => $filPath . '/element.country.php',
            'JBFilterElementCountryCheckbox'     => $filPath . '/element.country.checkbox.php',
            'JBFilterElementCountryRadio'        => $filPath . '/element.country.radio.php',
            'JBFilterElementCountrySelect'       => $filPath . '/element.country.select.php',
            'JBFilterElementCountryChosen'       => $filPath . '/element.country.select.chosen.php',
            'JBFilterElementDate'                => $filPath . '/element.date.php',
            'JBFilterElementDateRange'           => $filPath . '/element.date.range.php',
            'JBFilterElementFrontpage'           => $filPath . '/element.frontpage.php',
            'JBFilterElementFrontpageJqueryUI'   => $filPath . '/element.frontpage.jqueryui.php',
            'JBFilterElementHidden'              => $filPath . '/element.hidden.php',
            'JBFilterElementImageexists'         => $filPath . '/element.imageexists.php',
            'JBFilterElementImageexistsJqueryui' => $filPath . '/element.imageexists.jqueryui.php',
            'JBFilterElementJBColor'             => $filPath . '/element.jbcolor.php',
            'JBFilterElementJBPriceCalc'         => $filPath . '/element.jbpricecalc.php',
            'JBFilterElementJBPricePlain'        => $filPath . '/element.jbpriceplain.php',
            'JBFilterElementJbselectcascade'     => $filPath . '/element.jbselectcascade.php',
            'JBFilterElementJqueryui'            => $filPath . '/element.jqueryui.php',
            'JBFilterElementName'                => $filPath . '/element.name.php',
            'JBFilterElementNameCheckbox'        => $filPath . '/element.name.checkbox.php',
            'JBFilterElementNameRadio'           => $filPath . '/element.name.radio.php',
            'JBFilterElementNameSelect'          => $filPath . '/element.name.select.php',
            'JBFilterElementNameChosen'          => $filPath . '/element.name.select.chosen.php',
            'JBFilterElementRadio'               => $filPath . '/element.radio.php',
            'JBFilterElementRating'              => $filPath . '/element.rating.php',
            'JBFilterElementRatingRanges'        => $filPath . '/element.rating.ranges.php',
            'JBFilterElementRatingSlider'        => $filPath . '/element.rating.slider.php',
            'JBFilterElementSelect'              => $filPath . '/element.select.php',
            'JBFilterElementSelectChosen'        => $filPath . '/element.select.chosen.php',
            'JBFilterElementSlider'              => $filPath . '/element.slider.php',
            'JBFilterElementTag'                 => $filPath . '/element.tag.php',
            'JBFilterElementTagCheckbox'         => $filPath . '/element.tag.checkbox.php',
            'JBFilterElementTagRadio'            => $filPath . '/element.tag.radio.php',
            'JBFilterElementTagSelect'           => $filPath . '/element.tag.select.php',
            'JBFilterElementTagSelectChosen'     => $filPath . '/element.tag.select.chosen.php',
            'JBFilterElementText'                => $filPath . '/element.text.php',
            'JBFilterElementTextRange'           => $filPath . '/element.text.range.php',

            // events
            'JBZooSystemPlugin'                  => $evtPath . '/jsystem.php',
            'JBEvent'                            => $evtPath . '/jbevent.php',
            'JBEventApplication'                 => $evtPath . '/jbevent.application.php',
            'JBEventBasket'                      => $evtPath . '/jbevent.basket.php',
            'JBEventCategory'                    => $evtPath . '/jbevent.category.php',
            'JBEventComment'                     => $evtPath . '/jbevent.comment.php',
            'JBEventElement'                     => $evtPath . '/jbevent.element.php',
            'JBEventItem'                        => $evtPath . '/jbevent.item.php',
            'JBEventJBZoo'                       => $evtPath . '/jbevent.jbzoo.php',
            'JBEventLayout'                      => $evtPath . '/jbevent.layout.php',
            'JBEventSubmission'                  => $evtPath . '/jbevent.submission.php',
            'JBEventTag'                         => $evtPath . '/jbevent.tag.php',
            'JBEventType'                        => $evtPath . '/jbevent.type.php',
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
