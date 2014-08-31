<?php

namespace Map;

use \Backing;
use \BackingQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'backing' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class BackingTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;
    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.BackingTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'mums';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'backing';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Backing';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Backing';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 8;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 2;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 6;

    /**
     * the column name for the ID field
     */
    const ID = 'backing.ID';

    /**
     * the column name for the ITEM_ID field
     */
    const ITEM_ID = 'backing.ITEM_ID';

    /**
     * the column name for the NAME field
     */
    const NAME = 'backing.NAME';

    /**
     * the column name for the PRICE field
     */
    const PRICE = 'backing.PRICE';

    /**
     * the column name for the SIZE_ID field
     */
    const SIZE_ID = 'backing.SIZE_ID';

    /**
     * the column name for the GRADE_ID field
     */
    const GRADE_ID = 'backing.GRADE_ID';

    /**
     * the column name for the IMAGE field
     */
    const IMAGE = 'backing.IMAGE';

    /**
     * the column name for the IMAGE_MIME field
     */
    const IMAGE_MIME = 'backing.IMAGE_MIME';

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
        self::TYPE_PHPNAME       => array('Id', 'ItemId', 'Name', 'Price', 'SizeId', 'GradeId', 'Image', 'ImageMime', ),
        self::TYPE_STUDLYPHPNAME => array('id', 'itemId', 'name', 'price', 'sizeId', 'gradeId', 'image', 'imageMime', ),
        self::TYPE_COLNAME       => array(BackingTableMap::ID, BackingTableMap::ITEM_ID, BackingTableMap::NAME, BackingTableMap::PRICE, BackingTableMap::SIZE_ID, BackingTableMap::GRADE_ID, BackingTableMap::IMAGE, BackingTableMap::IMAGE_MIME, ),
        self::TYPE_RAW_COLNAME   => array('ID', 'ITEM_ID', 'NAME', 'PRICE', 'SIZE_ID', 'GRADE_ID', 'IMAGE', 'IMAGE_MIME', ),
        self::TYPE_FIELDNAME     => array('id', 'item_id', 'name', 'price', 'size_id', 'grade_id', 'image', 'image_mime', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'ItemId' => 1, 'Name' => 2, 'Price' => 3, 'SizeId' => 4, 'GradeId' => 5, 'Image' => 6, 'ImageMime' => 7, ),
        self::TYPE_STUDLYPHPNAME => array('id' => 0, 'itemId' => 1, 'name' => 2, 'price' => 3, 'sizeId' => 4, 'gradeId' => 5, 'image' => 6, 'imageMime' => 7, ),
        self::TYPE_COLNAME       => array(BackingTableMap::ID => 0, BackingTableMap::ITEM_ID => 1, BackingTableMap::NAME => 2, BackingTableMap::PRICE => 3, BackingTableMap::SIZE_ID => 4, BackingTableMap::GRADE_ID => 5, BackingTableMap::IMAGE => 6, BackingTableMap::IMAGE_MIME => 7, ),
        self::TYPE_RAW_COLNAME   => array('ID' => 0, 'ITEM_ID' => 1, 'NAME' => 2, 'PRICE' => 3, 'SIZE_ID' => 4, 'GRADE_ID' => 5, 'IMAGE' => 6, 'IMAGE_MIME' => 7, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'item_id' => 1, 'name' => 2, 'price' => 3, 'size_id' => 4, 'grade_id' => 5, 'image' => 6, 'image_mime' => 7, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, )
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
        $this->setName('backing');
        $this->setPhpName('Backing');
        $this->setClassName('\\Backing');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('ITEM_ID', 'ItemId', 'VARCHAR', false, 15, null);
        $this->addColumn('NAME', 'Name', 'VARCHAR', true, 32, null);
        $this->addColumn('PRICE', 'Price', 'DECIMAL', true, 10, null);
        $this->addForeignKey('SIZE_ID', 'SizeId', 'INTEGER', 'size', 'ID', true, null, null);
        $this->addForeignKey('GRADE_ID', 'GradeId', 'INTEGER', 'grade', 'ID', true, null, null);
        $this->addColumn('IMAGE', 'Image', 'BLOB', false, null, null);
        $this->addColumn('IMAGE_MIME', 'ImageMime', 'VARCHAR', false, 31, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Size', '\\Size', RelationMap::MANY_TO_ONE, array('size_id' => 'id', ), null, null);
        $this->addRelation('Grade', '\\Grade', RelationMap::MANY_TO_ONE, array('grade_id' => 'id', ), null, null);
        $this->addRelation('Mum', '\\Mum', RelationMap::ONE_TO_MANY, array('id' => 'backing_id', ), null, null, 'Mums');
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
        return $withPrefix ? BackingTableMap::CLASS_DEFAULT : BackingTableMap::OM_CLASS;
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
     * @return array (Backing object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = BackingTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = BackingTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + BackingTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = BackingTableMap::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            BackingTableMap::addInstanceToPool($obj, $key);
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
            $key = BackingTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = BackingTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                BackingTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(BackingTableMap::ID);
            $criteria->addSelectColumn(BackingTableMap::ITEM_ID);
            $criteria->addSelectColumn(BackingTableMap::NAME);
            $criteria->addSelectColumn(BackingTableMap::PRICE);
            $criteria->addSelectColumn(BackingTableMap::SIZE_ID);
            $criteria->addSelectColumn(BackingTableMap::GRADE_ID);
        } else {
            $criteria->addSelectColumn($alias . '.ID');
            $criteria->addSelectColumn($alias . '.ITEM_ID');
            $criteria->addSelectColumn($alias . '.NAME');
            $criteria->addSelectColumn($alias . '.PRICE');
            $criteria->addSelectColumn($alias . '.SIZE_ID');
            $criteria->addSelectColumn($alias . '.GRADE_ID');
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
        return Propel::getServiceContainer()->getDatabaseMap(BackingTableMap::DATABASE_NAME)->getTable(BackingTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getServiceContainer()->getDatabaseMap(BackingTableMap::DATABASE_NAME);
      if (!$dbMap->hasTable(BackingTableMap::TABLE_NAME)) {
        $dbMap->addTableObject(new BackingTableMap());
      }
    }

    /**
     * Performs a DELETE on the database, given a Backing or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Backing object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(BackingTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Backing) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(BackingTableMap::DATABASE_NAME);
            $criteria->add(BackingTableMap::ID, (array) $values, Criteria::IN);
        }

        $query = BackingQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) { BackingTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) { BackingTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the backing table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return BackingQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Backing or Criteria object.
     *
     * @param mixed               $criteria Criteria or Backing object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(BackingTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Backing object
        }

        if ($criteria->containsKey(BackingTableMap::ID) && $criteria->keyContainsValue(BackingTableMap::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.BackingTableMap::ID.')');
        }


        // Set the correct dbName
        $query = BackingQuery::create()->mergeWith($criteria);

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

} // BackingTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BackingTableMap::buildTableMap();
