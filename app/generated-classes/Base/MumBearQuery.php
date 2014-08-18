<?php

namespace Base;

use \MumBear as ChildMumBear;
use \MumBearQuery as ChildMumBearQuery;
use \Exception;
use \PDO;
use Map\MumBearTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'mum_bear' table.
 *
 *
 *
 * @method     ChildMumBearQuery orderByMumId($order = Criteria::ASC) Order by the mum_id column
 * @method     ChildMumBearQuery orderByBearId($order = Criteria::ASC) Order by the bear_id column
 *
 * @method     ChildMumBearQuery groupByMumId() Group by the mum_id column
 * @method     ChildMumBearQuery groupByBearId() Group by the bear_id column
 *
 * @method     ChildMumBearQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildMumBearQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildMumBearQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildMumBearQuery leftJoinMum($relationAlias = null) Adds a LEFT JOIN clause to the query using the Mum relation
 * @method     ChildMumBearQuery rightJoinMum($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Mum relation
 * @method     ChildMumBearQuery innerJoinMum($relationAlias = null) Adds a INNER JOIN clause to the query using the Mum relation
 *
 * @method     ChildMumBearQuery leftJoinBear($relationAlias = null) Adds a LEFT JOIN clause to the query using the Bear relation
 * @method     ChildMumBearQuery rightJoinBear($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Bear relation
 * @method     ChildMumBearQuery innerJoinBear($relationAlias = null) Adds a INNER JOIN clause to the query using the Bear relation
 *
 * @method     ChildMumBear findOne(ConnectionInterface $con = null) Return the first ChildMumBear matching the query
 * @method     ChildMumBear findOneOrCreate(ConnectionInterface $con = null) Return the first ChildMumBear matching the query, or a new ChildMumBear object populated from the query conditions when no match is found
 *
 * @method     ChildMumBear findOneByMumId(int $mum_id) Return the first ChildMumBear filtered by the mum_id column
 * @method     ChildMumBear findOneByBearId(int $bear_id) Return the first ChildMumBear filtered by the bear_id column
 *
 * @method     array findByMumId(int $mum_id) Return ChildMumBear objects filtered by the mum_id column
 * @method     array findByBearId(int $bear_id) Return ChildMumBear objects filtered by the bear_id column
 *
 */
abstract class MumBearQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \Base\MumBearQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'mums', $modelName = '\\MumBear', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildMumBearQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildMumBearQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \MumBearQuery) {
            return $criteria;
        }
        $query = new \MumBearQuery();
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
     * $obj = $c->findPk(array(12, 34), $con);
     * </code>
     *
     * @param array[$mum_id, $bear_id] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildMumBear|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = MumBearTableMap::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(MumBearTableMap::DATABASE_NAME);
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
     * @return   ChildMumBear A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT MUM_ID, BEAR_ID FROM mum_bear WHERE MUM_ID = :p0 AND BEAR_ID = :p1';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            $obj = new ChildMumBear();
            $obj->hydrate($row);
            MumBearTableMap::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
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
     * @return ChildMumBear|array|mixed the result, formatted by the current formatter
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
     * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
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
     * @return ChildMumBearQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(MumBearTableMap::MUM_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(MumBearTableMap::BEAR_ID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildMumBearQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(MumBearTableMap::MUM_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(MumBearTableMap::BEAR_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the mum_id column
     *
     * Example usage:
     * <code>
     * $query->filterByMumId(1234); // WHERE mum_id = 1234
     * $query->filterByMumId(array(12, 34)); // WHERE mum_id IN (12, 34)
     * $query->filterByMumId(array('min' => 12)); // WHERE mum_id > 12
     * </code>
     *
     * @see       filterByMum()
     *
     * @param     mixed $mumId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMumBearQuery The current query, for fluid interface
     */
    public function filterByMumId($mumId = null, $comparison = null)
    {
        if (is_array($mumId)) {
            $useMinMax = false;
            if (isset($mumId['min'])) {
                $this->addUsingAlias(MumBearTableMap::MUM_ID, $mumId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($mumId['max'])) {
                $this->addUsingAlias(MumBearTableMap::MUM_ID, $mumId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MumBearTableMap::MUM_ID, $mumId, $comparison);
    }

    /**
     * Filter the query on the bear_id column
     *
     * Example usage:
     * <code>
     * $query->filterByBearId(1234); // WHERE bear_id = 1234
     * $query->filterByBearId(array(12, 34)); // WHERE bear_id IN (12, 34)
     * $query->filterByBearId(array('min' => 12)); // WHERE bear_id > 12
     * </code>
     *
     * @see       filterByBear()
     *
     * @param     mixed $bearId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMumBearQuery The current query, for fluid interface
     */
    public function filterByBearId($bearId = null, $comparison = null)
    {
        if (is_array($bearId)) {
            $useMinMax = false;
            if (isset($bearId['min'])) {
                $this->addUsingAlias(MumBearTableMap::BEAR_ID, $bearId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($bearId['max'])) {
                $this->addUsingAlias(MumBearTableMap::BEAR_ID, $bearId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MumBearTableMap::BEAR_ID, $bearId, $comparison);
    }

    /**
     * Filter the query by a related \Mum object
     *
     * @param \Mum|ObjectCollection $mum The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMumBearQuery The current query, for fluid interface
     */
    public function filterByMum($mum, $comparison = null)
    {
        if ($mum instanceof \Mum) {
            return $this
                ->addUsingAlias(MumBearTableMap::MUM_ID, $mum->getId(), $comparison);
        } elseif ($mum instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MumBearTableMap::MUM_ID, $mum->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByMum() only accepts arguments of type \Mum or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Mum relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildMumBearQuery The current query, for fluid interface
     */
    public function joinMum($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Mum');

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
            $this->addJoinObject($join, 'Mum');
        }

        return $this;
    }

    /**
     * Use the Mum relation Mum object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \MumQuery A secondary query class using the current class as primary query
     */
    public function useMumQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMum($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Mum', '\MumQuery');
    }

    /**
     * Filter the query by a related \Bear object
     *
     * @param \Bear|ObjectCollection $bear The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMumBearQuery The current query, for fluid interface
     */
    public function filterByBear($bear, $comparison = null)
    {
        if ($bear instanceof \Bear) {
            return $this
                ->addUsingAlias(MumBearTableMap::BEAR_ID, $bear->getId(), $comparison);
        } elseif ($bear instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MumBearTableMap::BEAR_ID, $bear->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByBear() only accepts arguments of type \Bear or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Bear relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildMumBearQuery The current query, for fluid interface
     */
    public function joinBear($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Bear');

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
            $this->addJoinObject($join, 'Bear');
        }

        return $this;
    }

    /**
     * Use the Bear relation Bear object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \BearQuery A secondary query class using the current class as primary query
     */
    public function useBearQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinBear($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Bear', '\BearQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildMumBear $mumBear Object to remove from the list of results
     *
     * @return ChildMumBearQuery The current query, for fluid interface
     */
    public function prune($mumBear = null)
    {
        if ($mumBear) {
            $this->addCond('pruneCond0', $this->getAliasedColName(MumBearTableMap::MUM_ID), $mumBear->getMumId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(MumBearTableMap::BEAR_ID), $mumBear->getBearId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the mum_bear table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MumBearTableMap::DATABASE_NAME);
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
            MumBearTableMap::clearInstancePool();
            MumBearTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildMumBear or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildMumBear object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(MumBearTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(MumBearTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        MumBearTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            MumBearTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

} // MumBearQuery
