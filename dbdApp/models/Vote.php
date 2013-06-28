<?php
class Vote extends dbdModel {
	const TABLE_NAME = 'votes';
	const TABLE_KEY = 'vote_id';
	const TABLE_FIELD_TO = 'to';
	const TABLE_FIELD_FROM = 'from';
	const TABLE_FIELD_VOTE = 'vote';
	const TABLE_FIELD_DATE = 'date';

	/**
	 * @param string $to
	 */
	public function setTo($to) {
		$this->to = $to;
	}

	/**
	 * @param string $from
	 */
	public function setFrom($from) {
		$this->from = $from;
	}

	/**
	 * @param int $vote
	 */
	public function setVote($vote) {
		$this->vote = $vote;
	}

	/**
	 * @param string $date
	 */
	public function setDate($date) {
		$this->date = $date;
	}

	/**
	 * @param array $fields
	 */
	public function save($fields = array()) {
		SVException::hold();
		SVException::ensure(($this->hasTo() && !isset($fields[self::TABLE_FIELD_TO])) || !empty($fields[self::TABLE_FIELD_TO]), SVException::VOTE_TO);
		SVException::ensure(($this->hasFrom() && !isset($fields[self::TABLE_FIELD_FROM])) || !empty($fields[self::TABLE_FIELD_FROM]), SVException::VOTE_FROM);
		SVException::ensure(($this->hasVote() && !isset($fields[self::TABLE_FIELD_VOTE])) || !empty($fields[self::TABLE_FIELD_VOTE]), SVException::VOTE_VOTE);
		SVException::release();

		if ($this->id == 0) {
			$this->setDate(dbdDB::date());
		}

		parent::save($fields);
	}

	/**
	 * @return array
	 */
	public function getData() {
		$data = parent::getData();
		$data['date'] = $this->getDate();
		return $data;
	}

	/**
	 * @return string
	 */
	public function getTo() {
		return $this->to;
	}

	/**
	 * @return string
	 */
	public function getFrom() {
		return $this->from;
	}

	/**
	 * @return int
	 */
	public function getVote() {
		return $this->vote;
	}

	/**
	 * @return string
	 */
	public function getDate() {
		return dbdDB::datez($this->date);
	}
}