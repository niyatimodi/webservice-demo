<?php

	class Db_connect

  	{

    	var $user;

		var $pass;

		var $db;

		var $host;

		var $table;

		function Db_connect($host,$user_name,$password,$database)

	  	{

			$link=mysql_connect ($host, $user_name, $password) or die ('cannot connect to the database because: ' . mysql_error());

         	mysql_select_db ($database);

	 	}

	 	function check_admin($usr,$pass,$table)

	 	{

   			$sql="select *from ".$table;

			$result=mysql_query($sql) or die("login error".mysql_error());

			$row=mysql_fetch_object($result);

			if($usr==$row->admin_username)

		 	{

		   		$f=1;

		   		$_SESSION['$usr'];

		

			}  

			else

		 	{

		   		$f=0;

		 	}								 

		 	return $f;

	  	}//end of function

		function select_query($table,$fields,$condition="",$limit="",$display_query="")

     	{

			

			$this->table=$table;

	   		$sql="select ".$fields." from ".$table." ".$condition." ".$limit;

	   		

		//echo "<br> query is ".$sql."<br>";

			if($display_query !="")

			{

				echo "<br> query is ".$sql."<br>";

			}

			//echo $sql;

			$result=mysql_query($sql) or die("There is some error in select query table name is :".$table.mysql_error());	   

			return $result;

     	}

		

		function total_records($rs)

     	{

			return mysql_num_rows($rs);

     	}

		function maxid($table,$field,$alias,$condition="",$display_query="")

		{ 

			if($condition!="")

				$res="select ifnull(max(".$field."),0)+1 as ".$alias." from ".$table." ".$condition;

			else

				$res="select ifnull(max(".$field."),0)+1 as ".$alias." from ".$table;

			if($display_query !="")

			{

				echo "<br> query is ".$res."<br>";

			}

			$result=mysql_query($res);			

			$row_maxid=mysql_fetch_object($result);

			$max_id=$row_maxid->$alias;			

			return $max_id;

		}		

		function minid($table,$field,$alias,$condition="",$display_query="")

		{ 

			if($condition!="")

				$res="select ifnull(min(".$field."),0)+1 as ".$alias." from ".$table." ".$condition;

			else

				$res="select ifnull(min(".$field."),0)+1 as ".$alias." from ".$table;

			if($display_query !="")

			{

				echo "<br> query is ".$res."<br>";

			}

			$result=mysql_query($res);			

			$row_minid=mysql_fetch_object($result);

			$min_id=$row_minid->$alias;			

			return $min_id;

		}		

	  	function insert_record($table,$field)

	   	{

		  	$q="";

		  	$q1 = "";

		 	foreach($field as $i => $value)

		 	{

				$value = addslashes($value);

				$q=$q.$i.",";

			 	$q1=$q1."'".$value."',";

				//echo $q1;

				//die();

			  	

	     	}	  

			$q=substr($q,0,strlen($q)-1);

			$q1=substr($q1,0,strlen($q1)-1);

			

		 	$sql="insert into ".$table."(".$q .") values (".$q1.")";

		 	//echo "Insert SQL==".$sql; die;

 	     	$rs=mysql_query($sql) or die("There is some error in insert query in table ".$table.mysql_error());

		 	return $rs;

		}//end of insert

		/*function update_record($table,$field,$condition)

	   	{

		  	$q="";

		  	$q1="";

		 	foreach($field as $i => $value , $j=>$key)

		 	{

				echo "<br>".$j;

				/*$q=$q.$i.",";

			 	if(is_numeric($value))

			  	{

			   		$q1=$q1.$value.",";

			  	}

			  	else

			  	{

					$q1=$q1."'".$value."',";

			  	}*/

	     	//}	  

			/*$q=substr($q,0,strlen($q)-1);

			$q1=substr($q1,0,strlen($q1)-1);

		 	$sql="update  ".$table." set ".$q .") values (".$q1.")";

		 	//echo "Insert SQL==".$sql;

 	     	$rs=mysql_query($sql) or die("There is some error in insert query:".mysql_error());

		 	return $rs;*/

		//}//end of insert

  	  function update($table,$field,$condition)

	   {

	      

	      $q="";

		  $q1="";

		  $str="";

		  foreach($field as $i => $value)

		  {

			$q=$q.$i.",";

			$q1=$q1.$value.",";

			$str=$str.$i." = '".$value."',";

			

			//$q=$q.$i.",";

		 	//$q1=$q1."'".$value."',";

	      }

		  $q=substr($q,0,strlen($q)-1);

		  $q1=substr($q1,0,strlen($q1)-1);

		 

		  $str=substr($str,0,strlen($str)-1);

		  $sql="update ".$table." set ".$str."".$condition;
		 

		  //echo "<br>".$sql;

		  $result_update=mysql_query($sql)or die("update query==".mysql_error());

		  return $result_update;

	   }//end of update	   



	    function search_record($table,$field,$criteria,$condition="")

		{

	    	echo "test";

		   	$q="";

		   	$q1="";

		   	$str="";

           	if($criteria =="OR")

			{

		  		foreach($field as $i => $value)

			   	{

			     	$q=$q.$i.",";

					if(is_numeric($value))

				  	{ 

				   		if($value !=NULL)

						{

					  		$q1=$q1.$value.",";

					  		$str=$str.$i."= ".$value." OR ";

						}

				   		else

						{

					   		$q1=$q1." NULL ".",";

					   		$str=$str.$i."= NULL "." OR ";

						}

			 	  	} 

				  	else

			      	{

					 	if($value != "")

					 	{

							$q1=$q1."'".$value."',";

					   		$str=$str.$i."= '".$value."'"." OR ";

			  	     	}

					 	else

					  	{

							 $q1=$q1."''";

					     	$str=$str.$i."= '' "." OR ";

					  	}	

				   	}

				}	  

		   		$str=substr($str,0,strlen($str)-4);

	            $sql="select * from ".$table ." where  ".$str;

				$rs=mysql_query($sql);	

				return $rs;

			} //end of if criteria

			else

			{

				foreach($field as $i => $value)

			   	{

			     	$q=$q.$i.",";

				 	if(is_numeric($value))

				  	{ 

				    	if($value !=NULL)

					 	{

					   		$q1=$q1.$value.",";

					   		$str=$str.$i."= ".$value." AND ";

					 	}

					 	else

					 	{

					   		$q1=$q1." NULL ".",";

					 	}

				 	}

				 	else

				  	{

					 	if($value != "")

					 	{

				 	   		$q1=$q1."'".$value."',";

					   		$str=$str.$i."= '".$value."'"." AND ";

					 	}

					 	else

					  	{

						 	$q1=$q1."''";

					   	}	

				   	}

				}	  

		   		$str=substr($str,0,strlen($str)-4);

	            $sql="select * from ".$table ." where  ".$str;

				$rs=mysql_query($sql);	

				return $rs;

			}

	  	}//end of search

	 	

		function delete($table,$condition)

	    {

			$sql="delete from ".$table." ".$condition ;

		   	//echo $sql;

		   	$rs=mysql_query($sql) or die("There is some error in delete query :".mysql_error());

		    return $rs;

		}  

		function check_duplicate($table_name,$field_name,$condition,$print_query="")

		{

			$query = "select ".$field_name."  from ".$table_name." ". $condition;

			if($print_query!="")

				echo $query;			

			$rs = mysql_query($query) or  die("Some error in query:". mysql_error());

			$num=mysql_num_rows($rs);

			if($num==0)

				return false;

			else

				return true;		

		}

 

		

	}//end of class DB_connect

?>