<?php
$this->breadcrumbs=array(
	'Sites'=>array('index'),
	'Manage',
);

$this->menu=array(
array('label'=>'List Sites','url'=>array('index')),
array('label'=>'Create Sites','url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
$('.search-form').toggle();
return false;
});
$('.search-form form').submit(function(){
$.fn.yiiGridView.update('sites-grid', {
data: $(this).serialize()
});
return false;
});
");
?>

<?php 
/*
echo '
<h1>Manage Sites</h1>

<p>
	You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>
		&lt;&gt;</b>
	or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>
'

echo CHtml::link('Advanced Search','#',array('class'=>'search-button btn')); ?>
<div class="search-form" style="display:none">
	<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); 
echo '</div><!-- search-form -->'
*/
?>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
'id'=>'sites-grid',
'dataProvider'=>$model->search(),
'filter'=>$model,
'columns'=>array(
		'id',
		'name',
		'publishers_providers_id',
array(
'class'=>'bootstrap.widgets.TbButtonColumn',
),
),
)); ?>
