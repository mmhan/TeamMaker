<?php 
$skillsOptions = Set::combine($skills, "{n}.id", "{n}.name");
?>

<script type="text/javascript">
var TeamMaker = TeamMaker || {};
TeamMaker.Rules = {
	view :{
		container: "#rules",
		ruleTmpl: "#ruleTmpl",
		tmpl :<?php echo $this->Javascript->object(array(
			SKILL_NUMERIC_RANGE => '#numericRangeTemplate',
			SKILL_TEXT_RANGE => '#textRangeTemplate',
			SKILL_TEXT => '#textTemplate'
		)); ?>,
		filterValTmpl: "#filterValueTemplate",
		textRangeFilterValTmpl: "#textRangeFilterValueTemplate",
		constants: {
			NUMERIC_RANGE:<?php echo SKILL_NUMERIC_RANGE ?>,
			TEXT_RANGE:<?php echo SKILL_TEXT_RANGE ?>,
			TEXT: <?php echo SKILL_TEXT ?>
		}
	},
	data: {
		members: <?php echo $this->Javascript->object($members) ?>,
		skills: <?php echo $this->Javascript->object($skills) ?>
	}
}; 
</script>

<div class="hidden" id="rulesTmpl">
	<div id="ruleTmpl">
		<div class="rule clearfix" data-index="${i}">
			<div class="c20 skillSelect">
				<?php echo $this->Form->input(
					'Project.rule.${i}.type', 
					array(
						'options' => $skillsOptions, 
						'label' => 'Skill',
						'empty' => "-- Select One --"
					)
				); ?>
			</div>
			<div class="c70 ruleConditions">&nbsp;</div>
			<div class="c10">
				<ul class="rearrangeBtns ui-widget ui-helper-clearfix">
					<li class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-triangle-1-s moveDown" title="Move Down"></span></li>
					<li class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-triangle-1-n moveUp" title="Move Up"></span></li>
					<li class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-trash remove" title="Remove"></span></li>
				</ul>
			</div>
		</div>
	</div>
	<div id="numericRangeTemplate">
		<div class="c25 filterType numFilterType">
			<?php echo $this->Form->input('Project.rule.${i}.filter_type', array(
				'options' => array(
					"between" => "Between",
					"gt" => "Greater Than",
					"lt" => "Less Than",
					"gtet" => "Greater Than or Equal To",
					"ltet" => "Less Than or Equal To",
					"is" => "Is"
				),
				'empty' => "-- Select One --"
			))?>
		</div>
		<div class="c75 filterValue numRangeFilterValue">
		</div>
	</div>
	<div id="textRangeTemplate">
		<div class="c25 filterType textRangeFilterType">
			<?php echo $this->Form->input('Project.rule.${i}.filter_type', array(
				'options' => array(
					"between" => "Between",
					"gt" => "Greater Than",
					"lt" => "Less Than",
					"gtet" => "Greater Than or Equal To",
					"ltet" => "Less Than or Equal To",
					"is" => "Is"
				),
				'empty' => "-- Select One --"
			))?>
		</div>
		<div class="c75 filterValue textRangeFilterValue">
		</div>
	</div>
	<div id="textTemplate">
		<div class="c25 filterType textFilterType">
			<?php echo $this->Form->input('Project.rule.${i}.filter_type', array(
				'options' => array(
					'is' => 'Is',
					'!is' => "Is not",
					'contains' => "Contains",
					"!contains" => "Does not Contain",
					"matches" => "Matches RegExp"
				),
				'empty' => ' -- Select One -- '
			)) ?>
		</div>
		<div class="c75 filterValue textFilterValue">
		</div>
	</div>
	<div id="filterValueTemplate">
		<div class="input text">
			<label for="ProjectRuleFilterValue_${i}_${j}">${label}</label>
			<input type="text" name="data[Project][rule][${i}][filter_value][${j}]" id="ProjectRuleFilterValue_${i}_${j}" />
		</div>
	</div>
	<div id="textRangeFilterValueTemplate">
		<div class="input select">
			<label for="ProjectRuleFilterValue_${i}_${j}">${label}</label>
			<select name="data[Project][rule][${i}][filter_value][${j}]" id="ProjectRuleFilterValue_${i}_${j}"></select>
		</div>
	</div>
</div>
<?php echo $this->Javascript->link("make_team", false); ?>
<h1>Create/Edit Teams</h1>

<?php echo $this->Form->input("Project.rule.num", 
	array(
		'type' => 'text', 
		'label' => "Each team should have ",
		'after' => " number of members.",
		'div' => 'input text numberOfMembers'
	)); ?>
<div class="rulesContainer">
	<h3>Rules</h3>
	<div id="rules">
		
	</div>
	<ul class="actionsBtns ui-widget ui-helper-clearfix">
		<li class="">
			<?php echo $this->Html->link(
				$this->Html->tag("span", "", array('class' => 'ui-icon ui-icon-plusthick')) . "Add More", 
				"#", 
				array(
					'id' => "addMoreRule", 'class' => 'ui-state-default ui-corner-all',
					'escape' => false
				)
			) ?></li>
		<li class="">
			<?php echo $this->Html->link(
				$this->Html->tag("span", "", array('class' => 'ui-icon ui-icon-gear')) . "Generate Team", 
				"#", 
				array(
					'id' => "generateTeam", 'class' => 'ui-state-default ui-corner-all',
					'escape' => false
				)
			) ?></li>
	</ul>
</div>