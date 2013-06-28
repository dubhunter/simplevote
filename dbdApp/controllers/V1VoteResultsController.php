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
				if (!isset($data[$vote->getVote()])) {
					$data[$vote->getVote()] = 0;
				}
				$data[$vote->getVote()]++;
			}

			$this->data($data);
		} catch (SVException $e) {
			$this->e($e);
		}
	}
}
