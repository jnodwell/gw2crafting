<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Gw2crafter
 * @author     Jennifer Nodwell <jennifer@nodwell.net>
 * @copyright  2017 Jennifer Nodwell
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

JLoader::register('Gw2crafterHelper',
	JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_gw2crafter'
	. DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'gw2crafter.php');

/**
 * Class Gw2crafterFrontendHelper
 *
 * @since  1.6
 */
class Gw2crafterHelpersGw2crafter
{
	protected $gw2API_v2_commercelistings = 'https://api.guildwars2.com/v2/commerce/listings/';

	/**
	 * Get an instance of the named model
	 *
	 * @param   string $name Model name
	 *
	 * @return null|object
	 */
	public static function getModel($name)
	{
		$model = null;

		// If the file exists, let's
		if (file_exists(JPATH_SITE . '/components/com_gw2crafter/models/' . strtolower($name) . '.php'))
		{
			require_once JPATH_SITE . '/components/com_gw2crafter/models/' . strtolower($name) . '.php';
			$model = JModelLegacy::getInstance($name, 'Gw2crafterModel');
		}

		return $model;
	}

	/**
	 * Gets the files attached to an item
	 *
	 * @param   int    $pk    The item's id
	 *
	 * @param   string $table The table's name
	 *
	 * @param   string $field The field's name
	 *
	 * @return  array  The files
	 */
	public static function getFiles($pk, $table, $field)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query
			->select($field)
			->from($table)
			->where('id = ' . (int) $pk);

		$db->setQuery($query);

		return explode(',', $db->loadResult());
	}

	public static function getVendorPriceFormatted($id)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query
			->select('gw2_item_vendor_value')
			->from('#__gw2crafter_api_item')
			->where('id = ' . (int) $id);

		$db->setQuery($query);

		$intvalue = (int) $db->loadResult();

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

	public static function getApiPrice($item_id, $buy = true)
	{

		$json  = @file_get_contents('https://api.guildwars2.com/v2/commerce/listings/' . $item_id);
		$data  = json_decode($json, true);
		$buys  = $data['buys'];
		$sells = $data['sells'];

		if ($buy)
		{
			$intvalue = (int) $buys[0]['unit_price'];
		}
		else
		{
			$intvalue = (int) $sells[0]['unit_price'];
		}

		return $intvalue;
	}

	public static function getApiPriceArray($item_id)
	{
		$error = false;

		$json = @file_get_contents('https://api.guildwars2.com/v2/commerce/listings/' . $item_id);
		if ($json === false)
		{
			$error = true;
		}

		if ($error)
		{
			$buy_data  = array(
				'high_buy'             => 0,
				'crazy_buy'            => 0,
				'total_buys_in_top'    => 0,
				'total_buys_in_bottom' => 0,
				'total_buys'           => 0
			);
			$sell_data = array(
				'low_sell'              => 0,
				'crazy_sell'            => 0,
				'total_sells_in_top'    => 0,
				'total_sells_in_bottom' => 0,
				'total_sells'           => 0
			);
		}
		else
		{
			$data  = json_decode($json, true);
			$buys  = $data['buys'];
			$sells = $data['sells'];

			$total_buys           = 0;
			$total_buys_in_top    = 0;
			$total_buys_in_bottom = 0;
			$high_buy             = 0;
			$first                = true;
			$last                 = 0;
			foreach ($buys as $buy)
			{
				if ($first)
				{
					$high_buy = $buy['unit_price'];
					$first    = false;
				}
				if ($buy['unit_price'] > $high_buy * .75)
				{
					$total_buys_in_top += $buy['listings'] * $buy['quantity'];
				}
				if ($buy['unit_price'] < $high_buy * .25)
				{
					$total_buys_in_bottom += $buy['listings'] * $buy['quantity'];
				}
				$total_buys += $buy['listings'] * $buy['quantity'];
				$last = $buy['unit_price'];
			}
			$buy_data              = array(
				'high_buy'             => $high_buy,
				'crazy_buy'            => $last,
				'total_buys_in_top'    => $total_buys_in_top,
				'total_buys_in_bottom' => $total_buys_in_bottom,
				'total_buys'           => $total_buys
			);
			$total_sells           = 0;
			$total_sells_in_top    = 0;
			$total_sells_in_bottom = 0;
			$low_sell              = 0;
			$first                 = true;
			$last                  = 0;
			foreach ($sells as $sell)
			{
				if ($first)
				{
					$low_sell = $sell['unit_price'];
					$first    = false;
				}
				if ($sell['unit_price'] < $low_sell * 1.25)
				{
					$total_sells_in_bottom += $sell['listings'] * $sell['quantity'];
				}
				if ($sell['unit_price'] > $low_sell * 1.75)
				{
					$total_sells_in_top += $sell['listings'] * $sell['quantity'];
				}
				$total_sells += $sell['listings'] * $sell['quantity'];
				$last = $sell['unit_price'];
			}
			$sell_data = array(
				'low_sell'              => $low_sell,
				'crazy_sell'            => $last,
				'total_sells_in_top'    => $total_sells_in_top,
				'total_sells_in_bottom' => $total_sells_in_bottom,
				'total_sells'           => $total_sells
			);
		}

		return array($buy_data, $sell_data);
	}

	public static function getPriceFormatted($price)
	{

		$intvalue = (int) $price;
		$loss     = false;

		if ($intvalue < 0)
		{
			$intvalue = abs($intvalue);
			$loss     = true;
		}

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

		if ($loss)
		{
			$price = "<span style=\"color:red;\">" . $price . "</span>";
		}

		return $price;
	}

	/**
	 * Gets the edit permission for an user
	 *
	 * @param   mixed $item The item
	 *
	 * @return  bool
	 */
	public static function canUserEdit($item)
	{
		$permission = false;
		$user       = JFactory::getUser();

		if ($user->authorise('core.edit', 'com_gw2crafter'))
		{
			$permission = true;
		}
		else
		{
			if (isset($item->created_by))
			{
				if ($user->authorise('core.edit.own', 'com_gw2crafter') && $item->created_by == $user->id)
				{
					$permission = true;
				}
			}
			else
			{
				$permission = true;
			}
		}

		return $permission;
	}

	public static function itemHasRecipe($gw2_item_id)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query
			->select('gw2_recipe_id')
			->from('#__gw2crafter_api_recipe')
			->where('gw2_created_item_id = ' . (int) $gw2_item_id);

		$db->setQuery($query);

		$intvalue = (int) $db->loadResult();

		return $intvalue;
	}

	public static function getItemNameByGw2Id($gw2id)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query
			->select('gw2_item_name')
			->from('#__gw2crafter_api_item')
			->where('gw2_item_id = ' . (int) $gw2id);

		$db->setQuery($query);

		$name = $db->loadResult();

		return $name;
	}

	public static function getRecipeArray($gw2_item_id)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query
			->select('*')
			->from('#__gw2crafter_api_recipe')
			->where('gw2_created_item_id = ' . (int) $gw2_item_id);

		$db->setQuery($query);

		$row = $db->loadObject();
