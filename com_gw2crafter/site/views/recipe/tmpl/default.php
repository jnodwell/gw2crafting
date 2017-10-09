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
$document = JFactory::getDocument();
$url      = JUri::base() . 'administrator/components/com_gw2crafter/assets/css/gw2crafter.css';
$document->addStyleSheet($url);
$app        = JFactory::getApplication();
$pathway    = $app->getPathway();
$breadcrumb = $pathway->setPathway(array());
$pathway->addItem(JText::_('Item Database'), JRoute::_('index.php?option=com_gw2crafter&view=items&Itemid=104'));

$pathway->addItem($this->item->gw2_recipe_name, '');
$this->loadHelper('gw2crafter');
$price_data  = Gw2crafterHelpersGw2crafter::getApiPriceArray($this->item->gw2_created_item_id);
$marketdata  = true;
$buy         = $price_data[0]['high_buy'];
$sell        = $price_data[1]['low_sell'];
$crazy_buy   = $price_data[0]['crazy_buy'];
$crazy_sell  = $price_data[1]['crazy_sell'];
if ($buy == 0 && $sell == 0)
{
	$marketdata = false;
}
$craftprice = 0;
$recipe = Gw2crafterHelpersGw2crafter::getRecipeArray($this->item->gw2_created_item_id);
?>
<div class="item_recipe">
	<div class="recipe_details" id="recipe_details">
		<h1><?php echo $this->item->gw2_recipe_name;?></h1>
		<table>
			<tr>
				<td><?php echo JText::_('COM_GW2CRAFTER_LABEL_ITEM_RECIPE_TYPE'); ?>:</td>
				<td><?php echo $this->item->gw2_recipe_type; ?></td>
			</tr>
			<tr>
				<td><?php echo JText::_('COM_GW2CRAFTER_LABEL_ITEM_RECIPE_LEVEL'); ?>:</td>
				<td><?php echo $this->item->gw2_recipe_min_rating; ?></td>
			</tr>
			<tr>
				<td><?php echo JText::_('COM_GW2CRAFTER_LABEL_ITEM_RECIPE_OUT_QTY'); ?>:</td>
				<td><?php echo $this->item->gw2_output_item_count; ?></td>
			</tr>
		</table>
	</div>
	<div class="recipe_prices" id="recipe_prices">
		<table class="item_prices">
			<tr>
				<th><?php echo JText::_('COM_GW2CRAFTER_LABEL_ITEM_RECIPE_QTY'); ?></th>
				<th><?php echo JText::_('COM_GW2CRAFTER_FORM_LBL_ITEM_GW2_NAME'); ?></th>
				<th><?php echo JText::_('COM_GW2CRAFTER_LABEL_ITEM_RECIPE_ITEM_SINGLE_COST'); ?></th>
				<th><?php echo JText::_('COM_GW2CRAFTER_LABEL_ITEM_RECIPE_ITEM_EXTENDED_COST'); ?></th>
			</tr>
			<?php
			$craftprice = 0;
			foreach (Gw2crafterHelpersGw2crafter::getExpandedRecipeArray($this->item->gw2_created_item_id,1) as $item)
			{
				if (!$item['has_child']) {
					$itemprice = Gw2crafterHelpersGw2crafter::getApiPrice($item['item_id'], false);
				} else {
					$itemprice = 0;
				}?>
				<tr>
					<td><?php echo $item['qty']; ?></td>
					<td><?php echo $item['item_name']; ?></td>
				<?php if ($item['has_child']) : ?>
				    <td></td>
				    <td></td>
				<?php else: ?>
					<td class="right"><?php echo Gw2crafterHelpersGw2crafter::getPriceFormatted($itemprice); ?></td>
					<td class="right"><?php echo Gw2crafterHelpersGw2crafter::getPriceFormatted($itemprice*$item['parentqty']*$item['qty']); ?></td>
				<?php endif; ?>
				</tr>
				<?php
				$craftprice += $itemprice;
			} ?>
			<tr>
				<td></td>
				<td></td>
				<td>Total Craft Cost</td>
				<td class="right"><?php echo Gw2crafterHelpersGw2crafter::getPriceFormatted($craftprice); ?></td>
			</tr>
		</table>
	</div>
</div>




