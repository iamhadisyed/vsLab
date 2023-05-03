<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/18/16
 * Time: 2:29 PM
 */
namespace App\Http\Controllers;
use View;
use OpenCloud\Common\Constants\Header as HeaderConst;
use OpenCloud\Common\Constants\Mime as MimeConst;
use Carbon\Carbon;
//use Guzzle\Http\Client;
use GuzzleHttp\Exception\BadResponseException;

class ElasticsearchResource extends Controller{

//	const ELASTICSEARCH_URL = 'http://10.2.11.87:9200';
	const ELASTICSEARCH_URL = 'http://10.2.2.1:9200';

	const APPSERVER_INDEX = 'appserver-*';
	const ACTIVITY_DOCTYPE = 'activity';
	const SUCCESS = 'Success';
	const FAIL = 'Fail';

	protected $client;
	protected $requestHeader;

	protected static $ELASTICSEARCH_INDICES = array('logstash-*', 'appserver-*');

	public function __construct()
	{
//		$this->client = new Client(ElasticsearchResource::ELASTICSEARCH_URL);
//		$this->requestHeader = array(HeaderConst::CONTENT_TYPE => MimeConst::JSON);
	}

	protected function prepareRequest($request) {
		$request->setAuth('elastic', 'elasticpassword23');
	}

	/**
	 * Convert an indexPattern (e.g. appserver-*) into current actual DAILY index (i.e. appserver-YYYY.MM.DD)
	 * @param $indexPattern
	 * @return string $index
	 */
	public function getIndexFromPattern($indexPattern) {
		if ((strlen($indexPattern) > 0) && ($indexPattern[strlen($indexPattern)-1] === '*')) {
			$date = Carbon::now('UTC');
			$indexDate = $date->format('Y.m.d');
			return (substr($indexPattern, 0, -1) . $indexDate);
		} else {
			return $indexPattern;
		}
	}

	protected function getSearchPostRequest($index, $documentType, $body)
	{
		$path = $index;
		if ($documentType != null) {
			$path .= '/' . $documentType;
		}
		$path .= '/_search';
		$request = $this->client->post($path, $this->requestHeader, $body);
		$this->prepareRequest($request);
		return $request;
	}

	protected function getIndexPostRequest($index, $documentType, $body)
	{
		$index = $this->getIndexFromPattern($index);
		$path = $index . '/' . $documentType . '/';
		$request = $this->client->post($path, $this->requestHeader, $body);
		$this->prepareRequest($request);
		return $request;
	}

	/**
	 * Index a single item
	 */
	public function indexDocument($body, $indexPattern, $document)
	{
		if (is_null($indexPattern) || is_null($document) || is_null($body)) {
			$response = array("status" => ElasticsearchResource::FAIL,
				"response" => "Index, document type or document cannot be null");
		} else {
			$request = $this->getIndexPostRequest($indexPattern, $document, $body);
			try {
				$clientResponse = $request->send();
				$response = array("status" => ElasticsearchResource::SUCCESS,
					"response" => $clientResponse);
			} catch (BadResponseException $e) {
				// thrown if Elasticsearch call returns error
				$responseBodyString = $e->getResponse()->getBody(true);
				$response = array("status" => ElasticsearchResource::FAIL,
					"response" => $responseBodyString);
				// dump to server log

			} catch (CurlException $e) {
				// thrown if Elasticsearch is down/unreachable
				$errorMessage = $e->getError();
				$response = array("status" => ElasticsearchResource::FAIL,
					"response" => $errorMessage);
			}
		}
		return $response;
	}


