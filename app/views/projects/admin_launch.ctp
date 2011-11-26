<h1>Launch Status</h1>
<dl>
	<?php foreach ($members as $key => $group): ?>
		<dt><strong><?php echo Inflector::humanize($key) ?> Members</dt>
		<?php foreach ($group as $i => $m): ?>
			<dt><?php echo $m['Member']['name'] ?></dt>
			<dd><?php echo $m['Member']['email'] ?> - <?php echo $m['email'] ? 'Sent' : 'Error' ?></dd>
		<?php endforeach ?>
	<?php endforeach ?>
</dl>
<div class="actions">
	<ul>
		<li><?php echo $this->Html->link("&laquo; Back to Dashboard", array('action' => 'dashboard'), array('escape' => false)) ?></li>
	</ul>
</div>