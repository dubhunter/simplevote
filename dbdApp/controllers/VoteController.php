<?php
class VoteController extends SVController {

	const MAX_VOTES = 1;

	protected function init() {
		$this->setTemplate('twiml-empty.tpl');
	}

	public function doPost() {
		dbdLog($this->getParams());
		$to = $this->getParam('To');
		$from = $this->getParam('From');
		$vote = $this->getParam('Body');

		$count = Vote::getCount(array(
			'to' => $to,
			'from' => $from,
		));

		if (is_numeric($vote) && $count < self::MAX_VOTES) {
			$V = new Vote();
			$V->setTo($to);
			$V->setFrom($from);
			$V->setVote($vote);
			$V->save();
		}
	}
}
