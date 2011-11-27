<?php 
$projectId = $project['Project']['id'];
$remaining = $data['remaining'];
$total = $data['total']; 
?>

<h1><?php echo $project['Project']['name'] ?> : Dashboard</h1>
<p><?php echo $project['Project']['description'] ?></p>
<hr />
<div class="c70">
<h2>Create Teams</h2>
<?php if(count($remaining) == 0): ?>
<p>You can create teams now</p>
<?php else: ?>
<p>You have some remaining members who has not entered their data yet. 
	Consider 
	<?php echo $this->Html->link("extending the deadline", array('action'=>'settings', $projectId, 'status_to' => PROJECT_COLLECT)) ?>.</p>
<p>You may choose to start creating teams anyway.</p>
<?php endif; ?>
<div class="actions autoWidth">
	<ul>
		<li><?php echo $this->Html->link("Create Teams Now", array('controller' => 'teams', "action" => "create", $projectId)) ?></li>
	</ul>
</div>
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