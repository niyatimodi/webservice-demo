	<?php
	// Redirecting URL to given location
		//define('SALT', 'daisohw');
		define('SALT', 'daisohw');

		function slencrypt($text)
		{
			$enkey = trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, SALT, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));

			$data = str_replace(array('+','/'),array('-','_'),$enkey);
			return $data;
		}
		
		function sldecrypt($text)
		{
			$enkey = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, SALT, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));

			$data = str_replace(array('-','_'),array('+','/'),$enkey);
			return $data;
		}

		function getRemoteFileSize($url)
		{ 
		   $parsed = parse_url($url); 
		   $host = $parsed["host"]; 
		   $fp = @fsockopen($host, 80, $errno, $errstr, 20); 
		   if(!$fp)return false; 
		   else { 
		       @fputs($fp, "HEAD $url HTTP/1.1\r\n"); 
		       @fputs($fp, "HOST: $host\r\n"); 
		       @fputs($fp, "Connection: close\r\n\r\n"); 
		       $headers = ""; 
		       while(!@feof($fp))$headers .= @fgets ($fp, 128); 
		   } 
		   @fclose ($fp); 
		   $return = false; 
		   $arr_headers = explode("\n", $headers); 
		   foreach($arr_headers as $header) { 
		       $s = "Content-Length: "; 
		       if(substr(strtolower ($header), 0, strlen($s)) == strtolower($s)) { 
		           $return = trim(substr($header, strlen($s))); 
		           break; 
		       } 
		   } 
		   if($return){ 
		              $size = round($return / 1024, 2); 
		              $sz = "KB"; // Size In KB 
		        if ($size > 1024) { 
		            $size = round($size / 1024, 2); 
		            $sz = "MB"; // Size in MB 
		        } 
		        $return = "$size $sz"; 
		   } 
		   return $return; 
		} 

		function format_number($str) 
		{
			return number_format($str, 2, '.', '');
		}
		function RTESafe($strText) 
		{
			//returns safe code for preloading in the RTE
			$tmpString = trim($strText);
			
			//convert all types of single quotes
			$tmpString = str_replace(chr(145), chr(39), $tmpString);
			$tmpString = str_replace(chr(146), chr(39), $tmpString);
			$tmpString = str_replace("'", "&#39;", $tmpString);
			
			//convert all types of double quotes
			$tmpString = str_replace(chr(147), chr(34), $tmpString);
			$tmpString = str_replace(chr(148), chr(34), $tmpString);
		//	$tmpString = str_replace("\"", "\"", $tmpString);
			
			//replace carriage returns & line feeds
			$tmpString = str_replace(chr(10), " ", $tmpString);
			$tmpString = str_replace(chr(13), " ", $tmpString);
			
			return $tmpString;
		}
		function RTEChange($strText) 
		{
			$tmpString=ereg_replace('(.")',"'",$strText);
			return $tmpString;
		}
		function redirect($to)
		{
	  		$schema = $_SERVER['SERVER_PORT'] == '443' ? 'https' : 'http';
		  	$host = strlen($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:$_SERVER['SERVER_NAME'];
	 		if (headers_sent()) 
			{
				
				
				return false;
		
		
			}
	  		else
	  		{
				header("HTTP/1.1 301 Moved Permanently");
				
				$temp_local = "catracker";
				//$temp_local = "development/newadmin";
				header("Location: $schema://$host/$temp_local/$to");
				//header("Location: $schema://$host/$to");
				exit();
		 	}
	}
	
	
	// Encrpting the Data
	function encrypt($id)
	{
		//base64_decode($str);
		/*encode
		$eno = $id ;
		$eno = ($eno*3900)/13;
		$enew_no = dechex($eno);
		return $enew_no;*/
		return base64_encode($id);
	}

	// Dycrypting the Data
	function decrypt($id)
	{
		/*$dno =  hexdec($id) ;
		$dno = ($dno*(13/3900));
		return $dno;*/
		return base64_decode($id);
	}
	
	function imageUpload($images,$user_id=null,$flag=null)
	{
		$dirswfl = $_SERVER['DOCUMENT_ROOT']."catracker/admin/uploadedImages/".$user_id;

		if(!is_dir($dirswfl))
		{
		$chmod = (is_dir($dirswfl) === true) ? 644 : 777;
		if (in_array(get_current_user(), array('apache', 'httpd', 'nobody', 'system', 'webdaemon', 'www', 'www-data')) === true)
		{
		$chmod += 22;
		}
		
		mkdir($dirswfl);
		chmod($dirswfl, octdec(intval($chmod)));
		
		}

		$target = $_SERVER['DOCUMENT_ROOT']."catracker/admin/uploadedImages/".$user_id."/";
		$imageCount = count($_FILES[$images]['name']);
		for($i =0;$i<$imageCount;$i++)
		{
			$pieces = explode(".",$_FILES[$images]['name'][$i]);
			$countPieces = count($pieces);
			if($countPieces == 2)
			{
				$extension = strtolower($pieces[1]);
			}
			else
			{
				$extension = strtolower($pieces[$countPieces-1]);
			}
			$cleanstring = strtolower(trim(preg_replace('#\W+#', '_', $pieces[0]), '_'));
			$photo_file = $user_id.'_'.$cleanstring.'.'.$extension;
			$originalImage=$target.$photo_file;
			
			if(in_array($extension , array('jpg','jpeg', 'gif', 'png', 'bmp')))
			{	
				//Thumbnail file name File
				$image_filePath = $_FILES[$images]['tmp_name'][$i];
				$img_fileName = $user_id.'_'.$cleanstring.'_Thumb.'.$extension;
				$img_thumb = $target.$img_fileName;
				$extension = strtolower($pieces[1]);
				//Check the file format before upload
				
				//Find the height and width of the image
				list($gotwidth, $gotheight, $gottype, $gotattr)= getimagesize($image_filePath);
				//---------- To create thumbnail of image---------------
				if($extension=="jpg" || $extension=="jpeg" )
				{
					$src = imagecreatefromjpeg($_FILES[$images]['tmp_name'][$i]);
				}
				else if($extension=="png")
				{
					$src = imagecreatefrompng($_FILES[$images]['tmp_name'][$i]);
				}
				else
				{
					$src = imagecreatefromgif($_FILES[$images]['tmp_name'][$i]);
				}
				list($width,$height)=getimagesize($_FILES[$images]['tmp_name'][$i]);
				//This application developed by www.webinfopedia.com
				//Check the image is small that 124px
				if($gotwidth>=124)
				{
					//if bigger set it to 124
					$newwidth=124;
				}
				else
				{
					//if small let it be original
					$newwidth=$gotwidth;
				}
				
				//Find the new height
				$newheight=round(($gotheight*$newwidth)/$gotwidth);
				//Creating thumbnail
				$tmp=imagecreatetruecolor($newwidth,$newheight);
				imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight, $width,$height);
				//Create thumbnail image
				$createImageSave=imagejpeg($tmp,$img_thumb,100);
				if($createImageSave)
				{
					//upload the original file
					$uploadOrginal=move_uploaded_file($_FILES[$images]['tmp_name'][$i],$originalImage);
					if($flag=='edit')
					{
						$imageName[] = $photo_file;
					}
					else
					{
						if($i==0)
						{
							$imageName = $photo_file;
						}
						else
						{
							$imageName .= ','.$photo_file;
						}	
					}
				}				
			}
		}
		return $imageName;
	}

	function imageUploadforWebServices($images,$user_id=null,$flag=null)
	{
		$dirswfl = $_SERVER['DOCUMENT_ROOT']."catracker/admin/uploadedImages/".$user_id;

		if(!is_dir($dirswfl))
		{
		$chmod = (is_dir($dirswfl) === true) ? 644 : 777;
		if (in_array(get_current_user(), array('apache', 'httpd', 'nobody', 'system', 'webdaemon', 'www', 'www-data')) === true)
		{
		$chmod += 22;
		}
		
		mkdir($dirswfl);
		chmod($dirswfl, octdec(intval($chmod)));
		
		}

		$target = $_SERVER['DOCUMENT_ROOT']."catracker/admin/uploadedImages/".$user_id."/";
		$imageCount = count($_FILES[$images]['name']);
		
		$pieces = explode(".",$_FILES[$images]['name']);
		$countPieces = count($pieces);
		if($countPieces == 2)
		{
			$extension = strtolower($pieces[1]);
		}
		else
		{
			$extension = strtolower($pieces[$countPieces-1]);
		}
		$cleanstring = strtolower(trim(preg_replace('#\W+#', '_', $pieces[0]), '_'));
		$photo_file = $user_id.'_'.$cleanstring.'.'.$extension;
		$originalImage=$target.$photo_file;
		
		if(in_array($extension , array('jpg','jpeg', 'gif', 'png', 'bmp')))
		{	
			//Thumbnail file name File
			$image_filePath = $_FILES[$images]['tmp_name'];
			$img_fileName = $user_id.'_'.$cleanstring.'_Thumb.'.$extension;
			$img_thumb = $target.$img_fileName;
			$extension = strtolower($pieces[1]);
			//Check the file format before upload
			
			//Find the height and width of the image
			list($gotwidth, $gotheight, $gottype, $gotattr)= getimagesize($image_filePath);
			//---------- To create thumbnail of image---------------
			if($extension=="jpg" || $extension=="jpeg" )
			{
				$src = imagecreatefromjpeg($_FILES[$images]['tmp_name']);
			}
			else if($extension=="png")
			{
				$src = imagecreatefrompng($_FILES[$images]['tmp_name']);
			}
			else
			{
				$src = imagecreatefromgif($_FILES[$images]['tmp_name']);
			}
			list($width,$height)=getimagesize($_FILES[$images]['tmp_name']);
			//This application developed by www.webinfopedia.com
			//Check the image is small that 124px
			if($gotwidth>=124)
			{
				//if bigger set it to 124
				$newwidth=124;
			}
			else
			{
				//if small let it be original
				$newwidth=$gotwidth;
			}
			
			//Find the new height
			$newheight=round(($gotheight*$newwidth)/$gotwidth);
			//Creating thumbnail
			$tmp=imagecreatetruecolor($newwidth,$newheight);
			imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight, $width,$height);
			//Create thumbnail image
			$createImageSave=imagejpeg($tmp,$img_thumb,100);
			if($createImageSave)
			{
				//upload the original file
				//$uploadOrginal=move_uploaded_file($_FILES[$images]['tmp_name'],$originalImage);
				if(move_uploaded_file($_FILES[$images]['tmp_name'],$originalImage))
				{
					if($flag=='edit')
					{
						$imageName[] = $photo_file;
					}
					else
					{
						if($i==0)
						{
							$imageName = $photo_file;
						}
						else
						{
							$imageName .= ','.$photo_file;
						}	
					}
					return $imageName;
				}
				else
				{
					return "error";	
				}
			}				
		}	
	}
		
	function to_month($id)
	{
		/*January
		February
		March
		April
		May
		June
		July
		August
		September
		October
		November
		December*/
		
		if($id==1)
			return "January";
		else if($id==2)
			return "February";
		else if($id==3)
			return "March";
		else if($id==4)
			return "April";
		else if($id==5)
			return "May";			
		else if($id==6)
			return "June";
		else if($id==7)
			return "July";
		else if($id==8)
			return "August";
		else if($id==9)
			return "September";
		else if($id==10)
			return "October";
		else if($id==11)
			return "November";
		else if($id==12)
			return "December";			
		
	}
	
	
	
	// Fuction to Replace " ' with space
	function replace($givenstr)
	{
		$str = $givenstr;
		$chr = array("'");
		$finalstr = str_replace($chr, "\'", $str);
		return $finalstr;
	}
	function replace_date($date_to)
	{
		$str = $date_to;
		$chr = array("-","/");
		$date_to = str_replace($chr, "*", $str);
		$exp_date = explode("*",$date_to);
		$date_to = $exp_date[2]."/".$exp_date[1]."/".$exp_date[0];
		return($date_to);
	}
	function replace_date_dash($date_to)
	{
		$str = $date_to;
		$chr = array("-","/");
		$date_to = str_replace($chr, "*", $str);
		$exp_date = explode("*",$date_to);
		$date_to = $exp_date[2]."-".$exp_date[1]."-".$exp_date[0];
		return($date_to);
	}
	function replace_date1($date_to)
	{
		$str = $date_to;
		$chr = array("-","/");
		$date_to = str_replace($chr, "/", $str);
		$exp_date = explode("/",$date_to);
		return($exp_date);
	}
	
	function replace_comm($givenstr)
	{
		$str = $givenstr;
		$chr = array("'",",");
		$finalstr = str_replace($chr, "", $str);
		return $finalstr;
	}
	function replace_slash($givenstr)
	{
		$str = $givenstr;
		$chr = array("/");
		$finalstr = str_replace($chr, "_", $str);
		return strtolower($finalstr);
	}
	function add_slash($givenstr)
	{
		$str = $givenstr;
		$chr = array("_");
		$finalstr = str_replace($chr, "/", $str);
		return strtolower($finalstr);
	}
	function replace_safaricategory($givenstr)
	{
		$str = $givenstr;
		$chr = array("'"," ");
		$finalstr = str_replace($chr, "*", $str);
		return strtolower($finalstr);
	}
	function add_safaricategory($givenstr)
	{
		$str = $givenstr;
		$chr = array("*");
		$finalstr = str_replace($chr, " ", $str);
		return $finalstr;
	}
	
	
	function count_booking_id($id)
	{
		$s = substr($id,3,strlen($id)) + 1;
		$s = substr($id,0,3).$s;
		return $s;
	}
	/**
	 * @name calc_due_date()
	 * @created February 21, 2003
	 * @author J de Silva
	 * @modified July 30, 2004
	 * ------------------------------------------------------------------
	 */
	
	function upload_file($field,$path)
	{
		$uploadDir = $path."/";
		$uploadFile = $uploadDir.$_FILES[$field]['name'];
		
		$image_name = $_FILES[$field]['name'];
		
		
		if (move_uploaded_file($_FILES[$field]['tmp_name'], $uploadFile))
		{
			chmod("$uploadFile",0777);
			
		}
		else
			echo "<br>some problem";
		return $image_name;
	}
	function send_email($to,$message,$subject,$from,$from_email,$reply="",$reply_email="")
	{
		
		$headers = "From: \"".$from."\"<".$from_email.">\n";
		$headers .= "Reply-To: \"".$reply."\"<".$reply_email.">\n";
		$headers .= 'MIME-Version: 1.0' . "\n"; 
		$headers .= 'Content-Type: text/html; charset=iso-8859-1"'."\n\n"; 
		/*if( $_SERVER['DOCUMENT_ROOT']!="C:/Inetpub/wwwroot")
		{*/	
			mail($to, $subject, $message, $headers); 
		/*}*/
	}	
	function fill_combo($table_name,$control_name,$first_value,$display_value,$field_value,$condition,$print_query="",$selected_value="",$css_class="",$multiple=false,$event="",$default_add=false,$size="",$second_displayvalue="")
	{		
		if($second_displayvalue!="")
			$query= "select ".$display_value.",".$field_value.",".$second_displayvalue." from ".$table_name. "".$condition;
		else
			$query= "select ".$display_value.",".$field_value." from ".$table_name. "".$condition;
		if($print_query!="")
		{
			echo $query;
		}		
		$rs = mysql_query($query) or die("There some error in function :".mysql_error());		
		if($multiple!="" && $multiple!="false")
		{			
			echo "<select class='".$css_class."' name='".$control_name."[]' multiple='".$multiple."' size='".$size."'". $event.">";
		}
		else
		{						
			echo "<select class='".$css_class."' name='".$control_name."' ". $event.">";
		}
		if($first_value!="")
			echo "<option value=''>".$first_value."</option>";
		if($second_displayvalue!="")
		{
			while($row= mysql_fetch_object($rs))
			{
				echo $row->$field_value;
				if($row->$field_value==$selected_value)
					echo "<option selected value='".$row->$field_value."'>(".$row->$second_displayvalue.")".$row->$display_value."</option>";
				else
					echo "<option  value='".$row->$field_value."'>(".$row->$second_displayvalue.")".$row->$display_value."</option>";
			}
		}
		else
		{
			while($row= mysql_fetch_object($rs))
			{
				echo $row->$field_value;
				if($row->$field_value==$selected_value)
					echo "<option selected value='".$row->$field_value."'>".$row->$display_value."</option>";
				else
					echo "<option  value='".$row->$field_value."'>".$row->$display_value."</option>";
			}
		}
	}
	
	function randon_password()
	{
		return 	rand(1000,9999);
	}
	function send_sms($mobile_number,$message)
	{
		$request = ""; //initialize the request variable
		$param["user"] = "ebizpromo"; //this is the username of our TM4B account
		$param["password"] = "ebizpromo123"; //this is the password of our TM4B account
		$param["text"] = $message; //this is the message that we want to send
		$param["PhoneNumber"] = "91".$mobile_number; //these are the recipients of the message
		$param["sender"] = "ebizpromo";//this is our sender 
		foreach($param as $key=>$val) //traverse through each member of the param array
		{ 
		  $request.= $key."=".urlencode($val); //we have to urlencode the values
		  $request.= "&"; //append the ampersand (&) sign after each paramter/value pair
		}
		$request = substr($request, 0, strlen($request)-1); //remove the final ampersand sign from the request
		//First prepare the info that relates to the connection
		$host = "sms.globalbulksms.com";
		$script = "/sendsms.asp";
		$request_length = strlen($request);
		$method = "POST"; // must be POST if sending multiple messages
		if ($method == "GET") 
		{
		  $script .= "?$request";
		}
		
		//Now comes the header which we are going to post. 
		$header = "$method $script HTTP/1.1\r\n";
		$header .= "Host: $host\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "Content-Length: $request_length\r\n";
		$header .= "Connection: close\r\n\r\n";
		$header .= "$request\r\n";
		
		//Now we open up the connection
		$socket = @fsockopen($host, 80, $errno, $errstr); 
		if ($socket) //if its open, then...
		{ 
		  fputs($socket, $header); // send the details over
		  while(!feof($socket))
		  {
			$output[] = fgets($socket); //get the results 
		  }
		  fclose($socket); 
		} 
		//print "<pre>";
		//print_r($output);
		//print "</pre>";
	}
	function create_fck_editor($object_name,$editor_name,$editor_path,$width,$height,$value)
	{
		$object_name = new FCKeditor($editor_name) ;
		$object_name->BasePath = $editor_path;				
		$object_name->Width  = $width ;
		$object_name->Height = $height ;
		$object_name->Value = $value;
		$object_name->Create() ;
	}
	
	function imageUploadwithName($firstname, $images,$user_id=null,$flag=null)
	{
		$dirswfl = $_SERVER['DOCUMENT_ROOT']."catracker/admin/uploadedImages/".$user_id;

		if(!is_dir($dirswfl))
		{
		$chmod = (is_dir($dirswfl) === true) ? 644 : 777;
		if (in_array(get_current_user(), array('apache', 'httpd', 'nobody', 'system', 'webdaemon', 'www', 'www-data')) === true)
		{
		$chmod += 22;
		}
		
		mkdir($dirswfl);
		chmod($dirswfl, octdec(intval($chmod)));
		
		}

		$target = $_SERVER['DOCUMENT_ROOT']."catracker/admin/uploadedImages/".$user_id."/";
		$imageCount = count($_FILES[$images]['name']);
		
		$pieces = explode(".",$_FILES[$images]['name']);
		$countPieces = count($pieces);
		if($countPieces == 2)
		{
			$extension = strtolower($pieces[1]);
		}
		else
		{
			$extension = strtolower($pieces[$countPieces-1]);
		}
		$extension = "png";
		$cleanstring = strtolower(trim(preg_replace('#\W+#', '_', $firstname), '_'));
		$photo_file = $user_id.'_'.$cleanstring."_".sha1($user_id."_".$firstname).'.'.$extension;
		$originalImage=$target.$photo_file;

		if(in_array($extension , array('jpg','jpeg', 'gif', 'png', 'bmp')))
		{	
			//Thumbnail file name File
			$image_filePath = $_FILES[$images]['tmp_name'];
			$img_fileName = sha1($user_id."_".$firstname.'_small').".".$extension;
			$img_thumb = $target.$img_fileName;
			
			$img_fileName2 = sha1($user_id."_".$firstname.'_thumb').".".$extension;
			$img_thumb2 = $target.$img_fileName2;
			$extension = strtolower($pieces[1]);
			
			$img_fileName3 = sha1($user_id."_".$firstname).".png";
			$img_thumb3 = $target.$img_fileName3;
			$extension = strtolower($pieces[1]);
			//Check the file format before upload
			
			//Find the height and width of the image
			list($gotwidth, $gotheight, $gottype, $gotattr)= getimagesize($image_filePath);
			//---------- To create thumbnail of image---------------
			if($extension=="jpg" || $extension=="jpeg" )
			{
				$src = imagecreatefromjpeg($_FILES[$images]['tmp_name']);
			}
			else if($extension=="png")
			{
				$src = imagecreatefrompng($_FILES[$images]['tmp_name']);
			}
			else
			{
				$src = imagecreatefromgif($_FILES[$images]['tmp_name']);
			}
			list($width,$height)=getimagesize($_FILES[$images]['tmp_name']);
			//This application developed by www.webinfopedia.com
			//Check the image is small that 124px
			/*if($gotwidth > 64)
			{*/
				$height=$gotheight;
				$width=$gotwidth;
				
				$maxwidth = 64;
				$maxheight = 64;
				if ($maxwidth < $width && $width >= $height) {
				  $desired_width = $maxwidth;
				  $desired_height = ($desired_width / $width) * $height;
				}
				elseif ($maxheight < $height && $height >= $width) {
				  $desired_height = $maxheight;
				  $desired_width = ($desired_height /$height) * $width;
				}
				else {
				  $desired_height = $height;
				  $desired_width = $width;
				}
				$newwidth=$desired_width;
				$newheight=$desired_height;
			/*}
			else
			{
				$newwidth=$gotwidth;
			}*/
			//if small let it be original

			
			//------
			/*if($gotwidth > 100)
			{*/
				$maxwidth = 100;
				$maxheight = 100;
				if ($maxwidth < $width && $width >= $height) {
				  $desired_width = $maxwidth;
				  $desired_height = ($desired_width / $width) * $height;
				}
				elseif ($maxheight < $height && $height >= $width) {
				  $desired_height = $maxheight;
				  $desired_width = ($desired_height /$height) * $width;
				}
				else {
				  $desired_height = $height;
				  $desired_width = $width;
				}
				$newwidth2=$desired_width;
				$newheight2=$desired_height;
			/*}
			else
			{
				$newwidth2=$gotwidth;
			}*/
			
			//------
			/*if($gotwidth > 300)
			{*/
				$maxwidth = 300;
				$maxheight = 300;
				if ($maxwidth < $width && $width >= $height) {
				  $desired_width = $maxwidth;
				  $desired_height = ($desired_width / $width) * $height;
				}
				elseif ($maxheight < $height && $height >= $width) {
				  $desired_height = $maxheight;
				  $desired_width = ($desired_height /$height) * $width;
				}
				else {
				  $desired_height = $height;
				  $desired_width = $width;
				}
				$newwidth3=$desired_width;
				$newheight3=$desired_height;
			/*}
			else
			{
				$newwidth3=$gotwidth;
			}*/

			
			//Find the new height
			//h
			//$newheight=round(($gotheight*$newwidth)/$gotwidth);
			
			//$newheight2=round(($gotheight*$newwidth2)/$gotwidth);
			// Enf h
			
			//Creating thumbnail
			$tmp=imagecreatetruecolor($newwidth,$newheight);
			$tmp2=imagecreatetruecolor($newwidth2,$newheight2);
			$tmp3=imagecreatetruecolor($newwidth3,$newheight3);
			
			imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight, $width,$height);
			imagecopyresampled($tmp2,$src,0,0,0,0,$newwidth2,$newheight2, $width,$height);
			imagecopyresampled($tmp3,$src,0,0,0,0,$newwidth3,$newheight3, $width,$height);
			
			//Create thumbnail image
			$createImageSave=imagepng($tmp,$img_thumb);
			$createImageSave2=imagepng($tmp2,$img_thumb2);
			$createImageSave3=imagepng($tmp3,$img_thumb3);
			
			if($createImageSave)
			{
				//upload the original file
				//$uploadOrginal=move_uploaded_file($_FILES[$images]['tmp_name'],$originalImage);
				//move_uploaded_file($_FILES[$images]['tmp_name'],$originalImage)
				if(1==1)
				{
					if($flag=='edit')
					{
						$imageName[] = $photo_file;
					}
					else
					{
						if($i==0)
						{
							$imageName = $photo_file;
						}
						else
						{
							$imageName .= ','.$photo_file;
						}	
					}
					return array($imageName, $img_fileName2 ,$img_fileName);
				}
				else
				{
					return "error";	
				}
			}				
		}	
	}
	
	function binarytoimageSmall($binaryString, $firstname, $user_id=null,$flag=null)
	{
		$dirswfl = $_SERVER['DOCUMENT_ROOT']."catracker/admin/uploadedImages/".$user_id;

		if(!is_dir($dirswfl))
		{
			$chmod = (is_dir($dirswfl) === true) ? 644 : 777;
			if (in_array(get_current_user(), array('apache', 'httpd', 'nobody', 'system', 'webdaemon', 'www', 'www-data')) === true)
			{
			$chmod += 22;
			}
			mkdir($dirswfl);
			chmod($dirswfl, octdec(intval($chmod)));
		}
		
		$target = $_SERVER['DOCUMENT_ROOT']."catracker/admin/uploadedImages/".$user_id."/";
		$binaryString = base64_decode($binaryString);
		$f = finfo_open();
		$mime_type = finfo_buffer($f, $binaryString, FILEINFO_MIME_TYPE);
		$binaryImage = imagecreatefromstring($binaryString);
		
		$uri = 'data://application/octet-stream;base64,' . base64_encode($binaryString);
		$info = getimagesize($uri);
		
		$width = $info[0];
		$height = $info[1];
	
		$maxwidth = 64;
		$maxheight = 64;
		if ($maxwidth < $width && $width >= $height) {
		  $desired_width = $maxwidth;
		  $desired_height = ($desired_width / $width) * $height;
		}
		elseif ($maxheight < $height && $height >= $width) {
		  $desired_height = $maxheight;
		  $desired_width = ($desired_height /$height) * $width;
		}
		else {
		  $desired_height = $height;
		  $desired_width = $width;
		}
		
		//$desired_width = 64;
		//$desired_height = 64;
		$binaryImage = imagecreatefromstring($binaryString);
		$new = imagecreatetruecolor($desired_width, $desired_height); 
		$x = imagesx($binaryImage);
		$y = imagesy($binaryImage);
		imagecopyresampled($new, $binaryImage, 0, 0, 0, 0, $desired_width, $desired_height, $x, $y);
		
		$cleanstring = strtolower(trim(preg_replace('#\W+#', '_', $firstname), '_'));
		$newNameImage = sha1($user_id."_".$firstname."_small").".png";

		$binaryImageg_thumb = $target.$newNameImage;

		/*if($mime_type == "image/png")
		{*/
			$black = imagecolorallocatealpha($new, 0, 0, 0, 127);
			imagealphablending($new, false);
			imagecolortransparent($new, $black);
			$createImageSave=imagepng($new,$binaryImageg_thumb);
		/*}
		else
		{
			$createImageSave=imagejpeg($new,$binaryImageg_thumb,100);
		}*/
//		imagedestroy($binaryImage);
//		exit();

//echo slencrypt("12test"); die;
		return $newNameImage;
	}
	
	function binarytoimageMedium($binaryString, $firstname, $user_id=null,$flag=null)
	{
		$dirswfl = $_SERVER['DOCUMENT_ROOT']."catracker/admin/uploadedImages/".$user_id;
	
		if(!is_dir($dirswfl))
		{
			$chmod = (is_dir($dirswfl) === true) ? 644 : 777;
			if (in_array(get_current_user(), array('apache', 'httpd', 'nobody', 'system', 'webdaemon', 'www', 'www-data')) === true)
			{
			$chmod += 22;
			}
			mkdir($dirswfl);
			chmod($dirswfl, octdec(intval($chmod)));
		}
		
		$target = $_SERVER['DOCUMENT_ROOT']."catracker/admin/uploadedImages/".$user_id."/";
		$binaryString = base64_decode($binaryString);
		$f = finfo_open();
		$mime_type = finfo_buffer($f, $binaryString, FILEINFO_MIME_TYPE);
		$binaryImage = imagecreatefromstring($binaryString);
		
		$uri = 'data://application/octet-stream;base64,' . base64_encode($binaryString);
		$info = getimagesize($uri);
		
		$width = $info[0];
		$height = $info[1];
	
		/*$maxwidth = 300;
		$maxheight = 300;
		if ($maxwidth < $width && $width >= $height) {
		  $desired_width = $maxwidth;
		  $desired_height = ($desired_width / $width) * $height;
		}
		elseif ($maxheight < $height && $height >= $width) {
		  $desired_height = $maxheight;
		  $desired_width = ($desired_height /$height) * $width;
		}
		else {
		  $desired_height = $height;
		  $desired_width = $width;
		}*/
		
		$desired_width = $width;
		$desired_height = $height;
		
		$binaryImage = imagecreatefromstring($binaryString);
		$new = imagecreatetruecolor($desired_width, $desired_height); 
		$x = imagesx($binaryImage);
		$y = imagesy($binaryImage);
		imagecopyresampled($new, $binaryImage, 0, 0, 0, 0, $desired_width, $desired_height, $x, $y);
		
		$cleanstring = strtolower(trim(preg_replace('#\W+#', '_', $firstname), '_'));
		$newNameImage = sha1($user_id."_".$firstname."_thumb").".png";
		
		$binaryImageg_thumb = $target.$newNameImage;
		
		/*if($mime_type == "image/png")
		{*/
			$black = imagecolorallocatealpha($new, 0, 0, 0, 127);
			imagealphablending($new, false);
			imagecolortransparent($new, $black);
			$createImageSave=imagepng($new,$binaryImageg_thumb);
		/*}
		else
		{
			$createImageSave=imagejpeg($new,$binaryImageg_thumb,100);
		}*/
//		imagedestroy($binaryImage);
		return $newNameImage;
	}
	
	function binarytoimage($binaryString, $firstname, $user_id=null,$flag=null)
	{
		$dirswfl = $_SERVER['DOCUMENT_ROOT']."catracker/admin/uploadedImages/".$user_id;
	
		if(!is_dir($dirswfl))
		{
			$chmod = (is_dir($dirswfl) === true) ? 644 : 777;
			if (in_array(get_current_user(), array('apache', 'httpd', 'nobody', 'system', 'webdaemon', 'www', 'www-data')) === true)
			{
			$chmod += 22;
			}
			mkdir($dirswfl);
			chmod($dirswfl, octdec(intval($chmod)));
		}
		
		$target = $_SERVER['DOCUMENT_ROOT']."catracker/admin/uploadedImages/".$user_id."/";
		$binaryString = base64_decode($binaryString);
		$f = finfo_open();
		$mime_type = finfo_buffer($f, $binaryString, FILEINFO_MIME_TYPE);
		$binaryImage = imagecreatefromstring($binaryString);
		
		$uri = 'data://application/octet-stream;base64,' . base64_encode($binaryString);
		$info = getimagesize($uri);
		
		$width = $info[0];
		$height = $info[1];
		
		$desired_height = $info[1];
		$desired_width = $info[0];
		  
		/*$maxwidth = 300;
		$maxheight = 300;
		if ($maxwidth < $width && $width >= $height) {
		  $desired_width = $maxwidth;
		  $desired_height = ($desired_width / $width) * $height;
		}
		elseif ($maxheight < $height && $height >= $width) {
		  $desired_height = $maxheight;
		  $desired_width = ($desired_height /$height) * $width;
		}
		else {
		  $desired_height = $height;
		  $desired_width = $width;
		}*/
		
		//$desired_width = 300;
		//$desired_height = 300;
		$binaryImage = imagecreatefromstring($binaryString);
		$new = imagecreatetruecolor($desired_width, $desired_height); 
		$x = imagesx($binaryImage);
		$y = imagesy($binaryImage);
		imagecopyresampled($new, $binaryImage, 0, 0, 0, 0, $desired_width, $desired_height, $x, $y);
		
		$cleanstring = strtolower(trim(preg_replace('#\W+#', '_', $firstname), '_'));
		$newNameImage = sha1($user_id."_".$firstname).".png";

		$binaryImageg_thumb = $target.$newNameImage;
		
		/*if($mime_type == "image/png")
		{ */
			$black = imagecolorallocatealpha($new, 0, 0, 0, 127);
			imagealphablending($new, false);
			imagecolortransparent($new, $black);
			$createImageSave=imagepng($new,$binaryImageg_thumb);
		/*}
		else
		{
			$createImageSave=imagejpeg($new,$binaryImageg_thumb,100);
		}*/
//		imagedestroy($binaryImage);
		return $newNameImage;
	}
	
	function webServiceImageUploadBinary($binaryString, $sender_id, $receiver_id, $conversation_id=null,$flag=null)
	{
		$dirswfl = $_SERVER['DOCUMENT_ROOT']."catracker/admin/chatImage/".$conversation_id;
	
		if(!is_dir($dirswfl))
		{
			$chmod = (is_dir($dirswfl) === true) ? 644 : 777;
			if (in_array(get_current_user(), array('apache', 'httpd', 'nobody', 'system', 'webdaemon', 'www', 'www-data')) === true)
			{
			$chmod += 22;
			}
			mkdir($dirswfl);
			chmod($dirswfl, octdec(intval($chmod)));
		}
		
		$target = $_SERVER['DOCUMENT_ROOT']."catracker/admin/chatImage/".$conversation_id."/";
		$binaryString = base64_decode($binaryString);

		$f = finfo_open();
		$mime_type = finfo_buffer($f, $binaryString, FILEINFO_MIME_TYPE);
		
		$binaryImage = imagecreatefromstring($binaryString);
		
		$uri = 'data://application/octet-stream;base64,' . base64_encode($binaryString);
		$info = getimagesize($uri);
		
		$width = $info[0];
		$height = $info[1];
	
		$desired_height = $height;
	  	$desired_width = $width;
		/*$maxwidth = 300;
		$maxheight = 300;
		if ($maxwidth < $width && $width >= $height) {
		  $desired_width = $maxwidth;
		  $desired_height = ($desired_width / $width) * $height;
		}
		elseif ($maxheight < $height && $height >= $width) {
		  $desired_height = $maxheight;
		  $desired_width = ($desired_height /$height) * $width;
		}
		else {
		  $desired_height = $height;
		  $desired_width = $width;
		}*/

		//$desired_height=round(($gotheight*$desired_width)/$gotwidth);

//		$desired_width = 300;
//		$desired_height = 300;
		
		$binaryImage = imagecreatefromstring($binaryString);
		$new = imagecreatetruecolor($desired_width, $desired_height); 
		$x = imagesx($binaryImage);
		$y = imagesy($binaryImage);
		imagecopyresampled($new, $binaryImage, 0, 0, 0, 0, $desired_width, $desired_height, $x, $y);
		
		$timeparts = explode(" ",microtime());
		$currenttime = ($timeparts[0]*1000);
		$uq_code = substr( ceil($currenttime) + rand("99999","99999999"), 0, 6);
		
		$cleanstring = strtolower(trim(preg_replace('#\W+#', '_',$uq_code), '_'));

		if($info['mime'] == "image/png")
		{
			$newNameImage = sha1($sender_id.'_'.$receiver_id).".png";
		}else
		{
			$newNameImage = sha1($sender_id.'_'.$receiver_id).".png";
		}

		$binaryImageg_thumb = $target.$newNameImage;

		if($info['mime'] == "image/png")
		{
			$black = imagecolorallocatealpha($new, 0, 0, 0, 127);
			imagealphablending($new, false);
			imagecolortransparent($new, $black);
			$createImageSave=imagepng($new,$binaryImageg_thumb,0);
		}
		else
		{
			$createImageSave=imagejpeg($new,$binaryImageg_thumb,100);
		}
//		imagedestroy($binaryImage);

		return $newNameImage;
	}
	
	function webServiceImageUploadBinaryThumb($binaryString, $sender_id, $receiver_id, $conversation_id=null,$flag=null)
	{
		$dirswfl = $_SERVER['DOCUMENT_ROOT']."catracker/admin/chatImage/".$conversation_id;
	
		if(!is_dir($dirswfl))
		{
			$chmod = (is_dir($dirswfl) === true) ? 644 : 777;
			if (in_array(get_current_user(), array('apache', 'httpd', 'nobody', 'system', 'webdaemon', 'www', 'www-data')) === true)
			{
			$chmod += 22;
			}
			mkdir($dirswfl);
			chmod($dirswfl, octdec(intval($chmod)));
		}
		
		$target = $_SERVER['DOCUMENT_ROOT']."catracker/admin/chatImage/".$conversation_id."/";
		$binaryString = base64_decode($binaryString);

		$f = finfo_open();
		$mime_type = finfo_buffer($f, $binaryString, FILEINFO_MIME_TYPE);
		
		$binaryImage = imagecreatefromstring($binaryString);
		
		$uri = 'data://application/octet-stream;base64,' . base64_encode($binaryString);
		$info = getimagesize($uri);
		
		$width = $info[0];
		$height = $info[1];

		$maxwidth = 300;
		$maxheight = 300;
		if ($maxwidth < $width && $width >= $height) {
		  $desired_width = $maxwidth;
		  $desired_height = ($desired_width / $width) * $height;
		}
		elseif ($maxheight < $height && $height >= $width) {
		  $desired_height = $maxheight;
		  $desired_width = ($desired_height /$height) * $width;
		}
		else {
		  $desired_height = $height;
		  $desired_width = $width;
		}

		//$desired_height=round(($gotheight*$desired_width)/$gotwidth);

//		$desired_width = 300;
//		$desired_height = 300;
		
		$binaryImage = imagecreatefromstring($binaryString);
		$new = imagecreatetruecolor($desired_width, $desired_height); 
		$x = imagesx($binaryImage);
		$y = imagesy($binaryImage);
		imagecopyresampled($new, $binaryImage, 0, 0, 0, 0, $desired_width, $desired_height, $x, $y);
		
		$timeparts = explode(" ",microtime());
		$currenttime = ($timeparts[0]*1000);
		$uq_code = substr( ceil($currenttime) + rand("99999","99999999"), 0, 6);
		
		$cleanstring = strtolower(trim(preg_replace('#\W+#', '_',$uq_code), '_'));

		if($info['mime'] == "image/png")
		{
			$newNameImage = sha1($sender_id.'_'.$receiver_id.'_thumb').".png";
		}else
		{
			$newNameImage = sha1($sender_id.'_'.$receiver_id.'_thumb').".png";
		}

		$binaryImageg_thumb = $target.$newNameImage;

		if($info['mime'] == "image/png")
		{
			$black = imagecolorallocatealpha($new, 0, 0, 0, 127);
			imagealphablending($new, false);
			imagecolortransparent($new, $black);
			$createImageSave=imagepng($new,$binaryImageg_thumb,0);
		}
		else
		{
			$createImageSave=imagejpeg($new,$binaryImageg_thumb,100);
		}
//		imagedestroy($binaryImage);

		return $newNameImage;
	}
?>