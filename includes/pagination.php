<?php

class SP_RM_Pagination 
{
	/**
	 * Current Page
	 *
	 * @var integer
	 */
	var $page;
	
	/**
	 * Size of the records per page
	 *
	 * @var integer
	 */
	var $size;
	
	/**
	 * Total records
	 *
	 * @var integer
	 */
	var $total_records;
	
	/**
	 * Link used to build navigation
	 *
	 * @var string
	 */
	var $link;
	
	/**
	 * Class Constructor
	 *
	 * @param integer $page
	 * @param integer $size
	 * @param integer $total_records
	 */
	function __construct($page = null, $size = null, $total_records = null)
	{
		$this->page = $page;
		$this->size = $size;
		$this->total_records = $total_records;
	}
	
	/**
	 * Set's the current page
	 *
	 * @param unknown_type $page
	 */
	function setPage($page)
	{
		$this->page = 0+$page;
	}
	
	/**
	 * Set's the records per page
	 *
	 * @param integer $size
	 */
	function setSize($size)
	{
		$this->size = 0+$size;
	}
		
	/**
	 * Set's total records
	 *
	 * @param integer $total
	 */
	function setTotalRecords($total)
	{	
		
		$this->total_records = 0+$total;
	}
	
	/**
	 * Sets the link url for navigation pages
	 *
	 * @param string $url
	 */
	function setLink($url)
	{
		$this->link = $url;
	}
	
	/**
	 * Returns the LIMIT sql statement
	 *
	 * @return string
	 */
	function getLimitSql()
	{
		$sql = "LIMIT " . $this->getLimit();
		return $sql;
	}
		
	/**
	 * Get the LIMIT statment
	 *
	 * @return string
	 */
	function getLimit()
	{
		if ($this->total_records == 0)
		{
			$lastpage = 0;
		}
		else 
		{
			$lastpage = ceil($this->total_records/$this->size);
		}
		
		$page = $this->page;		
		
		if ($this->page < 1)
		{
			$page = 1;
		} 
		else if ($this->page > $lastpage && $lastpage > 0)
		{
			$page = $lastpage;
		}
		else 
		{
			$page = $this->page;
		}
		
		$sql = ($page - 1) * $this->size . "," . $this->size;
		
		return $sql;
	}
	
	/**
	 * Creates page navigation links
	 *
	 * @return 	string
	 */
	function create_links()
	{
		
	
		$totalItems = $this->total_records;
		
		$perPage = $this->size;
		$currentPage = $this->page;
		$link = $this->link;
	
		$totalPages = floor($totalItems / $perPage);


		$totalPages += ($totalItems % $perPage != 0) ? 1 : 0;
 
		if ($totalPages < 1 || $totalPages == 1){
		
			return null;
			
		}
  
		$output = null;
		$output = '<span class="pages">Page (' . $currentPage . '/' . $totalPages . ')</span>&nbsp;';
				
		$loopStart = 1; 
		$loopEnd = $totalPages;

		if ($totalPages > 15)
		{
			if ($currentPage <= 3)
			{
				$loopStart = 1;
				$loopEnd = 15;
			}
			else if ($currentPage >= $totalPages - 12)
			{
				$loopStart = $totalPages - 14;
				$loopEnd = $totalPages;
			}
			else
			{
				$loopStart = $currentPage - 12;
				$loopEnd = $currentPage + 5;
			}
		}

		if ($loopStart != 1){
			$output .= sprintf('<a href="' . $link . '">&#171;</a>', '1');
		}
		
		if ($currentPage > 1){
			$output .= sprintf('<a href="' . $link . '">Previous</a>', $currentPage - 1);
		}
		
		for ($i = $loopStart; $i <= $loopEnd; $i++)
		{
			if ($i == $currentPage){
				$output .= '<span class="curren">' . $i . '</span> ';
			} else {
				if($i>0){
				$output .= sprintf('<a href="' . $link . '">', $i) . $i . '</a> ';
				}
			}
		}

		if ($currentPage < $totalPages){
			$output .= sprintf('<a href="' . $link . '">Next</a>', $currentPage + 1);
		}
		
		if ($loopEnd != $totalPages){
			$output .= sprintf('<a href="' . $link . '">&#187;</a>', $totalPages);
		}

		return '<div class="pagination">' . $output . '</div>';
	}
}
