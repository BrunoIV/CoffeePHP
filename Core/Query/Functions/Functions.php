<?php
namespace Core\Query\Functions;

class Functions {

	public static function currentDate() {
		return new CurrentDateFunction();
	}

	public static function length($textOrColumn) {
		return new LengthFunction($textOrColumn);
	}

}
