<?php FireCake::log($remaining) ?>
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
		<td><?php echo $this->Html->link("Remind", array('action' => 'remind', $member['Member']['id'])) ?></td>
	</tr>
	<?php endforeach ?>
</table>
</div>
<div class="c30">
	
</div>