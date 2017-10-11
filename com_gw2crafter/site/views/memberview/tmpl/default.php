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

$user = JFactory::getUser();
$profile = JUserHelper::getProfile($user->id);
$apiKey = $profile->gw2profile['apikey'];
$canEdit = true;
if (!$apiKey) {
	$canEdit = false;
}
if ($canEdit) {
	$json  = @file_get_contents('https://api.guildwars2.com/v2/account/materials?access_token=' . $apiKey);
	$data  = json_decode($json, true);
?>

<div class="item_fields">

	<table class="table">
		
<?php
foreach($data as $something) {
	var_dump($something);
}
?>
	</table>

</div>
<?php } ?>
