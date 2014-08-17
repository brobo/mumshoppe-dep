<?php

namespace Base;

use \AccentBow as ChildAccentBow;
use \AccentBowQuery as ChildAccentBowQuery;
use \Exception;
use \PDO;
use Map\AccentBowTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'accent_bow' table.
 *
 *
 *
 * @method     ChildAccentBowQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildAccentBowQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildAccentBowQuery orderByGradeId($order = Criteria::ASC) Order by the grade_id column
 *
 * @method     ChildAccentBowQuery groupById() Group by the id column
 * @method     ChildAccentBowQuery groupByName() Group by the name column
 * @method     ChildAccentBowQuery groupByGradeId() Group by the grade_id column
 *
 * @method     ChildAccentBowQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildAccentBowQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildAccentBowQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildAccentBowQuery leftJoinGrade($relationAlias = null) Adds a LEFT JOIN clause to the query using the Grade relation
 * @method     ChildAccentBowQuery rightJoinGrade($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Grade relation
 * @method     ChildAccentBowQuery innerJoinGrade($relationAlias = null) Adds a INNER JOIN clause to the query using the Grade relation
 *
 * @method     ChildAccentBowQuery leftJoinMum($relationAlias = null) Adds a LEFT JOIN clause to the query using the Mum relation
 * @method     ChildAccentBowQuery rightJoinMum($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Mum relation
 * @method     ChildAccentBowQuery innerJoinMum($relationAlias = null) Adds a INNER JOIN clause to the query using the Mum relation
 *
 * @method     ChildAccentBow findOne(ConnectionInterface $con = null) Return the first ChildAccentBow matching the query
 * @method     ChildAccentBow findOneOrCreate(ConnectionInterface $con = null) Return the first ChildAccentBow matching the query, or a new ChildAccentBow object populated from the query conditions when no match is found
 *
 * @method     ChildAccentBow findOneById(int $id) Return the first ChildAccentBow filtered by the id column
 * @method     ChildAccentBow findOneByName(string $name) Return the first ChildAccentBow filtered by the name column
 * @method     ChildAccentBow findOneByGradeId(int $grade_id) Return the first ChildAccentBow filtered by the grade_id column
 *
 * @method     array findById(int $id) Return ChildAccentBow objects filtered by the id column
 * @method     array findByName(string $name) Return ChildAccentBow objects filtered by the name column
 * @method     array findByGradeId(int $grade_id) Return ChildAccentBow objects filtered by the grade_id column
 *
 */
abstract class AccentBowQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \Base\AccentBowQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'mums', $modelName = '\\AccentBow', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildAccentBowQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildAccentBowQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \AccentBowQuery) {
            return $criteria;
        }
        $query = new \AccentBowQuery();
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
     * @return ChildAccentBow|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = AccentBowTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(AccentBowTableMap::DATABASE_NAME);
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
     * @return   ChildAccentBow A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ID, NAME, GRADE_ID FROM accent_bow WHERE ID = :p0';
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
            $obj = new ChildAccentBow();
            $obj->hydrate($row);
            AccentBowTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildAccentBow|array|mixed the result, formatted by the current formatter
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
     * @return ChildAccentBowQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(AccentBowTableMap::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildAccentBowQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(AccentBowTableMap::ID, $keys, Criteria::IN);
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
     * @return ChildAccentBowQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(AccentBowTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(AccentBowTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AccentBowTableMap::ID, $id, $comparison);
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
     * @return ChildAccentBowQuery The current query, for fluid interface
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

        return $this->addUsingAlias(AccentBowTableMap::NAME, $name, $comparison);
    }

    /**
     * Filter the query on the grade_id column
     *
     * Example usage:
     * <code>
     * $query->filterByGradeId(1234); // WHERE grade_id = 1234
     * $query->filterByGradeId(array(12, 34)); // WHERE grade_id IN (12, 34)
     * $query->filterByGradeId(array('min' => 12)); // WHERE grade_id > 12
     * </code>
     *
     * @see       filterByGrade()
     *
     * @param     mixed $gradeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildAccentBowQuery The current query, for fluid interface
     */
    public function filterByGradeId($gradeId = null, $comparison = null)
    {
        if (is_array($gradeId)) {
            $useMinMax = false;
            if (isset($gradeId['min'])) {
                $this->addUsingAlias(AccentBowTableMap::GRADE_ID, $gradeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($gradeId['max'])) {
                $this->addUsingAlias(AccentBowTableMap::GRADE_ID, $gradeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AccentBowTableMap::GRADE_ID, $gradeId, $comparison);
    }

    /**
     * Filter the query by a related \Grade object
     *
     * @param \Grade|ObjectCollection $grade The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildAccentBowQuery The current query, for fluid interface
     */
    public function filterByGrade($grade, $comparison = null)
    {
        if ($grade instanceof \Grade) {
            return $this
                ->addUsingAlias(AccentBowTableMap::GRADE_ID, $grade->getId(), $comparison);
        } elseif ($grade instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(AccentBowTableMap::GRADE_ID, $grade->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByGrade() only accepts arguments of type \Grade or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Grade relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildAccentBowQuery The current query, for fluid interface
     */
    public function joinGrade($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Grade');

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
            $this->addJoinObject($join, 'Grade');
        }

        return $this;
    }

    /**
     * Use the Grade relation Grade object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \GradeQuery A secondary query class using the current class as primary query
     */
    public function useGradeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinGrade($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Grade', '\GradeQuery');
    }

    /**
     * Filter the query by a related \Mum object
     *
     * @param \Mum|ObjectCollection $mum  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildAccentBowQuery The current query, for fluid interface
     */
    public function filterByMum($mum, $comparison = null)
    {
        if ($mum instanceof \Mum) {
            return $this
                ->addUsingAlias(AccentBowTableMap::ID, $mum->getAccentBowId(), $comparison);
        } elseif ($mum instanceof ObjectCollection) {
            return $this
                ->useMumQuery()
                ->filterByPrimaryKeys($mum->getPrimaryKeys())
                ->endUse();
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
     * @return ChildAccentBowQuery The current query, for fluid interface
     */
    public function joinMum($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
    public function useMumQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinMum($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Mum', '\MumQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildAccentBow $accentBow Object to remove from the list of results
     *
     * @return ChildAccentBowQuery The current query, for fluid interface
     */
    public function prune($accentBow = null)
    {
        if ($accentBow) {
            $this->addUsingAlias(AccentBowTableMap::ID, $accentBow->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the accent_bow table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(AccentBowTableMap::DATABASE_NAME);
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
            AccentBowTableMap::clearInstancePool();
            AccentBowTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildAccentBow or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildAccentBow object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(AccentBowTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(AccentBowTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        AccentBowTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            AccentBowTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

} // AccentBowQuery
