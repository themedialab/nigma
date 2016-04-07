<?php

class TagController extends Controller
{
	public function actionTestIP($hash=null){
		$ip    = isset($hash) ? $hash : $_SERVER["REMOTE_ADDR"];
		echo 'IP: '.$ip;
		echo '<hr/>';

		$binPath      = Yii::app()->params['ipDbFile'];
		$location     = new IP2Location($binPath, IP2Location::FILE_IO);
		$ipData       = $location->lookup($ip, IP2Location::ALL);
		echo json_encode($ipData, JSON_PRETTY_PRINT);
		echo '<hr/>';
	}

	public function actionView($id){
		// Yii::log("impresion: " . var_export($imp->getErrors(), true));

		if(!$tag = Tags::model()->findByPk($id))
			die("Tag ID does't exists");
		if(!isset($_GET['pid']))
			die("Placement ID does't exists");

		
		// log impression
		
		$imp = new ImpLog();
		$imp->tags_id = $tag->id;
		$imp->placements_id = $_GET['pid'];
		$imp->date = new CDbExpression('NOW()');


		// pubid
		
		$imp->pubid = isset($_GET['pubid']) ? $_GET['pubid'] : null;

		
		// Get visitor parameters
		
		$imp->server_ip    = isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : null;
		$imp->ip_forwarded = isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : null;
		$imp->user_agent   = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
		$imp->languaje     = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : null;
		$imp->referer      = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
		$imp->app          = isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? $_SERVER['HTTP_X_REQUESTED_WITH'] : null;


		// Get userAgent data

		if(isset($imp->user_agent)){

			$wurfl = WurflManager::loadWurfl();
			$device = $wurfl->getDeviceForUserAgent($imp->user_agent);
			$imp->device          = $device->getCapability('brand_name');
			$imp->device_model    = $device->getCapability('marketing_name');
			$imp->os              = $device->getCapability('device_os');
			$imp->os_version      = $device->getCapability('device_os_version');
			$imp->browser         = $device->getVirtualCapability('advertised_browser');
			$imp->browser_version = $device->getVirtualCapability('advertised_browser_version');
			
			if ($device->getCapability('is_tablet') == 'true')
				$imp->device_type = 'Tablet';
			else if ($device->getCapability('is_wireless_device') == 'true')
				$imp->device_type = 'Mobile';
			else
				$imp->device_type = 'Desktop';

		}


		// Get ip data

		$ip = isset($imp->ip_forwarded) ? $imp->ip_forwarded : $imp->server_ip;
		if(isset($ip)){
			// $binPath      = YiiBase::getPathOfAlias('application') . "/data/ip2location.BIN";
			$binPath      = Yii::app()->params['ipDbFile'];
			$location     = new IP2Location($binPath, IP2Location::FILE_IO);
			$ipData       = $location->lookup($ip, IP2Location::ALL);
			$imp->country = $ipData->countryCode;
			$imp->city    = $ipData->cityName;
			$imp->carrier = $ipData->mobileCarrierName;
		}

		/*
		// revenue and cost
		
		// targeting
		$match_country = isset($imp->tags->country) ? $imp->country == $imp->tags->country : true;
		$conn_type = $imp->carrier=='-' || $imp->carrier=='' || $imp->carrier=='Invalid IPv4 address.' || $imp->carrier=='Invalid IPv6 address.' ? 'WIFI' : '3G';
		$match_connection = isset($imp->tags->connection_type) ? $conn_type == $imp->tags->connection_type : true;
		$match_device = isset($imp->tags->device_type) ? $imp->device_type == $imp->tags->device_type || ($imp->device_type != 'Desktop' && $imp->tags->device_type == 'Mobile+Tablet') : true;
		$match_os = isset($imp->tags->os) ? $imp->os == $imp->tags->os : true;
		$match_version = isset($imp->tags->os_version) ? $imp->os_version >= $imp->tags->os_version : true;

		if( $match_country && $match_connection && $match_device && $match_os && $match_version ){
			
			// frequency
			$frequency = $imp->getFrequency();
				
			if($frequency < $imp->tags->freq_cap || $imp->tags->freq_cap == null){
				$imp->revenue = $imp->tags->campaigns->opportunities->rate > 0 ? $imp->tags->campaigns->opportunities->rate /1000 : 0;
				$imp->cost    = $imp->placements->rate > 0 ? $imp->placements->rate /1000 : 0;
			}

		}
		*/

		// log impression

		if(!$imp->save())
			Yii::log("impression error: " . json_encode($imp->getErrors(), true), 'error', 'system.model.impLog');
		// enviar macros

		$newCode = $imp->replaceMacro($tag->code);

		//print tag

		$this->renderPartial('view',array(
			'code'=>$newCode,
			'tag'=>$tag,
			'imp'=>$imp,
			));

	}

	public function actionJs($id){
		
		$pid    = isset($_GET['pid']) ? $_GET['pid'] : null;
		$width  = isset($_GET['width']) ? $_GET['width'] : null;
		$height = isset($_GET['height']) ? $_GET['height'] : null;
		$pubid  = isset($_GET['pubid']) ? $_GET['pubid'] : '';

		if(isset($pid) && isset($width) && isset($height)){

			echo 'document.write(\'<iframe src="http://bidbox.co/tag/'.$id.'?pid=&pubid='.$pubid.'" width="'.$width.'" height="'.$height.'" frameborder="0" scrolling="no" ></iframe>\');';

		}else{

			echo 'document.write(\'ERROR: Ad not setted properly\');';

		}



	}

}
