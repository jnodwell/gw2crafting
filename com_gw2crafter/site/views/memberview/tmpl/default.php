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

$apiKey = 'BB68E458-7296-A041-A10A-6810FFFC8BC17766F666-5DBE-4FDB-A6D8-D4920DC25016';

$canEdit = true;
if ($canEdit) {
	$json  = @file_get_contents('https://api.guildwars2.com/v2/account/materials?access_token=' . $apiKey);
	$data  = json_decode($json, true);
	var_dump($data);
?>

<div class="item_fields">

	<table class="table">
		

	</table>

</div>
<?php } ?>
