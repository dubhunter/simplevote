<?php
class SVController extends dbdController {

	const TWILIO_CREDENTIALS = 'constant/twilio.inc';

	/**
	 * @throws dbdException
	 */
	protected static function ensureTwilioCredentials() {
		if (!(defined('TWILIO_ACCOUNT_SID') && defined('TWILIO_AUTH_TOKEN'))) {
			dbdLoader::load(self::TWILIO_CREDENTIALS);
			if (!(defined('TWILIO_ACCOUNT_SID') && defined('TWILIO_AUTH_TOKEN'))) {
				throw new SVException("Twilio credentials file could not be included. PATH=" . self::TWILIO_CREDENTIALS);
			}
		}
	}

	protected function init() {
		$this->view->config_load('errorMsgs.conf');
		SVException::setMsgArray($this->view->get_config_vars());
		$this->view->clear_config();
	}

	/**
	 * Get current request host.
	 * If optional flag is passed, parameters are included.
	 * @return string
	 */
	protected function getHost() {
		return ($this->router->getParam('HTTPS') ? 'https' : 'http') . '://' . $this->router->getParam('HTTP_HOST');
	}

	/**
	 * Get current request url.
	 * If optional flag is passed, parameters are included.
	 * @param boolean $host
	 * @return string
	 */
	protected function getUrl($host = false) {
		return ($host ? $this->getHost() : '') . $this->router->getParam('REQUEST_URI');
	}

	/**
	 * Set an error header and exit
	 */
	protected function setErrorHeader() {
		if ($this->response_code >= 400 && $this->response_code < 500) {
			header('HTTP/1.1 ' . $this->response_code . " " . SVException::g($this->response_code));
			exit(0);
		}
	}

	/**
	 * Validate the
	 * @return bool
	 */
	protected function validTwilioRequest() {
		try {
			self::ensureTwilioCredentials();
		} catch (SVException $e) {
			return false;
		}
		$str = $this->getUrl(true);
		if (strlen(dbdMVC::getRequest()->getServer('QUERY_STRING'))) {
			$str .= '?' . dbdMVC::getRequest()->getServer('QUERY_STRING');
		}
		if ($this->getRequestMethod() == 'POST') {
			$data = dbdMVC::getRequest()->getPost();
			ksort($data);
			foreach ($data as $k => $v) {
				$str .= $k . $v;
			}
		}
		$str = base64_encode(hash_hmac('sha1', $str, TWILIO_AUTH_TOKEN, true));
		return $str == dbdMVC::getRequest()->getHeader('X-Twilio-Signature');
	}

}
