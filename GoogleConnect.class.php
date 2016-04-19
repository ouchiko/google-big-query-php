<?php

	/**
	 * Creating a usable google client object.  So much documentation and yet everything
	 * is do badly documented, out of date or just doesnt work.  It shouldn't be this crap.
	 */


	class GoogleConnect {

		private $APP_NAME;
		private $API_KEY;
		private $JSON_KEY_FILE;
		private $SCOPES;
		private $client;

		/**
		 * Setup and generate a Google_Client obejct
		 * @param type APP_NAME Choose an application name
		 * @param type $API_KEY [https://console.developers.google.com/apis/credentials?project=<XXX> API key]
		 * @param type $JSON_KEY_FILE [https://console.developers.google.com/apis/credentials?project=<XXX> Service Account keys]
		 * @param type $SCOPES [https://developers.google.com/identity/protocols/googlescopes#appstatev1]
		 * @return type
		 */
		public function __construct( $APP_NAME, $API_KEY, $JSON_KEY_FILE, $SCOPES ) {
			$this->API_KEY=$API_KEY;
			$this->JSON_KEY_FILE=$JSON_KEY_FILE;
			$this->SCOPES=$SCOPES;
			$this->APP_NAME=$APP_NAME;


			$this->generateGoogleClient();
		}

		/**
		 * Under the hood shit to connect to Google - It's a royal pain in the ass and they
		 * don't half make it boring and hard.
		 * @return Google_Client
		 */
		private function generateGoogleClient() {
			$client = new Google_Client();
			$client->setApplicationName($this->APP_NAME);
			$client->setDeveloperKey($this->API_KEY);

			$client->setScopes($this->SCOPES);

			$client->setAuthConfig($this->JSON_KEY_FILE);
			$client->useApplicationDefaultCredentials();

			$this->client= $client;
		}

		/**
		 * returns the client
		 * @return type
		 */
		public function get(){
			return $this->client;
		}


	}