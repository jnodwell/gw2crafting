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

$canEdit = JFactory::getUser()->authorise('core.edit', 'com_gw2crafter.' . $this->item->id);

if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_gw2crafter' . $this->item->id))
{
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>

<div class="item_fields">

	<table class="table">
		

		<tr>
			<th><?php echo JText::_('COM_GW2CRAFTER_FORM_LBL_FAVORITE_GW2_ITEM_ID'); ?></th>
			<td><?php echo $this->item->gw2_item_id; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_GW2CRAFTER_FORM_LBL_FAVORITE_GW2_NAME'); ?></th>
			<td><?php echo $this->item->gw2_name; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_GW2CRAFTER_FORM_LBL_FAVORITE_JOOMLA_USER_ID'); ?></th>
			<td><?php echo $this->item->joomla_user_id; ?></td>
		</tr>

	</table>

</div>

<?php if($canEdit && $this->item->checked_out == 0): ?>

	<a class="btn" href="<?php echo JRoute::_('index.php?option=com_gw2crafter&task=favorite.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_GW2CRAFTER_EDIT_ITEM"); ?></a>

<?php endif; ?>

<?php if (JFactory::getUser()->authorise('core.delete','com_gw2crafter.favorite.'.$this->item->id)) : ?>

	<a class="btn btn-danger" href="#deleteModal" role="button" data-toggle="modal">
		<?php echo JText::_("COM_GW2CRAFTER_DELETE_ITEM"); ?>
	</a>

	<div id="deleteModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3><?php echo JText::_('COM_GW2CRAFTER_DELETE_ITEM'); ?></h3>
		</div>
		<div class="modal-body">
			<p><?php echo JText::sprintf('COM_GW2CRAFTER_DELETE_CONFIRM', $this->item->id); ?></p>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal">Close</button>
			<a href="<?php echo JRoute::_('index.php?option=com_gw2crafter&task=favorite.remove&id=' . $this->item->id, false, 2); ?>" class="btn btn-danger">
				<?php echo JText::_('COM_GW2CRAFTER_DELETE_ITEM'); ?>
			</a>
		</div>
	</div>

<?php endif; ?>