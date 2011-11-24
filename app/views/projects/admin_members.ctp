
<div class="projectsAdminMembers">
<h1><?php echo $this->data['Project']['name'] ?> : Edit Members</h1>
<div class="colContainer">
	<div class="c30">
		<?php echo $this->Form->create("Project", array('action' => 'members')) ?>
		<fieldset>
			<legend>Add/Remove Existing Members</legend>
			<?php echo $this->Form->input("id"); ?>
			<?php echo $this->Form->input("Member.Member", array('div' => 'input select habtmSelector')); ?>
		</fieldset>
		<?php echo $this->Form->submit("Save Members") ?>
		<?php echo $this->Form->end(); ?>
	</div>
	<div class="c30">
		<?php echo $this->Form->create("Project", array('action' => 'import_file', 'type' => 'file')); ?>
		<fieldset>
			<legend>Import CSV file</legend>
			<?php echo $this->Form->input("id"); ?>
			<?php echo $this->Form->input("Upload.0.file", 
			array(
				'type' => 'file', 
				'label' => 'CSV File Import to Users' 
					. $this->Html->tag('span', "(Optional: You can add users later.)", array('class' => 'tip'))
				)
			); ?>
		</fieldset>
		<?php echo $this->Form->submit('Submit'); ?>
		<?php echo $this->Form->end(); ?>
	</div>
	<div class="c30">
		<fieldset id="" class="">
		  	<legend>Can't find a member?</legend>
		  	<div class="actions autoWidth">
		  		<ul class="">
					<li><?php echo $this->Html->link("Add New Member", array('action' => 'add', 'controller' => 'users', 'group_id' => ROLE_MEMBER, 'project_id' => $this->data['Project']['id'])); ?></li>
				</ul>				
			</div>
		</fieldset>
	</div>
</div>
<?php echo $this->Html->link("&laquo; Back to Dashboard", array('action' => 'dashboard', $this->data['Project']['id']), array('escape' => false)) ?>
</div>