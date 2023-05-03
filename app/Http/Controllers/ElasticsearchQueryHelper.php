<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/19/16
 * Time: 10:00 AM
 */

class ElasticsearchQueryHelper {

	const ASCENDING = 'asc';
	const DESCENDING = 'desc';

	// []
	public static function generateEmptyQuery() {
		return [];
	}

	// {"bool":[]}
	public static function generateBoolClause() {
		return ['bool' => []];
	}

	// {"match":{"field":"value"}}
	public static function generateMatchClause($field, $value) {
		return ["match" => [ $field => $value ]];
	}

	// {"match_all":{}}
	public static function generateMatchAllClause() {
		return ["match_all" => new stdClass()];
	}

	/* {"range":{"field":{"lt":"1480219200","gt":"1480215600","format":"epoch_second"}}}
	 *
	 */
	public static function generateRangeFilterClause($field, $fromTime, $toTime) {
		$from = $fromTime->format('U');
		$to = $toTime->format('U');
		$format = ['format' => 'epoch_second'];
		$range = ['range' => [ $field  => [ 'lt' => $to, 'gt' => $from, 'format' => 'epoch_second' ] ] ];
		return $range;
	}

	/* Before: {"bool":[]}
	   After:  {"bool":{"must":[{"match":{"field1":"value1"}}]}}
			   {"bool":{"must":[{"match":{"field1":"value1"}},{"match":{"field1":"value1"}}]}}
	*/
	public static function addMustToBoolClause(& $boolQuery, $match) {
		if (array_key_exists('must', $boolQuery['bool'])) {
			array_push($boolQuery['bool']['must'], $match);
		} else {
			$boolQuery['bool']['must'] = array($match);
		}
	}

	/* Before: {"bool":[]}
	   After:  {"bool":{"filter":[{"range":{"field1":{"lt":"1480219200","gt":"1480215600","format":"epoch_second"}}}]}}
			   {"bool":{"filter":[{"range":{"field1":{"lt":"1480219200","gt":"1480215600","format":"epoch_second"}}},
								  {"range":{"field1":{"lt":"1480219200","gt":"1480215600","format":"epoch_second"}}}]}}
	*/
	public static function addFilterToBoolClause(& $boolQuery, $rangeFilter) {
		if (array_key_exists('filter', $boolQuery['bool'])) {
			array_push($boolQuery['bool']['filter'], $rangeFilter);
		} else {
			$boolQuery['bool']['filter'] = array($rangeFilter);
		}
	}
	/* Before: []
	   After:  {"query":{"bool":[]}}
	 */
	public static function addQueryClauseToQuery(& $body, $queryTerm) {
		$body += ['query' => $queryTerm];
		return $body;
	}

	/* Before: []
	   After:  {"sort":{"sortField":{"order":"asc"}}}
	*/
	public static function addSortClauseToQuery(& $body, $sortField, $sortOrder) {
		$sortTerm = null;
		$sortTerm = array("$sortField" => ["order" => $sortOrder]);
		$body += ['sort' => $sortTerm];
		return $body;
	}

	/* Before: []
	   After:  {"_source":["sourceField1","sourceField2","sourceField3"]}
	*/
	public static function addSourceClauseToQuery(& $body, $sourceFields) {
		$body += ['_source' => $sourceFields];
		return $body;
	}

	/* Before: []
       After:  {"size": n}
	*/
	public static function addSizeClauseToQuery(& $body, $count) {
		$body += ['size' => $count];
		return $body;
	}

	/* Before: []
       After:  {"terminate_after": n}
	*/
	public static function addTerminateClauseToQuery(& $body, $count) {
		$body += ['terminate_after' => $count];
		return $body;
	}

	/**
	 * Search documents matching the given key:value pairs where key is a field in the document
	 * and value is the desired match value, e.g. ['user_name' => 'james', 'activity' => 'Log In']
	 */
	public static function generateSearchWithMatchClauses($matchKeyValuePairs) {
		$searchQuery = self::generateEmptyQuery();
		$boolClause = self::generateBoolClause();
		foreach ($matchKeyValuePairs as $field => $value) {
			$matchClause = self::generateMatchClause($field, $value);
			self::addMustToBoolClause($boolClause, $matchClause);
		}
		self::addQueryClauseToQuery($searchQuery, $boolClause);
		return $searchQuery;
	}

	/**
	 * Search documents matching the given key:value pairs and satisfying the time range filter on the specified field
	 */
	public static function generateSearchWithMatchAndFilterClauses($matchKeyValuePairs, $filterField, $fromTime, $toTime) {
		$searchQuery = self::generateSearchWithMatchClauses($matchKeyValuePairs);
		$rangeFilter = self::generateRangeFilterClause($filterField, $fromTime, $toTime);
		self::addFilterToBoolClause($searchQuery['query'], $rangeFilter);
		return $searchQuery;
	}

}