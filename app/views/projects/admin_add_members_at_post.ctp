<?php 
$hasError = 0; 
?>
<?php if ($fromImport): ?>
	<h2>Import Members : Completed</h2>
<?php else: ?>
	<h2>Add Project : Completed</h2>
<?php endif ?>
<div class="status_return">
<table>
	<thead>
		<th>Row</th>
		<th>Status</th>
		<th>Message</th>
	</thead>
	<?php foreach($data as $i => $row): ?>
	<tr>
		<td><?php echo $i + 1 ?></td>
		<td><?php echo $row['status'] ? "Success" : "Fail" ?></td>
		<td>
		<?php if (!empty($row['warning'])): ?>
		<p><strong>Warning</strong></p>
		<dl>
			<?php foreach ($row['warning'] as $key => $value) : ?>
			<dt><?=$key  ?></dt>
			<dd><?=$value  ?></dd>
			<?php endforeach; ?>
		</dl>
		<?php endif; ?>
		<?php if(!empty($row['error'])): $hasError++;?>
		<p><strong>Error</strong></p>
		<dl>
			<?php foreach($row['error'] as $field => $err): ?>
			<dt><?php echo Inflector::humanize($field) ?></dt>
			<dd><?php echo $err ?></dd>
			<?php endforeach; ?>
		</dl>
		<?php endif; ?>
		</td>
	</tr>
	<?php endforeach; ?>
</table>
<?php if(!$hasError): ?>
	<?php if ($fromImport): ?>
		<p>Members have been successfully imported.</p>
	<?php else: ?>
		<p>Your project has been successfully created and all members have been imported.</p>
	<?php endif ?>
<?php else: ?>
<?php if ($fromImport): ?>
	<p>We have imported <?php echo count($data) - $hasError ?> out of <?php echo count($data) ?> </p>
<?php else: ?>
	<p>Your project has been successfully created and imported <?php echo count($data) - $hasError ?> out of <?php echo count($data) ?> given members for the project.</p>
<?php endif ?>

<p>Please see above for a list of rows that we have failed to import.</p>
<div class="notice">
	<p>Please seperate those rows out from the uploaded file and ensure that they have all the necessary fields.<br />Import the seperated file again after fixing the data.</p>
</div>
<?php endif; ?>
<div class="c20 actions">
<ul>
	<li><?php echo $this->Html->link("Go to Dashboard", array('action' => 'dashboard', $projectId))?></li>
</ul>
</div>
<div class="c80 actions">
<p>
<?php echo $this->Html->link("Edit Members", array('action' => 'members', $projectId)) ?> | 
<?php echo $this->Html->link("Edit Skills", array('action' => 'skills', $projectId)) ?> | 
<?php echo $this->Html->link("Edit Rules", array('action' => 'rules', $projectId)) ?>
</p>
</div>
</div>