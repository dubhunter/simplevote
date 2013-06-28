<?php
class SVController extends dbdController {

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

}
