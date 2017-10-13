<?php
class Pagging {

   /* These are defaults */
   var $TotalResults;
   var $CurrentPage = 1;
   var $PageVarName = "page";
   var $ResultsPerPage = 20;
   var $LinksPerPage = 10;
   var $PaggingMethod;
   var $QueryString;

   function Pagging($PaggingMethod="get",$ResultsPerPage="20")
   {
   		$this->PaggingMethod = $PaggingMethod;
		$this->ResultsPerPage = $ResultsPerPage;
		

		
		
   }

   function InfoArray() {
      $this->TotalPages = $this->getTotalPages();
      $this->CurrentPage = $this->getCurrentPage();
      $this->ResultArray = array(
                           "PREV_PAGE" => $this->getPrevPage(),
                           "NEXT_PAGE" => $this->getNextPage(),
                           "CURRENT_PAGE" => $this->CurrentPage,
                           "TOTAL_PAGES" => $this->TotalPages,
                           "TOTAL_RESULTS" => $this->TotalResults,
                           "PAGE_NUMBERS" => $this->getNumbers(),
                           "MYSQL_LIMIT1" => $this->getStartOffset(),
                           "MYSQL_LIMIT2" => $this->ResultsPerPage,
                           "START_OFFSET" => $this->getStartOffset(),
                           "END_OFFSET" => $this->getEndOffset(),
                           "RESULTS_PER_PAGE" => $this->ResultsPerPage,
                           );
      
	  
	  //print_r($this->ResultArray);		 
	  return $this->ResultArray;
   }

   /* Start information functions */
   function getTotalPages() {
      /* Make sure we don't devide by zero */
      if($this->TotalResults != 0 && $this->ResultsPerPage != 0) {
         $result = ceil($this->TotalResults / $this->ResultsPerPage);
      }
      /* If 0, make it 1 page */
      if(isset($result) && $result == 0) {
         return 1;
      } else {
         return $result;
      }
   }

   function getStartOffset() {
      $offset = $this->ResultsPerPage * ($this->CurrentPage - 1);
      //if($offset != 0) { //$offset++; }
      return $offset;
   }

   function getEndOffset() {
      if($this->getStartOffset() > ($this->TotalResults - $this->ResultsPerPage)) 
	  {
         $offset = $this->TotalResults;
      } 
	  elseif($this->getStartOffset() != 0) 
	  {
         $offset = $this->getStartOffset() + $this->ResultsPerPage - 1;
      } 
	  else 
	  {
         $offset = $this->ResultsPerPage;
      }
      return $offset;
   }

   function getCurrentPage() 
   {
   	  $get_var = $_REQUEST[$this->PageVarName];
      if(isset($get_var) && !empty($get_var))
	  {
         return $get_var;
		 
      } else {
         return $this->CurrentPage;
      }
	  //return $_GET[$this->PageVarName];
	 //echo "page:".;
   }

   function getPrevPage() {
      if($this->CurrentPage > 1) {
         return $this->CurrentPage - 1;
      } else {
         return false;
      }
   }

   function getNextPage() {
      if($this->CurrentPage < $this->TotalPages) {
         return $this->CurrentPage + 1;
      } else {
         return false;
      }
   }

   function getStartNumber() {
      $links_per_page_half = $this->LinksPerPage / 2;
      /* See if curpage is less than half links per page */
      if($this->CurrentPage <= $links_per_page_half || $this->TotalPages <= $this->LinksPerPage) {
         return 1;
      /* See if curpage is greater than TotalPages minus Half links per page */
      } elseif($this->CurrentPage >= ($this->TotalPages - $links_per_page_half)) {
         return $this->TotalPages - $this->LinksPerPage + 1;
      } else {
         return $this->CurrentPage - $links_per_page_half;
      }
   }

   function getEndNumber() {
      if($this->TotalPages < $this->LinksPerPage) {
         return $this->TotalPages;
      } else {
         return $this->getStartNumber() + $this->LinksPerPage - 1;
      }
   }

