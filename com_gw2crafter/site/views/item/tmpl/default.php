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
$app = JFactory::getApplication();
$pathway = $app->getPathway();
$breadcrumb = $pathway->setPathway(array());
$pathway->addItem( JText::_( 'Item Database' ),JRoute::_('index.php?option=com_gw2crafter&view=items&Itemid=104'));
$pathway->addItem( $this->item->gw2_item_name,'');
$this->loadHelper('gw2crafter');
$price_data  = Gw2crafterHelpersGw2crafter::getApiPriceArray($this->item->gw2_item_id);
$marketdata = true;
$buy         = $price_data[0]['high_buy'];
$sell        = $price_data[1]['low_sell'];
$crazy_buy   = $price_data[0]['crazy_buy'];
$crazy_sell  = $price_data[1]['crazy_sell'];
$cost        = $buy * 1.1;
$breakeven   = $cost / .85;
$list        = $breakeven * .05;
$tax         = $breakeven * .1;
$fivepercent = ($cost / .85) * 1.065;
$tenpercent  = ($cost / .85) * 1.115;
if ($buy == 0 && $sell == 0) {
	$marketdata = false;
}
?>
<div class="item_container" id="item_container">
	<h1><?php echo $this->item->gw2_item_name; ?></h1>
	<div class="item_details" id="item_details">
		<img class="item_details" src="<?php echo $this->item->gw2_item_icon_url; ?>"/>
		<ul>
			<li><?php echo $this->item->gw2_item_type; ?></li>
			<li class="<?php echo strtolower($this->item->gw2_item_rarity); ?>"><?php echo $this->item->gw2_item_rarity; ?></li>
			<li>Required Level: <?php echo $this->item->gw2_item_required_level; ?></li>
		</ul>
	</div>
	<?php if ($marketdata) : ?>
	<div class="item_prices" id="item_prices">
		<table class="item_prices">
			<tr>
				<td><?php echo JText::_('COM_GW2CRAFTER_LABEL_ITEM_HIGHEST_BUY'); ?></td>
				<td><?php echo Gw2crafterHelpersGw2crafter::getPriceFormatted($buy); ?></td>
				<td><?php echo JText::_('COM_GW2CRAFTER_LABEL_ITEM_LOWEST_SELL'); ?></td>
				<td><?php echo Gw2crafterHelpersGw2crafter::getPriceFormatted($sell); ?></td>
			</tr>
			<tr>
				<td><?php echo JText::_('COM_GW2CRAFTER_LABEL_ITEM_LOWEST_BUY'); ?></td>
				<td><?php echo Gw2crafterHelpersGw2crafter::getPriceFormatted($crazy_buy); ?></td>
				<td><?php echo JText::_('COM_GW2CRAFTER_LABEL_ITEM_HIGHEST_SELL'); ?></td>
				<td><?php echo Gw2crafterHelpersGw2crafter::getPriceFormatted($crazy_sell); ?></td>
			</tr>
			<tr>
				<td colspan="2" class="center"><?php echo JText::_('Volume'); ?>
					: <?php echo $price_data[0]['total_buys']; ?>
					<?php echo JText::_('High'); ?> : <?php echo $price_data[0]['total_buys_in_top']; ?>
					<?php echo JText::_('Low'); ?> : <?php echo $price_data[0]['total_buys_in_bottom']; ?></td>
				<td colspan="2" class="center"><?php echo JText::_('Volume'); ?>
					: <?php echo $price_data[1]['total_sells']; ?>
					<?php echo JText::_('High'); ?> : <?php echo $price_data[1]['total_sells_in_top']; ?>
					<?php echo JText::_('Low'); ?> : <?php echo $price_data[1]['total_sells_in_bottom']; ?></td>
			</tr>
			<tr>
				<td colspan="4" class="center">B:S = <?php echo $sell != 0 ? number_format($buy / $sell, 4) : 0; ?>&nbsp;&nbsp;C:B
					= <?php echo number_format($buy / $cost, 4); ?>&nbsp;&nbsp;P:L = <?php echo number_format($sell
						/ ($sell - $sell * .05 - $sell * .1 - $cost), 4); ?>%
				</td>
			</tr>
			<tr>
				<td><?php echo JText::_('COM_GW2CRAFTER_LABEL_ITEM_CRAFT_PRICE'); ?></td>
				<td><?php echo Gw2crafterHelpersGw2crafter::getPriceFormatted($cost); ?></td>
				<td><?php echo JText::_('COM_GW2CRAFTER_LABEL_ITEM_LIST_FEE'); ?></td>
				<td><?php echo Gw2crafterHelpersGw2crafter::getPriceFormatted($list); ?></td>
			</tr>
			<tr>
				<td><?php echo JText::_('COM_GW2CRAFTER_LABEL_ITEM_BREAK_EVEN'); ?></td>
				<td><?php echo Gw2crafterHelpersGw2crafter::getPriceFormatted($breakeven); ?></td>
				<td><?php echo JText::_('COM_GW2CRAFTER_LABEL_ITEM_SALES_TAX'); ?></td>
				<td><?php echo Gw2crafterHelpersGw2crafter::getPriceFormatted($tax); ?></td>


			</tr>
			<tr>
				<td><?php echo JText::_('Dump'); ?></td>
				<td><?php echo Gw2crafterHelpersGw2crafter::getPriceFormatted($buy - $buy * .05 - $buy * .1
						- $cost); ?></td>
				<td><?php echo JText::_('List'); ?></td>
				<td><?php echo Gw2crafterHelpersGw2crafter::getPriceFormatted($sell - $sell * .05 - $sell * .1
						- $cost); ?></td>
			</tr>
		</table>
	</div>
	<?php else : ?>
	<div class="item_prices" id="item_prices">No Market Data
		</div>
	<?php endif; ?>
</div>


