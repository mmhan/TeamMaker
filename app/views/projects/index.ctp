<div class="projects">
	<h2><?php __('Projects');?></h2>
	<div class="clearfix">
		<div class="c50">
			<h3>Collection Phase</h3>
			<p>The following projects are currently requesting for your skills data and nominations</p>
			<?php if (array_key_exists(PROJECT_COLLECT, $projects) && !empty($projects[PROJECT_COLLECT])): ?>
			<ul class="projects collect">
				<?php foreach ($projects[PROJECT_COLLECT] as $k => $proj): ?>
				<li>
					<li><div class="project" data-id="<?php echo $proj['id'] ?>" >
						<h3><?php echo $proj['name'] ?></h3>
						<div class="actions">
							<ul>
								<li><?php echo $this->Html->link('Enter Data &raquo;', array('action' => "enter_data", $proj['id']), array('escape' => false)); ?></li>
								<li><?php echo $this->Html->link('Nominate Members &raquo;', array('action' => "nominate", $proj['id']), array('escape' => false)); ?></li>
							</ul>
						</div>
					</div></li>
				</li>
				<?php endforeach ?>
			</ul>			
			<?php endif ?>
		</div>
		<div class="c25">
			<h3>Grouping Phase</h3>
			<p></p>
		</div>
		<div class="c25">
			<h3>Archived Phase</h3>
			<p>These projects has been fixed by admin and no longer receiving feedbacks.</p>
		</div>
	</div>
</div>