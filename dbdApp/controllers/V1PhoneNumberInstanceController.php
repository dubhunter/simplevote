<?php

class V1PhoneNumberInstanceController extends V1ApiController {

	/**
	 * @var PhoneNumber $phoneNumber
	 */
	protected $phoneNumber;

	/**
	 * Ensure we have a valid PhoneNumber
	 */
	protected function init() {
		parent::init();

		try {
			$this->phoneNumber = PhoneNumber::getByDid('+' . $this->getParam('did'));
			SVException::ensure($this->phoneNumber->getId() > 0, SVException::NOT_FOUND);
		} catch (SVException $e) {
			$this->e($e);
			exit(0);
		}
	}

	public function doGet() {
		$this->data($this->phoneNumber->getData());
	}

	public function doPost() {
		try {
			if ($this->getParam('vote_max') !== null) {
				$this->phoneNumber->setVoteMax($this->getParam('vote_max'));
			}
			if ($this->getParam('valid_max') !== null) {
				$this->phoneNumber->setValidMax($this->getParam('valid_max'));
			}
			if ($this->getParam('valid_min') !== null) {
				$this->phoneNumber->setValidMin($this->getParam('valid_min'));
			}
			$this->phoneNumber->save();
			$this->data($this->phoneNumber->getData());
		} catch (SVException $e) {
			$this->e($e);
		}
	}

	public function doDelete() {
		try {
			$this->phoneNumber->delete();
		} catch (SVException $e) {
			$this->e($e);
		}
	}
}
