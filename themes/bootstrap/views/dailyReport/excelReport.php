<?php
/* @var $this DailyReportController */
/* @var $model DailyReport */

$path = 'uploads/';
$name = 'KickAds-DailyReport.xls';

$dateStart = isset($_POST['excel-dateStart']) ? $_POST['excel-dateStart'] : 'yesterday' ;
$dateEnd   = isset($_POST['excel-dateEnd']) ? $_POST['excel-dateEnd'] : 'yesterday';

$dateStart = date('Y-m-d', strtotime($dateStart));
$dateEnd = date('Y-m-d', strtotime($dateEnd));

$this->widget('EExcelWriter', array(
    'dataProvider' => $model->excel($dateStart, $dateEnd),
    'title'        => 'EExcelWriter',
    'stream'       => TRUE,
    'fileName'     => $name,
    'filePath'     => $path,
    'columns'      => array(
        array(
            'name'  => 'campaign_name',
            'value' => 'Campaigns::model()->getExternalName($data->campaigns_id)',
        ),
        array(
            'name'  => 'network_name',
            'value' => '$data->networks->name',
        ),
        'imp',
        'imp_adv',
        'clics',
        'conv_api',
        'conv_adv',
        array(
            'name'  =>  'spend',
            'value' =>  '$data->getSpendUSD()',
            'htmlOptions'=>array('style'=>'width: 60px'),
        ),
        array(
            'name'  => 'revenue',
            'value' => '$data->getRevenueUSD()',
        ),
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
            'name'  => 'date',
            'value' => 'date("d-m-Y", strtotime($data->date))',
        ),
    ),
));

unlink($path . $name);

?>