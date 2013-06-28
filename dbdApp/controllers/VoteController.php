<?php
class VoteController extends SVController {

	public function doPost() {
		try {
			SVException::ensure($this->validTwilioRequest(), SVException::UNAUTHORIZED);

			$to = $this->getParam('To');
			$from = $this->getParam('From');
			$vote = $this->getParam('Body');

			$phoneNumber = PhoneNumber::getByDid($to);

			if (!$phoneNumber) {
				throw new SVException(SVException::BAD_REQUEST);
			}

			$voteMax = $phoneNumber->getVoteMax();
			$validMin = $phoneNumber->getValidMin();
			$validMax = $phoneNumber->getValidMax();

			$count = Vote::getCount(array(
				'to' => $to,
				'from' => $from,
			));

			$this->view->assign('voter', $from);

			if (is_numeric($vote) && $vote >= $validMin && $vote <= $validMax) {
				if ($count < $voteMax) {
					$V = new Vote();
					$V->setTo($to);
					$V->setFrom($from);
					$V->setVote($vote);
					$V->save();
					$this->setTemplate('twiml-thanks.tpl');
				} else {
					$this->view->assign('voteMax', $voteMax);
					$this->setTemplate('twiml-max-votes.tpl');
				}
			} else {
				$this->view->assign('validMin', $validMin);
				$this->view->assign('validMax', $validMax);
				$this->setTemplate('twiml-invalid.tpl');
			}
		} catch (SVException $e) {
			$this->e($e);
		}
	}
}
