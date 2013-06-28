<?php
class SVError extends SVController {
	public function doDefault() {
		dbdError::doError($this);
	}
}