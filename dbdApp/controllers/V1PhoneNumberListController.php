<?php

class V1PhoneNumberListController extends V1ApiController {

	public function doGet() {
		try {
			$limit = $this->genLimit();
			$phoneNumbers = PhoneNumber::getAll($limit);

			$data = array();
			foreach ($phoneNumbers as $phoneNumber) {
				$data[] = $phoneNumber->getData();
			}

			$total = PhoneNumber::getCount();

			$this->dataList(array('phone_numbers' => $data), $total, '/v1/phone-numbers');
		} catch (SVException $e) {
			$this->e($e);
		}
	}

	public function doPost() {
		try {
			$phoneNumber = new PhoneNumber();
			$phoneNumber->setDid($this->getParam('did'));
			if ($this->getParam('vote_max') !== null) {
				$phoneNumber->setVoteMax($this->getParam('vote_max'));
			}
			if ($this->getParam('valid_max') !== null) {
				$phoneNumber->setValidMax($this->getParam('valid_max'));
			}
			if ($this->getParam('valid_min') !== null) {
				$phoneNumber->setValidMin($this->getParam('valid_min'));
			}
			$phoneNumber->save();
			$this->data($phoneNumber->getData());
		} catch (SVException $e) {
			$this->e($e);
		}
	}
}
