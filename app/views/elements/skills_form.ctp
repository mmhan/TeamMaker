<script type="text/javascript">
var TeamMaker = TeamMaker || {}; 
TeamMaker.skillsForm ={
	tmpl :<?php echo $this->Javascript->object(array(
		SKILL_NUMERIC_RANGE => '#numericRangeTemplate',
		SKILL_TEXT_RANGE => '#textRangeTemplate',
		SKILL_TEXT => '#textTemplate'
	)); ?>,
	constants: {
		NUMERIC_RANGE:<?php echo SKILL_NUMERIC_RANGE ?>,
		TEXT_RANGE:<?php echo SKILL_TEXT_RANGE ?>,
		TEXT: <?php echo SKILL_TEXT ?>
	}
}; 
</script>
<div id="skillTemplate" class="hidden">
<div class="skill colContainer clearfix">
	<div class="c20 skillValuesType">
		<select name="data[Skill][${i}][type]" id="skillType${i}">
			<option value=""> -- Select One --</option>
			<option value="<?php echo SKILL_NUMERIC_RANGE ?>">Numerical Range</option>
			<option value="<?php echo SKILL_TEXT_RANGE ?>">Text Range</option>
			<option value="<?php echo SKILL_TEXT ?>">Text</option>
		</select>
	</div>
	<div class="c80 skillOption colContainer">
	</div>
</div>
</div>
<div id="numericRangeTemplate" class="hidden">
	<div class="c40 skillName">
		<input type="text" id="skillName${i}" name="data[Skill][${i}][name]" />
	</div>
	<div class="c60 skillValueRange numericRange">
		<div class="colContainer clearfix">
			<div class="c10"><label for="skillMin${i}">Min : </label></div>
			<div class="c40"><input type="text" id="skillMin${i}" name="data[Skill][${i}][min]" class="min"/></div>
			<div class="c10"><label for="skillMax${i}">Max : </label></div>
			<div class="c40"><input type="text" id="skillMax${i}" name="data[Skill][${i}][max]" class="max"/></div>
			<input type="hidden" name="data[Skill][${i}][range]" id="skillRange${i}" class="range"/>
		</div>
		<div class="skillTypeDesc tip"><dl>
			<dt>Valid Allowable Values</dt>
			<dd>
				Integers - <strong>0,1,2,10,100,1000</strong> or <br />
				Decimals - <strong>0.0, 5.0, 5.5, 100.0, 99.9</strong>
			</dd>
		</dl></div>
	</div>
</div>

<div id="textRangeTemplate" class="hidden">
	<div class="c40 skillName">
		<input type="text" id="skillName${i}" name="data[Skill][${i}][name]" />
	</div>
	<div class="c60 skillValueRange textRange">
		<input type="text" id="skillRange${i}" name="data[Skill][${i}][range]"/>
		<div class="skillTypeDesc tip">
			<p>
				Please seperate each value using a pipe <strong>|</strong> character. If the values has a certain order, the order should be ascending order.<br />
				e.g: <br />
				<strong>Hopeless|Bad|Okay|Good|Awesome</strong><br /><strong>Sun|Mon|Tue|Wed|Thur|Fri|Sat</strong>
			</p>
		</div>
	</div>
</div>
<div id="textTemplate" class="hidden">
	<div class="c40 skillName">
		<input type="text" id="skillName" name="data[Skill][${i}][name]"/>
	</div>
	<div class="c60 skillValueRange text">
		<div class="colContainer clearfix">
			<div class="c40"><label for="skillRange${i}">Max Character Count</label></div>
			<div class="c60"><input type="text" id="skillRange${i}" name="data[Skill][${i}][range]"/></div>
		</div>
		<div class="skillTypeDesc tip">
			<p>Please enter the maximum number of characters allowed for this field.</p>
		</div>
	</div>
</div>
<fieldset>
	<legend>Skills</legend>
<div id="skillsForm">
	<div class="colContainer">
		<div class="c20">Skill Values Type</div>
		<div class="colContainer c80">
			<div class="c40">Skill Name</div>
			<div class="c60">Skill Value Range</div>			
		</div>
	</div>
	<div class="skills clearfix">
	</div>
	<div class="actions">
	<ul>
		<li><a href="#" class="add">Add More</a></li>
	</ul>
	</div>
</div>
</fieldset>