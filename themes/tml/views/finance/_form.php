<?php
/* @var $model TransactionCount */
?>

<div class="modal-header">
	<a class="close" data-dismiss="modal">&times;</a>
	<h4>Transaction Count</h4>
</div>


<div class="modal-body">
	<?php 
	reset($countries);
	$first_country = key($countries);
	if (FilterManager::model()->isUserTotalAccess('clients.validateIo')){
		$form = $this->beginWidget('bootstrap.widgets.TbActiveForm',array(
			'id'                   =>'transaction-count-form',
			// 'type'                 =>'inline',
			'htmlOptions'          =>array('class'=>'well'),
			'action'               =>$this->createUrl('finance/addTransaction/'),
			// to enable ajax validation
			'enableAjaxValidation' =>false,
			//'clientOptions'        =>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
		)); 
		echo '<fieldset>';
			$month=date('m', strtotime($period));
			$year=date('Y', strtotime($period));
			$startDate=date('Y-m-d', strtotime($year.'-'.$month.'-01'));
			$endDate=date('Y-m-d', strtotime($year.'-'.$month.'-31'));
			echo KHtml::filterCountries(NULL,array(),$id,'carrier',false);
			echo KHtml::filterCarrier(NULL,NULL,array(),$first_country,'carrier');
			//echo $form->dropDownList($model,'carrier',$carriers); 
			echo KHtml::filterProduct(NULL,array(),$id,false);
			echo $form->textFieldRow($model, 'volume', array('class'=>'span3')); 
			echo $form->textFieldRow($model, 'rate', array('class'=>'span3')); 
			// echo $form->hiddenField($model, 'opportunities_id',array('value'=>$id)); 
			echo $form->hiddenField($model, 'finance_entities_id',array('value'=>$id)); 
			echo $form->hiddenField($model, 'period',array('value'=>$period)); 
			echo $form->hiddenField($model, 'date',array('value'=>date('Y-m-d H:i:s', strtotime('NOW')))); 
			echo $form->hiddenField($model, 'users_id',array('value'=>Yii::App()->user->getId())); 
		
		echo '<br>';
	 	$this->widget('bootstrap.widgets.TbButton', array(
				'buttonType'  => 'ajaxSubmit',
				'type'        => 'primary',
				'label'       => 'Add',
				'url'         => $this->createUrl('finance/addTransaction/'),
				'htmlOptions' => array('name' => 'submit', 'id'=>'submitAddTransactionCount'),
				'ajaxOptions' => array(
						'type'       => 'post',
						'beforeSend' => 'function(){$("body").undelegate("#submitAddTransactionCount","click");}',
						'success'    => 'js:function(data){
							console.log("Return: "+data);
		                	$.fn.yiiGridView.update("transaction-count-grid");
		            	}',
		        )

			)); 
		echo '</fieldset> ';
		$this->endWidget(); 
	}
	?>

	<?php 
            $this->widget('yiibooster.widgets.TbExtendedGridView', array(
			'id'           => 'transaction-count-grid',
			'dataProvider' => $model->getTransactions($id,$period),
			'type'         => 'striped bordered',    
			'template'     => '{items} {pager} {summary}',
			'columns'      => array(
                array('name'              =>'id'),
                // array('name'              =>'ios_id'),
                array('name'              =>'carriers_id_carrier', 'value'=>'$data->getCarrier()'),
                array('name'              =>'country', 'value'=>'$data->getCountry()'),
                array('name'              =>'product'),
                array('name'              =>'period'),
                array('name'              =>'volume'),
                array('name'              =>'rate'),
                array('name'              =>'users_id', 'value'=>'$data->getUserName()'),
                array('name'              =>'date'),
                array('name'              =>'delete', 
                	  'type'			  =>'raw', 
					  'header'            =>false,
				      'filter'            =>false,
					  'headerHtmlOptions' => array('width' => '5'),
					  'htmlOptions'		  =>array('style'=>'text-align:left !important'),
                	  'value' 			  =>'CHtml::link(
												"<i class=\"icon-remove\"></i>",
												array(),
							    				array("data-toggle"=>"tooltip", "data-original-title"=>"Delete", "class"=>"linkinvoiced",  
							    					"onclick" => 
							    					"js:bootbox.confirm(\"Are you sure?\", function(confirmed){
							    						if(confirmed){
									    					$.post(\"delete/".$data["id"]."\",{})
									                            .success(function( data ) {
									                				$.fn.yiiGridView.update(\"transaction-count-grid\");
										                            // alert(data );
										                            // window.location = document.URL;
									                            });
															}
														 })
													")
												);',
                ),
    			// array(
				// 	    'class'=>'CButtonColumn',
				// 	    'template'=>'{delete}',
				// )
            ),
        )); ?>
</div>

<div class="modal-footer">
	Fields with <span class="required">*</span> are required.
</div>

<?php Yii::app()->clientScript->registerScript('verifedIcon', "
					$('.linkinvoiced').click(function(e){
                            e.preventDefault();
                            
                        });
                    ", CClientScript::POS_READY); ?>
