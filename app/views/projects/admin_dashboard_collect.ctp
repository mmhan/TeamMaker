<?php $projectId = $project['Project']['id']; ?>

<h1><?php echo $project['Project']['name'] ?> : Dashboard</h1>
<p><?php echo $project['Project']['description'] ?></p>
<hr />
<div class="c70">
<h2>Remaining Users</h2>
<table>
	<tr>
		<th>Name</th>
		<th>Action</th>
	</tr>
	<?php foreach ($remaining as $i => $member): ?>
	<tr>
		<td><?php echo $member['Member']['name'] ?></td>
		<td><?php //TODO: implement this later 
			//echo $this->Html->link("Remind", array('action' => 'remind', $member['Member']['id'])) ?>
			<?php echo $this->Html->link("Remind", "mailto:" . $member['Member']['email']) ?>
		</td>
	</tr>
	<?php endforeach ?>
</table>
</div>
<div class="c30">
	<div class="projMgmt">
		<h3 class="dataStatus">Data Status</h3>
		<p class="tips"><?php echo $total - count($remaining) ?> / <?php echo count($remaining) ?></p>
		<p>&nbsp;</p>
		<p class="tips">Cut-off date is on <?php echo $this->Time->niceShort($project['Project']['collection_end']); ?></p>
	</div>
	<?php echo $this->element('projects/settings', array('id' => $projectId)); ?>
	<?php echo $this->element('projects/rules', array('id' => $projectId)); ?>
	
</div>