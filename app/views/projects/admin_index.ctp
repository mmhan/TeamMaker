<div class="projects">
	<h2><?php __('Projects');?></h2>
	<div class="clearfix"><div class="actions fRight">
		<ul>
			<li><?php echo $this->Html->link("Add Project", array('action' => 'add', 'admin' => true)); ?></li>
		</ul>
	</div></div>
	<div class="clearfix">
		<div class="c25">
			<h3>Seeding Phase</h3>
			<p>Newly created projects, members have not received notification to fill in their skills yet.</p>
			<?php if(array_key_exists(PROJECT_SEED,$projects) && !empty($projects[PROJECT_SEED])): ?>
			<ul class="projects seed">			
				<?php foreach($projects[PROJECT_SEED] as $proj): ?>
				<li><div class="project" data-id="<?php echo $proj['id'] ?>" >
					<h3><?php echo $proj['name'] ?></h3>
					<div class="actions">
						<ul>
							<li><?php echo $this->Html->link('Go to Project &raquo;', array('action' => "dashboard", $proj['id']), array('escape' => false)); ?></li>
						</ul>
					</div>
				</div></li>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>
		</div>
		<div class="c25">
			<h3>Collection Phase</h3>
			<p>Launched Projects, members have been notifed to fill in their skills data and nomination data.</p>
		</div>
		<div class="c25">
			<h3>Grouping Phase</h3>
			<p>Projects with members who are ready to be divided into teams and Projects with teams divided awaiting feedbacks from users.</p>
		</div>
		<div class="c25">
			<h3>Archived Phase</h3>
			<p>These projects has been fixed by admin and no longer receiving feedbacks.</p>
		</div>
	</div>
</div>