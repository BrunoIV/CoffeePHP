<?php

namespace Core\Helpers;

class HelperProvider {
	public function getHtml() { return new HtmlHelper(); }
	public function getArray() { return new ArrayHelper(); }
}
