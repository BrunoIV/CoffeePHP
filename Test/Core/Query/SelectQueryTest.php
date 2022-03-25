<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
Use \Core\Query\Restrictions\Restrictions;
Use \Core\Query\Functions\Functions;

final class SelectQueryTest extends TestCase {

	/**
	 * Testea la selección de todas las columnas sin usar una alias para la tabla
	 */
	public function testSeleccionarTodosProductos(): void {
		$qb = new \Core\Query\QueryBuilder();
		$query = $qb->select()->from('Product');

		$this->assertEquals(
			'SELECT * FROM PRO_PRODUCTOS',
			$query->getSql()
		);
	}

	/**
	 * Testea la selección de todas las columnas (omitiendo parámetros en select)
	 * y un from con un alias
	 */
    public function testSeleccionarTodosProductosConAliasEnFrom(): void {
        $qb = new \Core\Query\QueryBuilder();
		$query = $qb->select()->from(['Product' => 'prods']);

		$this->assertEquals(
            'SELECT * FROM PRO_PRODUCTOS prods',
        	$query->getSql()
        );
    }

	/**
	 * Testea un WHERE sencillo: 'columna' = valor
	 */
	public function testSimpleWhere(): void {
		$qb = new \Core\Query\QueryBuilder();
		$query = $qb->select()
		->from(['Product' => 'p'])
		->where(Restrictions::equals('id', 1));

		$this->assertEquals(
			'SELECT * FROM PRO_PRODUCTOS p WHERE PRO_ID = 1',
			$query->getSql()
		);
	}

	/**
	 * Testea un IN sencillo: columna IN(array valores)
	 */
	public function testWhereIn(): void {
		$qb = new \Core\Query\QueryBuilder();
		$query = $qb->select()
		->from(['Product' => 'p'])
		->where(Restrictions::in('id', [2,3,4]));

		$this->assertEquals(
			'SELECT * FROM PRO_PRODUCTOS p WHERE PRO_ID IN (2, 3, 4)',
			$query->getSql()
		);
	}

	/**
	 * Testea la claúsula IN con una subconsulta
	 */
	public function testWhereInSubselect(): void {
		$qb = new \Core\Query\QueryBuilder();

		$subselect = $qb->select(['id'])->from(['Product' => 'prd']);

		$query = $qb->select()
		->from(['Product' => 'p'])
		->where(Restrictions::in('id', $subselect));

		$this->assertEquals(
			'SELECT * FROM PRO_PRODUCTOS p WHERE PRO_ID IN (SELECT PRO_ID FROM PRO_PRODUCTOS prd)',
			$query->getSql()
		);
	}

	/**
	 * Testea una claúsula OR simple
	 */
	public function testWhereOr(): void {
		$qb = new \Core\Query\QueryBuilder();
		$query = $qb->select()
		->from(['Product' => 'p'])
		->where(Restrictions::or(Restrictions::equals('id', 1), Restrictions::equals('id', 3)));

		$this->assertEquals(
			'SELECT * FROM PRO_PRODUCTOS p WHERE PRO_ID = 1 OR PRO_ID = 3',
			$query->getSql()
		);
	}

	/**
	 * Testea un where complejo, compuesto por AND, OR, IN, EQUALS
	 */
	public function testWhereComplejo(): void {
		$qb = new \Core\Query\QueryBuilder();
		$query = $qb->select()
		->from(['Product' => 'p'])
		->where(
			Restrictions::and(
				Restrictions::or(
					Restrictions::equals('nombre', 'iPhone'),
					Restrictions::in('nombre', [1, 3])
				),
				Restrictions::equals('activo', 1)
			)
		);

		$this->assertEquals(
			"SELECT * FROM PRO_PRODUCTOS p WHERE (PRO_NOMBRE = 'iPhone' OR PRO_NOMBRE IN (1, 3)) AND PRO_ACTIVO = 1",
			$query->getSql()
		);
	}

	/**
	 * Test selección id y nombre de los productos, limitando los resultados a 50
	 */
	public function testLimit(): void {
        $qb = new \Core\Query\QueryBuilder();
		$query = $qb->select(['id', 'nombre'])->from(['Product' => 'prods'])->limit(50);

		$this->assertEquals(
            'SELECT PRO_ID, PRO_NOMBRE FROM PRO_PRODUCTOS prods LIMIT 50',
        	$query->getSql()
        );
    }

	/**
	 * Test consulta con order by DESC
	 */
	public function testOrderByDesc(): void {
        $qb = new \Core\Query\QueryBuilder();
		$query = $qb->select(['id', 'nombre'])->from(['Product' => 'prods'])->orderBy(['id' => 'desc']);

		$this->assertEquals(
            'SELECT PRO_ID, PRO_NOMBRE FROM PRO_PRODUCTOS prods ORDER BY PRO_ID DESC',
        	$query->getSql()
        );
    }

	public function testOrderByAsc(): void {
        $qb = new \Core\Query\QueryBuilder();
		$query = $qb->select(['id', 'nombre'])->from(['Product' => 'prods'])->orderBy(['nombre' => 'asc']);

		$this->assertEquals(
            'SELECT PRO_ID, PRO_NOMBRE FROM PRO_PRODUCTOS prods ORDER BY PRO_NOMBRE ASC',
        	$query->getSql()
        );
    }

	/**
	 * Test múltiples order by
	 */
	public function testOrderByMultiple(): void {
        $qb = new \Core\Query\QueryBuilder();
		$query = $qb->select(['id', 'nombre'])->from(['Product' => 'prods'])->orderBy(['nombre' => 'asc', 'id' => 'desc', 'activo']);

		$this->assertEquals(
            'SELECT PRO_ID, PRO_NOMBRE FROM PRO_PRODUCTOS prods ORDER BY PRO_NOMBRE ASC, PRO_ID DESC, PRO_ACTIVO',
        	$query->getSql()
        );
    }

	public function testUnion(): void {
        $qb = new \Core\Query\QueryBuilder();
		$union = $qb->select(['nombre'])->from(['Product' => 'p2']);
		$query = $qb->select(['id'])->from(['Product' => 'p1'])->union($union);

		$this->assertEquals(
			'SELECT PRO_ID FROM PRO_PRODUCTOS p1 UNION SELECT PRO_NOMBRE FROM PRO_PRODUCTOS p2',
			$query->getSql()
		);
    }

	public function testFunction() {
		$qb = new \Core\Query\QueryBuilder();
		$query = $qb->select(['id', 'nombre', Functions::currentDate()])->from(['Product' => 'prods']);

		$this->assertEquals(
			'SELECT PRO_ID, PRO_NOMBRE, SYSDATE() FROM PRO_PRODUCTOS prods',
			$query->getSql()
		);
	}

}
