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