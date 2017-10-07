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

$this->loadHelper('gw2crafter');
$buy         = Gw2crafterHelpersGw2crafter::getApiPrice($this->item->gw2_item_id, true);
$sell        = Gw2crafterHelpersGw2crafter::getApiPrice($this->item->gw2_item_id, false);
$cost        = $buy * 1.1;
$breakeven   = $cost / .85;
$list        = $breakeven * .05;
$tax         = $breakeven * .1;
$fivepercent = ($cost / .85) * 1.065;
$tenpercent  = ($cost / .85) * 1.115;
?>
<div class="item_container" id="item_container">
	<h1><?php echo $this->item->gw2_item_name; ?></h1>
	<div class="item_details" id="item_details">
		<img class="item_details" src="<?php echo $this->item->gw2_item_icon_url;?>" />
		<ul>
			<li><?php echo $this->item->gw2_item_type; ?></li>
			<li class="<?php echo strtolower($this->item->gw2_item_rarity);?>"><?php echo $this->item->gw2_item_rarity;?></li>
			<li>Required Level: <?php echo $this->item->gw2_item_required_level;?></li>
		</ul>
	</div>
	<div class="item_prices" id="item_prices">
		<table class="item_prices">
			<tr>
				<td><?php echo JText::_('COM_GW2CRAFTER_LABEL_ITEM_HIGHEST_BUY'); ?></td>
				<td><?php echo Gw2crafterHelpersGw2crafter::getPriceFormatted($buy); ?></td>
			</tr>
			<tr>
				<td><?php echo JText::_('COM_GW2CRAFTER_LABEL_ITEM_LOWEST_SELL'); ?></td>
				<td><?php echo Gw2crafterHelpersGw2crafter::getPriceFormatted($sell); ?></td>
			</tr>
			<tr>
				<td><?php echo JText::_('Spread'); ?></td>
				<td><?php echo ($buy > 0) ?  $sell / $buy * 100 . '%' : 0; ?></td>
			</tr>
			<tr>
				<td><?php echo JText::_('COM_GW2CRAFTER_LABEL_ITEM_CRAFT_PRICE'); ?></td>
				<td><?php echo Gw2crafterHelpersGw2crafter::getPriceFormatted($cost); ?></td>
			</tr>
			<tr>
				<td><?php echo JText::_('COM_GW2CRAFTER_LABEL_ITEM_LIST_FEE'); ?></td>
				<td><?php echo Gw2crafterHelpersGw2crafter::getPriceFormatted($list); ?></td>
			</tr>
			<tr>
				<td><?php echo JText::_('COM_GW2CRAFTER_LABEL_ITEM_SALES_TAX'); ?></td>
				<td><?php echo Gw2crafterHelpersGw2crafter::getPriceFormatted($tax); ?></td>
			</tr>
			<tr>
				<td><?php echo JText::_('COM_GW2CRAFTER_LABEL_ITEM_BREAK_EVEN'); ?></td>
				<td><?php echo Gw2crafterHelpersGw2crafter::getPriceFormatted($breakeven); ?></td>
			</tr>
			<tr>
				<td><?php echo JText::_('COM_GW2CRAFTER_LABEL_ITEM_FIVE_PERCENT'); ?></td>
				<td><?php echo Gw2crafterHelpersGw2crafter::getPriceFormatted($fivepercent); ?></td>
			</tr>
			<tr>
				<td><?php echo JText::_('COM_GW2CRAFTER_LABEL_ITEM_TEN_PERCENT'); ?></td>
				<td><?php echo Gw2crafterHelpersGw2crafter::getPriceFormatted($tenpercent); ?></td>
			</tr>
		</table>
	</div>
</div>


