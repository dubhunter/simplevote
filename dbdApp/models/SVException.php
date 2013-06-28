<?php
class SVException extends dbdHoldableException {

	const BAD_REQUEST = 400;
	const UNAUTHORIZED = 401;
	const FORBIDDEN = 403;
	const NOT_FOUND = 404;
	const METHOD_NOT_ALLOWED = 405;

	const VOTE_TO = 1000;
	const VOTE_FROM = 1001;
	const VOTE_VOTE = 1002;

	const PHONE_NUMBER_DID = 1100;

	private static $msgs = array();

	public function __construct($code = 0) {
		parent::__construct(self::g($code), $code);
	}

	public static function setMsgArray($msgs) {
		self::$msgs = is_array($msgs) ? $msgs : array();
	}

	public static function g($code) {
		$key = 'error' . $code;
		return isset(self::$msgs[$key]) ? self::$msgs[$key] : get_class() . ': Message for code ' . $code . ' could not be found.';
	}

	public static function ensure($expr, $code) {
		if (!$expr) {
			self::intercept(new self($code));
		}
	}
}