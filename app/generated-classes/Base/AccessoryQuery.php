<?php

namespace Base;

use \Accessory as ChildAccessory;
use \AccessoryQuery as ChildAccessoryQuery;
use \Exception;
use \PDO;
use Map\AccessoryTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'accessory' table.
 *
 * 
 *
 * @method     ChildAccessoryQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildAccessoryQuery orderByItemId($order = Criteria::ASC) Order by the item_id column
 * @method     ChildAccessoryQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildAccessoryQuery orderByUnderclassman($order = Criteria::ASC) Order by the underclassman column
 * @method     ChildAccessoryQuery orderByJunior($order = Criteria::ASC) Order by the junior column
 * @method     ChildAccessoryQuery orderBySenior($order = Criteria::ASC) Order by the senior column
 * @method     ChildAccessoryQuery orderByPrice($order = Criteria::ASC) Order by the price column
 * @method     ChildAccessoryQuery orderByCategoryId($order = Criteria::ASC) Order by the category_id column
 * @method     ChildAccessoryQuery orderByImage($order = Criteria::ASC) Order by the image column
 * @method     ChildAccessoryQuery orderByImageMime($order = Criteria::ASC) Order by the image_mime column
 *
 * @method     ChildAccessoryQuery groupById() Group by the id column
 * @method     ChildAccessoryQuery groupByItemId() Group by the item_id column
 * @method     ChildAccessoryQuery groupByName() Group by the name column
 * @method     ChildAccessoryQuery groupByUnderclassman() Group by the underclassman column
 * @method     ChildAccessoryQuery groupByJunior() Group by the junior column
 * @method     ChildAccessoryQuery groupBySenior() Group by the senior column
 * @method     ChildAccessoryQuery groupByPrice() Group by the price column
 * @method     ChildAccessoryQuery groupByCategoryId() Group by the category_id column
 * @method     ChildAccessoryQuery groupByImage() Group by the image column
 * @method     ChildAccessoryQuery groupByImageMime() Group by the image_mime column
 *
 * @method     ChildAccessoryQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildAccessoryQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildAccessoryQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildAccessoryQuery leftJoinAccessoryCategory($relationAlias = null) Adds a LEFT JOIN clause to the query using the AccessoryCategory relation
 * @method     ChildAccessoryQuery rightJoinAccessoryCategory($relationAlias = null) Adds a RIGHT JOIN clause to the query using the AccessoryCategory relation
 * @method     ChildAccessoryQuery innerJoinAccessoryCategory($relationAlias = null) Adds a INNER JOIN clause to the query using the AccessoryCategory relation
 *
 * @method     ChildAccessoryQuery leftJoinMumAccessory($relationAlias = null) Adds a LEFT JOIN clause to the query using the MumAccessory relation
 * @method     ChildAccessoryQuery rightJoinMumAccessory($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MumAccessory relation
 * @method     ChildAccessoryQuery innerJoinMumAccessory($relationAlias = null) Adds a INNER JOIN clause to the query using the MumAccessory relation
 *
 * @method     ChildAccessory findOne(ConnectionInterface $con = null) Return the first ChildAccessory matching the query
 * @method     ChildAccessory findOneOrCreate(ConnectionInterface $con = null) Return the first ChildAccessory matching the query, or a new ChildAccessory object populated from the query conditions when no match is found
 *
 * @method     ChildAccessory findOneById(int $id) Return the first ChildAccessory filtered by the id column
 * @method     ChildAccessory findOneByItemId(string $item_id) Return the first ChildAccessory filtered by the item_id column
 * @method     ChildAccessory findOneByName(string $name) Return the first ChildAccessory filtered by the name column
 * @method     ChildAccessory findOneByUnderclassman(boolean $underclassman) Return the first ChildAccessory filtered by the underclassman column
 * @method     ChildAccessory findOneByJunior(boolean $junior) Return the first ChildAccessory filtered by the junior column
 * @method     ChildAccessory findOneBySenior(boolean $senior) Return the first ChildAccessory filtered by the senior column
 * @method     ChildAccessory findOneByPrice(string $price) Return the first ChildAccessory filtered by the price column
 * @method     ChildAccessory findOneByCategoryId(int $category_id) Return the first ChildAccessory filtered by the category_id column
 * @method     ChildAccessory findOneByImage(resource $image) Return the first ChildAccessory filtered by the image column
 * @method     ChildAccessory findOneByImageMime(string $image_mime) Return the first ChildAccessory filtered by the image_mime column
 *
 * @method     array findById(int $id) Return ChildAccessory objects filtered by the id column
 * @method     array findByItemId(string $item_id) Return ChildAccessory objects filtered by the item_id column
 * @method     array findByName(string $name) Return ChildAccessory objects filtered by the name column
 * @method     array findByUnderclassman(boolean $underclassman) Return ChildAccessory objects filtered by the underclassman column
 * @method     array findByJunior(boolean $junior) Return ChildAccessory objects filtered by the junior column
 * @method     array findBySenior(boolean $senior) Return ChildAccessory objects filtered by the senior column
 * @method     array findByPrice(string $price) Return ChildAccessory objects filtered by the price column
 * @method     array findByCategoryId(int $category_id) Return ChildAccessory objects filtered by the category_id column
 * @method     array findByImage(resource $image) Return ChildAccessory objects filtered by the image column
 * @method     array findByImageMime(string $image_mime) Return ChildAccessory objects filtered by the image_mime column
 *
 */
abstract class AccessoryQuery extends ModelCriteria
{
    
    /**
     * Initializes internal state of \Base\AccessoryQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'mums', $modelName = '\\Accessory', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildAccessoryQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildAccessoryQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \AccessoryQuery) {
            return $criteria;
        }
        $query = new \AccessoryQuery();
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
     * @return ChildAccessory|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = AccessoryTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(AccessoryTableMap::DATABASE_NAME);
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
     * @return   ChildAccessory A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ID, ITEM_ID, NAME, UNDERCLASSMAN, JUNIOR, SENIOR, PRICE, CATEGORY_ID FROM accessory WHERE ID = :p0';
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
            $obj = new ChildAccessory();
            $obj->hydrate($row);
            AccessoryTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildAccessory|array|mixed the result, formatted by the current formatter
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
     * @return ChildAccessoryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(AccessoryTableMap::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildAccessoryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(AccessoryTableMap::ID, $keys, Criteria::IN);
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
     * @return ChildAccessoryQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(AccessoryTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(AccessoryTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AccessoryTableMap::ID, $id, $comparison);
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
     * @return ChildAccessoryQuery The current query, for fluid interface
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

        return $this->addUsingAlias(AccessoryTableMap::ITEM_ID, $itemId, $comparison);
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
     * @return ChildAccessoryQuery The current query, for fluid interface
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

        return $this->addUsingAlias(AccessoryTableMap::NAME, $name, $comparison);
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
     * @return ChildAccessoryQuery The current query, for fluid interface
     */
    public function filterByUnderclassman($underclassman = null, $comparison = null)
    {
        if (is_string($underclassman)) {
            $underclassman = in_array(strtolower($underclassman), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(AccessoryTableMap::UNDERCLASSMAN, $underclassman, $comparison);
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
     * @return ChildAccessoryQuery The current query, for fluid interface
     */
    public function filterByJunior($junior = null, $comparison = null)
    {
        if (is_string($junior)) {
            $junior = in_array(strtolower($junior), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(AccessoryTableMap::JUNIOR, $junior, $comparison);
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
     * @return ChildAccessoryQuery The current query, for fluid interface
     */
    public function filterBySenior($senior = null, $comparison = null)
    {
        if (is_string($senior)) {
            $senior = in_array(strtolower($senior), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(AccessoryTableMap::SENIOR, $senior, $comparison);
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
     * @return ChildAccessoryQuery The current query, for fluid interface
     */
    public function filterByPrice($price = null, $comparison = null)
    {
        if (is_array($price)) {
            $useMinMax = false;
            if (isset($price['min'])) {
                $this->addUsingAlias(AccessoryTableMap::PRICE, $price['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($price['max'])) {
                $this->addUsingAlias(AccessoryTableMap::PRICE, $price['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AccessoryTableMap::PRICE, $price, $comparison);
    }

    /**
     * Filter the query on the category_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCategoryId(1234); // WHERE category_id = 1234
     * $query->filterByCategoryId(array(12, 34)); // WHERE category_id IN (12, 34)
     * $query->filterByCategoryId(array('min' => 12)); // WHERE category_id > 12
     * </code>
     *
     * @see       filterByAccessoryCategory()
     *
     * @param     mixed $categoryId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildAccessoryQuery The current query, for fluid interface
     */
    public function filterByCategoryId($categoryId = null, $comparison = null)
    {
        if (is_array($categoryId)) {
            $useMinMax = false;
            if (isset($categoryId['min'])) {
                $this->addUsingAlias(AccessoryTableMap::CATEGORY_ID, $categoryId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($categoryId['max'])) {
                $this->addUsingAlias(AccessoryTableMap::CATEGORY_ID, $categoryId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AccessoryTableMap::CATEGORY_ID, $categoryId, $comparison);
    }

    /**
     * Filter the query on the image column
     *
     * @param     mixed $image The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildAccessoryQuery The current query, for fluid interface
     */
    public function filterByImage($image = null, $comparison = null)
    {

        return $this->addUsingAlias(AccessoryTableMap::IMAGE, $image, $comparison);
    }

    /**
     * Filter the query on the image_mime column
     *
     * Example usage:
     * <code>
     * $query->filterByImageMime('fooValue');   // WHERE image_mime = 'fooValue'
     * $query->filterByImageMime('%fooValue%'); // WHERE image_mime LIKE '%fooValue%'
     * </code>
     *
     * @param     string $imageMime The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildAccessoryQuery The current query, for fluid interface
     */
    public function filterByImageMime($imageMime = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($imageMime)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $imageMime)) {
                $imageMime = str_replace('*', '%', $imageMime);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(AccessoryTableMap::IMAGE_MIME, $imageMime, $comparison);
    }

    /**
     * Filter the query by a related \AccessoryCategory object
     *
     * @param \AccessoryCategory|ObjectCollection $accessoryCategory The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildAccessoryQuery The current query, for fluid interface
     */
    public function filterByAccessoryCategory($accessoryCategory, $comparison = null)
    {
        if ($accessoryCategory instanceof \AccessoryCategory) {
            return $this
                ->addUsingAlias(AccessoryTableMap::CATEGORY_ID, $accessoryCategory->getId(), $comparison);
        } elseif ($accessoryCategory instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(AccessoryTableMap::CATEGORY_ID, $accessoryCategory->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByAccessoryCategory() only accepts arguments of type \AccessoryCategory or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the AccessoryCategory relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildAccessoryQuery The current query, for fluid interface
     */
    public function joinAccessoryCategory($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('AccessoryCategory');

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
            $this->addJoinObject($join, 'AccessoryCategory');
        }

        return $this;
    }

    /**
     * Use the AccessoryCategory relation AccessoryCategory object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \AccessoryCategoryQuery A secondary query class using the current class as primary query
     */
    public function useAccessoryCategoryQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinAccessoryCategory($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'AccessoryCategory', '\AccessoryCategoryQuery');
    }

    /**
     * Filter the query by a related \MumAccessory object
     *
     * @param \MumAccessory|ObjectCollection $mumAccessory  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildAccessoryQuery The current query, for fluid interface
     */
    public function filterByMumAccessory($mumAccessory, $comparison = null)
    {
        if ($mumAccessory instanceof \MumAccessory) {
            return $this
                ->addUsingAlias(AccessoryTableMap::ID, $mumAccessory->getAccessoryId(), $comparison);
        } elseif ($mumAccessory instanceof ObjectCollection) {
            return $this
                ->useMumAccessoryQuery()
                ->filterByPrimaryKeys($mumAccessory->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByMumAccessory() only accepts arguments of type \MumAccessory or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MumAccessory relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildAccessoryQuery The current query, for fluid interface
     */
    public function joinMumAccessory($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MumAccessory');

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
            $this->addJoinObject($join, 'MumAccessory');
        }

        return $this;
    }

    /**
     * Use the MumAccessory relation MumAccessory object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \MumAccessoryQuery A secondary query class using the current class as primary query
     */
    public function useMumAccessoryQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMumAccessory($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MumAccessory', '\MumAccessoryQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildAccessory $accessory Object to remove from the list of results
     *
     * @return ChildAccessoryQuery The current query, for fluid interface
     */
    public function prune($accessory = null)
    {
        if ($accessory) {
            $this->addUsingAlias(AccessoryTableMap::ID, $accessory->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the accessory table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(AccessoryTableMap::DATABASE_NAME);
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
            AccessoryTableMap::clearInstancePool();
            AccessoryTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildAccessory or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildAccessory object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(AccessoryTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(AccessoryTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            

        AccessoryTableMap::removeInstanceFromPool($criteria);
        
            $affectedRows += ModelCriteria::delete($con);
            AccessoryTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

} // AccessoryQuery
