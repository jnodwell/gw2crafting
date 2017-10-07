<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Gw2crafter
 * @author     Jennifer Nodwell <jennifer@nodwell.net>
 * @copyright  2017 Jennifer Nodwell
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

/**
 * Gw2crafter model.
 *
 * @since  1.6
 */
class Gw2crafterModelItem extends JModelAdmin
{
	/**
	 * @var      string    The prefix to use with controller messages.
	 * @since    1.6
	 */
	protected $text_prefix = 'COM_GW2CRAFTER';

	/**
	 * @var    string    Alias to manage history control
	 * @since   3.2
	 */
	public $typeAlias = 'com_gw2crafter.item';

	/**
	 * @var null  Item data
	 * @since  1.6
	 */
	protected $item = null;

	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param   string $type   The table type to instantiate
	 * @param   string $prefix A prefix for the table class name. Optional.
	 * @param   array  $config Configuration array for model. Optional.
	 *
	 * @return    JTable    A database object
	 *
	 * @since    1.6
	 */
	public function getTable($type = 'Item', $prefix = 'Gw2crafterTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param   array   $data     An optional array of data for the form to interogate.
	 * @param   boolean $loadData True if the form is to load its own data (default case), false if not.
	 *
	 * @return  JForm  A JForm object on success, false on failure
	 *
	 * @since    1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Initialise variables.
		$app = JFactory::getApplication();

		// Get the form.
		$form = $this->loadForm(
			'com_gw2crafter.item', 'item',
			array(
				'control'   => 'jform',
				'load_data' => $loadData
			)
		);

		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return   mixed  The data for the form.
	 *
	 * @since    1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_gw2crafter.edit.item.data', array());

		if (empty($data))
		{
			if ($this->item === null)
			{
				$this->item = $this->getItem();
			}

			$data = $this->item;
		}

		return $data;
	}

	/**
	 * Method to get a single record.
	 *
	 * @param   integer $pk The id of the primary key.
	 *
	 * @return  mixed    Object on success, false on failure.
	 *
	 * @since    1.6
	 */
	public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk))
		{
			// Do any procesing on fields here if needed
		}

		return $item;
	}

	public function getItemByGw2Id($gw2_item_id)
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true);

		$query->select($db->quoteName(array('id')));
		$query->from($db->quoteName('#__gw2crafter_api_item'));
		$query->where($db->quoteName('gw2_item_id') . ' = ' . $gw2_item_id);

		$db->setQuery($query);

		$results = $db->loadObjectList();

		foreach ($results as $row)
		{
			return $row->id;
		}

		return 0;
	}

	public function getItemNameByGw2ItemId($gw2_item_id)
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true);

		$query->select($db->quoteName(array('gw2_item_name')));
		$query->from($db->quoteName('#__gw2crafter_api_item'));
		$query->where($db->quoteName('gw2_item_id') . ' = ' . $gw2_item_id);

		$db->setQuery($query);

		$results = $db->loadObjectList();

		foreach ($results as $row)
		{
			return $row->gw2_item_name;
		}

		return 0;
	}

	public function addFromJson($json_data) {
		$data = new stdClass();
		$data->id = null;
		$data->state = 1;
		$data->created_by = '635';
		$data->modified_by = '635';
		$data->ordering = $json_data['id'];
		$data->gw2_item_id = $json_data['id'];
		$data->gw2_item_name = $json_data['name'];
		$data->gw2_item_type = $json_data['type'];
		$data->gw2_item_icon_url = $json_data['icon'];
		$data->gw2_item_rarity = $json_data['rarity'];
		$data->gw2_item_vendor_value = $json_data['vendor_value'];
		$data->gw2_item_required_level = $json_data['level'];

		$db = JFactory::getDBO();
		$db->insertObject( '#__gw2crafter_api_item', $data );
	}

	/**
	 * Method to duplicate an Item
	 *
	 * @param   array &$pks An array of primary key IDs.
	 *
	 * @return  boolean  True if successful.
	 *
	 * @throws  Exception
	 */
	public function duplicate(&$pks)
	{
		$user = JFactory::getUser();

		// Access checks.
		if (!$user->authorise('core.create', 'com_gw2crafter'))
		{
			throw new Exception(JText::_('JERROR_CORE_CREATE_NOT_PERMITTED'));
		}

		$dispatcher = JEventDispatcher::getInstance();
		$context    = $this->option . '.' . $this->name;

		// Include the plugins for the save events.
		JPluginHelper::importPlugin($this->events_map['save']);

		$table = $this->getTable();

		foreach ($pks as $pk)
		{
			if ($table->load($pk, true))
			{
				// Reset the id to create a new record.
				$table->id = 0;

				if (!$table->check())
				{
					throw new Exception($table->getError());
				}


				// Trigger the before save event.
				$result = $dispatcher->trigger($this->event_before_save, array($context, &$table, true));

				if (in_array(false, $result, true) || !$table->store())
				{
					throw new Exception($table->getError());
				}

				// Trigger the after save event.
				$dispatcher->trigger($this->event_after_save, array($context, &$table, true));
			}
			else
			{
				throw new Exception($table->getError());
			}
		}

		// Clean cache
		$this->cleanCache();

		return true;
	}

	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @param   JTable $table Table Object
	 *
	 * @return void
	 *
	 * @since    1.6
	 */
	protected function prepareTable($table)
	{
		jimport('joomla.filter.output');

		if (empty($table->id))
		{
			// Set ordering to the last item if not set
			if (@$table->ordering === '')
			{
				$db = JFactory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__gw2crafter_api_item');
				$max             = $db->loadResult();
				$table->ordering = $max + 1;
			}
		}
	}
}