	/**
	 * Search
	 */
	public function searchDocuments($body, $indexPattern, $document)
	{
		if (is_null($indexPattern) || is_null($body)) {
			$response = array("status" => ElasticsearchResource::FAIL,
				"response" => "Index or search body cannot be null");
		} else {
			$request = $this->getSearchPostRequest($indexPattern, $document, $body);
			try {
				$clientResponse = $request->send();
				$response = array("status" => ElasticsearchResource::SUCCESS,
					"response" => $clientResponse->json());
			} catch (BadResponseException $e) {
				// thrown if Elasticsearch call returns error
				$responseBodyString = $e->getResponse()->getBody(true);
				$response = array("status" => ElasticsearchResource::FAIL,
					"response" => $responseBodyString);
				// dump to server log

			} catch (\Guzzle\Http\Exception\CurlException $e) {
				// thrown if Elasticsearch is down/unreachable
				$errorMessage = $e->getError();
				$response = array("status" => ElasticsearchResource::FAIL,
					"response" => $errorMessage);
			}
		}
		return $response;
	}


	public function search()
	{
		if (Request::ajax()) {
			$input = Input::all();

			$matches = $input['matches'];
			$body = \ElasticsearchQueryHelper::generateSearchWithMatchClauses($matches);
//			\ElasticsearchQueryHelper::addSortClauseToQuery($body, "updated_at", \ElasticsearchQueryHelper::DESCENDING);
			ElasticsearchQueryHelper::addTerminateClauseToQuery($body, 100);

			if (array_key_exists('beforeTime', $input) && array_key_exists('afterTime', $input)) {
				$fromTime = DateTime::createFromFormat('U', $input['afterTime']);
				$toTime = DateTime::createFromFormat('U', $input['beforeTime']);
				$rangeFilter = ElasticsearchQueryHelper::generateRangeFilterClause($input['dateField'], $fromTime, $toTime);
				ElasticsearchQueryHelper::addFilterToBoolClause($body['query'], $rangeFilter);
			}
			$searchBody = json_encode($body);

			$response = $this->searchDocuments($searchBody, $input['index'], $input['documentType']);

			$logs = array();
			if ($response['status'] == ElasticsearchResource::SUCCESS) {
				foreach ($response['response']['hits']['hits'] as $hit) {
					$log = array();
					foreach ($hit['_source'] as $field => $value) {
						$log[$field] = $value;
					}
					array_push($logs, $log);
				}
			}
			$response = array("status" => ElasticsearchResource::SUCCESS,
				"response" => $logs);
			return $response;

		}
	}

	protected function getCatRequest($command, $options) {
		$path = "_cat" . "/" . $command;
		if ($options != null) {
			$path .= "?" .$options;
		}
		$request = $this->client->get($path, $this->requestHeader);
		$this->prepareRequest($request);
		return $request;
	}

	protected function getMappingRequest($index, $docType, $field) {
		$path = $index;
		if ($docType != null) {
			$path .= "/" .$docType;
		}
		$path .= "/_mapping";
		if ($field != null) {
			$path .= '/field/' . $field;
		}
		$request = $this->client->get($path, $this->requestHeader);
		$this->prepareRequest($request);
		return $request;
	}

	public function getIndices() {
		// this will return all indices in elasticsearch:
		// $request = $this->getCatRequest("indices", "h=index");
		// but we need to know which ones are applicable to this App and which to ignore,
		// hence using class static
		$response = array("status" => ElasticsearchResource::SUCCESS,
			    "response" => ElasticsearchResource::$ELASTICSEARCH_INDICES);
		return $response;
	}

	public function getDocumentTypes($index) {
		$request = $this->getMappingRequest($index, null, null);
		try {
			$clientResponse = $request->send();
			$body = $clientResponse->json();

			$x = array();
			$n = 10;    // check just the first n indices (since indices could be daily/hourly we might have hundreds or thousands)
			foreach ($body as $mapIndex) {
				$x = array_merge($x, array_keys($mapIndex["mappings"]));
				if (--$n == 0) {
					break;
				}
			}
			$x = array_unique($x);
			$response = array("status" => ElasticsearchResource::SUCCESS,
				"response" => $x);
		} catch (BadResponseException $e) {
			// thrown if Elasticsearch call returns error
			$responseBodyString = $e->getResponse()->getBody(true);
			$response = array("status" => ElasticsearchResource::FAIL,
				"response" => $responseBodyString);
			// dump to server log

		} catch (\Guzzle\Http\Exception\CurlException $e) {
			// thrown if Elasticsearch is down/unreachable
			$errorMessage = $e->getError();
			$response = array("status" => ElasticsearchResource::FAIL,
				"response" => $errorMessage);
		}
		return $response;
	}

