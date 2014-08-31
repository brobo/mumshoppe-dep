<?php

namespace Map;

use \Mum;
use \MumQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'mum' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class MumTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;
    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.MumTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'mums';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'mum';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Mum';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Mum';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 15;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 15;

    /**
     * the column name for the ID field
     */
    const ID = 'mum.ID';

    /**
     * the column name for the CUSTOMER_ID field
     */
    const CUSTOMER_ID = 'mum.CUSTOMER_ID';

    /**
     * the column name for the BACKING_ID field
     */
    const BACKING_ID = 'mum.BACKING_ID';

    /**
     * the column name for the ACCENT_BOW_ID field
     */
    const ACCENT_BOW_ID = 'mum.ACCENT_BOW_ID';

    /**
     * the column name for the LETTER1_ID field
     */
    const LETTER1_ID = 'mum.LETTER1_ID';

    /**
     * the column name for the NAME_RIBBON1 field
     */
    const NAME_RIBBON1 = 'mum.NAME_RIBBON1';

    /**
     * the column name for the LETTER2_ID field
     */
    const LETTER2_ID = 'mum.LETTER2_ID';

    /**
     * the column name for the NAME_RIBBON2 field
     */
    const NAME_RIBBON2 = 'mum.NAME_RIBBON2';

    /**
     * the column name for the STATUS_ID field
     */
    const STATUS_ID = 'mum.STATUS_ID';

    /**
     * the column name for the PAID field
     */
    const PAID = 'mum.PAID';

    /**
     * the column name for the ORDER_DATE field
     */
    const ORDER_DATE = 'mum.ORDER_DATE';

    /**
     * the column name for the PAID_DATE field
     */
    const PAID_DATE = 'mum.PAID_DATE';

    /**
     * the column name for the DEPOSIT_SALE_ID field
     */
    const DEPOSIT_SALE_ID = 'mum.DEPOSIT_SALE_ID';

    /**
     * the column name for the PAID_SALE_ID field
     */
    const PAID_SALE_ID = 'mum.PAID_SALE_ID';

    /**
     * the column name for the DELIVERY_DATE field
     */
    const DELIVERY_DATE = 'mum.DELIVERY_DATE';

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
        self::TYPE_PHPNAME       => array('Id', 'CustomerId', 'BackingId', 'AccentBowId', 'Letter1Id', 'NameRibbon1', 'Letter2Id', 'NameRibbon2', 'StatusId', 'Paid', 'OrderDate', 'PaidDate', 'DepositSaleId', 'PaidSaleId', 'DeliveryDate', ),
        self::TYPE_STUDLYPHPNAME => array('id', 'customerId', 'backingId', 'accentBowId', 'letter1Id', 'nameRibbon1', 'letter2Id', 'nameRibbon2', 'statusId', 'paid', 'orderDate', 'paidDate', 'depositSaleId', 'paidSaleId', 'deliveryDate', ),
        self::TYPE_COLNAME       => array(MumTableMap::ID, MumTableMap::CUSTOMER_ID, MumTableMap::BACKING_ID, MumTableMap::ACCENT_BOW_ID, MumTableMap::LETTER1_ID, MumTableMap::NAME_RIBBON1, MumTableMap::LETTER2_ID, MumTableMap::NAME_RIBBON2, MumTableMap::STATUS_ID, MumTableMap::PAID, MumTableMap::ORDER_DATE, MumTableMap::PAID_DATE, MumTableMap::DEPOSIT_SALE_ID, MumTableMap::PAID_SALE_ID, MumTableMap::DELIVERY_DATE, ),
        self::TYPE_RAW_COLNAME   => array('ID', 'CUSTOMER_ID', 'BACKING_ID', 'ACCENT_BOW_ID', 'LETTER1_ID', 'NAME_RIBBON1', 'LETTER2_ID', 'NAME_RIBBON2', 'STATUS_ID', 'PAID', 'ORDER_DATE', 'PAID_DATE', 'DEPOSIT_SALE_ID', 'PAID_SALE_ID', 'DELIVERY_DATE', ),
        self::TYPE_FIELDNAME     => array('id', 'customer_id', 'backing_id', 'accent_bow_id', 'letter1_id', 'name_ribbon1', 'letter2_id', 'name_ribbon2', 'status_id', 'paid', 'order_date', 'paid_date', 'deposit_sale_id', 'paid_sale_id', 'delivery_date', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'CustomerId' => 1, 'BackingId' => 2, 'AccentBowId' => 3, 'Letter1Id' => 4, 'NameRibbon1' => 5, 'Letter2Id' => 6, 'NameRibbon2' => 7, 'StatusId' => 8, 'Paid' => 9, 'OrderDate' => 10, 'PaidDate' => 11, 'DepositSaleId' => 12, 'PaidSaleId' => 13, 'DeliveryDate' => 14, ),
        self::TYPE_STUDLYPHPNAME => array('id' => 0, 'customerId' => 1, 'backingId' => 2, 'accentBowId' => 3, 'letter1Id' => 4, 'nameRibbon1' => 5, 'letter2Id' => 6, 'nameRibbon2' => 7, 'statusId' => 8, 'paid' => 9, 'orderDate' => 10, 'paidDate' => 11, 'depositSaleId' => 12, 'paidSaleId' => 13, 'deliveryDate' => 14, ),
        self::TYPE_COLNAME       => array(MumTableMap::ID => 0, MumTableMap::CUSTOMER_ID => 1, MumTableMap::BACKING_ID => 2, MumTableMap::ACCENT_BOW_ID => 3, MumTableMap::LETTER1_ID => 4, MumTableMap::NAME_RIBBON1 => 5, MumTableMap::LETTER2_ID => 6, MumTableMap::NAME_RIBBON2 => 7, MumTableMap::STATUS_ID => 8, MumTableMap::PAID => 9, MumTableMap::ORDER_DATE => 10, MumTableMap::PAID_DATE => 11, MumTableMap::DEPOSIT_SALE_ID => 12, MumTableMap::PAID_SALE_ID => 13, MumTableMap::DELIVERY_DATE => 14, ),
        self::TYPE_RAW_COLNAME   => array('ID' => 0, 'CUSTOMER_ID' => 1, 'BACKING_ID' => 2, 'ACCENT_BOW_ID' => 3, 'LETTER1_ID' => 4, 'NAME_RIBBON1' => 5, 'LETTER2_ID' => 6, 'NAME_RIBBON2' => 7, 'STATUS_ID' => 8, 'PAID' => 9, 'ORDER_DATE' => 10, 'PAID_DATE' => 11, 'DEPOSIT_SALE_ID' => 12, 'PAID_SALE_ID' => 13, 'DELIVERY_DATE' => 14, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'customer_id' => 1, 'backing_id' => 2, 'accent_bow_id' => 3, 'letter1_id' => 4, 'name_ribbon1' => 5, 'letter2_id' => 6, 'name_ribbon2' => 7, 'status_id' => 8, 'paid' => 9, 'order_date' => 10, 'paid_date' => 11, 'deposit_sale_id' => 12, 'paid_sale_id' => 13, 'delivery_date' => 14, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, )
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
        $this->setName('mum');
        $this->setPhpName('Mum');
        $this->setClassName('\\Mum');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('CUSTOMER_ID', 'CustomerId', 'INTEGER', 'customer', 'ID', true, null, null);
        $this->addForeignKey('BACKING_ID', 'BackingId', 'INTEGER', 'backing', 'ID', false, null, null);
        $this->addForeignKey('ACCENT_BOW_ID', 'AccentBowId', 'INTEGER', 'accent_bow', 'ID', false, null, null);
        $this->addForeignKey('LETTER1_ID', 'Letter1Id', 'INTEGER', 'letter', 'ID', false, null, null);
        $this->addColumn('NAME_RIBBON1', 'NameRibbon1', 'VARCHAR', false, 255, null);
        $this->addForeignKey('LETTER2_ID', 'Letter2Id', 'INTEGER', 'letter', 'ID', false, null, null);
        $this->addColumn('NAME_RIBBON2', 'NameRibbon2', 'VARCHAR', false, 255, null);
        $this->addForeignKey('STATUS_ID', 'StatusId', 'INTEGER', 'status', 'ID', false, null, null);
        $this->addColumn('PAID', 'Paid', 'BOOLEAN', false, 1, null);
        $this->addColumn('ORDER_DATE', 'OrderDate', 'TIMESTAMP', false, null, null);
        $this->addColumn('PAID_DATE', 'PaidDate', 'TIMESTAMP', false, null, null);
        $this->addColumn('DEPOSIT_SALE_ID', 'DepositSaleId', 'VARCHAR', false, 255, null);
        $this->addColumn('PAID_SALE_ID', 'PaidSaleId', 'VARCHAR', false, 255, null);
        $this->addColumn('DELIVERY_DATE', 'DeliveryDate', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Customer', '\\Customer', RelationMap::MANY_TO_ONE, array('customer_id' => 'id', ), null, null);
        $this->addRelation('Backing', '\\Backing', RelationMap::MANY_TO_ONE, array('backing_id' => 'id', ), null, null);
        $this->addRelation('AccentBow', '\\AccentBow', RelationMap::MANY_TO_ONE, array('accent_bow_id' => 'id', ), null, null);
        $this->addRelation('Letter', '\\Letter', RelationMap::MANY_TO_ONE, array('letter1_id' => 'id', 'letter2_id' => 'id', ), null, null);
        $this->addRelation('Status', '\\Status', RelationMap::MANY_TO_ONE, array('status_id' => 'id', ), null, null);
        $this->addRelation('MumAccessory', '\\MumAccessory', RelationMap::ONE_TO_MANY, array('id' => 'mum_id', ), null, null, 'MumAccessories');
        $this->addRelation('MumBear', '\\MumBear', RelationMap::ONE_TO_MANY, array('id' => 'mum_id', ), null, null, 'MumBears');
        $this->addRelation('Bear', '\\Bear', RelationMap::MANY_TO_MANY, array(), null, null, 'Bears');
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
        return $withPrefix ? MumTableMap::CLASS_DEFAULT : MumTableMap::OM_CLASS;
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
     * @return array (Mum object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = MumTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = MumTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + MumTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = MumTableMap::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            MumTableMap::addInstanceToPool($obj, $key);
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
            $key = MumTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = MumTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                MumTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(MumTableMap::ID);
            $criteria->addSelectColumn(MumTableMap::CUSTOMER_ID);
            $criteria->addSelectColumn(MumTableMap::BACKING_ID);
            $criteria->addSelectColumn(MumTableMap::ACCENT_BOW_ID);
            $criteria->addSelectColumn(MumTableMap::LETTER1_ID);
            $criteria->addSelectColumn(MumTableMap::NAME_RIBBON1);
            $criteria->addSelectColumn(MumTableMap::LETTER2_ID);
            $criteria->addSelectColumn(MumTableMap::NAME_RIBBON2);
            $criteria->addSelectColumn(MumTableMap::STATUS_ID);
            $criteria->addSelectColumn(MumTableMap::PAID);
            $criteria->addSelectColumn(MumTableMap::ORDER_DATE);
            $criteria->addSelectColumn(MumTableMap::PAID_DATE);
            $criteria->addSelectColumn(MumTableMap::DEPOSIT_SALE_ID);
            $criteria->addSelectColumn(MumTableMap::PAID_SALE_ID);
            $criteria->addSelectColumn(MumTableMap::DELIVERY_DATE);
        } else {
            $criteria->addSelectColumn($alias . '.ID');
            $criteria->addSelectColumn($alias . '.CUSTOMER_ID');
            $criteria->addSelectColumn($alias . '.BACKING_ID');
            $criteria->addSelectColumn($alias . '.ACCENT_BOW_ID');
            $criteria->addSelectColumn($alias . '.LETTER1_ID');
            $criteria->addSelectColumn($alias . '.NAME_RIBBON1');
            $criteria->addSelectColumn($alias . '.LETTER2_ID');
            $criteria->addSelectColumn($alias . '.NAME_RIBBON2');
            $criteria->addSelectColumn($alias . '.STATUS_ID');
            $criteria->addSelectColumn($alias . '.PAID');
            $criteria->addSelectColumn($alias . '.ORDER_DATE');
            $criteria->addSelectColumn($alias . '.PAID_DATE');
            $criteria->addSelectColumn($alias . '.DEPOSIT_SALE_ID');
            $criteria->addSelectColumn($alias . '.PAID_SALE_ID');
            $criteria->addSelectColumn($alias . '.DELIVERY_DATE');
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
        return Propel::getServiceContainer()->getDatabaseMap(MumTableMap::DATABASE_NAME)->getTable(MumTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getServiceContainer()->getDatabaseMap(MumTableMap::DATABASE_NAME);
      if (!$dbMap->hasTable(MumTableMap::TABLE_NAME)) {
        $dbMap->addTableObject(new MumTableMap());
      }
    }

    /**
     * Performs a DELETE on the database, given a Mum or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Mum object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(MumTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Mum) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(MumTableMap::DATABASE_NAME);
            $criteria->add(MumTableMap::ID, (array) $values, Criteria::IN);
        }

        $query = MumQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) { MumTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) { MumTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the mum table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return MumQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Mum or Criteria object.
     *
     * @param mixed               $criteria Criteria or Mum object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MumTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Mum object
        }

        if ($criteria->containsKey(MumTableMap::ID) && $criteria->keyContainsValue(MumTableMap::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.MumTableMap::ID.')');
        }


        // Set the correct dbName
        $query = MumQuery::create()->mergeWith($criteria);

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

} // MumTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
MumTableMap::buildTableMap();