if ($row)
{

	$recipeId              = $row->gw2_recipe_id;
	$gw2_recipe_type       = $row->gw2_recipe_type;
	$gw2_recipe_min_rating = $row->gw2_recipe_min_rating;
	$gw2_output_item_count = $row->gw2_output_item_count;
	$recipe                = array(
		'gw2_recipe_type'       => $gw2_recipe_type,
		'gw2_recipe_min_rating' => $gw2_recipe_min_rating,
		'gw2_output_item_count' => $gw2_output_item_count,
		'row_id'                => $row->id,
		'recipe_items'          => array(),
	);
	$query                 = $db->getQuery(true);
	$query
		->select('*')
		->from('#__gw2crafter_recipe_items')
		->where('gw2crafter_api_recipe_id = ' . (int) $recipeId);

	$db->setQuery($query);

	$result   = $db->loadObjectList();
	$itemlist = array();
	foreach ($result as $row)
	{

		$recipe_row = array(
			'qty'       => $row->qty,
			'item_id'   => $row->gw2crafter_api_item_id,
			'item_name' => self::getItemNameByGw2Id($row->gw2crafter_api_item_id),
		);
		$itemlist[] = $recipe_row;
	}
	$recipe['recipe_items'] = $itemlist;

	return $recipe;
}
return false;
	}

	public static function getExpandedRecipeArray($gw2_item_id,$parentqty)
	{
		$recipe       = self::getRecipeArray($gw2_item_id);
		$recipe_items = $recipe['recipe_items'];
		$itemlist = array();

		foreach ($recipe_items as $row)
		{
			$haschild = false;
			if (self::itemHasRecipe($row['item_id']))
			{
				$haschild = true;
			}
			$itemlist[] = array(
				'qty'       => $row['qty'],
				'item_id'   => $row['item_id'],
				'item_name' => $row['item_name'],
				'parentqty' => $parentqty,
				'has_child' => $haschild,
			);
			if ($haschild)
			{
				foreach (self::getExpandedRecipeArray($row['item_id'],$row['qty']) as $childrow) {
					$itemlist[] = $childrow;
				}
			}
		}

		$recipe['recipe_items'] = $itemlist;

		return $recipe['recipe_items'];
	}
}