   function getNumbers() {
      for($i=$this->getStartNumber(); $i<=$this->getEndNumber(); $i++) {
         $numbers[] = $i;
      }
      return $numbers;
   }
   function get_limit()
   {
   		$InfoArray = $this->InfoArray();
		return "limit ".$InfoArray["MYSQL_LIMIT1"].",".$InfoArray["MYSQL_LIMIT2"];
   }
   function print_pagging()
   {
   		/* Get our array of valuable paging information! */
	   $InfoArray = $this->InfoArray();
		//print_r($InfoArray);
	   
	   /* Everything below here are just examples! */
	
	   /* Print our some info like "Displaying page 1 of 49" */
	   //echo "Displaying page " . $InfoArray["CURRENT_PAGE"] . " of " . $InfoArray["TOTAL_PAGES"] . "<BR>";
	   //echo "Displaying results " . $InfoArray["START_OFFSET"] . " - " . $InfoArray["END_OFFSET"] . " of " . $InfoArray["TOTAL_RESULTS"] . "<BR>";
	
	   /* Print our first link */
	   if($InfoArray["CURRENT_PAGE"]!= 1) {
		  echo "<a href='?page=1' class='lastViewLink'>&lt;&lt;</a> ";
	   } else {
		  echo "&lt;&lt; ";
	   }
	
	   /* Print out our prev link */
	   if($InfoArray["PREV_PAGE"]) {
		  echo "<a href='?page=" . $InfoArray["PREV_PAGE"] . "' class='lastViewLink'>Previous</a> | ";
	   } else {
		  echo "Previous | ";
	   }
	
	   /* Example of how to print our number links! */
	   for($i=0; $i<count($InfoArray["PAGE_NUMBERS"]); $i++) {
		  if($InfoArray["CURRENT_PAGE"] == $InfoArray["PAGE_NUMBERS"][$i]) {
			 echo $InfoArray["PAGE_NUMBERS"][$i] . " | ";
		  } else {
			 echo "<a href='?page=" . $InfoArray["PAGE_NUMBERS"][$i] . "' class='lastViewLink'>" . $InfoArray["PAGE_NUMBERS"][$i] . "</a> | ";
		  }
	   }
	
	   /* Print out our next link */
	   if($InfoArray["NEXT_PAGE"]) {
		  echo " <a href='?page=" . $InfoArray["NEXT_PAGE"] . "' class='lastViewLink'>Next</a>";
	   } else {
		  echo " Next";
	   }
	
	   /* Print our last link */
	   if($InfoArray["CURRENT_PAGE"]!= $InfoArray["TOTAL_PAGES"]) {
		  echo " <a href='?page=" . $InfoArray["TOTAL_PAGES"] . "' class='lastViewLink'>>></a>";
	   } else {
		  echo " &gt;&gt;";
	   } 
   }
   
   
   function print_pagging_users()
   {
   	
   		/* Get our array of valuable paging information! */
	   $InfoArray = $this->InfoArray();
	   if($InfoArray["TOTAL_RESULTS"]==0)
	   {
		   	return;
	   }
		//print_r($InfoArray);
	   
	   /* Everything below here are just examples! */
	
	   /* Print our some info like "Displaying page 1 of 49" */
	   //echo "Displaying page " . $InfoArray["CURRENT_PAGE"] . " of " . $InfoArray["TOTAL_PAGES"] . "<BR>";
	   //echo "Displaying results " . $InfoArray["START_OFFSET"] . " - " . $InfoArray["END_OFFSET"] . " of " . $InfoArray["TOTAL_RESULTS"] . "<BR>";
	
	   /* Print our first link */
	   if($InfoArray["CURRENT_PAGE"]!= 1) {
		  echo "<a href='#' class='lastViewLink' onclick='SubmitPage(1)'>&lt;&lt;</a> ";
	   } else {
		  echo "&lt;&lt; ";
	   }
	
	   /* Print out our prev link */
	   if($InfoArray["PREV_PAGE"]) {
		  echo "<a  class='lastViewLink' href='javascript:SubmitPage(".$InfoArray["PREV_PAGE"].")'>Previous</a> | ";
	   } else {
		  echo "Previous | ";
	   }
	
	   /* Example of how to print our number links! */
	   for($i=0; $i<count($InfoArray["PAGE_NUMBERS"]); $i++) {
		  if($InfoArray["CURRENT_PAGE"] == $InfoArray["PAGE_NUMBERS"][$i]) {
			 echo $InfoArray["PAGE_NUMBERS"][$i] . " | ";
		  } else {
			 echo "<a class='lastViewLink' href='javascript:SubmitPage(".$InfoArray["PAGE_NUMBERS"][$i].")'>" . $InfoArray["PAGE_NUMBERS"][$i] . "</a> | ";
		  }
	   }
	
	   /* Print out our next link */
	   if($InfoArray["NEXT_PAGE"]) {
		  echo " <a  class='lastViewLink' href='javascript:SubmitPage(".$InfoArray["NEXT_PAGE"].")'>Next</a>";
	   } else {
		  echo " Next";
	   }
	
	   /* Print our last link */
	   if($InfoArray["CURRENT_PAGE"]!= $InfoArray["TOTAL_PAGES"]) {
		  echo " <a class='lastViewLink' href='javascript:SubmitPage(".$InfoArray["TOTAL_PAGES"].")'>>></a>";
	   } else {
		  echo " &gt;&gt;";
	   } 
   }
   
   
   
   
   
   
   function print_pagging_front()
   {
   	
   		/* Get our array of valuable paging information! */
	   $InfoArray = $this->InfoArray();
	   if($InfoArray["TOTAL_RESULTS"]==0)
	   {
		   	return;
	   }
		//print_r($InfoArray);
	   
	   /* Everything below here are just examples! */
	
	   /* Print our some info like "Displaying page 1 of 49" */
	   //echo "Displaying page " . $InfoArray["CURRENT_PAGE"] . " of " . $InfoArray["TOTAL_PAGES"] . "<BR>";
	   //echo "Displaying results " . $InfoArray["START_OFFSET"] . " - " . $InfoArray["END_OFFSET"] . " of " . $InfoArray["TOTAL_RESULTS"] . "<BR>";
	
	   /* Print our first link */
	   if($InfoArray["CURRENT_PAGE"]!= 1) {
		  echo "<a href='#' class='inner_page_no' onclick='SubmitPage(1)'>&lt;&lt;</a> ";
	   } else {
		  echo "&lt;&lt; ";
	   }
	
	   /* Print out our prev link */
	   if($InfoArray["PREV_PAGE"]) {
		  echo "<a  class='inner_page_no' href='javascript:SubmitPage(".$InfoArray["PREV_PAGE"].")'>Previous</a> | ";
	   } else {
		  echo "Previous | ";
	   }
	
	   /* Example of how to print our number links! */
	   for($i=0; $i<count($InfoArray["PAGE_NUMBERS"]); $i++) {
		  if($InfoArray["CURRENT_PAGE"] == $InfoArray["PAGE_NUMBERS"][$i]) {
			 echo $InfoArray["PAGE_NUMBERS"][$i] . " | ";
		  } else {
			 echo "<a class='inner_page_no' href='javascript:SubmitPage(".$InfoArray["PAGE_NUMBERS"][$i].")'>" . $InfoArray["PAGE_NUMBERS"][$i] . "</a> | ";
		  }
	   }
	
	   /* Print out our next link */
	   if($InfoArray["NEXT_PAGE"]) {
		  echo " <a  class='inner_page_no' href='javascript:SubmitPage(".$InfoArray["NEXT_PAGE"].")'>Next</a>";
	   } else {
		  echo " Next";
	   }
	
	   /* Print our last link */
	   if($InfoArray["CURRENT_PAGE"]!= $InfoArray["TOTAL_PAGES"]) {
		  echo " <a class='inner_page_no' href='javascript:SubmitPage(".$InfoArray["TOTAL_PAGES"].")'>>></a>";
	   } else {
		  echo " &gt;&gt;";
	   } 
   }
   
   
   

}
?>