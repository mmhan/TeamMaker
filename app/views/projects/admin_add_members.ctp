<?php
$fromImport = isset($this->params['named']['from_import']) && $this->params['named']['from_import'];
?>

<script type="text/javascript">
var TeamMaker = TeamMaker || {};
TeamMaker.UserFields = <?php echo $this->Javascript->object($userTableFields); ?>;
TeamMaker.SkillFields = <?php echo $this->Javascript->object($skillFields); ?>
</script>
<div class="projects">
<div class="importForm">
<?php 
echo $this->Form->create('Project', 
array(
	'url' =>array('action' =>'add_members', 'admin' => true, $this->data['Project']['id'], $this->data['Upload']['id']),
	'data-status' => Router::url(array('action' => 'add_members_status', 'admin' => true))	
));?>
	<fieldset>
 		<legend><?php __( ($fromImport) ? 'Import Members' : 'New Project : Step 2'); ?></legend>
		<p>Please map the imported fields with existing attributes of the database.</p>
		<?php
			echo $this->Form->input('id');
			echo $this->Form->hidden('Upload.id');
			echo $this->Form->hidden('from_import', array('value' => $fromImport));
		?>
		<br />
		<table>
			<thead>
				<th class="importedFieldName">Imported Field Names</th>
				<th>Action</th>
				<th>Existing Field Name</th>
			</thead>
			<?php foreach($importedFields as $i => $field): ?>
			<tr class="importFields" data-index="<?php echo $i ?>" data-fieldname="<?php echo $field ?>">
				<td class="importedFieldName">
					<?php echo $field ?> 
					<?php echo $this->Form->hidden("Import.$i.field_name", array('value' => $field)); ?>
				</td>
				<td class="actions">
				<?php echo $this->Form->input("Import.$i.action", array(
					'options' => array(
						'discard' => 'Discard',
						'mapField' => 'Maps To : ',
						'isSkill' => "is a skill called : "
					),
					'label' => false, 'div' => false,
					'value' => 'discard',
					'class' => 'memberImportActions'
				)); ?>
				</td>
				<td class="actionOptionsContainer"></td>
			</tr>
			<?php endforeach; ?>
		</table>
		
	</fieldset>
<?php echo $this->Form->submit(
	$fromImport ? 
		__("Submit", true) : 
		__("Next", true) . ' &raquo;', 
		array('escape' => false)
); ?> 
<?php echo $this->Form->end();?>
<?php echo
	$fromImport ? '' :  
	$this->Html->link('Skip this step &raquo;', array('action' => 'index'), array('escape' => false)); ?> 
</div>
<div class="importStatus" style="display:none;">
	<h2>Importing members</h2>
	<div id="progressIndicator">
		<div class="progress"></div>
		<span id="progressTxt"></span>
	</div>
</div>
<div id="status" style="display:none;">
	<p>Please wait...</p>
</div>
</div>