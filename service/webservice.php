<?php

$file = fopen('php://input','r');
$jsonInput ="";
while(! feof($file))
  {
	$jsonInput .= fgets($file);
  }

fclose($file);

$input_params  = json_decode($jsonInput,true);



	include("include/common_vars.php");
	include("include/common_class.php");
	include("include/session.php");
	include("include/config.php");
	include("include/function.php");
	
	date_default_timezone_set("UTC");
	if($input_params['mode'] == 'registerUser')
	{
		$firstName = $input_params['firstName'];
		$password = $input_params['password'];
		$userName = $input_params['userName'];
		$email = $input_params['emailId'];
		$lastName = $input_params['lastName'];
		$updatedate = $input_params['updatedate'];		
		$birthDate =  $input_params['birthDate'];
		$birthDate = str_replace("/", "-", $birthDate);
		$birthDate = date("Y-m-d", strtotime($birthDate));
//echo "<pre>";print_r($input_params);die;
		if(!isset($firstName) || empty($userName) || empty($email) || !isset($birthDate) ||  empty($lastName) || empty($password) || empty($updatedate))
		{
			header('Content-type: application/json');
			echo json_encode(array('status'=>0,'message'=>'Please fill all require fields.'));
		}
		else
		{	
			
			$query_user_detail=$con->select_query("user","*"," where user_id !='".$userId."' and email='".$email."' ");
			

			if(mysql_num_rows($query_user_detail) > 0){
				header('Content-type: application/json');
				echo json_encode(array('status'=>1,'message'=>'Email already exists.'));
				die;
			}else{

				$fields = array( "username" => $userName,
								 "firstname" => $firstName,
								 "lastname" => $lastName,
								 "password" => $password,
								 "email" => $email,
								 "birthDate" => $birthDate,
								 "updatedate" => $updatedate								 );
			}
		
				$code_update=$con->insert_record("user",$fields,"");
				$user_Id = mysql_insert_id();
				header('Content-type: application/json');
				echo json_encode(array('status'=>1,'userId'=> $user_Id,'message'=>'You are successfully registered.'));
		
		}
	}
	else if($input_params['mode'] == 'userAuthantication')
	{
		$emailId = $input_params['emailId'];
		$password = $input_params['password'];
		$deviceToken = $input_params['devicetoken'];
		
		if(empty($emailId) || empty($password))
		{
			header('Content-type: application/json');
			echo json_encode(array('status'=>0,'message'=>'Please fill all require fields.'));
		}
		else
		{
			$query_user_detail=$con->select_query("user","*"," where email='".$emailId."' AND password='".$password."' ","","");
			$row_state=mysql_fetch_assoc($query_user_detail);

			if(!empty($row_state))
			{
				if($row_state['password']==$password)
				{

					$userDetail = array("userId"=>intval($row_state['user_id']),
									    "userName"=>$row_state['username'],
									     "firstName"=>$row_state['firstname'],
									     "lastName"=>$row_state['lastname'],
									    "emailId"=>$row_state['email'],
									    "password"=>$row_state['password'],
									 	"birthYear"=>$row_state['birthDate'],
									 	);


						header('Content-type: application/json');
						echo json_encode(array('status'=>1,'message'=>'You have successfully logged in.','userDetail'=>$userDetail));
						die;
					
				}
				else
				{
					header('Content-type: application/json');
					echo json_encode(array('status'=>0,'message'=>'Invalid username or password.'));
				}
			}
			else
			{
				header('Content-type: application/json');
				echo json_encode(array('status'=>0,'message'=>'Invalid username or password.'));
			}
		}
	}
	else if($input_params['mode']=="emergency"){


		$checkInactiveUserSql = mysql_query("select * from emergency");
		while($inactive_state=mysql_fetch_assoc($checkInactiveUserSql)){
$img =  "http://" . $_SERVER['SERVER_NAME'].'/service/admin/images/'.$inactive_state['image'];
			$pages[] = array(' e_id'=> $inactive_state['e_id'],
							  'police_Number'=> $inactive_state['police_Number'],
							  'Hospital_Number'=> $inactive_state['Hospital_Number'],
							  'Fire_Number'=> $inactive_state['Fire_Number']);
		}
		if(empty($pages))
		{
			header('Content-type: application/json');
			echo json_encode(array('status'=>0,'message'=>'No emergency found.'));
			exit;
		}else{

			header('Content-type: application/json');
			echo json_encode(array('status'=>1,'emergency'=>$pages,'message'=>'Get emergency details  successfully.'));

		}
	}
	// Maintence Details 
	else if($input_params['mode'] == 'Maintence')
	{
		$House_Number = $input_params['House_Number'];
		$stetus = $input_params['stetus'];
		
		$deviceToken = $input_params['devicetoken'];
		
		if(empty($House_Number) || empty($stetus))
		{
			header('Content-type: application/json');
			echo json_encode(array('status'=>0,'message'=>'Please fill all require fields.'));
		}
		else
		{
			$query_user_detail=$con->select_query("maintenance","*"," where House_Number='".$House_Number."' AND stetus='".$stetus."' ","","");
			$row_state=mysql_fetch_assoc($query_user_detail);

			if(!empty($row_state))
			{
				if($row_state['House_Number']==$House_Number)
				{

					$userDetail = array("m_id"=>intval($row_state['m_id']),
									    "House_Number"=>$row_state['House_Number'],
									     "month"=>$row_state['month'],
									     "rate"=>$row_state['rate'],
									    "stetus"=>$row_state['stetus'],
									    
									 	);


						header('Content-type: application/json');
						echo json_encode(array('status'=>1,'message'=>'You have successfully logged in.','userDetail'=>$userDetail));
						die;
					
				}
				else
				{
					header('Content-type: application/json');
					echo json_encode(array('status'=>0,'message'=>'Invalid username or password.'));
				}
			}
			else
			{
				header('Content-type: application/json');
				echo json_encode(array('status'=>0,'message'=>'Invalid username or password.'));
			}
		}
	}
	
	else if($input_params['mode'] == 'forgotPassword')
	{
		$email = $input_params['email'];
		
		if(empty($email))
		{
			header('Content-type: application/json');
			echo json_encode(array('status'=>'ERROR','message'=>'Please fill all require fields.'));
		}
		else
		{
		$query_user_detail=$con->select_query("user","user_id,firstname,lastname,email,password"," where email='".$email."'","","");
		
		$row_state=mysql_fetch_assoc($query_user_detail);
		if(!empty($row_state))
		{
			if($row_state['email']==$email)
			{
				//-----------Start Unique code for verication---------------//
					$timeparts = explode(" ",microtime());
					$currenttime = ($timeparts[0]*1000);
					$verify_code = substr( ceil($currenttime) + rand("99999","99999999"), 0, 8);
				//-----------End Unique code for verication---------------//
				
				$body = "<html> 
						<body><p>Hello ".$row_state['firstname']."&nbsp;".$row_state['lastname'].",</p>
						<p>Your Account Details are </p><p><b>Email:</b> ".$row_state['email']."</p><p><b>Password:</b> ".$verify_code."</p>
						<p>Thank You,</p><p>DMTC</p></body></html>";

				$mail = new PHPMailer();
				$mail->IsSMTP(); // telling the class to use SMTP
				$mail->Host       = 'smtp.gmail.com'; // SMTP server
				$mail->Username   = 'niyatimodi58@gmail.com'; 
				$mail->Password   = 'patel@patel1';
				$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
				$mail->mail = $mail;
				$mail->From = 'phpredstorm@gmail.com';
				$mail->FromName = 'DMTC'; 
				$mail->Subject = 'DMTC Account Forgot Password';
				$mail->AddAddress($row_state['email']);
				$mail->MsgHTML($body);
				
				header('Content-type: application/json');
				if($mail->send())
				{					
					$updatefields = array("password"=>$verify_code);
					$code_update=$con->update("user",$updatefields," where user_id='".$row_state['user_id']."'");
				
					echo json_encode(array('status'=>"OK",'message'=>'Your password has been sent to your registered Email-id.')); 
				}
				else
				{
					echo json_encode(array('status'=>"ERROR",'message'=>'Error in sending email, Please try again.'));
				}
			}
			else
			{
				header('Content-type: application/json');
				echo json_encode(array('status'=>"ERROR",'message'=>'Email does not exist.'));
			}
		}
		else
		{
			header('Content-type: application/json');
			echo json_encode(array('status'=>"ERROR",'message'=>'Email does not exist.'));
		}
		  }
	}
	
?>