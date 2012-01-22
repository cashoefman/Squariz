<?php $this->pageHeader = 'Your profile' ?>

<div class="form form-stacked" style="background-image: url('/resources/update.png'); background-position: right bottom; background-repeat: no-repeat;">
<?php echo CHtml::beginForm()?>
<fieldset>

<?php echo CHtml::errorSummary($model, '', '', array('class'=>'alert-message block-message error'))?>

<?php if(!empty($message)):?>
	<div class="alert-message info">
	<p><?php echo $message?></p>
	</div>
<?php endif;?>

<p class="">
	<?php echo CHtml::activeLabel($model, 'fname')?>
	<?php echo CHtml::activeTextField($model, 'fname')?>
</p>

<p class="">
	<?php echo CHtml::activeLabel($model, 'lname')?>
	<?php echo CHtml::activeTextField($model, 'lname')?>
</p>

<br />

<h3>Caller id</h3>
<p class="hint">You need to set up the following information precisely so that we can recognize you.</p>

<p class="">
	<?php echo CHtml::activeLabel($model, 'skype')?>
	<?php echo CHtml::activeTextField($model, 'skype')?>
</p>

<p class="">
	<?php echo CHtml::activeLabel($model, 'phone')?>
	<?php echo CHtml::activeTextField($model, 'phone')?>
	<span class="help-block">
		<strong>Example:</strong> +13126273743
    </span>
</p>

<p class="">
	<?php echo CHtml::activeLabel($model, 'email')?>
	<?php echo CHtml::activeTextField($model, 'email')?>
</p>

<?php echo CHtml::submitButton('Save', array('class'=>'btn primary'))?> &nbsp;
<?php echo CHtml::link('Delete account', '#', array('class'=>'btn danger', 'data-controls-modal'=>"delete_dialog", 'data-backdrop'=>'true'))?>


</fieldset>
<?php echo CHtml::endForm()?>
</div>

<script>
$(document).ready(function(){
	$('#delete_dialog .close-dialog').live('click', function(){
		$('#delete_dialog').modal('hide')
	});
});
</script>

<div class="modal hide fade" id="delete_dialog">
	<div class="modal-header">
		<a class="close" href="#">x</a>
		<h3>Delete account</h3>
	</div>
	<div class="modal-body">
		<p>You are about to delete a profile. Are you sure?</p>
	</div>
	<div class="modal-footer">
		<?php echo CHtml::beginForm(array('site/delete'))?>
		
		<?php echo CHtml::hiddenField('del_key', $model->token)?>
		
		<a href="#" class="btn secondary close-dialog">No</a> 
		<?php echo CHtml::submitButton('Yes', array('class'=>'btn danger', 'name'=>'delete'))?>		
		
		<?php echo CHtml::endForm()?>
	</div>
</div>