	/*
	 * Return an array of all the fields and their types in the given index/documentType
	 */
	public function getDocumentFields($index, $documentType) {
		// get a document
		$matchAllClause = ElasticsearchQueryHelper::generateMatchAllClause();

		$searchQuery = ElasticsearchQueryHelper::generateEmptyQuery();
		ElasticsearchQueryHelper::addQueryClauseToQuery($searchQuery, $matchAllClause);
		$body = $searchQuery;

		ElasticsearchQueryHelper::addSizeClauseToQuery($body, 1);
		ElasticsearchQueryHelper::addTerminateClauseToQuery($body, 1);
		$searchBody = json_encode($body);

		$response = App::make('ElasticsearchResource')->searchDocuments($searchBody, $index, $documentType);

		// get document fields from _source
		$allFields = array();
		if ($response['status'] == ElasticsearchResource::SUCCESS) {
			//N.B. Assumes we will always have a search hit, i.e. at least one doc in the index
			if (count($response['response']['hits']['hits']) > 0) {
				$allFields = array_keys($response['response']['hits']['hits']['0']['_source']);
				if ($documentType == null) {
					$documentType = $response['response']['hits']['hits']['0']['_type'];
				}
			}
		}

		// get docType mapping
		// for each field, if type == "date", add field to date fields
		//                 else add field to text fields
		$x = array();
		foreach ($allFields as $field) {
			$fieldTypeResponse = $this->getFieldsAndTypes($response['response']['hits']['hits']['0']['_source'][$field],  $index, $documentType, $field);
			if ($fieldTypeResponse['status'] == ElasticsearchResource::SUCCESS) {
				foreach ($fieldTypeResponse['response'] as $key => $value) {
					$x[$key] = $value;
				}
			}
		}
		$response = array("status" => ElasticsearchResource::SUCCESS,
			"response" => $x);

		return $response;
	}

	protected function getFieldsAndTypes($sourceField, $index, $documentType, $field) {
		$x = array();
		$request = $this->getMappingRequest($index, $documentType, $field);
		try {
			$clientResponse = $request->send();
			$body = $clientResponse->json();

			$first = reset($body);
			if (!empty($first['mappings'])) {
				$subFieldIndex = strrpos($field, ".");
				if ($subFieldIndex != false) {
					$subField = substr($field, $subFieldIndex+1);
				} else {
					$subField = $field;
				}
				$x[$field] = $first['mappings'][$documentType][$field]['mapping'][$subField]['type'];
			} else {
				foreach (array_keys($sourceField) as $subField) {
					$fi = $field . "." . $subField;
					$response = $this->getFieldsAndTypes($sourceField[$subField], $index, $documentType, $fi);
					foreach ($response['response'] as $key => $value) {
						$x[$key] = $value;
					}
				}
			}
		} catch (BadResponseException $e) {
			// thrown if Elasticsearch call returns error
			$responseBodyString = $e->getResponse()->getBody(true);
			$response = array("status" => ElasticsearchResource::FAIL,
				"response" => $responseBodyString);
			// dump to server log
		} catch (CurlException $e) {
			// thrown if Elasticsearch is down/unreachable
			$errorMessage = $e->getError();
			$response = array("status" => ElasticsearchResource::FAIL,
				"response" => $errorMessage);
		}
		$response = array("status" => ElasticsearchResource::SUCCESS,
			"response" => $x);

		return $response;
	}
}
