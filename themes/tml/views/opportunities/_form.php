<?php
/* @var $this OpportunitiesController
 * @var $model Opportunities
 * @var $form CActiveForm 
 * @var $advertiser Advertisers[]
 * @var $ios Ios[]
 * @var $account Users[]
 * @var $country GeoLocation[]
 * @var $carrier Carriers[]
 * @var model_adv Model Adv values
 * @var wifi true-false
 */
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Opportunity <?php echo $model->isNewRecord ? "" : "#". $model->id; ?></h4>
</div>


<div class="modal-body">

    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'                   =>'opportunities-form',
		'type'                 =>'horizontal',
		'htmlOptions'          =>array('class'=>'well'),
		// to enable ajax validation
		'enableAjaxValidation' =>true,
		'clientOptions'        =>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
    )); ?>
    <fieldset>

      <?php 

      echo "<h5>VAS Attributes</h5>";
      echo "<hr>";

      if ( $model->isNewRecord ) {
        echo $form->dropDownListRow($model, 'advertiser_name', $advertiser, 
            array(
              'prompt'   => 'Select an advertiser', 
              'onChange' => '
                  if ( ! this.value) {
                    return;
                  }
                  $.post(
                      "getRegions/"+this.value,
                      "",
                      function(data)
                      {
                          // alert(data);
                        $(".regions-dropdownlist").html(data);
                      }
                  )
                  '
            ));
         echo $form->dropDownListRow($model, 'regions_id', $regions, 
            array(
              'prompt'   => 'Select an region', 
              'class'=>'regions-dropdownlist',
              'onChange' => '
                      if ( ! this.value) {
                        return;
                      }
                      $.post(
                          "getCarriers/"+this.value,
                          "",
                          function(data)
                          {
                            // alert(data);
                            $(".carriers-dropdownlist").html(data);
                          }
                      )
                  ',
            ));
        //echo $form->dropDownListRow($model, 'ios_id', $ios, array('class'=>'ios-dropdownlist', 'prompt' => 'Select an IOs'));
      } else {
        echo $form->textFieldRow($model, 'id', array('type'=>'hidden', 'class'=>'span3', 'readonly'=>true, ));
        //echo $form->textFieldRow($advertiser, 'name', array('class'=>'span3', 'readonly'=>true, 'label'=>$ios->getAttributeLabel('advertisers_id') ));
        //echo $form->textFieldRow($regions, 'name', array('class'=>'span3', 'readonly'=>true, 'label'=>$model->getAttributeLabel('regions_id') ));
      
      }

      

      $model->carriers_id == NULL ? $multicarrier = true : $multicarrier = false;
      echo $form->dropDownListRow($model, 'carriers_id', $carrier, 
            array(
              'class'    => 'carriers-dropdownlist', 
              'prompt'   => 'Select a carrier', 
              'encode'   => false,
              'disabled' => $multicarrier
            ));
      echo $form->checkboxRow($model, 'multi_carrier', 
            array(
              'checked'  => $multicarrier,
              'onChange' => '
                  if (this.checked == "1") {
                    $("#opportunities-form #Opportunities_carriers_id option:eq(0)").prop("selected", true);
                    $("#opportunities-form #Opportunities_carriers_id").prop("disabled", true);
                  }else{
                    $("#opportunities-form #Opportunities_carriers_id").prop("disabled", false);
                  }
                  return;
                  '
            ));

      $model->rate == NULL ? $multirate = true : $multirate = false;
      echo $form->textFieldRow($model, 'rate', 
            array(
              'class'    => 'span3',
              'disabled' => $multirate
            ));
      echo $form->checkboxRow($model, 'multi_rate', 
            array(
              'checked'  => $multirate,
              'onChange' => '
                  if (this.checked == "1") {
                    //alert("ok");
                    $("#opportunities-form #Opportunities_rate").val("");
                    $("#opportunities-form #Opportunities_rate").prop("disabled", true);
                  }else{
                    //alert("no");
                    $("#opportunities-form #Opportunities_rate").prop("disabled", false);
                  }
                  return;
                  '
            ));

      echo $form->textFieldRow($model, 'budget', array('class'=>'span3'));
      echo $form->checkboxRow($model, 'open_budget', 
            array(
              'onChange' => '
                  if (this.checked == "1") {
                    //alert("ok");
                    $("#Opportunities_budget").val("");
                    $("#Opportunities_budget").prop("disabled", true);
                  }else{
                    //alert("no");
                    $("#Opportunities_budget").prop("disabled", false);
                  }
                  return;
                  '
            ));
      echo $form->dropDownListRow($model, 'model_adv', $model_adv);
      echo $form->textFieldRow($model, 'product', array('class'=>'span3'));
      echo $form->dropDownListRow($model, 'account_manager_id', $account , array('prompt' => 'Select an Account Manager'));
      echo $form->checkboxRow($model, 'wifi');
      echo $form->textFieldRow($model, 'server_to_server', array('class'=>'span3'));
      echo $form->datepickerRow($model, 'startDate', array(
                'options' => array(
                    'autoclose'      => true,
                    'todayHighlight' => true,
                    'clearBtn'       => true,
                    'format'         => 'yyyy-mm-dd',
                    'viewformat'     => 'dd-mm-yyyy',
                    'placement'      => 'right',
                ),
                'htmlOptions' => array(
                    'class' => 'span3',
                )),
                array(
                    'append' => '<label for="Opportunities_startDate"><i class="icon-calendar"></i></label>',
                )
        );
      echo $form->datepickerRow($model, 'endDate', array(
                'options' => array(
                    'autoclose'      => true,
                    'todayHighlight' => true,
                    'clearBtn'       => true,
                    'format'         => 'yyyy-mm-dd',
                    'viewformat'     => 'dd-mm-yyyy',
                    'placement'      => 'right',
                ),
                'htmlOptions' => array(
                    'class' => 'span3',
                )),
                array(
                    'append' => '<label for="Opportunities_endDate"><i class="icon-calendar"></i></label>',
                )
        );

      // ----- Branding attributes
      echo "<h5>Branding Attributes</h5>";
      echo "<hr>";
      
      echo $form->textFieldRow($model, 'freq_cap', array('class'=>'span3'));
      echo $form->textFieldRow($model, 'imp_per_day', array('class'=>'span3'));
      echo $form->textFieldRow($model, 'imp_total', array('class'=>'span3'));
      echo $form->textFieldRow($model, 'targeting', array('class'=>'span3'));
      echo $form->textFieldRow($model, 'sizes', array('class'=>'span3'));
      echo $form->dropDownListRow($model, 'channel', $channels, 
        array(
          'prompt' => 'Select a channel', 
          'onChange' => '
                  if ( this.value == "Categories" || this.value == "Sites Specifics") {
                    $("#Opportunities_channel_description").prop("disabled", false);
                  } else {
                    $("#Opportunities_channel_description").prop("disabled", true);
                  }
                  ',
        ));
      echo $form->textFieldRow($model, 'channel_description', array('disabled'=>true, 'class'=>'span3'));
      echo "<hr>";
      echo $form->textAreaRow($model, 'comment', array('class'=>'span3', 'rows'=>5));

      // ----- End - Branding attributes
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
    Edit Opportunity attributes. Fields with <span class="required">*</span> are required.
</div>