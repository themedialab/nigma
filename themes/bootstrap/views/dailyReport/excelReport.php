<?php
/* @var $this DailyReportController */
/* @var $model DailyReport */

$path = 'uploads/';
$name = 'KickAds-DailyReport.xls';

$this->widget('EExcelWriter', array(
    'dataProvider' => $model->excel(),
    'title'        => 'EExcelWriter',
    'stream'       => TRUE,
    'fileName'     => $name,
    'filePath'     => $path,
    'columns'      => array(
        array(
        	'name' => 'campaign_name',
        	'value' => '$data->campaigns->name',
        ),
        array(
        	'name' => 'network_name',
        	'value' => '$data->networks->name',
        ),
        'imp',
        'imp_adv',
        'conv_api',
        'conv_adv',
        'spend',
        'revenue',
        array(
            'name'  => 'profit',
            'value' =>  '$data->profit',
        ),
        array(
            'name'  => 'click_rate',
            'value' => '$data->click_rate * 100',
        ),
        array(
            'name'  => 'conv_rate',
            'value' => '$data->conv_rate * 100',
        ),
        array(
            'name'  => 'profit_perc',
            'value' => '$data->profit_perc * 100',
        ),
        array(
            'name'  => 'eCPM',
            'value' => '$data->eCPM',
        ),
        array(
            'name'  => 'eCPC',
            'value' => '$data->eCPC',
        ),
        array(
            'name'  => 'eCPA',
            'value' => '$data->eCPA',
        ),
        array(
            'name' => 'date',
            'value' => 'date("d-m-Y", strtotime($data->date))',
        ),
    ),
));

unlink($path . $name);

?>