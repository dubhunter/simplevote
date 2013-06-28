<?php
class Vote extends dbdModel {
	const TABLE_NAME = 'votes';
	const TABLE_KEY = 'vote_id';

	public function setTo($to) {
		$this->to = $to;
	}

	public function setFrom($from) {
		$this->from = $from;
	}

	public function setVote($vote) {
		$this->vote = $vote;
	}

	public function setDate($date) {
		$this->date = $date;
	}

	public function getTo() {
		return $this->to;
	}

	public function getFrom() {
		return $this->from;
	}

	public function getVote() {
		return $this->vote;
	}

	public function getDate() {
		return dbdDB::datez($this->date);
	}
}