<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Gw2crafter
 * @author     Jennifer Nodwell <jennifer@nodwell.net>
 * @copyright  2017 Jennifer Nodwell
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

/**
 * Gw2crafter helper.
 *
 * @since  1.6
 */
class Gw2crafterHelper
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  string
	 *
	 * @return void
	 */
	public static function addSubmenu($vName = '')
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_GW2CRAFTER_TITLE_ITEMS'),
			'index.php?option=com_gw2crafter&view=items',
			$vName == 'items'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_GW2CRAFTER_TITLE_RECIPES'),
			'index.php?option=com_gw2crafter&view=recipes',
			$vName == 'items'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_GW2CRAFTER_TITLE_FAVORITES'),
			'index.php?option=com_gw2crafter&view=favorites',
			$vName == 'favorites'
		);

		JHtmlSidebar::addEntry(
			JText::_('COM_GW2CRAFTER_TITLE_MEMBERVIEWS'),
			'index.php?option=com_gw2crafter&view=memberviews',
			$vName == 'memberviews'
		);

	}

	/**
	 * Gets the files attached to an item
	 *
	 * @param   int     $pk     The item's id
	 *
	 * @param   string  $table  The table's name
	 *
	 * @param   string  $field  The field's name
	 *
	 * @return  array  The files
	 */
	public static function getFiles($pk, $table, $field)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query
			->select($field)
			->from($table)
			->where('id = ' . (int) $pk);

		$db->setQuery($query);

		return explode(',', $db->loadResult());
	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return    JObject
	 *
	 * @since    1.6
	 */
	public static function getActions()
	{
		$user   = JFactory::getUser();
		$result = new JObject;

		$assetName = 'com_gw2crafter';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action)
		{
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}
}

