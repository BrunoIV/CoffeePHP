<?php

namespace Core\Query\Restrictions;

class Restrictions {
	public static function equals(string $column, $comparation) {
		return new EqualsRestriction($column, $comparation);
	}

	public static function in(string $column, $comparation) {
		return new InRestriction($column, $comparation);
	}

	public static function or() {
		return new AndOrRestriction(func_get_args(), "OR");
	}

	public static function and() {
		return new AndOrRestriction(func_get_args(), "AND");
	}
}
