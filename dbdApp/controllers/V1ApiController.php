<?php

class V1ApiController extends SVController {

	const DEFAULT_PAGE_SIZE = 50;

	private $default_render_json = true;

	protected $data = array();

	protected function init() {
		parent::init();

		$this->noRender();

		if ($this->router->getParam('HTTP_ORIGIN')) {
			header('Access-Control-Allow-Origin: ' . ($this->router->getParam('HTTPS') ? 'https' : 'http') . '://' . $this->router->getParam('HTTP_ORIGIN'));
		}
		header('Access-Control-Allow-Credentials: true');
		if ($this->getRequestMethod() == 'OPTIONS') {
			header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
			header('Access-Control-Max-Age: 604800');
			header('Access-Control-Allow-Headers: x-requested-with, x-requested-by');
			exit(0);
		}
	}

	public function autoExec() {
		if ($this->default_render_json === true) {
			$this->renderJson();
		}
		parent::autoExec();
	}

	/**
	 * @param $url
	 * @param $params
	 * @return string
	 */
	protected function buildUrl($url, $params) {
		return http_build_url($url, array('query' => http_build_query($params)));
	}

	/**
	 * @return array
	 */
	protected function data() {
		$argc = func_num_args();
		$argv = func_get_args();
		switch ($argc) {
			case 1:
				if (is_array($argv[0])) {
					$this->data = array_merge($this->data, $argv[0]);
				} else {
					return $this->data[$argv[0]];
				}
				break;
			case 2:
				$this->data[$argv[0]] = $argv[1];
				break;
		}
		return $this->data;
	}

	/**
	 * @param array $data
	 * @param int $total
	 * @param string $endpoint
	 */
	protected function dataList($data, $total, $endpoint) {
		$params = $this->getParams();
		if (isset($params['page'])) {
			unset($params['page']);
		}
		$page = $this->getParam('page') ? : 0;
		$pagesize = $this->getParam('pagesize') ? : self::DEFAULT_PAGE_SIZE;
		$numpages = max(ceil($total / $pagesize), 1);
		$start = $page * $pagesize;
		$end = min($start + $pagesize - 1, $total);
		$baseuri = $this->getHost() . $endpoint;

		$uri = $this->buildUrl($baseuri, $params);
		$firstpageuri = $this->buildUrl($baseuri, array_merge($params, array('page' => 0)));
		$nextpageuri = $numpages > 0 & $page < $numpages - 1 ? $this->buildUrl($baseuri, array_merge($params, array('page' => $page + 1))) : null;
		$previouspageuri = $page > 0 ? $this->buildUrl($baseuri, array_merge($params, array('page' => $page - 1))) : null;
		$lastpageuri = $this->buildUrl($baseuri, array_merge($params, array('page' => $numpages - 1)));

		$this->data($data);
		$this->data(array(
			'page' => $page,
			'numpages' => $numpages,
			'pagesize' => $pagesize,
			'total' => $total,
			'start' => $start,
			'end' => $end,
			'uri' => $uri,
			'firstpageuri' => $firstpageuri,
			'nextpageuri' => $nextpageuri,
			'previouspageuri' => $previouspageuri,
			'lastpageuri' => $lastpageuri,
		));

	}

	/**
	 * @param bool $echo
	 * @return string
	 */
	protected function renderJson($echo = true) {
		if ($echo) {
			header('X-Runtime: ' . dbdMVC::getExecutionTime());
			$this->setErrorHeader();
			header('Content-Type: application/json');
		}
		if ($echo) {
			dbdOB::start();
		}
		$out = '';
		if ($this->getParam('callback')) {
			$out .= $this->getParam('callback') . '(';
		}
		$out .= @json_encode($this->data);
		if ($this->getParam('callback')) {
			$out .= ')';
		}
		if ($echo) {
			echo $out;
			dbdOB::flush();
		}
		$this->default_render_json = false;
		return $out;
	}

	/**
	 * Disable auto render on destruction.
	 */
	public function noRenderJson() {
		$this->default_render_json = false;
	}

	protected function assignAllParamsData() {
		$this->data($this->getParams());
	}

	/**
	 * @param Exception $e
	 */
	protected function e(Exception $e) {
		$errors = array();
		if ($e instanceof dbdHoldableException) {
			foreach ($e->getHeld() as $he) {
				$this->response_code = $he->getCode();
				$errors[] = $he->getMessage();
			}
		}
		if (empty($errors)) {
			$this->response_code = $e->getCode();
			$errors[] = $e->getMessage();
		}
		$this->data('errors', $errors);
	}

	/**
	 * @return string
	 */
	protected function genLimit() {
		$page = $this->getParam('page') ? : 0;
		$pagesize = $this->getParam('pagesize') ? : self::DEFAULT_PAGE_SIZE;
		return ($page * $pagesize) . ',' . $pagesize;
	}

	public function doDefault() {
		$this->e(new SVException(SVException::METHOD_NOT_ALLOWED));
	}
}