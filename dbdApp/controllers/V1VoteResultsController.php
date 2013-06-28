<?php

class V1VoteResultsController extends V1ApiController {

	public function doGet() {
		try {
			$did = '+' . $this->getParam('to');

			$phoneNumber = PhoneNumber::getByDid($did);

			for ($i = $phoneNumber->getValidMin(); $i <= $phoneNumber->getValidMax(); $i++) {
				$this->data[$i] = 0;
			}

			/** @var Vote[] $votes */
			$votes = Vote::getAll(array(
				'to' => $did,
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
