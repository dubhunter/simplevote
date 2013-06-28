<?php
class PhoneNumber extends dbdModel {
	const TABLE_NAME = 'phone_numbers';
	const TABLE_KEY = 'phone_number_id';
	const TABLE_FIELD_DID = 'did';
	const TABLE_FIELD_OPTIONS = 'options';
	const TABLE_FIELD_DATE_CREATED = 'date_created';
	const TABLE_FIELD_DATE_UPDATED = 'date_updated';
	const OPTION_VOTE_MAX = 'vote-max';
	const OPTION_VALID_MIN = 'valid-min';
	const OPTION_VALID_MAX = 'valid-max';

	/**
	 * @param array $tableKeys
	 * @param $limit
	 * @return PhoneNumber[]
	 */
	public static function getAll($tableKeys = array(), $limit) {
		return parent::getAll($tableKeys, '`' . self::TABLE_FIELD_DID . '`', $limit);
	}

	/**
	 * @param $did
	 * @return PhoneNumber|null
	 */
	public static function getByDid($did) {
		$phoneNumbers = self::getAll(array(
			self::TABLE_FIELD_DID => $did,
		));

		return count($phoneNumbers) ? $phoneNumbers[0] : null;
	}

	/**
	 * @param string $did
	 */
	public function setDid($did) {
		$this->did = $did;
	}

	/**
	 * @param string $dateCreated
	 */
	public function setDateCreated($dateCreated) {
		$this->date_created = $dateCreated;
	}

	/**
	 * @param string $dateUpdated
	 */
	public function setDateUpdated($dateUpdated) {
		$this->date_updated = $dateUpdated;
	}

	/**
	 * @param array $fields
	 */
	public function save($fields = array()) {
		SVException::hold();
		SVException::ensure(($this->hasDid() && !isset($fields[self::TABLE_FIELD_DID])) || !empty($fields[self::TABLE_FIELD_DID]), SVException::PHONE_NUMBER_DID);
		SVException::release();

		if ($this->id == 0) {
			$this->setDateCreated(dbdDB::date());
		}
		$this->setDateUpdated(dbdDB::date());

		parent::save($fields);
	}

	/**
	 * JSON encode and set the Options
	 * @param array $options
	 */
	protected function setOptions($options) {
		$this->options = json_encode($options);
	}

	/**
	 * JSON decode and return the Options
	 * @return array List of set options
	 */
	protected function getOptions() {
		return json_decode($this->options, true);
	}

	/**
	 * Set one of the Options
	 * @param string $property
	 * @param string $value
	 */
	protected function set($property, $value) {
		$options = $this->getOptions();
		$options[$property] = $value;
		$this->setOptions($options);
	}

	/**
	 * Check if one of the Options is set
	 * @param string $property
	 * @return bool
	 */
	protected function has($property) {
		$options = $this->getOptions();
		return isset($options[$property]) && !empty($options[$property]);
	}

	/**
	 * Get one of the Options
	 * @param string $property
	 * @param null $default
	 * @return null|mixed
	 */
	protected function get($property, $default = null) {
		$options = $this->getOptions();
		return isset($options[$property]) ? $options[$property] : $default;
	}

	/**
	 * @param int $voteMax
	 */
	public function setVoteMax($voteMax) {
		$this->set(self::OPTION_VOTE_MAX, $voteMax);
	}

	/**
	 * @return bool
	 */
	public function hasVoteMax() {
		return $this->has(self::OPTION_VOTE_MAX);
	}

	/**
	 * @return int
	 */
	public function getVoteMax() {
		return $this->get(self::OPTION_VOTE_MAX, 1);
	}

	/**
	 * @param int $validMin
	 */
	public function setValidMin($validMin) {
		$this->set(self::OPTION_VALID_MIN, $validMin);
	}

	/**
	 * @return bool
	 */
	public function hasValidMin() {
		return $this->has(self::OPTION_VALID_MIN);
	}

	/**
	 * @return int
	 */
	public function getValidMin() {
		return $this->get(self::OPTION_VALID_MIN, 0);
	}

	/**
	 * @param int $validMax
	 */
	public function setValidMax($validMax) {
		$this->set(self::OPTION_VALID_MAX, $validMax);
	}

	/**
	 * @return bool
	 */
	public function hasValidMax() {
		return $this->has(self::OPTION_VALID_MAX);
	}

	/**
	 * @return int
	 */
	public function getValidMax() {
		return $this->get(self::OPTION_VALID_MAX, 1);
	}

	/**
	 * @return array
	 */
	public function getData() {
		$data = parent::getData();
		unset($data['options']);
		$data['vote_max'] = $this->getVoteMax();
		$data['valid_min'] = $this->getValidMin();
		$data['valid_max'] = $this->getValidMax();
		$data['date_created'] = $this->getDateCreated();
		$data['date_updated'] = $this->getDateUpdated();
		return $data;
	}

	/**
	 * @return string
	 */
	public function getDid() {
		return $this->did;
	}

	/**
	 * @return string
	 */
	public function getDateCreated() {
		return dbdDB::datez($this->date_created);
	}

	/**
	 * @return string
	 */
	public function getDateUpdated() {
		return dbdDB::datez($this->date_updated);
	}
}