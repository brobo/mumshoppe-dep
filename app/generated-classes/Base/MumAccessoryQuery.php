<?php

namespace Base;

use \MumAccessory as ChildMumAccessory;
use \MumAccessoryQuery as ChildMumAccessoryQuery;
use \Exception;
use \PDO;
use Map\MumAccessoryTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'mum_accessory' table.
 *
 *
 *
 * @method     ChildMumAccessoryQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildMumAccessoryQuery orderByMumId($order = Criteria::ASC) Order by the mum_id column
 * @method     ChildMumAccessoryQuery orderByAccessoryId($order = Criteria::ASC) Order by the accessory_id column
 * @method     ChildMumAccessoryQuery orderByQuantity($order = Criteria::ASC) Order by the quantity column
 *
 * @method     ChildMumAccessoryQuery groupById() Group by the id column
 * @method     ChildMumAccessoryQuery groupByMumId() Group by the mum_id column
 * @method     ChildMumAccessoryQuery groupByAccessoryId() Group by the accessory_id column
 * @method     ChildMumAccessoryQuery groupByQuantity() Group by the quantity column
 *
 * @method     ChildMumAccessoryQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildMumAccessoryQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildMumAccessoryQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildMumAccessoryQuery leftJoinMum($relationAlias = null) Adds a LEFT JOIN clause to the query using the Mum relation
 * @method     ChildMumAccessoryQuery rightJoinMum($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Mum relation
 * @method     ChildMumAccessoryQuery innerJoinMum($relationAlias = null) Adds a INNER JOIN clause to the query using the Mum relation
 *
 * @method     ChildMumAccessoryQuery leftJoinAccessory($relationAlias = null) Adds a LEFT JOIN clause to the query using the Accessory relation
 * @method     ChildMumAccessoryQuery rightJoinAccessory($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Accessory relation
 * @method     ChildMumAccessoryQuery innerJoinAccessory($relationAlias = null) Adds a INNER JOIN clause to the query using the Accessory relation
 *
 * @method     ChildMumAccessory findOne(ConnectionInterface $con = null) Return the first ChildMumAccessory matching the query
 * @method     ChildMumAccessory findOneOrCreate(ConnectionInterface $con = null) Return the first ChildMumAccessory matching the query, or a new ChildMumAccessory object populated from the query conditions when no match is found
 *
 * @method     ChildMumAccessory findOneById(int $id) Return the first ChildMumAccessory filtered by the id column
 * @method     ChildMumAccessory findOneByMumId(int $mum_id) Return the first ChildMumAccessory filtered by the mum_id column
 * @method     ChildMumAccessory findOneByAccessoryId(int $accessory_id) Return the first ChildMumAccessory filtered by the accessory_id column
 * @method     ChildMumAccessory findOneByQuantity(int $quantity) Return the first ChildMumAccessory filtered by the quantity column
 *
 * @method     array findById(int $id) Return ChildMumAccessory objects filtered by the id column
 * @method     array findByMumId(int $mum_id) Return ChildMumAccessory objects filtered by the mum_id column
 * @method     array findByAccessoryId(int $accessory_id) Return ChildMumAccessory objects filtered by the accessory_id column
 * @method     array findByQuantity(int $quantity) Return ChildMumAccessory objects filtered by the quantity column
 *
 */
abstract class MumAccessoryQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \Base\MumAccessoryQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'mums', $modelName = '\\MumAccessory', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildMumAccessoryQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildMumAccessoryQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \MumAccessoryQuery) {
            return $criteria;
        }
        $query = new \MumAccessoryQuery();
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
     * @return ChildMumAccessory|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = MumAccessoryTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(MumAccessoryTableMap::DATABASE_NAME);
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
     * @return   ChildMumAccessory A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ID, MUM_ID, ACCESSORY_ID, QUANTITY FROM mum_accessory WHERE ID = :p0';
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
            $obj = new ChildMumAccessory();
            $obj->hydrate($row);
            MumAccessoryTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildMumAccessory|array|mixed the result, formatted by the current formatter
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
     * @return ChildMumAccessoryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(MumAccessoryTableMap::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildMumAccessoryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(MumAccessoryTableMap::ID, $keys, Criteria::IN);
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
     * @return ChildMumAccessoryQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(MumAccessoryTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(MumAccessoryTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MumAccessoryTableMap::ID, $id, $comparison);
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
     * @return ChildMumAccessoryQuery The current query, for fluid interface
     */
    public function filterByMumId($mumId = null, $comparison = null)
    {
        if (is_array($mumId)) {
            $useMinMax = false;
            if (isset($mumId['min'])) {
                $this->addUsingAlias(MumAccessoryTableMap::MUM_ID, $mumId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($mumId['max'])) {
                $this->addUsingAlias(MumAccessoryTableMap::MUM_ID, $mumId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MumAccessoryTableMap::MUM_ID, $mumId, $comparison);
    }

    /**
     * Filter the query on the accessory_id column
     *
     * Example usage:
     * <code>
     * $query->filterByAccessoryId(1234); // WHERE accessory_id = 1234
     * $query->filterByAccessoryId(array(12, 34)); // WHERE accessory_id IN (12, 34)
     * $query->filterByAccessoryId(array('min' => 12)); // WHERE accessory_id > 12
     * </code>
     *
     * @see       filterByAccessory()
     *
     * @param     mixed $accessoryId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMumAccessoryQuery The current query, for fluid interface
     */
    public function filterByAccessoryId($accessoryId = null, $comparison = null)
    {
        if (is_array($accessoryId)) {
            $useMinMax = false;
            if (isset($accessoryId['min'])) {
                $this->addUsingAlias(MumAccessoryTableMap::ACCESSORY_ID, $accessoryId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($accessoryId['max'])) {
                $this->addUsingAlias(MumAccessoryTableMap::ACCESSORY_ID, $accessoryId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MumAccessoryTableMap::ACCESSORY_ID, $accessoryId, $comparison);
    }

    /**
     * Filter the query on the quantity column
     *
     * Example usage:
     * <code>
     * $query->filterByQuantity(1234); // WHERE quantity = 1234
     * $query->filterByQuantity(array(12, 34)); // WHERE quantity IN (12, 34)
     * $query->filterByQuantity(array('min' => 12)); // WHERE quantity > 12
     * </code>
     *
     * @param     mixed $quantity The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMumAccessoryQuery The current query, for fluid interface
     */
    public function filterByQuantity($quantity = null, $comparison = null)
    {
        if (is_array($quantity)) {
            $useMinMax = false;
            if (isset($quantity['min'])) {
                $this->addUsingAlias(MumAccessoryTableMap::QUANTITY, $quantity['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($quantity['max'])) {
                $this->addUsingAlias(MumAccessoryTableMap::QUANTITY, $quantity['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MumAccessoryTableMap::QUANTITY, $quantity, $comparison);
    }

    /**
     * Filter the query by a related \Mum object
     *
     * @param \Mum|ObjectCollection $mum The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMumAccessoryQuery The current query, for fluid interface
     */
    public function filterByMum($mum, $comparison = null)
    {
        if ($mum instanceof \Mum) {
            return $this
                ->addUsingAlias(MumAccessoryTableMap::MUM_ID, $mum->getId(), $comparison);
        } elseif ($mum instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MumAccessoryTableMap::MUM_ID, $mum->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return ChildMumAccessoryQuery The current query, for fluid interface
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
     * Filter the query by a related \Accessory object
     *
     * @param \Accessory|ObjectCollection $accessory The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMumAccessoryQuery The current query, for fluid interface
     */
    public function filterByAccessory($accessory, $comparison = null)
    {
        if ($accessory instanceof \Accessory) {
            return $this
                ->addUsingAlias(MumAccessoryTableMap::ACCESSORY_ID, $accessory->getId(), $comparison);
        } elseif ($accessory instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MumAccessoryTableMap::ACCESSORY_ID, $accessory->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByAccessory() only accepts arguments of type \Accessory or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Accessory relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildMumAccessoryQuery The current query, for fluid interface
     */
    public function joinAccessory($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Accessory');

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
            $this->addJoinObject($join, 'Accessory');
        }

        return $this;
    }

    /**
     * Use the Accessory relation Accessory object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \AccessoryQuery A secondary query class using the current class as primary query
     */
    public function useAccessoryQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinAccessory($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Accessory', '\AccessoryQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildMumAccessory $mumAccessory Object to remove from the list of results
     *
     * @return ChildMumAccessoryQuery The current query, for fluid interface
     */
    public function prune($mumAccessory = null)
    {
        if ($mumAccessory) {
            $this->addUsingAlias(MumAccessoryTableMap::ID, $mumAccessory->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the mum_accessory table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MumAccessoryTableMap::DATABASE_NAME);
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
            MumAccessoryTableMap::clearInstancePool();
            MumAccessoryTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildMumAccessory or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildMumAccessory object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(MumAccessoryTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(MumAccessoryTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        MumAccessoryTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            MumAccessoryTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

} // MumAccessoryQuery
