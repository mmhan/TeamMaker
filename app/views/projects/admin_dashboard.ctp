<h1><?php echo $project['Project']['name'] ?> : Dashboard</h1>
<p><?php echo $project['Project']['description'] ?></p>
<hr />

<div class="clearfix">
	<div class="c25 projMgmt">
		<h3 class="settings"><?php echo $this->Html->link("Settings", array('action' => 'settings', $project['Project']['id'])); ?></h3>
		<p class="tips">Change cut-off dates, deadlines, Add project admins.</p>
	</div>
</div>