<?php

class V1VoteResultsController extends V1ApiController {

	public function doGet() {
		try {
			/** @var Vote[] $votes */
			$votes = Vote::getAll(array(
				'to' => '+' . $this->getParam('to'),
			));

			foreach ($votes as $vote) {
				$key = $vote->getVote();
				if (!isset($this->data[$key])) {
					$this->data[$key] = 0;
				}
				$this->data[$key]++;
			}
		} catch (SVException $e) {
			$this->e($e);
		}
	}
}
