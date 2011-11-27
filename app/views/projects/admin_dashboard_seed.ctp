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
	<div class="c25"><?php echo $this->element('projects/rules', array('id' => $project['Project']['id'])) ?></div>
	<div class="c25"><?php echo $this->element('projects/settings', array('id' => $project['Project']['id'])) ?></div>
</div>
<div class="launch">
<?php echo $this->Form->create("Project", array('action' => 'launch')) ?>
<?php echo $this->Form->input('id', array('value' => $project['Project']['id'])) ?>

<div class="message">
<p><strong>Warning</strong></p>
<p>Notification emails will be sent out to all new users; asking them to sign in, fill in their skills and nomination data.</p>
<p>You will no longer be able to add new users or skills after launching.</p>
</div>

<?php echo $this->Form->submit("Launch Project") ?></div>


<?php echo $this->Form->end(); ?>
</div>