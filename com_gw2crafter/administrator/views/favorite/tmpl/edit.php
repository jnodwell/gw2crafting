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
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . 'media/com_gw2crafter/css/form.css');
?>
<script type="text/javascript">
	js = jQuery.noConflict();
	js(document).ready(function () {
		
	js('input:hidden.gw2_item_id').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('gw2_item_idhidden')){
			js('#jform_gw2_item_id option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_gw2_item_id").trigger("liszt:updated");
	js('input:hidden.joomla_user_id').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('joomla_user_idhidden')){
			js('#jform_joomla_user_id option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_joomla_user_id").trigger("liszt:updated");
	});

	Joomla.submitbutton = function (task) {
		if (task == 'favorite.cancel') {
			Joomla.submitform(task, document.getElementById('favorite-form'));
		}
		else {
			
			if (task != 'favorite.cancel' && document.formvalidator.isValid(document.id('favorite-form'))) {
				
				Joomla.submitform(task, document.getElementById('favorite-form'));
			}
			else {
				alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
			}
		}
	}
</script>

<form
	action="<?php echo JRoute::_('index.php?option=com_gw2crafter&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" enctype="multipart/form-data" name="adminForm" id="favorite-form" class="form-validate">

	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_GW2CRAFTER_TITLE_FAVORITE', true)); ?>
		<div class="row-fluid">
			<div class="span10 form-horizontal">
				<fieldset class="adminform">

									<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
				<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
				<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
				<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
				<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

				<?php echo $this->form->renderField('created_by'); ?>
				<?php echo $this->form->renderField('modified_by'); ?>				<?php echo $this->form->renderField('gw2_item_id'); ?>

			<?php
				foreach((array)$this->item->gw2_item_id as $value): 
					if(!is_array($value)):
						echo '<input type="hidden" class="gw2_item_id" name="jform[gw2_item_idhidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>				<?php echo $this->form->renderField('gw2_name'); ?>
				<?php echo $this->form->renderField('joomla_user_id'); ?>

			<?php
				foreach((array)$this->item->joomla_user_id as $value): 
					if(!is_array($value)):
						echo '<input type="hidden" class="joomla_user_id" name="jform[joomla_user_idhidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>

					<?php if ($this->state->params->get('save_history', 1)) : ?>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('version_note'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('version_note'); ?></div>
					</div>
					<?php endif; ?>
				</fieldset>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php if (JFactory::getUser()->authorise('core.admin','gw2crafter')) : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'permissions', JText::_('JGLOBAL_ACTION_PERMISSIONS_LABEL', true)); ?>
		<?php echo $this->form->getInput('rules'); ?>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
<?php endif; ?>

		<?php echo JHtml::_('bootstrap.endTabSet'); ?>

		<input type="hidden" name="task" value=""/>
		<?php echo JHtml::_('form.token'); ?>

	</div>
</form>
