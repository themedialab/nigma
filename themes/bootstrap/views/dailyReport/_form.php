<?php
/* @var $this DailyReportController */
/* @var $model DailyReport */
/* @var $form CActiveForm */
/* @var $providers */
/* @var $campaigns */
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Daily Report <?php echo $model->isNewRecord ? "" : "#". $model->id; ?></h4>
</div>


<div class="modal-body">

    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'daily-report-form',
        'type'=>'horizontal',
        'htmlOptions'=>array('class'=>'well'),
        // to enable ajax validation
        'enableAjaxValidation'=>true,
        'clientOptions'=>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
    )); ?>
    <fieldset>
        <?php 

        if ($model->isNewRecord)
            echo $form->dropDownListRow($model, 'campaigns_id', $campaigns, array('prompt' => 'Select campaign'));
        else
            echo $form->dropDownListRow($model, 'campaigns_id', $campaigns, array('prompt' => 'Select campaign', 'disabled' => 'disabled'));

        //echo $form->hiddenField($model, 'providers_id', array('type'=>"hidden") );
        echo '<hr>';
        echo $form->textFieldRow($model, 'imp', array('class'=>'span3'));
        echo $form->textFieldRow($model, 'imp_adv', array('class'=>'span3'));
        echo $form->textFieldRow($model, 'clics', array('class'=>'span3'));
        echo $form->textFieldRow($model, 'spend', array('class'=>'span3', 'prepend'=>'$'));
        echo $form->hiddenField($model, 'is_from_api', array('type'=>'hidden'));       
        echo $form->datepickerRow($model, 'date', array(
                'options' => array(
                    'autoclose'      => true,
                    'todayHighlight' => true,
                    'format'         => 'yyyy-mm-dd',
                    'viewformat'     => 'dd-mm-yyyy',
                    'placement'      => 'right',
                ),
                'htmlOptions' => array(
                    'class' => 'span3',
                )),
                array(
                    'append' => '<label for="DailyReport_date"><i class="icon-calendar"></i></label>',
                )
        );

        ?>

    <?php //echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'success', 'label'=>'Submit')); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'type'=>'reset', 'label'=>'Reset')); ?>
    </div>
    </fieldset>

    <?php $this->endWidget(); ?>


</div>

<div class="modal-footer">
    Create new Daily Report. Fields with <span class="required">*</span> are required.
</div>