<?php

namespace Map;

use \Accessory;
use \AccessoryQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'accessory' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class AccessoryTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;
    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.AccessoryTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'mums';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'accessory';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Accessory';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Accessory';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 9;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 1;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 8;

    /**
     * the column name for the ID field
     */
    const ID = 'accessory.ID';

    /**
     * the column name for the ITEM_ID field
     */
    const ITEM_ID = 'accessory.ITEM_ID';

    /**
     * the column name for the NAME field
     */
    const NAME = 'accessory.NAME';

    /**
     * the column name for the UNDERCLASSMAN field
     */
    const UNDERCLASSMAN = 'accessory.UNDERCLASSMAN';

    /**
     * the column name for the JUNIOR field
     */
    const JUNIOR = 'accessory.JUNIOR';

    /**
     * the column name for the SENIOR field
     */
    const SENIOR = 'accessory.SENIOR';

    /**
     * the column name for the PRICE field
     */
    const PRICE = 'accessory.PRICE';

    /**
     * the column name for the CATEGORY_ID field
     */
    const CATEGORY_ID = 'accessory.CATEGORY_ID';

    /**
     * the column name for the IMAGE field
     */
    const IMAGE = 'accessory.IMAGE';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'ItemId', 'Name', 'Underclassman', 'Junior', 'Senior', 'Price', 'CategoryId', 'Image', ),
        self::TYPE_STUDLYPHPNAME => array('id', 'itemId', 'name', 'underclassman', 'junior', 'senior', 'price', 'categoryId', 'image', ),
        self::TYPE_COLNAME       => array(AccessoryTableMap::ID, AccessoryTableMap::ITEM_ID, AccessoryTableMap::NAME, AccessoryTableMap::UNDERCLASSMAN, AccessoryTableMap::JUNIOR, AccessoryTableMap::SENIOR, AccessoryTableMap::PRICE, AccessoryTableMap::CATEGORY_ID, AccessoryTableMap::IMAGE, ),
        self::TYPE_RAW_COLNAME   => array('ID', 'ITEM_ID', 'NAME', 'UNDERCLASSMAN', 'JUNIOR', 'SENIOR', 'PRICE', 'CATEGORY_ID', 'IMAGE', ),
        self::TYPE_FIELDNAME     => array('id', 'item_id', 'name', 'underclassman', 'junior', 'senior', 'price', 'category_id', 'image', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'ItemId' => 1, 'Name' => 2, 'Underclassman' => 3, 'Junior' => 4, 'Senior' => 5, 'Price' => 6, 'CategoryId' => 7, 'Image' => 8, ),
        self::TYPE_STUDLYPHPNAME => array('id' => 0, 'itemId' => 1, 'name' => 2, 'underclassman' => 3, 'junior' => 4, 'senior' => 5, 'price' => 6, 'categoryId' => 7, 'image' => 8, ),
        self::TYPE_COLNAME       => array(AccessoryTableMap::ID => 0, AccessoryTableMap::ITEM_ID => 1, AccessoryTableMap::NAME => 2, AccessoryTableMap::UNDERCLASSMAN => 3, AccessoryTableMap::JUNIOR => 4, AccessoryTableMap::SENIOR => 5, AccessoryTableMap::PRICE => 6, AccessoryTableMap::CATEGORY_ID => 7, AccessoryTableMap::IMAGE => 8, ),
        self::TYPE_RAW_COLNAME   => array('ID' => 0, 'ITEM_ID' => 1, 'NAME' => 2, 'UNDERCLASSMAN' => 3, 'JUNIOR' => 4, 'SENIOR' => 5, 'PRICE' => 6, 'CATEGORY_ID' => 7, 'IMAGE' => 8, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'item_id' => 1, 'name' => 2, 'underclassman' => 3, 'junior' => 4, 'senior' => 5, 'price' => 6, 'category_id' => 7, 'image' => 8, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, )
    );

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('accessory');
        $this->setPhpName('Accessory');
        $this->setClassName('\\Accessory');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('ITEM_ID', 'ItemId', 'VARCHAR', false, 15, null);
        $this->addColumn('NAME', 'Name', 'VARCHAR', true, 64, null);
        $this->addColumn('UNDERCLASSMAN', 'Underclassman', 'BOOLEAN', true, 1, false);
        $this->addColumn('JUNIOR', 'Junior', 'BOOLEAN', true, 1, false);
        $this->addColumn('SENIOR', 'Senior', 'BOOLEAN', true, 1, false);
        $this->addColumn('PRICE', 'Price', 'DECIMAL', true, 10, null);
        $this->addForeignKey('CATEGORY_ID', 'CategoryId', 'INTEGER', 'accessory_category', 'ID', false, null, null);
        $this->addColumn('IMAGE', 'Image', 'VARCHAR', false, 32, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('AccessoryCategory', '\\AccessoryCategory', RelationMap::MANY_TO_ONE, array('category_id' => 'id', ), null, null);
        $this->addRelation('MumAccessory', '\\MumAccessory', RelationMap::ONE_TO_MANY, array('id' => 'accessory_id', ), null, null, 'MumAccessories');
    } // buildRelations()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {

            return (int) $row[
                            $indexType == TableMap::TYPE_NUM
                            ? 0 + $offset
                            : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
                        ];
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? AccessoryTableMap::CLASS_DEFAULT : AccessoryTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     * @return array (Accessory object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = AccessoryTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = AccessoryTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + AccessoryTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = AccessoryTableMap::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            AccessoryTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = AccessoryTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = AccessoryTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                AccessoryTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(AccessoryTableMap::ID);
            $criteria->addSelectColumn(AccessoryTableMap::ITEM_ID);
            $criteria->addSelectColumn(AccessoryTableMap::NAME);
            $criteria->addSelectColumn(AccessoryTableMap::UNDERCLASSMAN);
            $criteria->addSelectColumn(AccessoryTableMap::JUNIOR);
            $criteria->addSelectColumn(AccessoryTableMap::SENIOR);
            $criteria->addSelectColumn(AccessoryTableMap::PRICE);
            $criteria->addSelectColumn(AccessoryTableMap::CATEGORY_ID);
        } else {
            $criteria->addSelectColumn($alias . '.ID');
            $criteria->addSelectColumn($alias . '.ITEM_ID');
            $criteria->addSelectColumn($alias . '.NAME');
            $criteria->addSelectColumn($alias . '.UNDERCLASSMAN');
            $criteria->addSelectColumn($alias . '.JUNIOR');
            $criteria->addSelectColumn($alias . '.SENIOR');
            $criteria->addSelectColumn($alias . '.PRICE');
            $criteria->addSelectColumn($alias . '.CATEGORY_ID');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(AccessoryTableMap::DATABASE_NAME)->getTable(AccessoryTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getServiceContainer()->getDatabaseMap(AccessoryTableMap::DATABASE_NAME);
      if (!$dbMap->hasTable(AccessoryTableMap::TABLE_NAME)) {
        $dbMap->addTableObject(new AccessoryTableMap());
      }
    }

    /**
     * Performs a DELETE on the database, given a Accessory or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Accessory object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(AccessoryTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Accessory) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(AccessoryTableMap::DATABASE_NAME);
            $criteria->add(AccessoryTableMap::ID, (array) $values, Criteria::IN);
        }

        $query = AccessoryQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) { AccessoryTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) { AccessoryTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the accessory table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return AccessoryQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Accessory or Criteria object.
     *
     * @param mixed               $criteria Criteria or Accessory object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(AccessoryTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Accessory object
        }

        if ($criteria->containsKey(AccessoryTableMap::ID) && $criteria->keyContainsValue(AccessoryTableMap::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.AccessoryTableMap::ID.')');
        }


        // Set the correct dbName
        $query = AccessoryQuery::create()->mergeWith($criteria);

        try {
            // use transaction because $criteria could contain info
            // for more than one table (I guess, conceivably)
            $con->beginTransaction();
            $pk = $query->doInsert($con);
            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $pk;
    }

} // AccessoryTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
AccessoryTableMap::buildTableMap();
