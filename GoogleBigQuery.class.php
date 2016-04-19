<?php

	/**
	 * A wrapper aroung Google's BigQuery for PHP.
	 * Absolute lack of documentation for PHP is just rubbish
	 */

	class GoogleBigQuery {

		private $googleClient;
		private $bigQuery;
		private $project;

		const JOB_STATUS_ALWAYS_OUT = true;
		const JOB_STATUS_QUIT_ON_ERROR = true;

		/**
		 * Constructs a BigQuery object using the GClient.
		 * @param <Google_Client> $googleClient 
		 * @return type
		 */
		public function __construct($googleClient){
			$this->googleClient=$googleClient;
			$this->bigQuery = new Google_Service_Bigquery($this->googleClient);
		}

		/**
		 * Sets the project ID for the query
		 * @param <String> $project 
		 * @return NA
		 */
		public function setProject($project){
			$this->project=$project;
		}

		/**
		 * Format the response rows into a key'd array.
		 * @param type $queryResults 
		 * @return type
		 */
		private function doKeyArray($queryResults){
			$fields = $queryResults->getSchema()->getFields();
			$fieldNames = array();
			$su=array();
			foreach ($fields as $field) {
				$fieldNames[] = $field['name'];
			}

			foreach ( $queryResults['rows'] as $row ){
				$r=array();
				foreach ( $row as $id=>$value )
					$r[$fieldNames[$id]]=$value->v;
				$su[]=$r;
			}

			return $su;
		}

		/**
		 * Not used yet..
		 * @param type $job 
		 * @return type
		 */
		private function jobStatus($job){
			$status = new Google_Service_Bigquery_JobStatus();
		    $status = $job->getStatus();

		    if ( GoogleBigQuery::JOB_STATUS_ALWAYS_OUT ){
		    	print "<XMP>";
		    	print_r($status);
		    	print "</XMP>";
		    }
		    
		    if ($status->count() != 0) {
		        $err_res = $status->getErrorResult();
		        if ( GoogleBigQuery::JOB_STATUS_QUIT_ON_ERROR ) {
		        	die($err_res->getMessage());
		        }

		        return $err_res->getMessage();
		        
		    }
		 
		}

		/**
		 * Run a query against BigQuery and return the results.
		 * @param <String> $sql 
		 * @param <Boolean>|bool $isJSON 
		 * @param <Boolean>|bool $formatToKeyArray 
		 * @return <array>
		 */
		public function query($sql, $isJSON=false, $formatToKeyArray=true){
			$query = new Google_Service_Bigquery_QueryRequest();
			$query->setQuery($sql);
			$query->setTimeoutMs(0);
			$response = $this->bigQuery->jobs->query($this->project, $query);
			$job_id = $response->getJobReference()->getJobId();
			$pageToken = null;
			do {
				$queryResults = $this->bigQuery->jobs->getQueryResults($this->project, $job_id);
				$queryResults->setPageToken($pageToken);
			} while (!$queryResults->getJobComplete());

			if ( $formatToKeyArray )
				return ($isJSON) ? json_encode($this->doKeyArray($queryResults)) : $this->doKeyArray($queryResults);

			return $queryResults;
		}


		
		
	}