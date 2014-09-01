<?php

namespace Map;

use \Size;
use \SizeQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'size' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class SizeTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;
    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.SizeTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'mums';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'size';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Size';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Size';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 6;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 2;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 4;

    /**
     * the column name for the ID field
     */
    const ID = 'size.ID';

    /**
     * the column name for the NAME field
     */
    const NAME = 'size.NAME';

    /**
     * the column name for the BEAR_LIMIT field
     */
    const BEAR_LIMIT = 'size.BEAR_LIMIT';

    /**
     * the column name for the PRODUCT_ID field
     */
    const PRODUCT_ID = 'size.PRODUCT_ID';

    /**
     * the column name for the IMAGE field
     */
    const IMAGE = 'size.IMAGE';

    /**
     * the column name for the IMAGE_MIME field
     */
    const IMAGE_MIME = 'size.IMAGE_MIME';

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
        self::TYPE_PHPNAME       => array('Id', 'Name', 'BearLimit', 'ProductId', 'Image', 'ImageMime', ),
        self::TYPE_STUDLYPHPNAME => array('id', 'name', 'bearLimit', 'productId', 'image', 'imageMime', ),
        self::TYPE_COLNAME       => array(SizeTableMap::ID, SizeTableMap::NAME, SizeTableMap::BEAR_LIMIT, SizeTableMap::PRODUCT_ID, SizeTableMap::IMAGE, SizeTableMap::IMAGE_MIME, ),
        self::TYPE_RAW_COLNAME   => array('ID', 'NAME', 'BEAR_LIMIT', 'PRODUCT_ID', 'IMAGE', 'IMAGE_MIME', ),
        self::TYPE_FIELDNAME     => array('id', 'name', 'bear_limit', 'product_id', 'image', 'image_mime', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Name' => 1, 'BearLimit' => 2, 'ProductId' => 3, 'Image' => 4, 'ImageMime' => 5, ),
        self::TYPE_STUDLYPHPNAME => array('id' => 0, 'name' => 1, 'bearLimit' => 2, 'productId' => 3, 'image' => 4, 'imageMime' => 5, ),
        self::TYPE_COLNAME       => array(SizeTableMap::ID => 0, SizeTableMap::NAME => 1, SizeTableMap::BEAR_LIMIT => 2, SizeTableMap::PRODUCT_ID => 3, SizeTableMap::IMAGE => 4, SizeTableMap::IMAGE_MIME => 5, ),
        self::TYPE_RAW_COLNAME   => array('ID' => 0, 'NAME' => 1, 'BEAR_LIMIT' => 2, 'PRODUCT_ID' => 3, 'IMAGE' => 4, 'IMAGE_MIME' => 5, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'name' => 1, 'bear_limit' => 2, 'product_id' => 3, 'image' => 4, 'image_mime' => 5, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, )
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
        $this->setName('size');
        $this->setPhpName('Size');
        $this->setClassName('\\Size');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('NAME', 'Name', 'VARCHAR', true, 32, null);
        $this->addColumn('BEAR_LIMIT', 'BearLimit', 'INTEGER', true, null, null);
        $this->addForeignKey('PRODUCT_ID', 'ProductId', 'INTEGER', 'product', 'ID', true, null, null);
        $this->addColumn('IMAGE', 'Image', 'BLOB', false, null, null);
        $this->addColumn('IMAGE_MIME', 'ImageMime', 'VARCHAR', false, 31, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Product', '\\Product', RelationMap::MANY_TO_ONE, array('product_id' => 'id', ), null, null);
        $this->addRelation('Backing', '\\Backing', RelationMap::ONE_TO_MANY, array('id' => 'size_id', ), null, null, 'Backings');
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
        return $withPrefix ? SizeTableMap::CLASS_DEFAULT : SizeTableMap::OM_CLASS;
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
     * @return array (Size object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = SizeTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = SizeTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + SizeTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = SizeTableMap::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            SizeTableMap::addInstanceToPool($obj, $key);
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
            $key = SizeTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = SizeTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                SizeTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(SizeTableMap::ID);
            $criteria->addSelectColumn(SizeTableMap::NAME);
            $criteria->addSelectColumn(SizeTableMap::BEAR_LIMIT);
            $criteria->addSelectColumn(SizeTableMap::PRODUCT_ID);
        } else {
            $criteria->addSelectColumn($alias . '.ID');
            $criteria->addSelectColumn($alias . '.NAME');
            $criteria->addSelectColumn($alias . '.BEAR_LIMIT');
            $criteria->addSelectColumn($alias . '.PRODUCT_ID');
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
        return Propel::getServiceContainer()->getDatabaseMap(SizeTableMap::DATABASE_NAME)->getTable(SizeTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getServiceContainer()->getDatabaseMap(SizeTableMap::DATABASE_NAME);
      if (!$dbMap->hasTable(SizeTableMap::TABLE_NAME)) {
        $dbMap->addTableObject(new SizeTableMap());
      }
    }

    /**
     * Performs a DELETE on the database, given a Size or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Size object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(SizeTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Size) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(SizeTableMap::DATABASE_NAME);
            $criteria->add(SizeTableMap::ID, (array) $values, Criteria::IN);
        }

        $query = SizeQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) { SizeTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) { SizeTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the size table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return SizeQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Size or Criteria object.
     *
     * @param mixed               $criteria Criteria or Size object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SizeTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Size object
        }

        if ($criteria->containsKey(SizeTableMap::ID) && $criteria->keyContainsValue(SizeTableMap::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.SizeTableMap::ID.')');
        }


        // Set the correct dbName
        $query = SizeQuery::create()->mergeWith($criteria);

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

} // SizeTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
SizeTableMap::buildTableMap();
