<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Gw2crafter
 * @author     Jennifer Nodwell <jennifer@nodwell.net>
 * @copyright  2017 Jennifer Nodwell
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Gw2crafter records.
 *
 * @since  1.6
 */
class Gw2crafterModelFavorites extends JModelList
{
/**
	* Constructor.
	*
	* @param   array  $config  An optional associative array of configuration settings.
	*
	* @see        JController
	* @since      1.6
	*/
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.`id`',
				'ordering', 'a.`ordering`',
				'state', 'a.`state`',
				'created_by', 'a.`created_by`',
				'modified_by', 'a.`modified_by`',
				'gw2_item_id', 'a.`gw2_item_id`',
				'gw2_name', 'a.`gw2_name`',
				'joomla_user_id', 'a.`joomla_user_id`',
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   Elements order
	 * @param   string  $direction  Order direction
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter state.
		$search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $app->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.state', $published);
		// Filtering gw2_item_id
		$this->setState('filter.gw2_item_id', $app->getUserStateFromRequest($this->context.'.filter.gw2_item_id', 'filter_gw2_item_id', '', 'string'));

		// Filtering joomla_user_id
		$this->setState('filter.joomla_user_id', $app->getUserStateFromRequest($this->context.'.filter.joomla_user_id', 'filter_joomla_user_id', '', 'string'));


		// Load the parameters.
		$params = JComponentHelper::getParams('com_gw2crafter');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.gw2_item_id', 'asc');
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return   string A store id.
	 *
	 * @since    1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.state');

		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return   JDatabaseQuery
	 *
	 * @since    1.6
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select', 'DISTINCT a.*'
			)
		);
		$query->from('`#__gw2crafter_crafter_favorites` AS a');

		// Join over the users for the checked out user
		$query->select("uc.name AS uEditor");
		$query->join("LEFT", "#__users AS uc ON uc.id=a.checked_out");

		// Join over the user field 'created_by'
		$query->select('`created_by`.name AS `created_by`');
		$query->join('LEFT', '#__users AS `created_by` ON `created_by`.id = a.`created_by`');

		// Join over the user field 'modified_by'
		$query->select('`modified_by`.name AS `modified_by`');
		$query->join('LEFT', '#__users AS `modified_by` ON `modified_by`.id = a.`modified_by`');
		// Join over the foreign key 'gw2_item_id'
		$query->select('`#__gw2crafter_api_item_2831653`.`gw2_item_name` AS gw2crafter_api_item_fk_value_2831653');
		$query->join('LEFT', '#__gw2crafter_api_item AS #__gw2crafter_api_item_2831653 ON #__gw2crafter_api_item_2831653.`gw2_item_id` = a.`gw2_item_id`');
		// Join over the foreign key 'joomla_user_id'
		$query->select('`#__users_2834797`.`username` AS users_fk_value_2834797');
		$query->join('LEFT', '#__users AS #__users_2834797 ON #__users_2834797.`id` = a.`joomla_user_id`');

		// Filter by published state
		$published = $this->getState('filter.state');

		if (is_numeric($published))
		{
			$query->where('a.state = ' . (int) $published);
		}
		elseif ($published === '')
		{
			$query->where('(a.state IN (0, 1))');
		}

		// Filter by search in title
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->Quote('%' . $db->escape($search, true) . '%');
				$query->where('(#__gw2crafter_api_item_2831653.gw2_item_name LIKE ' . $search . '  OR #__users_2834797.username LIKE ' . $search . ' )');
			}
		}


		// Filtering gw2_item_id
		$filter_gw2_item_id = $this->state->get("filter.gw2_item_id");

		if ($filter_gw2_item_id !== null && !empty($filter_gw2_item_id))
		{
			$query->where("a.`gw2_item_id` = '".$db->escape($filter_gw2_item_id)."'");
		}

		// Filtering joomla_user_id
		$filter_joomla_user_id = $this->state->get("filter.joomla_user_id");

		if ($filter_joomla_user_id !== null && !empty($filter_joomla_user_id))
		{
			$query->where("a.`joomla_user_id` = '".$db->escape($filter_joomla_user_id)."'");
		}
		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering');
		$orderDirn = $this->state->get('list.direction');

		if ($orderCol && $orderDirn)
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}
		return $query;
	}

	/**
	 * Get an array of data items
	 *
	 * @return mixed Array of data items on success, false on failure.
	 */
	public function getItems()
	{
		$items = parent::getItems();

		foreach ($items as $oneItem)
		{

			if (isset($oneItem->gw2_item_id))
			{
				$values    = explode(',', $oneItem->gw2_item_id);
				$textValue = array();

				foreach ($values as $value)
				{
					$db    = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
						->select('`#__gw2crafter_api_item_2831653`.`gw2_item_name`')
						->from($db->quoteName('#__gw2crafter_api_item', '#__gw2crafter_api_item_2831653'))
						->where($db->quoteName('gw2_item_id') . ' = '. $db->quote($db->escape($value)));

					$db->setQuery($query);
					$results = $db->loadObject();

					if ($results)
					{
						$textValue[] = $results->gw2_item_name;
					}
				}

				$oneItem->gw2_item_id = !empty($textValue) ? implode(', ', $textValue) : $oneItem->gw2_item_id;
			}

			if (isset($oneItem->joomla_user_id))
			{
				$values    = explode(',', $oneItem->joomla_user_id);
				$textValue = array();

				foreach ($values as $value)
				{
					$db    = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
						->select('`#__users_2834797`.`username`')
						->from($db->quoteName('#__users', '#__users_2834797'))
						->where($db->quoteName('id') . ' = '. $db->quote($db->escape($value)));

					$db->setQuery($query);
					$results = $db->loadObject();

					if ($results)
					{
						$textValue[] = $results->username;
					}
				}

				$oneItem->joomla_user_id = !empty($textValue) ? implode(', ', $textValue) : $oneItem->joomla_user_id;
			}
		}

		return $items;
	}
}
