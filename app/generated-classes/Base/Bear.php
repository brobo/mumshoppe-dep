<?php

namespace Base;

use \Bear as ChildBear;
use \BearQuery as ChildBearQuery;
use \Mum as ChildMum;
use \MumBear as ChildMumBear;
use \MumBearQuery as ChildMumBearQuery;
use \MumQuery as ChildMumQuery;
use \Exception;
use \PDO;
use Map\BearTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;

abstract class Bear implements ActiveRecordInterface 
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\BearTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * The value for the item_id field.
     * @var        string
     */
    protected $item_id;

    /**
     * The value for the name field.
     * @var        string
     */
    protected $name;

    /**
     * The value for the underclassman field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $underclassman;

    /**
     * The value for the junior field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $junior;

    /**
     * The value for the senior field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $senior;

    /**
     * The value for the price field.
     * @var        string
     */
    protected $price;

    /**
     * The value for the image field.
     * @var        resource
     */
    protected $image;

    /**
     * Whether the lazy-loaded $image value has been loaded from database.
     * This is necessary to avoid repeated lookups if $image column is NULL in the db.
     * @var boolean
     */
    protected $image_isLoaded = false;

    /**
     * The value for the image_mime field.
     * @var        string
     */
    protected $image_mime;

    /**
     * Whether the lazy-loaded $image_mime value has been loaded from database.
     * This is necessary to avoid repeated lookups if $image_mime column is NULL in the db.
     * @var boolean
     */
    protected $image_mime_isLoaded = false;

    /**
     * @var        ObjectCollection|ChildMumBear[] Collection to store aggregation of ChildMumBear objects.
     */
    protected $collMumBears;
    protected $collMumBearsPartial;

    /**
     * @var        ChildMum[] Collection to store aggregation of ChildMum objects.
     */
    protected $collMums;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection
     */
    protected $mumsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection
     */
    protected $mumBearsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->underclassman = false;
        $this->junior = false;
        $this->senior = false;
    }

    /**
     * Initializes internal state of Base\Bear object.
     * @see applyDefaults()
     */
    public function __construct()
    {
        $this->applyDefaultValues();
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !empty($this->modifiedColumns);
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return in_array($col, $this->modifiedColumns);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return array_unique($this->modifiedColumns);
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (Boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (Boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            while (false !== ($offset = array_search($col, $this->modifiedColumns))) {
                array_splice($this->modifiedColumns, $offset, 1);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>Bear</code> instance.  If
     * <code>obj</code> is an instance of <code>Bear</code>, delegates to
     * <code>equals(Bear)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        $thisclazz = get_class($this);
        if (!is_object($obj) || !($obj instanceof $thisclazz)) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey()
            || null === $obj->getPrimaryKey())  {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        if (null !== $this->getPrimaryKey()) {
            return crc32(serialize($this->getPrimaryKey()));
        }

        return crc32(serialize(clone $this));
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return Bear The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     *
     * @return Bear The current object, for fluid interface
     */
    public function importFrom($parser, $data)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), TableMap::TYPE_PHPNAME);

        return $this;
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        return array_keys(get_object_vars($this));
    }

    /**
     * Get the [id] column value.
     * 
     * @return   int
     */
    public function getId()
    {

        return $this->id;
    }

    /**
     * Get the [item_id] column value.
     * 
     * @return   string
     */
    public function getItemId()
    {

        return $this->item_id;
    }

    /**
     * Get the [name] column value.
     * 
     * @return   string
     */
    public function getName()
    {

        return $this->name;
    }

    /**
     * Get the [underclassman] column value.
     * 
     * @return   boolean
     */
    public function getUnderclassman()
    {

        return $this->underclassman;
    }

    /**
     * Get the [junior] column value.
     * 
     * @return   boolean
     */
    public function getJunior()
    {

        return $this->junior;
    }

    /**
     * Get the [senior] column value.
     * 
     * @return   boolean
     */
    public function getSenior()
    {

        return $this->senior;
    }

    /**
     * Get the [price] column value.
     * 
     * @return   string
     */
    public function getPrice()
    {

        return $this->price;
    }

    /**
     * Get the [image] column value.
     * 
     * @param      ConnectionInterface An optional ConnectionInterface connection to use for fetching this lazy-loaded column.
     * @return   resource
     */
    public function getImage(ConnectionInterface $con = null)
    {
        if (!$this->image_isLoaded && $this->image === null && !$this->isNew()) {
            $this->loadImage($con);
        }


        return $this->image;
    }

    /**
     * Load the value for the lazy-loaded [image] column.
     *
     * This method performs an additional query to return the value for
     * the [image] column, since it is not populated by
     * the hydrate() method.
     *
     * @param      $con ConnectionInterface (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - any underlying error will be wrapped and re-thrown.
     */
    protected function loadImage(ConnectionInterface $con = null)
    {
        $c = $this->buildPkeyCriteria();
        $c->addSelectColumn(BearTableMap::IMAGE);
        try {
            $dataFetcher = ChildBearQuery::create(null, $c)->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
            $row = $dataFetcher->fetch();
            $dataFetcher->close();

        $firstColumn = $row ? current($row) : null;

            if ($firstColumn !== null) {
                $this->image = fopen('php://memory', 'r+');
                fwrite($this->image, $firstColumn);
                rewind($this->image);
            } else {
                $this->image = null;
            }
            $this->image_isLoaded = true;
        } catch (Exception $e) {
            throw new PropelException("Error loading value for [image] column on demand.", 0, $e);
        }
    }
    /**
     * Get the [image_mime] column value.
     * 
     * @param      ConnectionInterface An optional ConnectionInterface connection to use for fetching this lazy-loaded column.
     * @return   string
     */
    public function getImageMime(ConnectionInterface $con = null)
    {
        if (!$this->image_mime_isLoaded && $this->image_mime === null && !$this->isNew()) {
            $this->loadImageMime($con);
        }


        return $this->image_mime;
    }

    /**
     * Load the value for the lazy-loaded [image_mime] column.
     *
     * This method performs an additional query to return the value for
     * the [image_mime] column, since it is not populated by
     * the hydrate() method.
     *
     * @param      $con ConnectionInterface (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - any underlying error will be wrapped and re-thrown.
     */
    protected function loadImageMime(ConnectionInterface $con = null)
    {
        $c = $this->buildPkeyCriteria();
        $c->addSelectColumn(BearTableMap::IMAGE_MIME);
        try {
            $dataFetcher = ChildBearQuery::create(null, $c)->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
            $row = $dataFetcher->fetch();
            $dataFetcher->close();

        $firstColumn = $row ? current($row) : null;

            $this->image_mime = ($firstColumn !== null) ? (string) $firstColumn : null;
            $this->image_mime_isLoaded = true;
        } catch (Exception $e) {
            throw new PropelException("Error loading value for [image_mime] column on demand.", 0, $e);
        }
    }
    /**
     * Set the value of [id] column.
     * 
     * @param      int $v new value
     * @return   \Bear The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = BearTableMap::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [item_id] column.
     * 
     * @param      string $v new value
     * @return   \Bear The current object (for fluent API support)
     */
    public function setItemId($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->item_id !== $v) {
            $this->item_id = $v;
            $this->modifiedColumns[] = BearTableMap::ITEM_ID;
        }


        return $this;
    } // setItemId()

    /**
     * Set the value of [name] column.
     * 
     * @param      string $v new value
     * @return   \Bear The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[] = BearTableMap::NAME;
        }


        return $this;
    } // setName()

    /**
     * Sets the value of the [underclassman] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * 
     * @param      boolean|integer|string $v The new value
     * @return   \Bear The current object (for fluent API support)
     */
    public function setUnderclassman($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->underclassman !== $v) {
            $this->underclassman = $v;
            $this->modifiedColumns[] = BearTableMap::UNDERCLASSMAN;
        }


        return $this;
    } // setUnderclassman()

    /**
     * Sets the value of the [junior] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * 
     * @param      boolean|integer|string $v The new value
     * @return   \Bear The current object (for fluent API support)
     */
    public function setJunior($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->junior !== $v) {
            $this->junior = $v;
            $this->modifiedColumns[] = BearTableMap::JUNIOR;
        }


        return $this;
    } // setJunior()

    /**
     * Sets the value of the [senior] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * 
     * @param      boolean|integer|string $v The new value
     * @return   \Bear The current object (for fluent API support)
     */
    public function setSenior($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->senior !== $v) {
            $this->senior = $v;
            $this->modifiedColumns[] = BearTableMap::SENIOR;
        }


        return $this;
    } // setSenior()

    /**
     * Set the value of [price] column.
     * 
     * @param      string $v new value
     * @return   \Bear The current object (for fluent API support)
     */
    public function setPrice($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->price !== $v) {
            $this->price = $v;
            $this->modifiedColumns[] = BearTableMap::PRICE;
        }


        return $this;
    } // setPrice()

    /**
     * Set the value of [image] column.
     * 
     * @param      resource $v new value
     * @return   \Bear The current object (for fluent API support)
     */
    public function setImage($v)
    {
        // explicitly set the is-loaded flag to true for this lazy load col;
        // it doesn't matter if the value is actually set or not (logic below) as
        // any attempt to set the value means that no db lookup should be performed
        // when the getImage() method is called.
        $this->image_isLoaded = true;

        // Because BLOB columns are streams in PDO we have to assume that they are
        // always modified when a new value is passed in.  For example, the contents
        // of the stream itself may have changed externally.
        if (!is_resource($v) && $v !== null) {
            $this->image = fopen('php://memory', 'r+');
            fwrite($this->image, $v);
            rewind($this->image);
        } else { // it's already a stream
            $this->image = $v;
        }
        $this->modifiedColumns[] = BearTableMap::IMAGE;


        return $this;
    } // setImage()

    /**
     * Set the value of [image_mime] column.
     * 
     * @param      string $v new value
     * @return   \Bear The current object (for fluent API support)
     */
    public function setImageMime($v)
    {
        // explicitly set the is-loaded flag to true for this lazy load col;
        // it doesn't matter if the value is actually set or not (logic below) as
        // any attempt to set the value means that no db lookup should be performed
        // when the getImageMime() method is called.
        $this->image_mime_isLoaded = true;

        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->image_mime !== $v) {
            $this->image_mime = $v;
            $this->modifiedColumns[] = BearTableMap::IMAGE_MIME;
        }


        return $this;
    } // setImageMime()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
            if ($this->underclassman !== false) {
                return false;
            }

            if ($this->junior !== false) {
                return false;
            }

            if ($this->senior !== false) {
                return false;
            }

        // otherwise, everything was equal, so return TRUE
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {


            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : BearTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : BearTableMap::translateFieldName('ItemId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->item_id = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : BearTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : BearTableMap::translateFieldName('Underclassman', TableMap::TYPE_PHPNAME, $indexType)];
            $this->underclassman = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : BearTableMap::translateFieldName('Junior', TableMap::TYPE_PHPNAME, $indexType)];
            $this->junior = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : BearTableMap::translateFieldName('Senior', TableMap::TYPE_PHPNAME, $indexType)];
            $this->senior = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : BearTableMap::translateFieldName('Price', TableMap::TYPE_PHPNAME, $indexType)];
            $this->price = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 7; // 7 = BearTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating \Bear object", 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(BearTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildBearQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        // Reset the image lazy-load column
        $this->image = null;
        $this->image_isLoaded = false;

        // Reset the image_mime lazy-load column
        $this->image_mime = null;
        $this->image_mime_isLoaded = false;

        if ($deep) {  // also de-associate any related objects?

            $this->collMumBears = null;

            $this->collMums = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Bear::setDeleted()
     * @see Bear::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(BearTableMap::DATABASE_NAME);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = ChildBearQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $con->commit();
                $this->setDeleted(true);
            } else {
                $con->commit();
            }
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(BearTableMap::DATABASE_NAME);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                BearTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                // Rewind the image LOB column, since PDO does not rewind after inserting value.
                if ($this->image !== null && is_resource($this->image)) {
                    rewind($this->image);
                }

                $this->resetModified();
            }

            if ($this->mumsScheduledForDeletion !== null) {
                if (!$this->mumsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk  = $this->getPrimaryKey();
                    foreach ($this->mumsScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($remotePk, $pk);
                    }

                    MumBearQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->mumsScheduledForDeletion = null;
                }

                foreach ($this->getMums() as $mum) {
                    if ($mum->isModified()) {
                        $mum->save($con);
                    }
                }
            } elseif ($this->collMums) {
                foreach ($this->collMums as $mum) {
                    if ($mum->isModified()) {
                        $mum->save($con);
                    }
                }
            }

            if ($this->mumBearsScheduledForDeletion !== null) {
                if (!$this->mumBearsScheduledForDeletion->isEmpty()) {
                    \MumBearQuery::create()
                        ->filterByPrimaryKeys($this->mumBearsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->mumBearsScheduledForDeletion = null;
                }
            }

                if ($this->collMumBears !== null) {
            foreach ($this->collMumBears as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[] = BearTableMap::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . BearTableMap::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(BearTableMap::ID)) {
            $modifiedColumns[':p' . $index++]  = 'ID';
        }
        if ($this->isColumnModified(BearTableMap::ITEM_ID)) {
            $modifiedColumns[':p' . $index++]  = 'ITEM_ID';
        }
        if ($this->isColumnModified(BearTableMap::NAME)) {
            $modifiedColumns[':p' . $index++]  = 'NAME';
        }
        if ($this->isColumnModified(BearTableMap::UNDERCLASSMAN)) {
            $modifiedColumns[':p' . $index++]  = 'UNDERCLASSMAN';
        }
        if ($this->isColumnModified(BearTableMap::JUNIOR)) {
            $modifiedColumns[':p' . $index++]  = 'JUNIOR';
        }
        if ($this->isColumnModified(BearTableMap::SENIOR)) {
            $modifiedColumns[':p' . $index++]  = 'SENIOR';
        }
        if ($this->isColumnModified(BearTableMap::PRICE)) {
            $modifiedColumns[':p' . $index++]  = 'PRICE';
        }
        if ($this->isColumnModified(BearTableMap::IMAGE)) {
            $modifiedColumns[':p' . $index++]  = 'IMAGE';
        }
        if ($this->isColumnModified(BearTableMap::IMAGE_MIME)) {
            $modifiedColumns[':p' . $index++]  = 'IMAGE_MIME';
        }

        $sql = sprintf(
            'INSERT INTO bear (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'ID':                        
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case 'ITEM_ID':                        
                        $stmt->bindValue($identifier, $this->item_id, PDO::PARAM_STR);
                        break;
                    case 'NAME':                        
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case 'UNDERCLASSMAN':
                        $stmt->bindValue($identifier, (int) $this->underclassman, PDO::PARAM_INT);
                        break;
                    case 'JUNIOR':
                        $stmt->bindValue($identifier, (int) $this->junior, PDO::PARAM_INT);
                        break;
                    case 'SENIOR':
                        $stmt->bindValue($identifier, (int) $this->senior, PDO::PARAM_INT);
                        break;
                    case 'PRICE':                        
                        $stmt->bindValue($identifier, $this->price, PDO::PARAM_STR);
                        break;
                    case 'IMAGE':                        
                        if (is_resource($this->image)) {
                            rewind($this->image);
                        }
                        $stmt->bindValue($identifier, $this->image, PDO::PARAM_LOB);
                        break;
                    case 'IMAGE_MIME':                        
                        $stmt->bindValue($identifier, $this->image_mime, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = BearTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getItemId();
                break;
            case 2:
                return $this->getName();
                break;
            case 3:
                return $this->getUnderclassman();
                break;
            case 4:
                return $this->getJunior();
                break;
            case 5:
                return $this->getSenior();
                break;
            case 6:
                return $this->getPrice();
                break;
            case 7:
                return $this->getImage();
                break;
            case 8:
                return $this->getImageMime();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {
        if (isset($alreadyDumpedObjects['Bear'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Bear'][$this->getPrimaryKey()] = true;
        $keys = BearTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getItemId(),
            $keys[2] => $this->getName(),
            $keys[3] => $this->getUnderclassman(),
            $keys[4] => $this->getJunior(),
            $keys[5] => $this->getSenior(),
            $keys[6] => $this->getPrice(),
            $keys[7] => ($includeLazyLoadColumns) ? $this->getImage() : null,
            $keys[8] => ($includeLazyLoadColumns) ? $this->getImageMime() : null,
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }
        
        if ($includeForeignObjects) {
            if (null !== $this->collMumBears) {
                $result['MumBears'] = $this->collMumBears->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param      string $name
     * @param      mixed  $value field value
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return void
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = BearTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @param      mixed $value field value
     * @return void
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setItemId($value);
                break;
            case 2:
                $this->setName($value);
                break;
            case 3:
                $this->setUnderclassman($value);
                break;
            case 4:
                $this->setJunior($value);
                break;
            case 5:
                $this->setSenior($value);
                break;
            case 6:
                $this->setPrice($value);
                break;
            case 7:
                $this->setImage($value);
                break;
            case 8:
                $this->setImageMime($value);
                break;
        } // switch()
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = BearTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setItemId($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setName($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setUnderclassman($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setJunior($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setSenior($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setPrice($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setImage($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setImageMime($arr[$keys[8]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(BearTableMap::DATABASE_NAME);

        if ($this->isColumnModified(BearTableMap::ID)) $criteria->add(BearTableMap::ID, $this->id);
        if ($this->isColumnModified(BearTableMap::ITEM_ID)) $criteria->add(BearTableMap::ITEM_ID, $this->item_id);
        if ($this->isColumnModified(BearTableMap::NAME)) $criteria->add(BearTableMap::NAME, $this->name);
        if ($this->isColumnModified(BearTableMap::UNDERCLASSMAN)) $criteria->add(BearTableMap::UNDERCLASSMAN, $this->underclassman);
        if ($this->isColumnModified(BearTableMap::JUNIOR)) $criteria->add(BearTableMap::JUNIOR, $this->junior);
        if ($this->isColumnModified(BearTableMap::SENIOR)) $criteria->add(BearTableMap::SENIOR, $this->senior);
        if ($this->isColumnModified(BearTableMap::PRICE)) $criteria->add(BearTableMap::PRICE, $this->price);
        if ($this->isColumnModified(BearTableMap::IMAGE)) $criteria->add(BearTableMap::IMAGE, $this->image);
        if ($this->isColumnModified(BearTableMap::IMAGE_MIME)) $criteria->add(BearTableMap::IMAGE_MIME, $this->image_mime);

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = new Criteria(BearTableMap::DATABASE_NAME);
        $criteria->add(BearTableMap::ID, $this->id);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return   int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {

        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \Bear (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setItemId($this->getItemId());
        $copyObj->setName($this->getName());
        $copyObj->setUnderclassman($this->getUnderclassman());
        $copyObj->setJunior($this->getJunior());
        $copyObj->setSenior($this->getSenior());
        $copyObj->setPrice($this->getPrice());
        $copyObj->setImage($this->getImage());
        $copyObj->setImageMime($this->getImageMime());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getMumBears() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMumBear($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return                 \Bear Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('MumBear' == $relationName) {
            return $this->initMumBears();
        }
    }

    /**
     * Clears out the collMumBears collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addMumBears()
     */
    public function clearMumBears()
    {
        $this->collMumBears = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collMumBears collection loaded partially.
     */
    public function resetPartialMumBears($v = true)
    {
        $this->collMumBearsPartial = $v;
    }

    /**
     * Initializes the collMumBears collection.
     *
     * By default this just sets the collMumBears collection to an empty array (like clearcollMumBears());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMumBears($overrideExisting = true)
    {
        if (null !== $this->collMumBears && !$overrideExisting) {
            return;
        }
        $this->collMumBears = new ObjectCollection();
        $this->collMumBears->setModel('\MumBear');
    }

    /**
     * Gets an array of ChildMumBear objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildBear is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return Collection|ChildMumBear[] List of ChildMumBear objects
     * @throws PropelException
     */
    public function getMumBears($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collMumBearsPartial && !$this->isNew();
        if (null === $this->collMumBears || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collMumBears) {
                // return empty collection
                $this->initMumBears();
            } else {
                $collMumBears = ChildMumBearQuery::create(null, $criteria)
                    ->filterByBear($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collMumBearsPartial && count($collMumBears)) {
                        $this->initMumBears(false);

                        foreach ($collMumBears as $obj) {
                            if (false == $this->collMumBears->contains($obj)) {
                                $this->collMumBears->append($obj);
                            }
                        }

                        $this->collMumBearsPartial = true;
                    }

                    $collMumBears->getInternalIterator()->rewind();

                    return $collMumBears;
                }

                if ($partial && $this->collMumBears) {
                    foreach ($this->collMumBears as $obj) {
                        if ($obj->isNew()) {
                            $collMumBears[] = $obj;
                        }
                    }
                }

                $this->collMumBears = $collMumBears;
                $this->collMumBearsPartial = false;
            }
        }

        return $this->collMumBears;
    }

    /**
     * Sets a collection of MumBear objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $mumBears A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return   ChildBear The current object (for fluent API support)
     */
    public function setMumBears(Collection $mumBears, ConnectionInterface $con = null)
    {
        $mumBearsToDelete = $this->getMumBears(new Criteria(), $con)->diff($mumBears);

        
        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->mumBearsScheduledForDeletion = clone $mumBearsToDelete;

        foreach ($mumBearsToDelete as $mumBearRemoved) {
            $mumBearRemoved->setBear(null);
        }

        $this->collMumBears = null;
        foreach ($mumBears as $mumBear) {
            $this->addMumBear($mumBear);
        }

        $this->collMumBears = $mumBears;
        $this->collMumBearsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related MumBear objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related MumBear objects.
     * @throws PropelException
     */
    public function countMumBears(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collMumBearsPartial && !$this->isNew();
        if (null === $this->collMumBears || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMumBears) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getMumBears());
            }

            $query = ChildMumBearQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByBear($this)
                ->count($con);
        }

        return count($this->collMumBears);
    }

    /**
     * Method called to associate a ChildMumBear object to this object
     * through the ChildMumBear foreign key attribute.
     *
     * @param    ChildMumBear $l ChildMumBear
     * @return   \Bear The current object (for fluent API support)
     */
    public function addMumBear(ChildMumBear $l)
    {
        if ($this->collMumBears === null) {
            $this->initMumBears();
            $this->collMumBearsPartial = true;
        }

        if (!in_array($l, $this->collMumBears->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddMumBear($l);
        }

        return $this;
    }

    /**
     * @param MumBear $mumBear The mumBear object to add.
     */
    protected function doAddMumBear($mumBear)
    {
        $this->collMumBears[]= $mumBear;
        $mumBear->setBear($this);
    }

    /**
     * @param  MumBear $mumBear The mumBear object to remove.
     * @return ChildBear The current object (for fluent API support)
     */
    public function removeMumBear($mumBear)
    {
        if ($this->getMumBears()->contains($mumBear)) {
            $this->collMumBears->remove($this->collMumBears->search($mumBear));
            if (null === $this->mumBearsScheduledForDeletion) {
                $this->mumBearsScheduledForDeletion = clone $this->collMumBears;
                $this->mumBearsScheduledForDeletion->clear();
            }
            $this->mumBearsScheduledForDeletion[]= clone $mumBear;
            $mumBear->setBear(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Bear is new, it will return
     * an empty collection; or if this Bear has previously
     * been saved, it will retrieve related MumBears from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Bear.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return Collection|ChildMumBear[] List of ChildMumBear objects
     */
    public function getMumBearsJoinMum($criteria = null, $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildMumBearQuery::create(null, $criteria);
        $query->joinWith('Mum', $joinBehavior);

        return $this->getMumBears($query, $con);
    }

    /**
     * Clears out the collMums collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addMums()
     */
    public function clearMums()
    {
        $this->collMums = null; // important to set this to NULL since that means it is uninitialized
        $this->collMumsPartial = null;
    }

    /**
     * Initializes the collMums collection.
     *
     * By default this just sets the collMums collection to an empty collection (like clearMums());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initMums()
    {
        $this->collMums = new ObjectCollection();
        $this->collMums->setModel('\Mum');
    }

    /**
     * Gets a collection of ChildMum objects related by a many-to-many relationship
     * to the current object by way of the mum_bear cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildBear is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildMum[] List of ChildMum objects
     */
    public function getMums($criteria = null, ConnectionInterface $con = null)
    {
        if (null === $this->collMums || null !== $criteria) {
            if ($this->isNew() && null === $this->collMums) {
                // return empty collection
                $this->initMums();
            } else {
                $collMums = ChildMumQuery::create(null, $criteria)
                    ->filterByBear($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collMums;
                }
                $this->collMums = $collMums;
            }
        }

        return $this->collMums;
    }

    /**
     * Sets a collection of Mum objects related by a many-to-many relationship
     * to the current object by way of the mum_bear cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $mums A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return ChildBear The current object (for fluent API support)
     */
    public function setMums(Collection $mums, ConnectionInterface $con = null)
    {
        $this->clearMums();
        $currentMums = $this->getMums();

        $this->mumsScheduledForDeletion = $currentMums->diff($mums);

        foreach ($mums as $mum) {
            if (!$currentMums->contains($mum)) {
                $this->doAddMum($mum);
            }
        }

        $this->collMums = $mums;

        return $this;
    }

    /**
     * Gets the number of ChildMum objects related by a many-to-many relationship
     * to the current object by way of the mum_bear cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related ChildMum objects
     */
    public function countMums($criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        if (null === $this->collMums || null !== $criteria) {
            if ($this->isNew() && null === $this->collMums) {
                return 0;
            } else {
                $query = ChildMumQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByBear($this)
                    ->count($con);
            }
        } else {
            return count($this->collMums);
        }
    }

    /**
     * Associate a ChildMum object to this object
     * through the mum_bear cross reference table.
     *
     * @param  ChildMum $mum The ChildMumBear object to relate
     * @return ChildBear The current object (for fluent API support)
     */
    public function addMum(ChildMum $mum)
    {
        if ($this->collMums === null) {
            $this->initMums();
        }

        if (!$this->collMums->contains($mum)) { // only add it if the **same** object is not already associated
            $this->doAddMum($mum);
            $this->collMums[] = $mum;
        }

        return $this;
    }

    /**
     * @param    Mum $mum The mum object to add.
     */
    protected function doAddMum($mum)
    {
        $mumBear = new ChildMumBear();
        $mumBear->setMum($mum);
        $this->addMumBear($mumBear);
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$mum->getBears()->contains($this)) {
            $foreignCollection   = $mum->getBears();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a ChildMum object to this object
     * through the mum_bear cross reference table.
     *
     * @param ChildMum $mum The ChildMumBear object to relate
     * @return ChildBear The current object (for fluent API support)
     */
    public function removeMum(ChildMum $mum)
    {
        if ($this->getMums()->contains($mum)) {
            $this->collMums->remove($this->collMums->search($mum));

            if (null === $this->mumsScheduledForDeletion) {
                $this->mumsScheduledForDeletion = clone $this->collMums;
                $this->mumsScheduledForDeletion->clear();
            }

            $this->mumsScheduledForDeletion[] = $mum;
        }

        return $this;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->item_id = null;
        $this->name = null;
        $this->underclassman = null;
        $this->junior = null;
        $this->senior = null;
        $this->price = null;
        $this->image = null;
        $this->image_isLoaded = false;
        $this->image_mime = null;
        $this->image_mime_isLoaded = false;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references to other model objects or collections of model objects.
     *
     * This method is a user-space workaround for PHP's inability to garbage collect
     * objects with circular references (even in PHP 5.3). This is currently necessary
     * when using Propel in certain daemon or large-volume/high-memory operations.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collMumBears) {
                foreach ($this->collMumBears as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collMums) {
                foreach ($this->collMums as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        if ($this->collMumBears instanceof Collection) {
            $this->collMumBears->clearIterator();
        }
        $this->collMumBears = null;
        if ($this->collMums instanceof Collection) {
            $this->collMums->clearIterator();
        }
        $this->collMums = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(BearTableMap::DEFAULT_STRING_FORMAT);
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {

    }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
