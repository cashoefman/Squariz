<?php $this->pageHeader = 'Make the real world easier to use <br /><small>Keep up with friends, Discover where your friends are with a phone call or an SMS</small>' ?>

<div style="text-align: center;">
	<?php if(!empty($message)):?>
	<div class="alert-message warning">
        <p><?php echo $message?></p>
    </div>
    <?php endif;?>

	<img src="/resources/signup.png" alt="" />
	
	<br />
	
	<?php echo CHtml::link('Signup', array('site/auth'), array('class'=>'btn large primary'))?>
</div>