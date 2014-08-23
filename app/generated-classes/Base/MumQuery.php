<?php

namespace Base;

use \Mum as ChildMum;
use \MumQuery as ChildMumQuery;
use \Exception;
use \PDO;
use Map\MumTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'mum' table.
 *
 *
 *
 * @method     ChildMumQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildMumQuery orderByCustomerId($order = Criteria::ASC) Order by the customer_id column
 * @method     ChildMumQuery orderByBackingId($order = Criteria::ASC) Order by the backing_id column
 * @method     ChildMumQuery orderByAccentBowId($order = Criteria::ASC) Order by the accent_bow_id column
 * @method     ChildMumQuery orderByLetter1Id($order = Criteria::ASC) Order by the letter1_id column
 * @method     ChildMumQuery orderByNameRibbon1($order = Criteria::ASC) Order by the name_ribbon1 column
 * @method     ChildMumQuery orderByLetter2Id($order = Criteria::ASC) Order by the letter2_id column
 * @method     ChildMumQuery orderByNameRibbon2($order = Criteria::ASC) Order by the name_ribbon2 column
 * @method     ChildMumQuery orderByStatusId($order = Criteria::ASC) Order by the status_id column
 * @method     ChildMumQuery orderByPaid($order = Criteria::ASC) Order by the paid column
 * @method     ChildMumQuery orderByOrderDate($order = Criteria::ASC) Order by the order_date column
 * @method     ChildMumQuery orderByPaidDate($order = Criteria::ASC) Order by the paid_date column
 * @method     ChildMumQuery orderByDeliveryDate($order = Criteria::ASC) Order by the delivery_date column
 *
 * @method     ChildMumQuery groupById() Group by the id column
 * @method     ChildMumQuery groupByCustomerId() Group by the customer_id column
 * @method     ChildMumQuery groupByBackingId() Group by the backing_id column
 * @method     ChildMumQuery groupByAccentBowId() Group by the accent_bow_id column
 * @method     ChildMumQuery groupByLetter1Id() Group by the letter1_id column
 * @method     ChildMumQuery groupByNameRibbon1() Group by the name_ribbon1 column
 * @method     ChildMumQuery groupByLetter2Id() Group by the letter2_id column
 * @method     ChildMumQuery groupByNameRibbon2() Group by the name_ribbon2 column
 * @method     ChildMumQuery groupByStatusId() Group by the status_id column
 * @method     ChildMumQuery groupByPaid() Group by the paid column
 * @method     ChildMumQuery groupByOrderDate() Group by the order_date column
 * @method     ChildMumQuery groupByPaidDate() Group by the paid_date column
 * @method     ChildMumQuery groupByDeliveryDate() Group by the delivery_date column
 *
 * @method     ChildMumQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildMumQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildMumQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildMumQuery leftJoinCustomer($relationAlias = null) Adds a LEFT JOIN clause to the query using the Customer relation
 * @method     ChildMumQuery rightJoinCustomer($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Customer relation
 * @method     ChildMumQuery innerJoinCustomer($relationAlias = null) Adds a INNER JOIN clause to the query using the Customer relation
 *
 * @method     ChildMumQuery leftJoinBacking($relationAlias = null) Adds a LEFT JOIN clause to the query using the Backing relation
 * @method     ChildMumQuery rightJoinBacking($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Backing relation
 * @method     ChildMumQuery innerJoinBacking($relationAlias = null) Adds a INNER JOIN clause to the query using the Backing relation
 *
 * @method     ChildMumQuery leftJoinAccentBow($relationAlias = null) Adds a LEFT JOIN clause to the query using the AccentBow relation
 * @method     ChildMumQuery rightJoinAccentBow($relationAlias = null) Adds a RIGHT JOIN clause to the query using the AccentBow relation
 * @method     ChildMumQuery innerJoinAccentBow($relationAlias = null) Adds a INNER JOIN clause to the query using the AccentBow relation
 *
 * @method     ChildMumQuery leftJoinLetter($relationAlias = null) Adds a LEFT JOIN clause to the query using the Letter relation
 * @method     ChildMumQuery rightJoinLetter($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Letter relation
 * @method     ChildMumQuery innerJoinLetter($relationAlias = null) Adds a INNER JOIN clause to the query using the Letter relation
 *
 * @method     ChildMumQuery leftJoinStatus($relationAlias = null) Adds a LEFT JOIN clause to the query using the Status relation
 * @method     ChildMumQuery rightJoinStatus($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Status relation
 * @method     ChildMumQuery innerJoinStatus($relationAlias = null) Adds a INNER JOIN clause to the query using the Status relation
 *
 * @method     ChildMumQuery leftJoinMumTrinket($relationAlias = null) Adds a LEFT JOIN clause to the query using the MumTrinket relation
 * @method     ChildMumQuery rightJoinMumTrinket($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MumTrinket relation
 * @method     ChildMumQuery innerJoinMumTrinket($relationAlias = null) Adds a INNER JOIN clause to the query using the MumTrinket relation
 *
 * @method     ChildMumQuery leftJoinMumBear($relationAlias = null) Adds a LEFT JOIN clause to the query using the MumBear relation
 * @method     ChildMumQuery rightJoinMumBear($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MumBear relation
 * @method     ChildMumQuery innerJoinMumBear($relationAlias = null) Adds a INNER JOIN clause to the query using the MumBear relation
 *
 * @method     ChildMum findOne(ConnectionInterface $con = null) Return the first ChildMum matching the query
 * @method     ChildMum findOneOrCreate(ConnectionInterface $con = null) Return the first ChildMum matching the query, or a new ChildMum object populated from the query conditions when no match is found
 *
 * @method     ChildMum findOneById(int $id) Return the first ChildMum filtered by the id column
 * @method     ChildMum findOneByCustomerId(int $customer_id) Return the first ChildMum filtered by the customer_id column
 * @method     ChildMum findOneByBackingId(int $backing_id) Return the first ChildMum filtered by the backing_id column
 * @method     ChildMum findOneByAccentBowId(int $accent_bow_id) Return the first ChildMum filtered by the accent_bow_id column
 * @method     ChildMum findOneByLetter1Id(int $letter1_id) Return the first ChildMum filtered by the letter1_id column
 * @method     ChildMum findOneByNameRibbon1(string $name_ribbon1) Return the first ChildMum filtered by the name_ribbon1 column
 * @method     ChildMum findOneByLetter2Id(int $letter2_id) Return the first ChildMum filtered by the letter2_id column
 * @method     ChildMum findOneByNameRibbon2(string $name_ribbon2) Return the first ChildMum filtered by the name_ribbon2 column
 * @method     ChildMum findOneByStatusId(int $status_id) Return the first ChildMum filtered by the status_id column
 * @method     ChildMum findOneByPaid(boolean $paid) Return the first ChildMum filtered by the paid column
 * @method     ChildMum findOneByOrderDate(string $order_date) Return the first ChildMum filtered by the order_date column
 * @method     ChildMum findOneByPaidDate(string $paid_date) Return the first ChildMum filtered by the paid_date column
 * @method     ChildMum findOneByDeliveryDate(string $delivery_date) Return the first ChildMum filtered by the delivery_date column
 *
 * @method     array findById(int $id) Return ChildMum objects filtered by the id column
 * @method     array findByCustomerId(int $customer_id) Return ChildMum objects filtered by the customer_id column
 * @method     array findByBackingId(int $backing_id) Return ChildMum objects filtered by the backing_id column
 * @method     array findByAccentBowId(int $accent_bow_id) Return ChildMum objects filtered by the accent_bow_id column
 * @method     array findByLetter1Id(int $letter1_id) Return ChildMum objects filtered by the letter1_id column
 * @method     array findByNameRibbon1(string $name_ribbon1) Return ChildMum objects filtered by the name_ribbon1 column
 * @method     array findByLetter2Id(int $letter2_id) Return ChildMum objects filtered by the letter2_id column
 * @method     array findByNameRibbon2(string $name_ribbon2) Return ChildMum objects filtered by the name_ribbon2 column
 * @method     array findByStatusId(int $status_id) Return ChildMum objects filtered by the status_id column
 * @method     array findByPaid(boolean $paid) Return ChildMum objects filtered by the paid column
 * @method     array findByOrderDate(string $order_date) Return ChildMum objects filtered by the order_date column
 * @method     array findByPaidDate(string $paid_date) Return ChildMum objects filtered by the paid_date column
 * @method     array findByDeliveryDate(string $delivery_date) Return ChildMum objects filtered by the delivery_date column
 *
 */
abstract class MumQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \Base\MumQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'mums', $modelName = '\\Mum', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildMumQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildMumQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \MumQuery) {
            return $criteria;
        }
        $query = new \MumQuery();
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
     * @return ChildMum|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = MumTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(MumTableMap::DATABASE_NAME);
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
     * @return   ChildMum A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ID, CUSTOMER_ID, BACKING_ID, ACCENT_BOW_ID, LETTER1_ID, NAME_RIBBON1, LETTER2_ID, NAME_RIBBON2, STATUS_ID, PAID, ORDER_DATE, PAID_DATE, DELIVERY_DATE FROM mum WHERE ID = :p0';
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
            $obj = new ChildMum();
            $obj->hydrate($row);
            MumTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildMum|array|mixed the result, formatted by the current formatter
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
     * @return ChildMumQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(MumTableMap::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildMumQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(MumTableMap::ID, $keys, Criteria::IN);
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
     * @return ChildMumQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(MumTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(MumTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MumTableMap::ID, $id, $comparison);
    }

    /**
     * Filter the query on the customer_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCustomerId(1234); // WHERE customer_id = 1234
     * $query->filterByCustomerId(array(12, 34)); // WHERE customer_id IN (12, 34)
     * $query->filterByCustomerId(array('min' => 12)); // WHERE customer_id > 12
     * </code>
     *
     * @see       filterByCustomer()
     *
     * @param     mixed $customerId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMumQuery The current query, for fluid interface
     */
    public function filterByCustomerId($customerId = null, $comparison = null)
    {
        if (is_array($customerId)) {
            $useMinMax = false;
            if (isset($customerId['min'])) {
                $this->addUsingAlias(MumTableMap::CUSTOMER_ID, $customerId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($customerId['max'])) {
                $this->addUsingAlias(MumTableMap::CUSTOMER_ID, $customerId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MumTableMap::CUSTOMER_ID, $customerId, $comparison);
    }

    /**
     * Filter the query on the backing_id column
     *
     * Example usage:
     * <code>
     * $query->filterByBackingId(1234); // WHERE backing_id = 1234
     * $query->filterByBackingId(array(12, 34)); // WHERE backing_id IN (12, 34)
     * $query->filterByBackingId(array('min' => 12)); // WHERE backing_id > 12
     * </code>
     *
     * @see       filterByBacking()
     *
     * @param     mixed $backingId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMumQuery The current query, for fluid interface
     */
    public function filterByBackingId($backingId = null, $comparison = null)
    {
        if (is_array($backingId)) {
            $useMinMax = false;
            if (isset($backingId['min'])) {
                $this->addUsingAlias(MumTableMap::BACKING_ID, $backingId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($backingId['max'])) {
                $this->addUsingAlias(MumTableMap::BACKING_ID, $backingId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MumTableMap::BACKING_ID, $backingId, $comparison);
    }

    /**
     * Filter the query on the accent_bow_id column
     *
     * Example usage:
     * <code>
     * $query->filterByAccentBowId(1234); // WHERE accent_bow_id = 1234
     * $query->filterByAccentBowId(array(12, 34)); // WHERE accent_bow_id IN (12, 34)
     * $query->filterByAccentBowId(array('min' => 12)); // WHERE accent_bow_id > 12
     * </code>
     *
     * @see       filterByAccentBow()
     *
     * @param     mixed $accentBowId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMumQuery The current query, for fluid interface
     */
    public function filterByAccentBowId($accentBowId = null, $comparison = null)
    {
        if (is_array($accentBowId)) {
            $useMinMax = false;
            if (isset($accentBowId['min'])) {
                $this->addUsingAlias(MumTableMap::ACCENT_BOW_ID, $accentBowId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($accentBowId['max'])) {
                $this->addUsingAlias(MumTableMap::ACCENT_BOW_ID, $accentBowId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MumTableMap::ACCENT_BOW_ID, $accentBowId, $comparison);
    }

    /**
     * Filter the query on the letter1_id column
     *
     * Example usage:
     * <code>
     * $query->filterByLetter1Id(1234); // WHERE letter1_id = 1234
     * $query->filterByLetter1Id(array(12, 34)); // WHERE letter1_id IN (12, 34)
     * $query->filterByLetter1Id(array('min' => 12)); // WHERE letter1_id > 12
     * </code>
     *
     * @see       filterByLetter()
     *
     * @param     mixed $letter1Id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMumQuery The current query, for fluid interface
     */
    public function filterByLetter1Id($letter1Id = null, $comparison = null)
    {
        if (is_array($letter1Id)) {
            $useMinMax = false;
            if (isset($letter1Id['min'])) {
                $this->addUsingAlias(MumTableMap::LETTER1_ID, $letter1Id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($letter1Id['max'])) {
                $this->addUsingAlias(MumTableMap::LETTER1_ID, $letter1Id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MumTableMap::LETTER1_ID, $letter1Id, $comparison);
    }

    /**
     * Filter the query on the name_ribbon1 column
     *
     * Example usage:
     * <code>
     * $query->filterByNameRibbon1('fooValue');   // WHERE name_ribbon1 = 'fooValue'
     * $query->filterByNameRibbon1('%fooValue%'); // WHERE name_ribbon1 LIKE '%fooValue%'
     * </code>
     *
     * @param     string $nameRibbon1 The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMumQuery The current query, for fluid interface
     */
    public function filterByNameRibbon1($nameRibbon1 = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($nameRibbon1)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $nameRibbon1)) {
                $nameRibbon1 = str_replace('*', '%', $nameRibbon1);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(MumTableMap::NAME_RIBBON1, $nameRibbon1, $comparison);
    }

    /**
     * Filter the query on the letter2_id column
     *
     * Example usage:
     * <code>
     * $query->filterByLetter2Id(1234); // WHERE letter2_id = 1234
     * $query->filterByLetter2Id(array(12, 34)); // WHERE letter2_id IN (12, 34)
     * $query->filterByLetter2Id(array('min' => 12)); // WHERE letter2_id > 12
     * </code>
     *
     * @see       filterByLetter()
     *
     * @param     mixed $letter2Id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMumQuery The current query, for fluid interface
     */
    public function filterByLetter2Id($letter2Id = null, $comparison = null)
    {
        if (is_array($letter2Id)) {
            $useMinMax = false;
            if (isset($letter2Id['min'])) {
                $this->addUsingAlias(MumTableMap::LETTER2_ID, $letter2Id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($letter2Id['max'])) {
                $this->addUsingAlias(MumTableMap::LETTER2_ID, $letter2Id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MumTableMap::LETTER2_ID, $letter2Id, $comparison);
    }

    /**
     * Filter the query on the name_ribbon2 column
     *
     * Example usage:
     * <code>
     * $query->filterByNameRibbon2('fooValue');   // WHERE name_ribbon2 = 'fooValue'
     * $query->filterByNameRibbon2('%fooValue%'); // WHERE name_ribbon2 LIKE '%fooValue%'
     * </code>
     *
     * @param     string $nameRibbon2 The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMumQuery The current query, for fluid interface
     */
    public function filterByNameRibbon2($nameRibbon2 = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($nameRibbon2)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $nameRibbon2)) {
                $nameRibbon2 = str_replace('*', '%', $nameRibbon2);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(MumTableMap::NAME_RIBBON2, $nameRibbon2, $comparison);
    }

    /**
     * Filter the query on the status_id column
     *
     * Example usage:
     * <code>
     * $query->filterByStatusId(1234); // WHERE status_id = 1234
     * $query->filterByStatusId(array(12, 34)); // WHERE status_id IN (12, 34)
     * $query->filterByStatusId(array('min' => 12)); // WHERE status_id > 12
     * </code>
     *
     * @see       filterByStatus()
     *
     * @param     mixed $statusId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMumQuery The current query, for fluid interface
     */
    public function filterByStatusId($statusId = null, $comparison = null)
    {
        if (is_array($statusId)) {
            $useMinMax = false;
            if (isset($statusId['min'])) {
                $this->addUsingAlias(MumTableMap::STATUS_ID, $statusId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($statusId['max'])) {
                $this->addUsingAlias(MumTableMap::STATUS_ID, $statusId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MumTableMap::STATUS_ID, $statusId, $comparison);
    }

    /**
     * Filter the query on the paid column
     *
     * Example usage:
     * <code>
     * $query->filterByPaid(true); // WHERE paid = true
     * $query->filterByPaid('yes'); // WHERE paid = true
     * </code>
     *
     * @param     boolean|string $paid The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMumQuery The current query, for fluid interface
     */
    public function filterByPaid($paid = null, $comparison = null)
    {
        if (is_string($paid)) {
            $paid = in_array(strtolower($paid), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(MumTableMap::PAID, $paid, $comparison);
    }

    /**
     * Filter the query on the order_date column
     *
     * Example usage:
     * <code>
     * $query->filterByOrderDate('2011-03-14'); // WHERE order_date = '2011-03-14'
     * $query->filterByOrderDate('now'); // WHERE order_date = '2011-03-14'
     * $query->filterByOrderDate(array('max' => 'yesterday')); // WHERE order_date > '2011-03-13'
     * </code>
     *
     * @param     mixed $orderDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMumQuery The current query, for fluid interface
     */
    public function filterByOrderDate($orderDate = null, $comparison = null)
    {
        if (is_array($orderDate)) {
            $useMinMax = false;
            if (isset($orderDate['min'])) {
                $this->addUsingAlias(MumTableMap::ORDER_DATE, $orderDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($orderDate['max'])) {
                $this->addUsingAlias(MumTableMap::ORDER_DATE, $orderDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MumTableMap::ORDER_DATE, $orderDate, $comparison);
    }

    /**
     * Filter the query on the paid_date column
     *
     * Example usage:
     * <code>
     * $query->filterByPaidDate('2011-03-14'); // WHERE paid_date = '2011-03-14'
     * $query->filterByPaidDate('now'); // WHERE paid_date = '2011-03-14'
     * $query->filterByPaidDate(array('max' => 'yesterday')); // WHERE paid_date > '2011-03-13'
     * </code>
     *
     * @param     mixed $paidDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMumQuery The current query, for fluid interface
     */
    public function filterByPaidDate($paidDate = null, $comparison = null)
    {
        if (is_array($paidDate)) {
            $useMinMax = false;
            if (isset($paidDate['min'])) {
                $this->addUsingAlias(MumTableMap::PAID_DATE, $paidDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($paidDate['max'])) {
                $this->addUsingAlias(MumTableMap::PAID_DATE, $paidDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MumTableMap::PAID_DATE, $paidDate, $comparison);
    }

    /**
     * Filter the query on the delivery_date column
     *
     * Example usage:
     * <code>
     * $query->filterByDeliveryDate('2011-03-14'); // WHERE delivery_date = '2011-03-14'
     * $query->filterByDeliveryDate('now'); // WHERE delivery_date = '2011-03-14'
     * $query->filterByDeliveryDate(array('max' => 'yesterday')); // WHERE delivery_date > '2011-03-13'
     * </code>
     *
     * @param     mixed $deliveryDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMumQuery The current query, for fluid interface
     */
    public function filterByDeliveryDate($deliveryDate = null, $comparison = null)
    {
        if (is_array($deliveryDate)) {
            $useMinMax = false;
            if (isset($deliveryDate['min'])) {
                $this->addUsingAlias(MumTableMap::DELIVERY_DATE, $deliveryDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($deliveryDate['max'])) {
                $this->addUsingAlias(MumTableMap::DELIVERY_DATE, $deliveryDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MumTableMap::DELIVERY_DATE, $deliveryDate, $comparison);
    }

    /**
     * Filter the query by a related \Customer object
     *
     * @param \Customer|ObjectCollection $customer The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMumQuery The current query, for fluid interface
     */
    public function filterByCustomer($customer, $comparison = null)
    {
        if ($customer instanceof \Customer) {
            return $this
                ->addUsingAlias(MumTableMap::CUSTOMER_ID, $customer->getId(), $comparison);
        } elseif ($customer instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MumTableMap::CUSTOMER_ID, $customer->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCustomer() only accepts arguments of type \Customer or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Customer relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildMumQuery The current query, for fluid interface
     */
    public function joinCustomer($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Customer');

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
            $this->addJoinObject($join, 'Customer');
        }

        return $this;
    }

    /**
     * Use the Customer relation Customer object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CustomerQuery A secondary query class using the current class as primary query
     */
    public function useCustomerQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCustomer($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Customer', '\CustomerQuery');
    }

    /**
     * Filter the query by a related \Backing object
     *
     * @param \Backing|ObjectCollection $backing The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMumQuery The current query, for fluid interface
     */
    public function filterByBacking($backing, $comparison = null)
    {
        if ($backing instanceof \Backing) {
            return $this
                ->addUsingAlias(MumTableMap::BACKING_ID, $backing->getId(), $comparison);
        } elseif ($backing instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MumTableMap::BACKING_ID, $backing->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByBacking() only accepts arguments of type \Backing or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Backing relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildMumQuery The current query, for fluid interface
     */
    public function joinBacking($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Backing');

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
            $this->addJoinObject($join, 'Backing');
        }

        return $this;
    }

    /**
     * Use the Backing relation Backing object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \BackingQuery A secondary query class using the current class as primary query
     */
    public function useBackingQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinBacking($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Backing', '\BackingQuery');
    }

    /**
     * Filter the query by a related \AccentBow object
     *
     * @param \AccentBow|ObjectCollection $accentBow The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMumQuery The current query, for fluid interface
     */
    public function filterByAccentBow($accentBow, $comparison = null)
    {
        if ($accentBow instanceof \AccentBow) {
            return $this
                ->addUsingAlias(MumTableMap::ACCENT_BOW_ID, $accentBow->getId(), $comparison);
        } elseif ($accentBow instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MumTableMap::ACCENT_BOW_ID, $accentBow->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByAccentBow() only accepts arguments of type \AccentBow or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the AccentBow relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildMumQuery The current query, for fluid interface
     */
    public function joinAccentBow($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('AccentBow');

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
            $this->addJoinObject($join, 'AccentBow');
        }

        return $this;
    }

    /**
     * Use the AccentBow relation AccentBow object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \AccentBowQuery A secondary query class using the current class as primary query
     */
    public function useAccentBowQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinAccentBow($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'AccentBow', '\AccentBowQuery');
    }

    /**
     * Filter the query by a related \Letter object
     *
     * @param \Letter $letter The related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMumQuery The current query, for fluid interface
     */
    public function filterByLetter($letter, $comparison = null)
    {
        if ($letter instanceof \Letter) {
            return $this
                ->addUsingAlias(MumTableMap::LETTER1_ID, $letter->getId(), $comparison)
                ->addUsingAlias(MumTableMap::LETTER2_ID, $letter->getId(), $comparison);
        } else {
            throw new PropelException('filterByLetter() only accepts arguments of type \Letter');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Letter relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildMumQuery The current query, for fluid interface
     */
    public function joinLetter($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Letter');

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
            $this->addJoinObject($join, 'Letter');
        }

        return $this;
    }

    /**
     * Use the Letter relation Letter object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \LetterQuery A secondary query class using the current class as primary query
     */
    public function useLetterQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinLetter($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Letter', '\LetterQuery');
    }

    /**
     * Filter the query by a related \Status object
     *
     * @param \Status|ObjectCollection $status The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMumQuery The current query, for fluid interface
     */
    public function filterByStatus($status, $comparison = null)
    {
        if ($status instanceof \Status) {
            return $this
                ->addUsingAlias(MumTableMap::STATUS_ID, $status->getId(), $comparison);
        } elseif ($status instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MumTableMap::STATUS_ID, $status->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByStatus() only accepts arguments of type \Status or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Status relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildMumQuery The current query, for fluid interface
     */
    public function joinStatus($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Status');

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
            $this->addJoinObject($join, 'Status');
        }

        return $this;
    }

    /**
     * Use the Status relation Status object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \StatusQuery A secondary query class using the current class as primary query
     */
    public function useStatusQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinStatus($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Status', '\StatusQuery');
    }

    /**
     * Filter the query by a related \MumTrinket object
     *
     * @param \MumTrinket|ObjectCollection $mumTrinket  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMumQuery The current query, for fluid interface
     */
    public function filterByMumTrinket($mumTrinket, $comparison = null)
    {
        if ($mumTrinket instanceof \MumTrinket) {
            return $this
                ->addUsingAlias(MumTableMap::ID, $mumTrinket->getMumId(), $comparison);
        } elseif ($mumTrinket instanceof ObjectCollection) {
            return $this
                ->useMumTrinketQuery()
                ->filterByPrimaryKeys($mumTrinket->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByMumTrinket() only accepts arguments of type \MumTrinket or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MumTrinket relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildMumQuery The current query, for fluid interface
     */
    public function joinMumTrinket($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MumTrinket');

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
            $this->addJoinObject($join, 'MumTrinket');
        }

        return $this;
    }

    /**
     * Use the MumTrinket relation MumTrinket object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \MumTrinketQuery A secondary query class using the current class as primary query
     */
    public function useMumTrinketQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMumTrinket($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MumTrinket', '\MumTrinketQuery');
    }

    /**
     * Filter the query by a related \MumBear object
     *
     * @param \MumBear|ObjectCollection $mumBear  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMumQuery The current query, for fluid interface
     */
    public function filterByMumBear($mumBear, $comparison = null)
    {
        if ($mumBear instanceof \MumBear) {
            return $this
                ->addUsingAlias(MumTableMap::ID, $mumBear->getMumId(), $comparison);
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
     * @return ChildMumQuery The current query, for fluid interface
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
     * Filter the query by a related Bear object
     * using the mum_bear table as cross reference
     *
     * @param Bear $bear the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMumQuery The current query, for fluid interface
     */
    public function filterByBear($bear, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useMumBearQuery()
            ->filterByBear($bear, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   ChildMum $mum Object to remove from the list of results
     *
     * @return ChildMumQuery The current query, for fluid interface
     */
    public function prune($mum = null)
    {
        if ($mum) {
            $this->addUsingAlias(MumTableMap::ID, $mum->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the mum table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MumTableMap::DATABASE_NAME);
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
            MumTableMap::clearInstancePool();
            MumTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildMum or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildMum object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(MumTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(MumTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        MumTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            MumTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

} // MumQuery
