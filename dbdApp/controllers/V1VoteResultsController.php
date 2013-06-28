<?php

class V1VoteResultsController extends V1ApiController {

	public function doGet() {
		try {
			for ($i = self::VALID_MIN; $i <= self::VALID_MAX; $i++) {
				$this->data[$i] = 0;
			}

			/** @var Vote[] $votes */
			$votes = Vote::getAll(array(
				'to' => '+' . $this->getParam('to'),
			));

			foreach ($votes as $vote) {
				$key = $vote->getVote();
				$this->data[$key]++;
			}
		} catch (SVException $e) {
			$this->e($e);
		}
	}
}
