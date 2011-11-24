<h1><?php echo $project['Project']['name'] ?> : Dashboard</h1>
<p><?php echo $project['Project']['description'] ?></p>
<hr />

<div class="clearfix">
	<div class="c25 projMgmt">
		<h3 class="members"><?php echo $this->Html->link("Members", array('action' => 'members', $project['Project']['id'])) ?></h3>
		<p class="tips">Edit the list of members that will be included in this project.</p>
	</div>
	<div class="c25 projMgmt">
		<h3 class="skills"><?php echo $this->Html->link("Skills", array('action' => 'skills', $project['Project']['id'])); ?></h3>
		<p class="tips">Edit the list of skills that will be collected and/or imported for this project.</p>
	</div>
	<div class="c25 projMgmt">
		<h3 class="rules"><?php echo $this->Html->link("Rules", array('action' => 'rules', $project['Project']['id'])); ?></h3>
		<p class="tips">Edit and preview the list of rules that the members will be judged against.</p>
	</div>
	<div class="c25 projMgmt">
		<h3 class="settings"><?php echo $this->Html->link("Settings", array('action' => 'settings', $project['Project']['id'])); ?></h3>
		<p class="tips">Change cut-off dates, deadlines, Add project admins.</p>
	</div>
</div>