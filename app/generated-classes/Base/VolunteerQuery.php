<?php

namespace Base;

use \Volunteer as ChildVolunteer;
use \VolunteerQuery as ChildVolunteerQuery;
use \Exception;
use \PDO;
use Map\VolunteerTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'volunteer' table.
 *
 *
 *
 * @method     ChildVolunteerQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildVolunteerQuery orderByEmail($order = Criteria::ASC) Order by the email column
 * @method     ChildVolunteerQuery orderByPassword($order = Criteria::ASC) Order by the password column
 * @method     ChildVolunteerQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildVolunteerQuery orderByPhone($order = Criteria::ASC) Order by the phone column
 * @method     ChildVolunteerQuery orderByTokenExpiration($order = Criteria::ASC) Order by the token_expiration column
 *
 * @method     ChildVolunteerQuery groupById() Group by the id column
 * @method     ChildVolunteerQuery groupByEmail() Group by the email column
 * @method     ChildVolunteerQuery groupByPassword() Group by the password column
 * @method     ChildVolunteerQuery groupByName() Group by the name column
 * @method     ChildVolunteerQuery groupByPhone() Group by the phone column
 * @method     ChildVolunteerQuery groupByTokenExpiration() Group by the token_expiration column
 *
 * @method     ChildVolunteerQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildVolunteerQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildVolunteerQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildVolunteer findOne(ConnectionInterface $con = null) Return the first ChildVolunteer matching the query
 * @method     ChildVolunteer findOneOrCreate(ConnectionInterface $con = null) Return the first ChildVolunteer matching the query, or a new ChildVolunteer object populated from the query conditions when no match is found
 *
 * @method     ChildVolunteer findOneById(int $id) Return the first ChildVolunteer filtered by the id column
 * @method     ChildVolunteer findOneByEmail(string $email) Return the first ChildVolunteer filtered by the email column
 * @method     ChildVolunteer findOneByPassword(string $password) Return the first ChildVolunteer filtered by the password column
 * @method     ChildVolunteer findOneByName(string $name) Return the first ChildVolunteer filtered by the name column
 * @method     ChildVolunteer findOneByPhone(string $phone) Return the first ChildVolunteer filtered by the phone column
 * @method     ChildVolunteer findOneByTokenExpiration(string $token_expiration) Return the first ChildVolunteer filtered by the token_expiration column
 *
 * @method     array findById(int $id) Return ChildVolunteer objects filtered by the id column
 * @method     array findByEmail(string $email) Return ChildVolunteer objects filtered by the email column
 * @method     array findByPassword(string $password) Return ChildVolunteer objects filtered by the password column
 * @method     array findByName(string $name) Return ChildVolunteer objects filtered by the name column
 * @method     array findByPhone(string $phone) Return ChildVolunteer objects filtered by the phone column
 * @method     array findByTokenExpiration(string $token_expiration) Return ChildVolunteer objects filtered by the token_expiration column
 *
 */
abstract class VolunteerQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \Base\VolunteerQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'mums', $modelName = '\\Volunteer', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildVolunteerQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildVolunteerQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \VolunteerQuery) {
            return $criteria;
        }
        $query = new \VolunteerQuery();
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
     * @return ChildVolunteer|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = VolunteerTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(VolunteerTableMap::DATABASE_NAME);
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
     * @return   ChildVolunteer A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ID, PASSWORD, NAME, PHONE FROM volunteer WHERE ID = :p0';
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
            $obj = new ChildVolunteer();
            $obj->hydrate($row);
            VolunteerTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildVolunteer|array|mixed the result, formatted by the current formatter
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
     * @return ChildVolunteerQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(VolunteerTableMap::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildVolunteerQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(VolunteerTableMap::ID, $keys, Criteria::IN);
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
     * @return ChildVolunteerQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(VolunteerTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(VolunteerTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(VolunteerTableMap::ID, $id, $comparison);
    }

    /**
     * Filter the query on the email column
     *
     * Example usage:
     * <code>
     * $query->filterByEmail('fooValue');   // WHERE email = 'fooValue'
     * $query->filterByEmail('%fooValue%'); // WHERE email LIKE '%fooValue%'
     * </code>
     *
     * @param     string $email The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildVolunteerQuery The current query, for fluid interface
     */
    public function filterByEmail($email = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($email)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $email)) {
                $email = str_replace('*', '%', $email);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(VolunteerTableMap::EMAIL, $email, $comparison);
    }

    /**
     * Filter the query on the password column
     *
     * Example usage:
     * <code>
     * $query->filterByPassword('fooValue');   // WHERE password = 'fooValue'
     * $query->filterByPassword('%fooValue%'); // WHERE password LIKE '%fooValue%'
     * </code>
     *
     * @param     string $password The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildVolunteerQuery The current query, for fluid interface
     */
    public function filterByPassword($password = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($password)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $password)) {
                $password = str_replace('*', '%', $password);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(VolunteerTableMap::PASSWORD, $password, $comparison);
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
     * @return ChildVolunteerQuery The current query, for fluid interface
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

        return $this->addUsingAlias(VolunteerTableMap::NAME, $name, $comparison);
    }

    /**
     * Filter the query on the phone column
     *
     * Example usage:
     * <code>
     * $query->filterByPhone('fooValue');   // WHERE phone = 'fooValue'
     * $query->filterByPhone('%fooValue%'); // WHERE phone LIKE '%fooValue%'
     * </code>
     *
     * @param     string $phone The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildVolunteerQuery The current query, for fluid interface
     */
    public function filterByPhone($phone = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($phone)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $phone)) {
                $phone = str_replace('*', '%', $phone);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(VolunteerTableMap::PHONE, $phone, $comparison);
    }

    /**
     * Filter the query on the token_expiration column
     *
     * Example usage:
     * <code>
     * $query->filterByTokenExpiration('2011-03-14'); // WHERE token_expiration = '2011-03-14'
     * $query->filterByTokenExpiration('now'); // WHERE token_expiration = '2011-03-14'
     * $query->filterByTokenExpiration(array('max' => 'yesterday')); // WHERE token_expiration > '2011-03-13'
     * </code>
     *
     * @param     mixed $tokenExpiration The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildVolunteerQuery The current query, for fluid interface
     */
    public function filterByTokenExpiration($tokenExpiration = null, $comparison = null)
    {
        if (is_array($tokenExpiration)) {
            $useMinMax = false;
            if (isset($tokenExpiration['min'])) {
                $this->addUsingAlias(VolunteerTableMap::TOKEN_EXPIRATION, $tokenExpiration['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($tokenExpiration['max'])) {
                $this->addUsingAlias(VolunteerTableMap::TOKEN_EXPIRATION, $tokenExpiration['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(VolunteerTableMap::TOKEN_EXPIRATION, $tokenExpiration, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildVolunteer $volunteer Object to remove from the list of results
     *
     * @return ChildVolunteerQuery The current query, for fluid interface
     */
    public function prune($volunteer = null)
    {
        if ($volunteer) {
            $this->addUsingAlias(VolunteerTableMap::ID, $volunteer->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the volunteer table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(VolunteerTableMap::DATABASE_NAME);
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
            VolunteerTableMap::clearInstancePool();
            VolunteerTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildVolunteer or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildVolunteer object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(VolunteerTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(VolunteerTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        VolunteerTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            VolunteerTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

} // VolunteerQuery
