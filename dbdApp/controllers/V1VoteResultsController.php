<?php

class V1VoteResultsController extends V1ApiController {

	public function doGet() {
		try {
			/** @var Vote[] $votes */
			$votes = Vote::getAll(array(
				'to' => '+' . $this->getParam('to'),
			));

			$data = array();
			foreach ($votes as $vote) {
				$key = $vote->getVote();
				if (!isset($data[$key])) {
					$data[$key] = 0;
				}
				$data[$key]++;
			}

			$this->data($data);
		} catch (SVException $e) {
			$this->e($e);
		}
	}
}
