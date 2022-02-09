<?php 

if(isset($_POST['submit']))
{
	if($_POST['text_string'] == '')
	{
		$error = 'Please enter data in textbox to analyze';
	}
	else
	{
		$string = $_POST['text_string'];
		
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'http://localhost:8080/iMTD/analyze',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS =>$_POST['text_string'],
		  CURLOPT_HTTPHEADER => array(
		    'Content-Type: text/plain'
		  ),
		));

		$output =json_decode(curl_exec($curl) , TRUE);
		//echo "<pre>"; print_r($output); echo "</pre>";
		// Getting Test Data from json.txt file
		/*$data = file_get_contents('json.txt');
		$output = json_decode($data , TRUE);*/

		if(isset($output['status']) && $output['status'] == 400)
		{
			$error = 'API Error : '.$output['error'];
		}
		else
		{
			$o = array();
			$i = 0;
			foreach($output as $key => $value)
			{
				if(!empty($value))
				{
					foreach($value as $val)
					{
						$o[$i] = array(
										'key' => $key,
										'begin' => $val['begin'],
										'end' => $val['end'],
										'text' => $val['text'],
										'polarity' => $val['polarity'],
										'conceptAttributes' => $val['conceptAttributes'],

									);
						$i++;
					}
				}
			}

			$keys = array_column($o, 'end');

			$sort = array();
			foreach($o as $k=>$v) {
			    $sort['begin'][$k] = $v['begin'];
			    $sort['end'][$k] = $v['end'];
			}
			# sort by event_type desc and then title asc
			array_multisort($sort['end'], SORT_DESC, $sort['begin'], SORT_ASC,$o);		
			//echo "<pre>"; print_r($output); echo "</pre>";
			//echo "<pre>"; print_r($o); echo "</pre>";
			$done_indexes = $end_indexes = array();
			if(!empty($o))
			{
				$i = 0;
				foreach ($o as $key => $value) 
				{
					$further_chk = 1;
					//echo '<hr>'.$value['begin'].' '.$value['end'].'<br>'; print_r($done_indexes); echo "</br>"; print_r($end_indexes);
					$e_check = 0;

					for( $j=0 ; $j < count($done_indexes); $j++)
					{
						if($value['begin'] >= $done_indexes[$j] && $value['end'] <= $end_indexes[$j])
						{
							$further_chk = 0;
							break;
						}
					}
					if($further_chk == 0){ continue; };

					/*if(isset($o[$i-1]['end'])){$e_check = $o[$i-1]['end']; }
					if($e_check != $value['end'] )
					{*/
						$done_indexes[] = $value['begin'];
						$end_indexes[] = $value['end'];
						$ulms = '';
						if(!empty($value['conceptAttributes'])){ $ulms = $value['conceptAttributes'][0]['cui']; }
						$char = '<span class="text tooltip_box '.$value['key'].'" data="'.$value['key'].'" >'.$value['text'];
						if($ulms != '')
						{
							$char .= ' <button class="span_btn btn"> ULMS : '.$ulms.' </button>';
						}
						if(!empty($value['conceptAttributes']))
						{
							$char .= '<b class="tooltiptext">'; 
							if($ulms != '')
							{
								$char .= '<p> <small class="background">ULMS</small> <small>'.$ulms.'</small> </p> ';
							}
							foreach($value['conceptAttributes'] as $conceptAttributes)
							{
								$char .= '<p> <small class="background">'.$conceptAttributes['codingScheme'].'</small> <small>'.$conceptAttributes['code'].'</small> </p> ';
							}
							$char .= '</b>';
						}
						$char .= '</span>';
						$pos = $value['begin'];
						$length = strlen($value['text']);
						$string = substr_replace($string,$char,$pos,$length);
					/*}*/
					$i++;	
				}

				$result = $string;
			}
		}		
	}
}

?>