<?php 
   class DBAccess
   {
     		private $db_link;
            function DBAccess($DB_HOST, $DB_USER, $DB_PASS,$DB_NAME)
            {
				      $this->db_link = mysql_connect($DB_HOST, $DB_USER,$DB_PASS); 
	                  if ($this->db_link == false)
		              { 
	                           echo mysql_errno().": ".mysql_error()."<br>"; 
                               die();
                      } 

                                  $db = mysql_select_db($DB_NAME);
					              if($db==false)
					              {
			                            echo mysql_errno().": ".mysql_error()."<br>"; 
	                                    die();
		                          }
	          }
                        /* 

                        * SelectQuery 

                        * Desc: Retrieve data from the database. 

                        * Parms: 

                        *   $tables - comma separated list of table names. 

                        *   $fields - comma separated list of field names or "*". 

                        *   $where - SQL Where clause (e.g. "where id=2"). 

                        *   $groupBy - SQL Group clause (e.g. "group by name"). 

                        *   $orderBy - SQL Order clause (e.g. "order by name"). 

                        *   $show_debug - If true then print SQL query. 

                        * Returns: 

                        *   2d array of rows and columns on success. 

                        *   Error String on failure. 

                        */ 

                        function select_query ($show_debug=false,$tables, $fields, $where="",$limit="",$groupBy="", $orderBy="") 
                        { 
                                    // Return the data requested by the fields, tables and where. 
                                    // Return the data in a 2 dimensional array. 
                                    $values = array(); 
                                    if (!empty($where))
									{ 
                                       if (!strstr(strtolower($where),"where ")) 
									   		$where = "where $where"; 
									} 
                                    $query = "select $fields from $tables $where $limit $groupBy $orderBy"; 
									//die($query);
									if ($show_debug == true) echo "query=$query<br>\n"; 
                                    $stmt = mysql_query ($query, $this->db_link); 
                                    if ($stmt == false)
									{
                                       echo mysql_errno().": ".mysql_error()."<br>"; 
                                    } 
                                    while ($fields = mysql_fetch_assoc($stmt))
									{ 
										   $values[] = $fields; 
                                    } 
                                    @mysql_free_result ($stmt); 
                                    @mysql_close($db); 
                                    return $values; 
                        } 

                        /********************************************************/ 
                        /* 
                        * InsertQuery 

                        * Desc: Insert data into the database. 

                        * Parms: 

                        *   $tableName - database table name. 

                        *   $values - associative array of field names and corresponding values. 

                        *   $debug - If true then return SQL query without executing. 

                        * Returns: 

                        *   Nothing on success. 

                        *   Error String on failure. 

                        */ 
                         function insert_query($tableName, $values, $debug=false) 
                         { 
                                      /* Insert the $values into the database. 

                                       * e.g. 

                                       * $values = array ("name"=>"kris","email"=>"karn@nucleus.com"); 

                                       * InsertQuery ("employee", $values); 

                                      */ 
									  return $this->InsertUpdateQuery("", $tableName, $values, $debug);
                         } 
                         /********************************************************/ 
                         /* 

                          * UpdateQuery 

                          * Desc: Update data in the database. 

                          * Parms: 

                          *   $tableName - database table name. 

                          *   $values - associative array of field names and corresponding values. 

                          *   $where - SQL Where clause to specify which row(s) to update. 

                          *   $debug - If true then return SQL query without executing. 

                          * Returns: 

                          *   Nothing on success. 

                          *   Error String on failure. 

                          */ 

                         function UpdateQuery ($tableName, $values, $where="", $debug=false) 
                         { 
                                      /* Update the $values in the database. 

                                       * e.g. 

                                       * $values = array ("name"=>"kris","email"=>"karn@nucleus.com"); 

                                       * $where = "WHERE id='1'"; 

                                       * UpdateQuery ("employee", $values, $where); 

                                      */ 
								
                                      if (!empty($where)) $where = " where $where "; 

                                      $this->InsertUpdateQuery($where, $tableName, $values, $debug); 
                         } 
                         /********************************************************/ 
                         function InsertUpdateQuery ($type, $tableName, $fieldValues, $debug=false) 
                         { 
						 
						 			
                                      $i = 0; 
                                      $fields = ""; 
                                      $values = ""; 
                                      $updateList = ""; 
                                      while (list ($key, $val) = each ($fieldValues))
									  { 
							             if ($i > 0){ 
										$fields .= ", "; 

										$values .= ", "; 

										$updateList .= ", "; 
    			                     } 
                
				                     $fields .= $key; 

                                                   // If you do not want to add quotes 

                                                   // around the field then specify 

                                                   // /*NO_QUOTES*/ when passing in the value. 

                                                   // For update statements like 

                                                   // "update poll set total_votes=total_votes+1", 

                                                   // you do not want 

                                                   // the value field to have quotes around it. 

                                                   if (strstr($val,"/*NO_QUOTES*/"))
												   { 
                                                                        $val = str_replace ("/*NO_QUOTES*/", "", $val); 
                                                                        $updateList .= "$key=$val"; 
                                                                        $values .= $val; 
                                                   } 
                                                   else
												   { 
											                     $updateList .= "$key='$val'"; 
									                            $values .= "'$val'"; 
                                                   } 

                        

                                                   $i++; 

                        

                                      } 
                                      if (empty($type)){ 
                                                   $query = "insert into $tableName ($fields) values ($values)"; 
                                      } 
                                      else{ 
                                                   $query = "update $tableName set $updateList $type"; 
                                      } 
                                      if ($debug)
									  { 
                                                  //@mysql_close($db); 
                                                  echo $query; 
                                      } 
									 // die($query);
                                      $stmt = mysql_query ($query, $this->db_link); 
                                      if (!$stmt){ 
                                                   
												   echo $query; 
												   echo mysql_error();
                                                   die();
                                      } 
									  
									  return mysql_insert_id($this->db_link);
                                      @mysql_free_result ($stmt); 
                         } 

            

                                                                                                 
            /* 

                        * DeleteQuery 

                        * Desc: Delete data from the database. 

                        * Parms: 

                        *   $tableName - database table name. 

                        *   $where - SQL Where clause to specify which row(s) to delete. 

                        *   $debug - If true then return SQL query without executing. 

                        * Returns: 

                        *   Nothing on success. 

                        *   Error String on failure. 

                        */ 

                        function delete_query ($tableName, $where="", $debug=false) 
						{ // Delete a row from the specified table. 
							  if (!empty($where))
							  { 
							  	if (!strstr(strtolower($where),"where ")) 
									$where = "where $where"; 
	                          } 
									  $query = "delete from $tableName $where";
									  //die($query); 
						  	if ($debug)
						    { 
								  @mysql_close($db); 
								  return $query; 
							} 
					              $stmt = mysql_query ($query, $this->db_link); 
		                          if (!$stmt)
								  { 
	                              	 echo mysql_error();
									 die();

		                          } 
			                          @mysql_free_result ($stmt); 
			            } 
						
						
						function delete_query_multiple ($tmp,$tableName, $where="", $debug=false) 
						{ // Delete a row from the specified table. 
							  if (!empty($where))
							  { 
							  	if (!strstr(strtolower($where),"where ")) 
									$where = "where $where"; 
	                          } 
									  $query = "delete $tmp from $tableName $where";
									  //die($query); 
						  	if ($debug)
						    { 
								  @mysql_close($db); 
								  return $query; 
							} 
					              $stmt = mysql_query ($query, $this->db_link); 
		                          if (!$stmt)
								  { 
	                              	 echo mysql_error();
									 die();

		                          } 
			                          @mysql_free_result ($stmt); 
			            } 

 

            function get_record_count($tables, $where="",$limit="",$groupBy="", $orderBy="", $show_debug=false)
            {
                        if (!empty($where))
						{ 
                               if (!strstr(strtolower($where),"where ")) 
							   		$where = "where $where"; 
			            } 
						$query = "select count(*) as Total from $tables $where $limit $groupBy $orderBy"; 
						if ($show_debug == true) echo "query=$query<br>\n"; 
						$stmt = mysql_query ($query, $this->db_link); 
						if ($stmt == false){ 
						   echo mysql_errno().": ".mysql_error()."<br>"; 
						} 
						$num_rows = mysql_fetch_assoc($stmt);
						@mysql_free_result ($stmt); 
						return $num_rows["Total"];                                  
            }
			
			function get_record_count_one($query, $show_debug=false)
            {
						
						if ($show_debug == true) echo "query=$query<br>\n"; 
						$stmt = mysql_query ($query, $this->db_link); 
						if ($stmt == false){ 
						   echo mysql_errno().": ".mysql_error()."<br>"; 
						} 
						$num_rows = mysql_fetch_assoc($stmt);
						@mysql_free_result ($stmt); 
						return $num_rows["Total"];                                  
            }
			
			

            function genrate_combo_options ($tables, $option_field,$value_field, $where="",$limit="",$groupBy="", $orderBy="", $show_debug=false) 
            {
                        $fields = $option_field.",".$value_field;
                        $records = $this->select_query($tables,$fields,$where,$limit,$groupBy, $orderBy,$show_debug);
                        for($count=0;$count<count($records);$count++)
                        {
                                    $return_array[$records[$count][$option_field]] = $records[$count][$value_field];
                        }
                        return $return_array;
            }
            function simple_query($query , $show_debug=false) 
             { 
                          // echo $query;
						          // Return the data requested by the fields, tables and where. 
                                    // Return the data in a 2 dimensional array. 
                                    $values = array(); 
                                    //$query = "select $fields from $tables $where $limit $groupBy $orderBy"; 
						if ($show_debug == true) echo "query=$query<br>\n"; 
						$stmt = mysql_query ($query, $this->db_link); 
						if ($stmt == false){ 

						   echo mysql_errno().": ".mysql_error()."<br>"; 
						   die(); 
						} 
						//die($query);
						while ($fields = mysql_fetch_assoc($stmt)){ 

						   $values[] = $fields; 
						} 
						@mysql_free_result ($stmt); 
						@mysql_close($db); 
						return $values; 
            } 

 

}

?>
