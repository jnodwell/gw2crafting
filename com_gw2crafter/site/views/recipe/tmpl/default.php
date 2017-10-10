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
$price_data = Gw2crafterHelpersGw2crafter::getApiPriceArray($this->item->gw2_created_item_id);
$marketdata = true;
$buy        = $price_data[0]['high_buy'];
$sell       = $price_data[1]['low_sell'];
$crazy_buy  = $price_data[0]['crazy_buy'];
$crazy_sell = $price_data[1]['crazy_sell'];
if ($buy == 0 && $sell == 0)
{
	$marketdata = false;
}
$craftprice = 0;
$recipe     = Gw2crafterHelpersGw2crafter::getRecipeArray($this->item->gw2_created_item_id);
?>
<div class="item_recipe">
	<div class="recipe_details" id="recipe_details">
		<h1><?php echo $this->item->gw2_recipe_name; ?></h1>
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
			$craftprice     = 0;
			$lastquantities = array(
				1 => 1,
				2 => 1,
				3 => 1,
				4 => 1,
				5 => 1,
				6 => 1
			);
			$final_items    = array();
			$final_names    = array();
			foreach (
				Gw2crafterHelpersGw2crafter::getExpandedRecipeArray($this->item->gw2_created_item_id, $this->item->gw2_output_item_count,$this->item->gw2_output_item_count, 1) as $item
			)
			{
				$lastquantities[$item['depth']] = $item['qty'];
				$item_qty                       = $item['qty'];
				if ($item['depth'] > 1)
				{
					if ($lastquantities[$item['depth'] - 1] != $item['parentqty'])
					{
						$lastquantities[$item['depth'] - 1] = $item['parentqty'];
					}
				}
				if (!$item['has_child'])
				{
					$itemprice = Gw2crafterHelpersGw2crafter::getApiPrice($item['item_id'], false);
				}
				else
				{
					$itemprice = 0;
				} ?>
				<tr>
					<td><?php if ($item['depth'] == 1)
						{
							echo $item['qty'];
						} ?></td>
					<td><?php if ($item['depth'] > 1)
						{
							$count = 1;
							while ($count++ < $item['depth'])
							{
								echo '&nbsp;&nbsp;&nbsp;';
							}
							echo $item['qty'] . ' - ';
						}
						$recipe_row = Gw2crafterHelpersGw2crafter::getItemRecipeRow($item['item_id']);
						if ($recipe_row)
						{
							?><a href="<?php echo JRoute::_('index.php?option=com_gw2crafter&view=recipe&id='
							. (int) Gw2crafterHelpersGw2crafter::getItemRecipeRow($item['item_id'])); ?>"
							     class="noline"><?php
							echo $item['item_name']; ?></a>
							<?php
						}
						else
						{
							echo $item['item_name'];
						}
						$item_qty = $item['qty'] * $item['parentqty'] / $item['makes'] / $item['parentmakes'];

						if ($item['depth'] > 1) { echo ' (' . $item_qty . ')';} ?></td>
					<?php if ($item['has_child']) : ?>
						<td></td>
						<td></td>
					<?php else: ?>
						<td class="right"><?php echo Gw2crafterHelpersGw2crafter::getPriceFormatted($itemprice); ?></td>
						<td class="right"><?php echo Gw2crafterHelpersGw2crafter::getPriceFormatted($itemprice
								* $item_qty); ?></td>
					<?php endif; ?>
				</tr>
				<?php
				$craftprice += $itemprice * $item_qty;
				if (!$item['has_child'])
				{
					if (!array_key_exists($item['item_id'], $final_names))
					{
						$final_names[$item['item_id']] = $item['item_name'];
					}
					if (!array_key_exists($item['item_id'], $final_items))
					{
						$final_items[$item['item_id']] = $item_qty;
					}
					else
					{
						$final_items[$item['item_id']] = $final_items[$item['item_id']] + $item_qty;
					}
				}
			} ?>
			<tr>
				<td></td>
				<td></td>
				<td>Total Craft Cost</td>
				<td class="right"><?php echo Gw2crafterHelpersGw2crafter::getPriceFormatted($craftprice); ?></td>
			</tr>
		</table>
		<div class="pane">
			<div >
				<h4>Shopping List</h4>
				<?php
				foreach ($final_items as $key => $qty)
				{
					echo "<p>" . $qty . ' ' . $final_names[$key];
					//TODO get user id and match api key to it from user profile fields
					$apiKey = 'BB68E458-7296-A041-A10A-6810FFFC8BC17766F666-5DBE-4FDB-A6D8-D4920DC25016';
					$json  = @file_get_contents('https://api.guildwars2.com/v2/account/materials?access_token=' . $apiKey);
					$data  = json_decode($json, true);
					foreach($data as $item) {
						if($item['id'] == $key) {
							echo " (" . $item['count']  . ' onhand)';
						}
					}
					echo  "</p>";
				}
				?>
			</div>
			<div class="right70">
				<?php
				$used_in = Gw2crafterHelpersGw2crafter::getUsedIn($this->item->gw2_created_item_id);
				if ($used_in)
				{ ?><h4>Used In</h4>
					<?php
					foreach ($used_in as $u => $item_id)
					{
						?>
						<p><a href="<?php echo JRoute::_('index.php?option=com_gw2crafter&view=recipe&id='
								. (int) Gw2crafterHelpersGw2crafter::getItemRecipeRow($item_id)); ?>"
						      class="noline"><?php
								echo Gw2crafterHelpersGw2crafter::getItemNameByGw2RecipeId($item_id); ?></a></p>
						<?php
					}
				}
				?>
			</div>
		</div>
	</div>
</div>




