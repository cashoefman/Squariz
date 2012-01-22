<?php echo '<?xml version="1.0" ?>';?>

<grammar xmlns="http://www.w3.org/2001/06/grammar" xml:lang="en-US" root = "MYRULE">

<rule id="MYRULE" scope="public">
	<one-of>
		<?php foreach($friends as $friend):?>
		<item><?php echo $this->sanitize($friend->firstName . ' '.$friend->lastName)?><tag><?php echo $friend->id?></tag></item>
		<?php endforeach;?>
	</one-of>
</rule>

</grammar>