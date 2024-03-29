<?php

namespace Map;

use \Volunteer;
use \VolunteerQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'volunteer' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class VolunteerTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;
    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.VolunteerTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'mums';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'volunteer';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Volunteer';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Volunteer';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 7;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 2;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 5;

    /**
     * the column name for the ID field
     */
    const ID = 'volunteer.ID';

    /**
     * the column name for the EMAIL field
     */
    const EMAIL = 'volunteer.EMAIL';

    /**
     * the column name for the PASSWORD field
     */
    const PASSWORD = 'volunteer.PASSWORD';

    /**
     * the column name for the NAME field
     */
    const NAME = 'volunteer.NAME';

    /**
     * the column name for the PHONE field
     */
    const PHONE = 'volunteer.PHONE';

    /**
     * the column name for the RIGHTS field
     */
    const RIGHTS = 'volunteer.RIGHTS';

    /**
     * the column name for the TOKEN_EXPIRATION field
     */
    const TOKEN_EXPIRATION = 'volunteer.TOKEN_EXPIRATION';

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
        self::TYPE_PHPNAME       => array('Id', 'Email', 'Password', 'Name', 'Phone', 'Rights', 'TokenExpiration', ),
        self::TYPE_STUDLYPHPNAME => array('id', 'email', 'password', 'name', 'phone', 'rights', 'tokenExpiration', ),
        self::TYPE_COLNAME       => array(VolunteerTableMap::ID, VolunteerTableMap::EMAIL, VolunteerTableMap::PASSWORD, VolunteerTableMap::NAME, VolunteerTableMap::PHONE, VolunteerTableMap::RIGHTS, VolunteerTableMap::TOKEN_EXPIRATION, ),
        self::TYPE_RAW_COLNAME   => array('ID', 'EMAIL', 'PASSWORD', 'NAME', 'PHONE', 'RIGHTS', 'TOKEN_EXPIRATION', ),
        self::TYPE_FIELDNAME     => array('id', 'email', 'password', 'name', 'phone', 'rights', 'token_expiration', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Email' => 1, 'Password' => 2, 'Name' => 3, 'Phone' => 4, 'Rights' => 5, 'TokenExpiration' => 6, ),
        self::TYPE_STUDLYPHPNAME => array('id' => 0, 'email' => 1, 'password' => 2, 'name' => 3, 'phone' => 4, 'rights' => 5, 'tokenExpiration' => 6, ),
        self::TYPE_COLNAME       => array(VolunteerTableMap::ID => 0, VolunteerTableMap::EMAIL => 1, VolunteerTableMap::PASSWORD => 2, VolunteerTableMap::NAME => 3, VolunteerTableMap::PHONE => 4, VolunteerTableMap::RIGHTS => 5, VolunteerTableMap::TOKEN_EXPIRATION => 6, ),
        self::TYPE_RAW_COLNAME   => array('ID' => 0, 'EMAIL' => 1, 'PASSWORD' => 2, 'NAME' => 3, 'PHONE' => 4, 'RIGHTS' => 5, 'TOKEN_EXPIRATION' => 6, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'email' => 1, 'password' => 2, 'name' => 3, 'phone' => 4, 'rights' => 5, 'token_expiration' => 6, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, )
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
        $this->setName('volunteer');
        $this->setPhpName('Volunteer');
        $this->setClassName('\\Volunteer');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('EMAIL', 'Email', 'VARCHAR', false, 64, null);
        $this->addColumn('PASSWORD', 'Password', 'VARCHAR', true, 255, null);
        $this->addColumn('NAME', 'Name', 'VARCHAR', true, 64, null);
        $this->addColumn('PHONE', 'Phone', 'VARCHAR', false, 16, null);
        $this->addColumn('RIGHTS', 'Rights', 'INTEGER', true, null, 0);
        $this->addColumn('TOKEN_EXPIRATION', 'TokenExpiration', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
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
        return $withPrefix ? VolunteerTableMap::CLASS_DEFAULT : VolunteerTableMap::OM_CLASS;
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
     * @return array (Volunteer object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = VolunteerTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = VolunteerTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + VolunteerTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = VolunteerTableMap::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            VolunteerTableMap::addInstanceToPool($obj, $key);
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
            $key = VolunteerTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = VolunteerTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                VolunteerTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(VolunteerTableMap::ID);
            $criteria->addSelectColumn(VolunteerTableMap::PASSWORD);
            $criteria->addSelectColumn(VolunteerTableMap::NAME);
            $criteria->addSelectColumn(VolunteerTableMap::PHONE);
            $criteria->addSelectColumn(VolunteerTableMap::RIGHTS);
        } else {
            $criteria->addSelectColumn($alias . '.ID');
            $criteria->addSelectColumn($alias . '.PASSWORD');
            $criteria->addSelectColumn($alias . '.NAME');
            $criteria->addSelectColumn($alias . '.PHONE');
            $criteria->addSelectColumn($alias . '.RIGHTS');
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
        return Propel::getServiceContainer()->getDatabaseMap(VolunteerTableMap::DATABASE_NAME)->getTable(VolunteerTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getServiceContainer()->getDatabaseMap(VolunteerTableMap::DATABASE_NAME);
      if (!$dbMap->hasTable(VolunteerTableMap::TABLE_NAME)) {
        $dbMap->addTableObject(new VolunteerTableMap());
      }
    }

    /**
     * Performs a DELETE on the database, given a Volunteer or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Volunteer object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(VolunteerTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Volunteer) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(VolunteerTableMap::DATABASE_NAME);
            $criteria->add(VolunteerTableMap::ID, (array) $values, Criteria::IN);
        }

        $query = VolunteerQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) { VolunteerTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) { VolunteerTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the volunteer table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return VolunteerQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Volunteer or Criteria object.
     *
     * @param mixed               $criteria Criteria or Volunteer object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(VolunteerTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Volunteer object
        }

        if ($criteria->containsKey(VolunteerTableMap::ID) && $criteria->keyContainsValue(VolunteerTableMap::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.VolunteerTableMap::ID.')');
        }


        // Set the correct dbName
        $query = VolunteerQuery::create()->mergeWith($criteria);

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

} // VolunteerTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
VolunteerTableMap::buildTableMap();
