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

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$document = JFactory::getDocument();
$url      = JUri::base() . 'administrator/components/com_gw2crafter/assets/css/gw2crafter.css';
$document->addStyleSheet($url);

$user       = JFactory::getUser();
$userId     = $user->get('id');
$listOrder  = $this->state->get('list.ordering');
$listDirn   = $this->state->get('list.direction');
$canCreate  = $user->authorise('core.create', 'com_gw2crafter')
	&& file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms'
		. DIRECTORY_SEPARATOR . 'itemform.xml');
$canEdit    = $user->authorise('core.edit', 'com_gw2crafter')
	&& file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms'
		. DIRECTORY_SEPARATOR . 'itemform.xml');
$canCheckin = $user->authorise('core.manage', 'com_gw2crafter');
$canChange  = $user->authorise('core.edit.state', 'com_gw2crafter');
$canDelete  = $user->authorise('core.delete', 'com_gw2crafter');
?>

<form action="<?php echo JRoute::_('index.php?option=com_gw2crafter&view=items'); ?>" method="post"
      name="adminForm" id="adminForm">

	<?php echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>
	<table class="table table-striped" id="itemList">
		<thead>
		<tr>

			<th width="40px" class='img'>

			</th>

			<th width="5%" class=''>
				<?php echo JHtml::_('grid.sort', 'COM_GW2CRAFTER_ITEMS_ID', 'a.id', $listDirn, $listOrder); ?>
			</th>
			<th width="5%" class=''>
				<?php echo JHtml::_('grid.sort', 'COM_GW2CRAFTER_ITEMS_GW2_ITEM_ID', 'a.gw2_item_id', $listDirn,
					$listOrder); ?>
			</th>
			<th class=''>
				<?php echo JHtml::_('grid.sort', 'COM_GW2CRAFTER_ITEMS_GW2_ITEM_NAME', 'a.gw2_item_name', $listDirn,
					$listOrder); ?>
			</th>
			<th class=''>
				<?php echo JHtml::_('grid.sort', 'COM_GW2CRAFTER_ITEMS_GW2_ITEM_TYPE', 'a.gw2_item_type', $listDirn,
					$listOrder); ?>
			</th>
			<th class=''>
				<?php echo JHtml::_('grid.sort', 'COM_GW2CRAFTER_ITEMS_GW2_ITEM_RARITY', 'a.gw2_item_rarity', $listDirn,
					$listOrder); ?>
			</th>

			<?php if ($canEdit || $canDelete): ?>
				<th class="center">
					<?php echo JText::_('COM_GW2CRAFTER_ITEMS_ACTIONS'); ?>
				</th>
			<?php endif; ?>

		</tr>
		</thead>
		<tfoot>
		<tr>
			<td colspan="<?php echo isset($this->items[0]) ? count(get_object_vars($this->items[0])) : 10; ?>">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->items as $i => $item) : ?>
			<?php $canEdit = $user->authorise('core.edit', 'com_gw2crafter'); ?>


			<tr class="row<?php echo $i % 2; ?>">

				<td class="img center">
					<img src="<?php echo $item->gw2_item_icon_url; ?>"/>
				</td>

				<td>

					<?php echo $item->id; ?>
				</td>
				<td>

					<?php echo $item->gw2_item_id; ?>
				</td>
				<td>
					<?php if (isset($item->checked_out) && $item->checked_out) : ?>
						<?php echo JHtml::_('jgrid.checkedout', $i, $item->uEditor, $item->checked_out_time, 'items.',
							$canCheckin); ?>
					<?php endif; ?>
					<a href="<?php echo JRoute::_('index.php?option=com_gw2crafter&view=item&id='
						. (int) $item->id); ?>">
						<?php echo $this->escape($item->gw2_item_name); ?></a>
				</td>
				<td class="<?php echo $item->gw2_item_type; ?>"><?php echo $item->gw2_item_type; ?></td>
				<td class="<?php echo strtolower($item->gw2_item_rarity); ?>"><?php echo $item->gw2_item_rarity; ?></td>
				<?php if ($canEdit || $canDelete): ?>
					<td class="center">
					</td>
				<?php endif; ?>

			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>

	<?php if ($canCreate) : ?>
		<a href="<?php echo JRoute::_('index.php?option=com_gw2crafter&task=itemform.edit&id=0', false, 0); ?>"
		   class="btn btn-success btn-small"><i
				class="icon-plus"></i>
			<?php echo JText::_('COM_GW2CRAFTER_ADD_ITEM'); ?></a>
	<?php endif; ?>

	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
	<?php echo JHtml::_('form.token'); ?>
</form>

<?php if ($canDelete) : ?>
	<script type="text/javascript">

		jQuery(document).ready(function () {
			jQuery('.delete-button').click(deleteItem);
		});

		function deleteItem() {

			if (!confirm("<?php echo JText::_('COM_GW2CRAFTER_DELETE_MESSAGE'); ?>")) {
				return false;
			}
		}
	</script>
<?php endif; ?>
<?php
function getPriceImages($intvalue)
{
	$gold   = 0;
	$silver = 0;
	$copper = 0;

	if ($intvalue > 9999)
	{
		//there is gold
		$gold     = floor($intvalue / 10000);
		$intvalue = $intvalue - $gold * 10000;
	}
	if ($intvalue > 99)
	{
		//there is silver
		$silver   = floor($intvalue / 100);
		$intvalue = $intvalue - (int) $silver * 100;
	}
	$copper = $intvalue;

	$price = $gold > 9 ? (int) $gold : '0' . (int) $gold;
	$price .= '<img src="/media/com_gw2crafter/images/Gold_coin.png" style="padding-left:5px;padding-right:10px;" />';
	$price .= $silver > 9 ? (int) $silver : '0' . (int) $silver;
	$price .= '<img src="/media/com_gw2crafter/images/Silver_coin.png" style="padding-left:5px;padding-right:10px;" />';
	$price .= $copper > 9 ? (int) $copper : '0' . (int) $copper;
	$price .= '<img src="/media/com_gw2crafter/images/Copper_coin.png" style="padding-left:5px;padding-right:10px;" />';

	return $price;
}


?>
