<script type="text/javascript">
var TeamMaker = TeamMaker || {}; 
TeamMaker.skillsType = <?php echo $this->Javascript->object(array(
	'NUMERIC_RANGE' => SKILL_NUMERIC_RANGE,
	'TEXT_RANGE' => SKILL_TEXT_RANGE,
	'TEXT' => SKILL_TEXT
)); ?>;
</script>
<div id="skillTemplate" class="hidden">
<div class="skill" data-index="${i}" class="">
	<div class="c20 skillValuesType">
		<select name="data[Skill][${i}][type]" id="skillType${i}">
			<option value="<?php echo SKILL_NUMERIC_RANGE ?>">Numerical Range</option>
			<option value="<?php echo SKILL_TEXT_RANGE ?>">Text Range</option>
			<option value="<?PHP echo SKILL_TEXT ?>">Text</option>
		</select>
	</div>
	<div class="c20 skillName">
	</div>
	<div class="c60 skillValueRange">
	</div>
</div>
</div>

<div 

<fieldset>
	<legend>Skills</legend>
<div id="skillsForm">
	<div class="colContainer">
		<div class="c20">Skill Values Type</div>
		<div class="c20">Skill Name</div>
		<div class="c60">Skill Value Range</div>
	</div>
	<div class="skills">
	</div>
	<div class="actions">
	<ul>
		<li><a href="#">Add More</a></li>
	</ul>
	</div>
</div>
</fieldset>