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

jimport('joomla.application.component.controlleradmin');

use Joomla\Utilities\ArrayHelper;

/**
 * Items list controller class.
 *
 * @since  1.6
 */
class Gw2crafterControllerItems extends JControllerAdmin
{

	protected $gw2API_v2_items = 'https://api.guildwars2.com/v2/items';

	/**
	 * Method to clone existing Items
	 *
	 * @return void
	 */
	public function duplicate()
	{
		// Check for request forgeries
		Jsession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Get id(s)
		$pks = $this->input->post->get('cid', array(), 'array');

		try
		{
			if (empty($pks))
			{
				throw new Exception(JText::_('COM_GW2CRAFTER_NO_ELEMENT_SELECTED'));
			}

			ArrayHelper::toInteger($pks);
			$model = $this->getModel();
			$model->duplicate($pks);
			$this->setMessage(Jtext::_('COM_GW2CRAFTER_ITEMS_SUCCESS_DUPLICATED'));
		}
		catch (Exception $e)
		{
			JFactory::getApplication()->enqueueMessage($e->getMessage(), 'warning');
		}

		$this->setRedirect('index.php?option=com_gw2crafter&view=items');
	}

	/**
	 * Proxy for getModel.
	 *
	 * @param   string $name   Optional. Model name
	 * @param   string $prefix Optional. Class prefix
	 * @param   array  $config Optional. Configuration array for model
	 *
	 * @return  object    The Model
	 *
	 * @since    1.6
	 */
	public function getModel($name = 'item', $prefix = 'Gw2crafterModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));

		return $model;
	}

	/**
	 * Method to save the submitted ordering values for records via AJAX.
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public function saveOrderAjax()
	{
		// Get the input
		$input = JFactory::getApplication()->input;
		$pks   = $input->post->get('cid', array(), 'array');
		$order = $input->post->get('order', array(), 'array');

		// Sanitize the input
		ArrayHelper::toInteger($pks);
		ArrayHelper::toInteger($order);

		// Get the model
		$model = $this->getModel();

		// Save the ordering
		$return = $model->saveorder($pks, $order);

		if ($return)
		{
			echo "1";
		}

		// Close the application
		JFactory::getApplication()->close();
	}

	public function updateapi()
	{
		$json = file_get_contents($this->gw2API_v2_items);
		$data = json_decode($json, true);
		$count = 0;
		foreach ($data as $key => $gw2_item_id) {
			$model = $this->getModel();
			if ($model->getItemByGw2Id($gw2_item_id) == 0 && $gw2_item_id != 0) {
				//we need to create a new row here
				$new_json = file_get_contents($this->gw2API_v2_items . '/' . $gw2_item_id);
				$item_data = json_decode($new_json,true);
				try
				{
					$model->addFromJson($item_data);
				} catch (Exception $e) {
					//fail gracefully
				}
			}
			$count++;
		}
        JFactory::getApplication()->enqueueMessage($count . ' rows updated/created','warning');
		// Redirect to the list screen.
		$this->setRedirect(JRoute::_('index.php?option=com_gw2crafting&view=items', false));

	}
}
