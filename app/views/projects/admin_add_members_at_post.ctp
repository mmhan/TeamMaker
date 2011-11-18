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
		<?php if(!empty($row['error'])): ?>
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
</div>