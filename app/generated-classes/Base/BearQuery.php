<?php

namespace Base;

use \Bear as ChildBear;
use \BearQuery as ChildBearQuery;
use \Exception;
use \PDO;
use Map\BearTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'bear' table.
 *
 *
 *
 * @method     ChildBearQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildBearQuery orderByItemId($order = Criteria::ASC) Order by the item_id column
 * @method     ChildBearQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildBearQuery orderByUnderclassman($order = Criteria::ASC) Order by the underclassman column
 * @method     ChildBearQuery orderByJunior($order = Criteria::ASC) Order by the junior column
 * @method     ChildBearQuery orderBySenior($order = Criteria::ASC) Order by the senior column
 * @method     ChildBearQuery orderByPrice($order = Criteria::ASC) Order by the price column
 * @method     ChildBearQuery orderByImage($order = Criteria::ASC) Order by the image column
 *
 * @method     ChildBearQuery groupById() Group by the id column
 * @method     ChildBearQuery groupByItemId() Group by the item_id column
 * @method     ChildBearQuery groupByName() Group by the name column
 * @method     ChildBearQuery groupByUnderclassman() Group by the underclassman column
 * @method     ChildBearQuery groupByJunior() Group by the junior column
 * @method     ChildBearQuery groupBySenior() Group by the senior column
 * @method     ChildBearQuery groupByPrice() Group by the price column
 * @method     ChildBearQuery groupByImage() Group by the image column
 *
 * @method     ChildBearQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildBearQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildBearQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildBearQuery leftJoinMumBear($relationAlias = null) Adds a LEFT JOIN clause to the query using the MumBear relation
 * @method     ChildBearQuery rightJoinMumBear($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MumBear relation
 * @method     ChildBearQuery innerJoinMumBear($relationAlias = null) Adds a INNER JOIN clause to the query using the MumBear relation
 *
 * @method     ChildBear findOne(ConnectionInterface $con = null) Return the first ChildBear matching the query
 * @method     ChildBear findOneOrCreate(ConnectionInterface $con = null) Return the first ChildBear matching the query, or a new ChildBear object populated from the query conditions when no match is found
 *
 * @method     ChildBear findOneById(int $id) Return the first ChildBear filtered by the id column
 * @method     ChildBear findOneByItemId(string $item_id) Return the first ChildBear filtered by the item_id column
 * @method     ChildBear findOneByName(string $name) Return the first ChildBear filtered by the name column
 * @method     ChildBear findOneByUnderclassman(boolean $underclassman) Return the first ChildBear filtered by the underclassman column
 * @method     ChildBear findOneByJunior(boolean $junior) Return the first ChildBear filtered by the junior column
 * @method     ChildBear findOneBySenior(boolean $senior) Return the first ChildBear filtered by the senior column
 * @method     ChildBear findOneByPrice(string $price) Return the first ChildBear filtered by the price column
 * @method     ChildBear findOneByImage(string $image) Return the first ChildBear filtered by the image column
 *
 * @method     array findById(int $id) Return ChildBear objects filtered by the id column
 * @method     array findByItemId(string $item_id) Return ChildBear objects filtered by the item_id column
 * @method     array findByName(string $name) Return ChildBear objects filtered by the name column
 * @method     array findByUnderclassman(boolean $underclassman) Return ChildBear objects filtered by the underclassman column
 * @method     array findByJunior(boolean $junior) Return ChildBear objects filtered by the junior column
 * @method     array findBySenior(boolean $senior) Return ChildBear objects filtered by the senior column
 * @method     array findByPrice(string $price) Return ChildBear objects filtered by the price column
 * @method     array findByImage(string $image) Return ChildBear objects filtered by the image column
 *
 */
abstract class BearQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \Base\BearQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'mums', $modelName = '\\Bear', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildBearQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildBearQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \BearQuery) {
            return $criteria;
        }
        $query = new \BearQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildBear|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = BearTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(BearTableMap::DATABASE_NAME);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return   ChildBear A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ID, ITEM_ID, NAME, UNDERCLASSMAN, JUNIOR, SENIOR, PRICE FROM bear WHERE ID = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            $obj = new ChildBear();
            $obj->hydrate($row);
            BearTableMap::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildBear|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return ChildBearQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(BearTableMap::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildBearQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(BearTableMap::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildBearQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(BearTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(BearTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BearTableMap::ID, $id, $comparison);
    }

    /**
     * Filter the query on the item_id column
     *
     * Example usage:
     * <code>
     * $query->filterByItemId('fooValue');   // WHERE item_id = 'fooValue'
     * $query->filterByItemId('%fooValue%'); // WHERE item_id LIKE '%fooValue%'
     * </code>
     *
     * @param     string $itemId The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildBearQuery The current query, for fluid interface
     */
    public function filterByItemId($itemId = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($itemId)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $itemId)) {
                $itemId = str_replace('*', '%', $itemId);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(BearTableMap::ITEM_ID, $itemId, $comparison);
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%'); // WHERE name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildBearQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $name)) {
                $name = str_replace('*', '%', $name);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(BearTableMap::NAME, $name, $comparison);
    }

    /**
     * Filter the query on the underclassman column
     *
     * Example usage:
     * <code>
     * $query->filterByUnderclassman(true); // WHERE underclassman = true
     * $query->filterByUnderclassman('yes'); // WHERE underclassman = true
     * </code>
     *
     * @param     boolean|string $underclassman The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildBearQuery The current query, for fluid interface
     */
    public function filterByUnderclassman($underclassman = null, $comparison = null)
    {
        if (is_string($underclassman)) {
            $underclassman = in_array(strtolower($underclassman), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(BearTableMap::UNDERCLASSMAN, $underclassman, $comparison);
    }

    /**
     * Filter the query on the junior column
     *
     * Example usage:
     * <code>
     * $query->filterByJunior(true); // WHERE junior = true
     * $query->filterByJunior('yes'); // WHERE junior = true
     * </code>
     *
     * @param     boolean|string $junior The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildBearQuery The current query, for fluid interface
     */
    public function filterByJunior($junior = null, $comparison = null)
    {
        if (is_string($junior)) {
            $junior = in_array(strtolower($junior), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(BearTableMap::JUNIOR, $junior, $comparison);
    }

    /**
     * Filter the query on the senior column
     *
     * Example usage:
     * <code>
     * $query->filterBySenior(true); // WHERE senior = true
     * $query->filterBySenior('yes'); // WHERE senior = true
     * </code>
     *
     * @param     boolean|string $senior The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildBearQuery The current query, for fluid interface
     */
    public function filterBySenior($senior = null, $comparison = null)
    {
        if (is_string($senior)) {
            $senior = in_array(strtolower($senior), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(BearTableMap::SENIOR, $senior, $comparison);
    }

    /**
     * Filter the query on the price column
     *
     * Example usage:
     * <code>
     * $query->filterByPrice(1234); // WHERE price = 1234
     * $query->filterByPrice(array(12, 34)); // WHERE price IN (12, 34)
     * $query->filterByPrice(array('min' => 12)); // WHERE price > 12
     * </code>
     *
     * @param     mixed $price The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildBearQuery The current query, for fluid interface
     */
    public function filterByPrice($price = null, $comparison = null)
    {
        if (is_array($price)) {
            $useMinMax = false;
            if (isset($price['min'])) {
                $this->addUsingAlias(BearTableMap::PRICE, $price['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($price['max'])) {
                $this->addUsingAlias(BearTableMap::PRICE, $price['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BearTableMap::PRICE, $price, $comparison);
    }

    /**
     * Filter the query on the image column
     *
     * Example usage:
     * <code>
     * $query->filterByImage('fooValue');   // WHERE image = 'fooValue'
     * $query->filterByImage('%fooValue%'); // WHERE image LIKE '%fooValue%'
     * </code>
     *
     * @param     string $image The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildBearQuery The current query, for fluid interface
     */
    public function filterByImage($image = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($image)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $image)) {
                $image = str_replace('*', '%', $image);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(BearTableMap::IMAGE, $image, $comparison);
    }

    /**
     * Filter the query by a related \MumBear object
     *
     * @param \MumBear|ObjectCollection $mumBear  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildBearQuery The current query, for fluid interface
     */
    public function filterByMumBear($mumBear, $comparison = null)
    {
        if ($mumBear instanceof \MumBear) {
            return $this
                ->addUsingAlias(BearTableMap::ID, $mumBear->getBearId(), $comparison);
        } elseif ($mumBear instanceof ObjectCollection) {
            return $this
                ->useMumBearQuery()
                ->filterByPrimaryKeys($mumBear->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByMumBear() only accepts arguments of type \MumBear or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MumBear relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildBearQuery The current query, for fluid interface
     */
    public function joinMumBear($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MumBear');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'MumBear');
        }

        return $this;
    }

    /**
     * Use the MumBear relation MumBear object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \MumBearQuery A secondary query class using the current class as primary query
     */
    public function useMumBearQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMumBear($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MumBear', '\MumBearQuery');
    }

    /**
     * Filter the query by a related Mum object
     * using the mum_bear table as cross reference
     *
     * @param Mum $mum the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildBearQuery The current query, for fluid interface
     */
    public function filterByMum($mum, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useMumBearQuery()
            ->filterByMum($mum, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   ChildBear $bear Object to remove from the list of results
     *
     * @return ChildBearQuery The current query, for fluid interface
     */
    public function prune($bear = null)
    {
        if ($bear) {
            $this->addUsingAlias(BearTableMap::ID, $bear->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the bear table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(BearTableMap::DATABASE_NAME);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            BearTableMap::clearInstancePool();
            BearTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildBear or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildBear object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
     public function delete(ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(BearTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(BearTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        BearTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            BearTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

} // BearQuery
