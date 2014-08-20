<?php
/* @var $this DailyReportController */
/* @var $model DailyReport */

$this->breadcrumbs=array(
	'Daily Reports'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List DailyReport', 'url'=>array('index')),
	array('label'=>'Create DailyReport', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#daily-report-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<div class="row">
	<div id="container-highchart" class="span12">
	<?php
	$this->Widget('ext.highcharts.HighstockWidget', array(
		'options'=>array(
			'chart'         => array( 'type' => 'area' ),
			'title'         => array( 'text' => ''),
			'rangeSelector' => array( 'enabled' => false ),
			'navigator'     => array( 'enabled' => false ),
			'scrollbar'     => array( 'enabled' => false ),
			'tooltip'       => array( 'crosshairs'=>'true', 'shared'=>'true' ),
			'legend'        => array(
				'align'           =>  'left',
				'borderWidth'     =>  1,
				'backgroundColor' => '#FFFFFF',
				'enabled'         =>  true,
				'floating'        =>  true,
				'layout'          => 'horizontal',
				'verticalAlign'   =>  'top',
	        	),
			
			'xAxis' => array( 
				'title' => array('text' => ''), 
				'categories' => array('14-07-2014', '15-07-2014', '16-07-2014', '17-07-2014', '18-07-2014', '19-07-2014', '20-07-2014', '21-07-2014')
				),
			'yAxis' => array( 'title' => array('text' => '') ),
			'series' => array(
				array(
					'name' => 'Spend',
					'data' => array( 10.22, 22.2 , 0, 10, 34, 45, 20, 15 ),
					),
				array(
					'name' => 'Conv',
					'data' => array( 02, 94, 124, 5, 82, 82, 82, 82 ),
					),
				array(
					'name' => 'Impressions', 
					'data' => array( 1022, 9993, 1012, 1000, 1498, 2498, 1298, 2698 ),
					),
				array(
					'name' => 'Clicks', 
					'data' => array(422, 393, 612, 500, 298, 398, 198, 408 ),
					),
				),
			)
		)
	);
	?>
	</div>
</div>

<hr>

<div class="botonera">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'type'        => 'info',
		'label'       => 'Add Daily Report Manualy',
		'block'       => false,
		'buttonType'  => 'ajaxButton',
		'url'         => 'create',
		'ajaxOptions' => array(
			'type'    => 'POST',
			'success' => 'function(data)
				{
	                    console.log(this.url);
		                //alert("create");
						$("#modalDailyReport").html(data);
						$("#modalDailyReport").modal("toggle");
				}',
			),
		'htmlOptions' => array('id' => 'createAjax'),
		)
	); ?>
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'type'        => 'info',
		'label'       => 'Excel Report',
		'block'       => false,
		'buttonType'  => 'link',
		'url'         => 'excelReport',
		'htmlOptions' => array('id' => 'excelReport'),
		)
	); ?>
</div>


<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id'                       => 'daily-report-grid',
	'dataProvider'             => $model->search(),
	'filter'                   => $model,
	'selectionChanged'         => 'js:selectionChangedDailyReport',
	'type'                     => 'striped condensed',
	'rowHtmlOptionsExpression' => 'array("data-row-id" => $data->id, "data-row-net-id" => $data->networks_id, "data-row-c-id" => $data->campaigns_id)',
	'template'                 => '{items} {pager} {summary}',
	'columns'                  => array(
		array(
			'name'  =>	'id',
        	'headerHtmlOptions' => array('style' => 'width: 50px'),
        	'htmlOptions'	=> array( 'class' =>  'id'),
		),
		array(
			'name'  => 'account_manager',
			'value' => '$data->campaigns->opportunities->accountManager ? $data->campaigns->opportunities->accountManager->lastname . " " . $data->campaigns->opportunities->accountManager->name : ""',
        	'headerHtmlOptions' => array('style' => 'width: 120px'),
        	'htmlOptions'	=> array( 'class' =>  'id'),
		),
		array(
			'name'  => 'campaign_name',
			'value' => 'Campaigns::model()->getExternalName($data->campaigns_id)',
			'headerHtmlOptions' => array('style' => 'width: 120px'),
		),
		array(
			'name'  =>	'network_name',
			'value'	=>	'$data->networks->name',
		),
		array(	
			'name'	=>	'imp',
        ),
        array(
        	'name'	=>	'clics',
        ),
		array(
        	'name'	=>	'conv_api',
        	'htmlOptions'=>array('style'=>'width: 70px'),
        ),
		array(
        	'name'	=>	'conv_adv',
        	'type'	=>	'raw',
			'htmlOptions'=>array('style'=>'width: 70px'),
        	'value' =>	'
        			CHtml::textField("row-" . $row, $data->conv_adv, array(
        				"style" => "width:35px;", 
        				"onkeydown" => "$( \"#row-\" + $row ).parents( \"tr\" ).addClass( \"control-group error\" );" 
        				)) . " " .
        			CHtml::ajaxLink(
            				"<i class=\"icon-pencil\"></i>",
	            			Yii::app()->controller->createUrl("updateColumn"),
	        				array(
								"type"     => "POST",
								"dataType" => "json",
								"data"     => array( "id" => "js:$.fn.yiiGridView.getKey(\"daily-report-grid\", $row)",	 "newValue" => "js:$(\"#row-\" + $row).val()" ) ,
								"success"  => "function( data )
									{
										// change css properties
										$( \"#row-\" + $row ).parents( \"tr\" ).removeClass( \"control-group error\" );
										$( \"#row-\" + $row ).parents( \"tr\" ).addClass( \"control-group success\" );
									}",
								),
							array(
								"style"               => "width: 20px",
								"rel"                 => "tooltip",
								"data-original-title" => "Update"
								)
						)
					',
        ),
		array(
        	'name'	=>	'spend',
        	'value'	=>	'"$ " . $data->spend',
        ),
		// array(
		// 	'name'	=>	'model',
		// ),
		// array(
		// 	'name'	=>	'value',
        // ),
		array(
        	'name'	=>	'date',
        	'value'	=>	'date("d-m-Y", strtotime($data->date))',
        	'htmlOptions'	=> array( 'class' =>  'date'),
        ),
        array(
			'class'             => 'bootstrap.widgets.TbButtonColumn',
			'headerHtmlOptions' => array('style' => "width: 70px"),
			'buttons'           => array(
				'delete' => array(
					'visible' => '! $data->is_from_api',
				),
				'updateAjax' => array(
					'label'   => 'Update',
					'icon'    => 'pencil',
					'visible' => '! $data->is_from_api',
					'click'   => '
				    function(){
				    	// get row id from data-row-id attribute
				    	var id = $(this).parents("tr").attr("data-row-id");
				    	// use jquery post method to get updateAjax view in a modal window
				    	$.post(
						"update/"+id,
						"",
						function(data)
							{
								//alert(data);
								$("#modalDailyReport").html(data);
								$("#modalDailyReport").modal("toggle");
							}
						)
				    }
				    ',
				),
			),
			'template' => '{updateAjax} {delete}',
		),
	),
)); ?>

<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'modalDailyReport')); ?>

		<div class="modal-header"></div>
        <div class="modal-body"><h1>Campaigns</h1></div>
        <div class="modal-footer"></div>

<?php $this->endWidget(); ?>

<div class="row" id="blank-row">
</div>