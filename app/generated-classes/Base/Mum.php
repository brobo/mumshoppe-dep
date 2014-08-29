<?php

namespace Base;

use \AccentBow as ChildAccentBow;
use \AccentBowQuery as ChildAccentBowQuery;
use \Backing as ChildBacking;
use \BackingQuery as ChildBackingQuery;
use \Bear as ChildBear;
use \BearQuery as ChildBearQuery;
use \Customer as ChildCustomer;
use \CustomerQuery as ChildCustomerQuery;
use \Letter as ChildLetter;
use \LetterQuery as ChildLetterQuery;
use \Mum as ChildMum;
use \MumBear as ChildMumBear;
use \MumBearQuery as ChildMumBearQuery;
use \MumQuery as ChildMumQuery;
use \MumTrinket as ChildMumTrinket;
use \MumTrinketQuery as ChildMumTrinketQuery;
use \Status as ChildStatus;
use \StatusQuery as ChildStatusQuery;
use \DateTime;
use \Exception;
use \PDO;
use Map\MumTableMap;
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
use Propel\Runtime\Util\PropelDateTime;

abstract class Mum implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\MumTableMap';


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
     * The value for the customer_id field.
     * @var        int
     */
    protected $customer_id;

    /**
     * The value for the backing_id field.
     * @var        int
     */
    protected $backing_id;

    /**
     * The value for the accent_bow_id field.
     * @var        int
     */
    protected $accent_bow_id;

    /**
     * The value for the letter1_id field.
     * @var        int
     */
    protected $letter1_id;

    /**
     * The value for the name_ribbon1 field.
     * @var        string
     */
    protected $name_ribbon1;

    /**
     * The value for the letter2_id field.
     * @var        int
     */
    protected $letter2_id;

    /**
     * The value for the name_ribbon2 field.
     * @var        string
     */
    protected $name_ribbon2;

    /**
     * The value for the status_id field.
     * @var        int
     */
    protected $status_id;

    /**
     * The value for the paid field.
     * @var        boolean
     */
    protected $paid;

    /**
     * The value for the order_date field.
     * @var        string
     */
    protected $order_date;

    /**
     * The value for the paid_date field.
     * @var        string
     */
    protected $paid_date;

    /**
     * The value for the deposit_sale_id field.
     * @var        string
     */
    protected $deposit_sale_id;

    /**
     * The value for the paid_sale_id field.
     * @var        string
     */
    protected $paid_sale_id;

    /**
     * The value for the delivery_date field.
     * @var        string
     */
    protected $delivery_date;

    /**
     * @var        Customer
     */
    protected $aCustomer;

    /**
     * @var        Backing
     */
    protected $aBacking;

    /**
     * @var        AccentBow
     */
    protected $aAccentBow;

    /**
     * @var        Letter
     */
    protected $aLetter;

    /**
     * @var        Status
     */
    protected $aStatus;

    /**
     * @var        ObjectCollection|ChildMumTrinket[] Collection to store aggregation of ChildMumTrinket objects.
     */
    protected $collMumTrinkets;
    protected $collMumTrinketsPartial;

    /**
     * @var        ObjectCollection|ChildMumBear[] Collection to store aggregation of ChildMumBear objects.
     */
    protected $collMumBears;
    protected $collMumBearsPartial;

    /**
     * @var        ChildBear[] Collection to store aggregation of ChildBear objects.
     */
    protected $collBears;

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
    protected $bearsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection
     */
    protected $mumTrinketsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection
     */
    protected $mumBearsScheduledForDeletion = null;

    /**
     * Initializes internal state of Base\Mum object.
     */
    public function __construct()
    {
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
     * Compares this with another <code>Mum</code> instance.  If
     * <code>obj</code> is an instance of <code>Mum</code>, delegates to
     * <code>equals(Mum)</code>.  Otherwise, returns <code>false</code>.
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
     * @return Mum The current object, for fluid interface
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
     * @return Mum The current object, for fluid interface
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
     * Get the [customer_id] column value.
     *
     * @return   int
     */
    public function getCustomerId()
    {

        return $this->customer_id;
    }

    /**
     * Get the [backing_id] column value.
     *
     * @return   int
     */
    public function getBackingId()
    {

        return $this->backing_id;
    }

    /**
     * Get the [accent_bow_id] column value.
     *
     * @return   int
     */
    public function getAccentBowId()
    {

        return $this->accent_bow_id;
    }

    /**
     * Get the [letter1_id] column value.
     *
     * @return   int
     */
    public function getLetter1Id()
    {

        return $this->letter1_id;
    }

    /**
     * Get the [name_ribbon1] column value.
     *
     * @return   string
     */
    public function getNameRibbon1()
    {

        return $this->name_ribbon1;
    }

    /**
     * Get the [letter2_id] column value.
     *
     * @return   int
     */
    public function getLetter2Id()
    {

        return $this->letter2_id;
    }

    /**
     * Get the [name_ribbon2] column value.
     *
     * @return   string
     */
    public function getNameRibbon2()
    {

        return $this->name_ribbon2;
    }

    /**
     * Get the [status_id] column value.
     *
     * @return   int
     */
    public function getStatusId()
    {

        return $this->status_id;
    }

    /**
     * Get the [paid] column value.
     *
     * @return   boolean
     */
    public function getPaid()
    {

        return $this->paid;
    }

    /**
     * Get the [optionally formatted] temporal [order_date] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw \DateTime object will be returned.
     *
     * @return mixed Formatted date/time value as string or \DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getOrderDate($format = NULL)
    {
        if ($format === null) {
            return $this->order_date;
        } else {
            return $this->order_date instanceof \DateTime ? $this->order_date->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [paid_date] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw \DateTime object will be returned.
     *
     * @return mixed Formatted date/time value as string or \DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getPaidDate($format = NULL)
    {
        if ($format === null) {
            return $this->paid_date;
        } else {
            return $this->paid_date instanceof \DateTime ? $this->paid_date->format($format) : null;
        }
    }

    /**
     * Get the [deposit_sale_id] column value.
     *
     * @return   string
     */
    public function getDepositSaleId()
    {

        return $this->deposit_sale_id;
    }

    /**
     * Get the [paid_sale_id] column value.
     *
     * @return   string
     */
    public function getPaidSaleId()
    {

        return $this->paid_sale_id;
    }

    /**
     * Get the [optionally formatted] temporal [delivery_date] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw \DateTime object will be returned.
     *
     * @return mixed Formatted date/time value as string or \DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getDeliveryDate($format = NULL)
    {
        if ($format === null) {
            return $this->delivery_date;
        } else {
            return $this->delivery_date instanceof \DateTime ? $this->delivery_date->format($format) : null;
        }
    }

    /**
     * Set the value of [id] column.
     *
     * @param      int $v new value
     * @return   \Mum The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = MumTableMap::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [customer_id] column.
     *
     * @param      int $v new value
     * @return   \Mum The current object (for fluent API support)
     */
    public function setCustomerId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->customer_id !== $v) {
            $this->customer_id = $v;
            $this->modifiedColumns[] = MumTableMap::CUSTOMER_ID;
        }

        if ($this->aCustomer !== null && $this->aCustomer->getId() !== $v) {
            $this->aCustomer = null;
        }


        return $this;
    } // setCustomerId()

    /**
     * Set the value of [backing_id] column.
     *
     * @param      int $v new value
     * @return   \Mum The current object (for fluent API support)
     */
    public function setBackingId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->backing_id !== $v) {
            $this->backing_id = $v;
            $this->modifiedColumns[] = MumTableMap::BACKING_ID;
        }

        if ($this->aBacking !== null && $this->aBacking->getId() !== $v) {
            $this->aBacking = null;
        }


        return $this;
    } // setBackingId()

    /**
     * Set the value of [accent_bow_id] column.
     *
     * @param      int $v new value
     * @return   \Mum The current object (for fluent API support)
     */
    public function setAccentBowId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->accent_bow_id !== $v) {
            $this->accent_bow_id = $v;
            $this->modifiedColumns[] = MumTableMap::ACCENT_BOW_ID;
        }

        if ($this->aAccentBow !== null && $this->aAccentBow->getId() !== $v) {
            $this->aAccentBow = null;
        }


        return $this;
    } // setAccentBowId()

    /**
     * Set the value of [letter1_id] column.
     *
     * @param      int $v new value
     * @return   \Mum The current object (for fluent API support)
     */
    public function setLetter1Id($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->letter1_id !== $v) {
            $this->letter1_id = $v;
            $this->modifiedColumns[] = MumTableMap::LETTER1_ID;
        }

        if ($this->aLetter !== null && $this->aLetter->getId() !== $v) {
            $this->aLetter = null;
        }


        return $this;
    } // setLetter1Id()

    /**
     * Set the value of [name_ribbon1] column.
     *
     * @param      string $v new value
     * @return   \Mum The current object (for fluent API support)
     */
    public function setNameRibbon1($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name_ribbon1 !== $v) {
            $this->name_ribbon1 = $v;
            $this->modifiedColumns[] = MumTableMap::NAME_RIBBON1;
        }


        return $this;
    } // setNameRibbon1()

    /**
     * Set the value of [letter2_id] column.
     *
     * @param      int $v new value
     * @return   \Mum The current object (for fluent API support)
     */
    public function setLetter2Id($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->letter2_id !== $v) {
            $this->letter2_id = $v;
            $this->modifiedColumns[] = MumTableMap::LETTER2_ID;
        }

        if ($this->aLetter !== null && $this->aLetter->getId() !== $v) {
            $this->aLetter = null;
        }


        return $this;
    } // setLetter2Id()

    /**
     * Set the value of [name_ribbon2] column.
     *
     * @param      string $v new value
     * @return   \Mum The current object (for fluent API support)
     */
    public function setNameRibbon2($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name_ribbon2 !== $v) {
            $this->name_ribbon2 = $v;
            $this->modifiedColumns[] = MumTableMap::NAME_RIBBON2;
        }


        return $this;
    } // setNameRibbon2()

    /**
     * Set the value of [status_id] column.
     *
     * @param      int $v new value
     * @return   \Mum The current object (for fluent API support)
     */
    public function setStatusId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->status_id !== $v) {
            $this->status_id = $v;
            $this->modifiedColumns[] = MumTableMap::STATUS_ID;
        }

        if ($this->aStatus !== null && $this->aStatus->getId() !== $v) {
            $this->aStatus = null;
        }


        return $this;
    } // setStatusId()

    /**
     * Sets the value of the [paid] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param      boolean|integer|string $v The new value
     * @return   \Mum The current object (for fluent API support)
     */
    public function setPaid($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->paid !== $v) {
            $this->paid = $v;
            $this->modifiedColumns[] = MumTableMap::PAID;
        }


        return $this;
    } // setPaid()

    /**
     * Sets the value of [order_date] column to a normalized version of the date/time value specified.
     *
     * @param      mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return   \Mum The current object (for fluent API support)
     */
    public function setOrderDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, '\DateTime');
        if ($this->order_date !== null || $dt !== null) {
            if ($dt !== $this->order_date) {
                $this->order_date = $dt;
                $this->modifiedColumns[] = MumTableMap::ORDER_DATE;
            }
        } // if either are not null


        return $this;
    } // setOrderDate()

    /**
     * Sets the value of [paid_date] column to a normalized version of the date/time value specified.
     *
     * @param      mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return   \Mum The current object (for fluent API support)
     */
    public function setPaidDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, '\DateTime');
        if ($this->paid_date !== null || $dt !== null) {
            if ($dt !== $this->paid_date) {
                $this->paid_date = $dt;
                $this->modifiedColumns[] = MumTableMap::PAID_DATE;
            }
        } // if either are not null


        return $this;
    } // setPaidDate()

    /**
     * Set the value of [deposit_sale_id] column.
     *
     * @param      string $v new value
     * @return   \Mum The current object (for fluent API support)
     */
    public function setDepositSaleId($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->deposit_sale_id !== $v) {
            $this->deposit_sale_id = $v;
            $this->modifiedColumns[] = MumTableMap::DEPOSIT_SALE_ID;
        }


        return $this;
    } // setDepositSaleId()

    /**
     * Set the value of [paid_sale_id] column.
     *
     * @param      string $v new value
     * @return   \Mum The current object (for fluent API support)
     */
    public function setPaidSaleId($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->paid_sale_id !== $v) {
            $this->paid_sale_id = $v;
            $this->modifiedColumns[] = MumTableMap::PAID_SALE_ID;
        }


        return $this;
    } // setPaidSaleId()

    /**
     * Sets the value of [delivery_date] column to a normalized version of the date/time value specified.
     *
     * @param      mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return   \Mum The current object (for fluent API support)
     */
    public function setDeliveryDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, '\DateTime');
        if ($this->delivery_date !== null || $dt !== null) {
            if ($dt !== $this->delivery_date) {
                $this->delivery_date = $dt;
                $this->modifiedColumns[] = MumTableMap::DELIVERY_DATE;
            }
        } // if either are not null


        return $this;
    } // setDeliveryDate()

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


            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : MumTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : MumTableMap::translateFieldName('CustomerId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->customer_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : MumTableMap::translateFieldName('BackingId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->backing_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : MumTableMap::translateFieldName('AccentBowId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->accent_bow_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : MumTableMap::translateFieldName('Letter1Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->letter1_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : MumTableMap::translateFieldName('NameRibbon1', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name_ribbon1 = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : MumTableMap::translateFieldName('Letter2Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->letter2_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : MumTableMap::translateFieldName('NameRibbon2', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name_ribbon2 = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : MumTableMap::translateFieldName('StatusId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->status_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : MumTableMap::translateFieldName('Paid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->paid = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : MumTableMap::translateFieldName('OrderDate', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->order_date = (null !== $col) ? PropelDateTime::newInstance($col, null, '\DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : MumTableMap::translateFieldName('PaidDate', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->paid_date = (null !== $col) ? PropelDateTime::newInstance($col, null, '\DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : MumTableMap::translateFieldName('DepositSaleId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->deposit_sale_id = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : MumTableMap::translateFieldName('PaidSaleId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->paid_sale_id = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 14 + $startcol : MumTableMap::translateFieldName('DeliveryDate', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->delivery_date = (null !== $col) ? PropelDateTime::newInstance($col, null, '\DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 15; // 15 = MumTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating \Mum object", 0, $e);
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
        if ($this->aCustomer !== null && $this->customer_id !== $this->aCustomer->getId()) {
            $this->aCustomer = null;
        }
        if ($this->aBacking !== null && $this->backing_id !== $this->aBacking->getId()) {
            $this->aBacking = null;
        }
        if ($this->aAccentBow !== null && $this->accent_bow_id !== $this->aAccentBow->getId()) {
            $this->aAccentBow = null;
        }
        if ($this->aLetter !== null && $this->letter1_id !== $this->aLetter->getId()) {
            $this->aLetter = null;
        }
        if ($this->aLetter !== null && $this->letter2_id !== $this->aLetter->getId()) {
            $this->aLetter = null;
        }
        if ($this->aStatus !== null && $this->status_id !== $this->aStatus->getId()) {
            $this->aStatus = null;
        }
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
            $con = Propel::getServiceContainer()->getReadConnection(MumTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildMumQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aCustomer = null;
            $this->aBacking = null;
            $this->aAccentBow = null;
            $this->aLetter = null;
            $this->aStatus = null;
            $this->collMumTrinkets = null;

            $this->collMumBears = null;

            $this->collBears = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Mum::setDeleted()
     * @see Mum::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(MumTableMap::DATABASE_NAME);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = ChildMumQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(MumTableMap::DATABASE_NAME);
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
                MumTableMap::addInstanceToPool($this);
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

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aCustomer !== null) {
                if ($this->aCustomer->isModified() || $this->aCustomer->isNew()) {
                    $affectedRows += $this->aCustomer->save($con);
                }
                $this->setCustomer($this->aCustomer);
            }

            if ($this->aBacking !== null) {
                if ($this->aBacking->isModified() || $this->aBacking->isNew()) {
                    $affectedRows += $this->aBacking->save($con);
                }
                $this->setBacking($this->aBacking);
            }

            if ($this->aAccentBow !== null) {
                if ($this->aAccentBow->isModified() || $this->aAccentBow->isNew()) {
                    $affectedRows += $this->aAccentBow->save($con);
                }
                $this->setAccentBow($this->aAccentBow);
            }

            if ($this->aLetter !== null) {
                if ($this->aLetter->isModified() || $this->aLetter->isNew()) {
                    $affectedRows += $this->aLetter->save($con);
                }
                $this->setLetter($this->aLetter);
            }

            if ($this->aStatus !== null) {
                if ($this->aStatus->isModified() || $this->aStatus->isNew()) {
                    $affectedRows += $this->aStatus->save($con);
                }
                $this->setStatus($this->aStatus);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                $this->resetModified();
            }

            if ($this->bearsScheduledForDeletion !== null) {
                if (!$this->bearsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk  = $this->getPrimaryKey();
                    foreach ($this->bearsScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($pk, $remotePk);
                    }

                    MumBearQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->bearsScheduledForDeletion = null;
                }

                foreach ($this->getBears() as $bear) {
                    if ($bear->isModified()) {
                        $bear->save($con);
                    }
                }
            } elseif ($this->collBears) {
                foreach ($this->collBears as $bear) {
                    if ($bear->isModified()) {
                        $bear->save($con);
                    }
                }
            }

            if ($this->mumTrinketsScheduledForDeletion !== null) {
                if (!$this->mumTrinketsScheduledForDeletion->isEmpty()) {
                    \MumTrinketQuery::create()
                        ->filterByPrimaryKeys($this->mumTrinketsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->mumTrinketsScheduledForDeletion = null;
                }
            }

                if ($this->collMumTrinkets !== null) {
            foreach ($this->collMumTrinkets as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
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

        $this->modifiedColumns[] = MumTableMap::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . MumTableMap::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(MumTableMap::ID)) {
            $modifiedColumns[':p' . $index++]  = 'ID';
        }
        if ($this->isColumnModified(MumTableMap::CUSTOMER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'CUSTOMER_ID';
        }
        if ($this->isColumnModified(MumTableMap::BACKING_ID)) {
            $modifiedColumns[':p' . $index++]  = 'BACKING_ID';
        }
        if ($this->isColumnModified(MumTableMap::ACCENT_BOW_ID)) {
            $modifiedColumns[':p' . $index++]  = 'ACCENT_BOW_ID';
        }
        if ($this->isColumnModified(MumTableMap::LETTER1_ID)) {
            $modifiedColumns[':p' . $index++]  = 'LETTER1_ID';
        }
        if ($this->isColumnModified(MumTableMap::NAME_RIBBON1)) {
            $modifiedColumns[':p' . $index++]  = 'NAME_RIBBON1';
        }
        if ($this->isColumnModified(MumTableMap::LETTER2_ID)) {
            $modifiedColumns[':p' . $index++]  = 'LETTER2_ID';
        }
        if ($this->isColumnModified(MumTableMap::NAME_RIBBON2)) {
            $modifiedColumns[':p' . $index++]  = 'NAME_RIBBON2';
        }
        if ($this->isColumnModified(MumTableMap::STATUS_ID)) {
            $modifiedColumns[':p' . $index++]  = 'STATUS_ID';
        }
        if ($this->isColumnModified(MumTableMap::PAID)) {
            $modifiedColumns[':p' . $index++]  = 'PAID';
        }
        if ($this->isColumnModified(MumTableMap::ORDER_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'ORDER_DATE';
        }
        if ($this->isColumnModified(MumTableMap::PAID_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'PAID_DATE';
        }
        if ($this->isColumnModified(MumTableMap::DEPOSIT_SALE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'DEPOSIT_SALE_ID';
        }
        if ($this->isColumnModified(MumTableMap::PAID_SALE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'PAID_SALE_ID';
        }
        if ($this->isColumnModified(MumTableMap::DELIVERY_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'DELIVERY_DATE';
        }

        $sql = sprintf(
            'INSERT INTO mum (%s) VALUES (%s)',
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
                    case 'CUSTOMER_ID':
                        $stmt->bindValue($identifier, $this->customer_id, PDO::PARAM_INT);
                        break;
                    case 'BACKING_ID':
                        $stmt->bindValue($identifier, $this->backing_id, PDO::PARAM_INT);
                        break;
                    case 'ACCENT_BOW_ID':
                        $stmt->bindValue($identifier, $this->accent_bow_id, PDO::PARAM_INT);
                        break;
                    case 'LETTER1_ID':
                        $stmt->bindValue($identifier, $this->letter1_id, PDO::PARAM_INT);
                        break;
                    case 'NAME_RIBBON1':
                        $stmt->bindValue($identifier, $this->name_ribbon1, PDO::PARAM_STR);
                        break;
                    case 'LETTER2_ID':
                        $stmt->bindValue($identifier, $this->letter2_id, PDO::PARAM_INT);
                        break;
                    case 'NAME_RIBBON2':
                        $stmt->bindValue($identifier, $this->name_ribbon2, PDO::PARAM_STR);
                        break;
                    case 'STATUS_ID':
                        $stmt->bindValue($identifier, $this->status_id, PDO::PARAM_INT);
                        break;
                    case 'PAID':
                        $stmt->bindValue($identifier, (int) $this->paid, PDO::PARAM_INT);
                        break;
                    case 'ORDER_DATE':
                        $stmt->bindValue($identifier, $this->order_date ? $this->order_date->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'PAID_DATE':
                        $stmt->bindValue($identifier, $this->paid_date ? $this->paid_date->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'DEPOSIT_SALE_ID':
                        $stmt->bindValue($identifier, $this->deposit_sale_id, PDO::PARAM_STR);
                        break;
                    case 'PAID_SALE_ID':
                        $stmt->bindValue($identifier, $this->paid_sale_id, PDO::PARAM_STR);
                        break;
                    case 'DELIVERY_DATE':
                        $stmt->bindValue($identifier, $this->delivery_date ? $this->delivery_date->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
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
        $pos = MumTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getCustomerId();
                break;
            case 2:
                return $this->getBackingId();
                break;
            case 3:
                return $this->getAccentBowId();
                break;
            case 4:
                return $this->getLetter1Id();
                break;
            case 5:
                return $this->getNameRibbon1();
                break;
            case 6:
                return $this->getLetter2Id();
                break;
            case 7:
                return $this->getNameRibbon2();
                break;
            case 8:
                return $this->getStatusId();
                break;
            case 9:
                return $this->getPaid();
                break;
            case 10:
                return $this->getOrderDate();
                break;
            case 11:
                return $this->getPaidDate();
                break;
            case 12:
                return $this->getDepositSaleId();
                break;
            case 13:
                return $this->getPaidSaleId();
                break;
            case 14:
                return $this->getDeliveryDate();
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
        if (isset($alreadyDumpedObjects['Mum'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Mum'][$this->getPrimaryKey()] = true;
        $keys = MumTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getCustomerId(),
            $keys[2] => $this->getBackingId(),
            $keys[3] => $this->getAccentBowId(),
            $keys[4] => $this->getLetter1Id(),
            $keys[5] => $this->getNameRibbon1(),
            $keys[6] => $this->getLetter2Id(),
            $keys[7] => $this->getNameRibbon2(),
            $keys[8] => $this->getStatusId(),
            $keys[9] => $this->getPaid(),
            $keys[10] => $this->getOrderDate(),
            $keys[11] => $this->getPaidDate(),
            $keys[12] => $this->getDepositSaleId(),
            $keys[13] => $this->getPaidSaleId(),
            $keys[14] => $this->getDeliveryDate(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aCustomer) {
                $result['Customer'] = $this->aCustomer->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aBacking) {
                $result['Backing'] = $this->aBacking->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aAccentBow) {
                $result['AccentBow'] = $this->aAccentBow->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aLetter) {
                $result['Letter'] = $this->aLetter->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aStatus) {
                $result['Status'] = $this->aStatus->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collMumTrinkets) {
                $result['MumTrinkets'] = $this->collMumTrinkets->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
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
        $pos = MumTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

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
                $this->setCustomerId($value);
                break;
            case 2:
                $this->setBackingId($value);
                break;
            case 3:
                $this->setAccentBowId($value);
                break;
            case 4:
                $this->setLetter1Id($value);
                break;
            case 5:
                $this->setNameRibbon1($value);
                break;
            case 6:
                $this->setLetter2Id($value);
                break;
            case 7:
                $this->setNameRibbon2($value);
                break;
            case 8:
                $this->setStatusId($value);
                break;
            case 9:
                $this->setPaid($value);
                break;
            case 10:
                $this->setOrderDate($value);
                break;
            case 11:
                $this->setPaidDate($value);
                break;
            case 12:
                $this->setDepositSaleId($value);
                break;
            case 13:
                $this->setPaidSaleId($value);
                break;
            case 14:
                $this->setDeliveryDate($value);
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
        $keys = MumTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setCustomerId($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setBackingId($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setAccentBowId($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setLetter1Id($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setNameRibbon1($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setLetter2Id($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setNameRibbon2($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setStatusId($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setPaid($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setOrderDate($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setPaidDate($arr[$keys[11]]);
        if (array_key_exists($keys[12], $arr)) $this->setDepositSaleId($arr[$keys[12]]);
        if (array_key_exists($keys[13], $arr)) $this->setPaidSaleId($arr[$keys[13]]);
        if (array_key_exists($keys[14], $arr)) $this->setDeliveryDate($arr[$keys[14]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(MumTableMap::DATABASE_NAME);

        if ($this->isColumnModified(MumTableMap::ID)) $criteria->add(MumTableMap::ID, $this->id);
        if ($this->isColumnModified(MumTableMap::CUSTOMER_ID)) $criteria->add(MumTableMap::CUSTOMER_ID, $this->customer_id);
        if ($this->isColumnModified(MumTableMap::BACKING_ID)) $criteria->add(MumTableMap::BACKING_ID, $this->backing_id);
        if ($this->isColumnModified(MumTableMap::ACCENT_BOW_ID)) $criteria->add(MumTableMap::ACCENT_BOW_ID, $this->accent_bow_id);
        if ($this->isColumnModified(MumTableMap::LETTER1_ID)) $criteria->add(MumTableMap::LETTER1_ID, $this->letter1_id);
        if ($this->isColumnModified(MumTableMap::NAME_RIBBON1)) $criteria->add(MumTableMap::NAME_RIBBON1, $this->name_ribbon1);
        if ($this->isColumnModified(MumTableMap::LETTER2_ID)) $criteria->add(MumTableMap::LETTER2_ID, $this->letter2_id);
        if ($this->isColumnModified(MumTableMap::NAME_RIBBON2)) $criteria->add(MumTableMap::NAME_RIBBON2, $this->name_ribbon2);
        if ($this->isColumnModified(MumTableMap::STATUS_ID)) $criteria->add(MumTableMap::STATUS_ID, $this->status_id);
        if ($this->isColumnModified(MumTableMap::PAID)) $criteria->add(MumTableMap::PAID, $this->paid);
        if ($this->isColumnModified(MumTableMap::ORDER_DATE)) $criteria->add(MumTableMap::ORDER_DATE, $this->order_date);
        if ($this->isColumnModified(MumTableMap::PAID_DATE)) $criteria->add(MumTableMap::PAID_DATE, $this->paid_date);
        if ($this->isColumnModified(MumTableMap::DEPOSIT_SALE_ID)) $criteria->add(MumTableMap::DEPOSIT_SALE_ID, $this->deposit_sale_id);
        if ($this->isColumnModified(MumTableMap::PAID_SALE_ID)) $criteria->add(MumTableMap::PAID_SALE_ID, $this->paid_sale_id);
        if ($this->isColumnModified(MumTableMap::DELIVERY_DATE)) $criteria->add(MumTableMap::DELIVERY_DATE, $this->delivery_date);

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
        $criteria = new Criteria(MumTableMap::DATABASE_NAME);
        $criteria->add(MumTableMap::ID, $this->id);

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
     * @param      object $copyObj An object of \Mum (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setCustomerId($this->getCustomerId());
        $copyObj->setBackingId($this->getBackingId());
        $copyObj->setAccentBowId($this->getAccentBowId());
        $copyObj->setLetter1Id($this->getLetter1Id());
        $copyObj->setNameRibbon1($this->getNameRibbon1());
        $copyObj->setLetter2Id($this->getLetter2Id());
        $copyObj->setNameRibbon2($this->getNameRibbon2());
        $copyObj->setStatusId($this->getStatusId());
        $copyObj->setPaid($this->getPaid());
        $copyObj->setOrderDate($this->getOrderDate());
        $copyObj->setPaidDate($this->getPaidDate());
        $copyObj->setDepositSaleId($this->getDepositSaleId());
        $copyObj->setPaidSaleId($this->getPaidSaleId());
        $copyObj->setDeliveryDate($this->getDeliveryDate());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getMumTrinkets() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMumTrinket($relObj->copy($deepCopy));
                }
            }

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
     * @return                 \Mum Clone of current object.
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
     * Declares an association between this object and a ChildCustomer object.
     *
     * @param                  ChildCustomer $v
     * @return                 \Mum The current object (for fluent API support)
     * @throws PropelException
     */
    public function setCustomer(ChildCustomer $v = null)
    {
        if ($v === null) {
            $this->setCustomerId(NULL);
        } else {
            $this->setCustomerId($v->getId());
        }

        $this->aCustomer = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildCustomer object, it will not be re-added.
        if ($v !== null) {
            $v->addMum($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildCustomer object
     *
     * @param      ConnectionInterface $con Optional Connection object.
     * @return                 ChildCustomer The associated ChildCustomer object.
     * @throws PropelException
     */
    public function getCustomer(ConnectionInterface $con = null)
    {
        if ($this->aCustomer === null && ($this->customer_id !== null)) {
            $this->aCustomer = ChildCustomerQuery::create()->findPk($this->customer_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aCustomer->addMums($this);
             */
        }

        return $this->aCustomer;
    }

    /**
     * Declares an association between this object and a ChildBacking object.
     *
     * @param                  ChildBacking $v
     * @return                 \Mum The current object (for fluent API support)
     * @throws PropelException
     */
    public function setBacking(ChildBacking $v = null)
    {
        if ($v === null) {
            $this->setBackingId(NULL);
        } else {
            $this->setBackingId($v->getId());
        }

        $this->aBacking = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildBacking object, it will not be re-added.
        if ($v !== null) {
            $v->addMum($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildBacking object
     *
     * @param      ConnectionInterface $con Optional Connection object.
     * @return                 ChildBacking The associated ChildBacking object.
     * @throws PropelException
     */
    public function getBacking(ConnectionInterface $con = null)
    {
        if ($this->aBacking === null && ($this->backing_id !== null)) {
            $this->aBacking = ChildBackingQuery::create()->findPk($this->backing_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aBacking->addMums($this);
             */
        }

        return $this->aBacking;
    }

    /**
     * Declares an association between this object and a ChildAccentBow object.
     *
     * @param                  ChildAccentBow $v
     * @return                 \Mum The current object (for fluent API support)
     * @throws PropelException
     */
    public function setAccentBow(ChildAccentBow $v = null)
    {
        if ($v === null) {
            $this->setAccentBowId(NULL);
        } else {
            $this->setAccentBowId($v->getId());
        }

        $this->aAccentBow = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildAccentBow object, it will not be re-added.
        if ($v !== null) {
            $v->addMum($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildAccentBow object
     *
     * @param      ConnectionInterface $con Optional Connection object.
     * @return                 ChildAccentBow The associated ChildAccentBow object.
     * @throws PropelException
     */
    public function getAccentBow(ConnectionInterface $con = null)
    {
        if ($this->aAccentBow === null && ($this->accent_bow_id !== null)) {
            $this->aAccentBow = ChildAccentBowQuery::create()->findPk($this->accent_bow_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aAccentBow->addMums($this);
             */
        }

        return $this->aAccentBow;
    }

    /**
     * Declares an association between this object and a ChildLetter object.
     *
     * @param                  ChildLetter $v
     * @return                 \Mum The current object (for fluent API support)
     * @throws PropelException
     */
    public function setLetter(ChildLetter $v = null)
    {
        if ($v === null) {
            $this->setLetter1Id(NULL);
        } else {
            $this->setLetter1Id($v->getId());
        }

        if ($v === null) {
            $this->setLetter2Id(NULL);
        } else {
            $this->setLetter2Id($v->getId());
        }

        $this->aLetter = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildLetter object, it will not be re-added.
        if ($v !== null) {
            $v->addMum($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildLetter object
     *
     * @param      ConnectionInterface $con Optional Connection object.
     * @return                 ChildLetter The associated ChildLetter object.
     * @throws PropelException
     */
    public function getLetter(ConnectionInterface $con = null)
    {
        if ($this->aLetter === null && ($this->letter1_id !== null && $this->letter2_id !== null)) {
            $this->aLetter = ChildLetterQuery::create()
                ->filterByMum($this) // here
                ->findOne($con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aLetter->addMums($this);
             */
        }

        return $this->aLetter;
    }

    /**
     * Declares an association between this object and a ChildStatus object.
     *
     * @param                  ChildStatus $v
     * @return                 \Mum The current object (for fluent API support)
     * @throws PropelException
     */
    public function setStatus(ChildStatus $v = null)
    {
        if ($v === null) {
            $this->setStatusId(NULL);
        } else {
            $this->setStatusId($v->getId());
        }

        $this->aStatus = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildStatus object, it will not be re-added.
        if ($v !== null) {
            $v->addMum($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildStatus object
     *
     * @param      ConnectionInterface $con Optional Connection object.
     * @return                 ChildStatus The associated ChildStatus object.
     * @throws PropelException
     */
    public function getStatus(ConnectionInterface $con = null)
    {
        if ($this->aStatus === null && ($this->status_id !== null)) {
            $this->aStatus = ChildStatusQuery::create()->findPk($this->status_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aStatus->addMums($this);
             */
        }

        return $this->aStatus;
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
        if ('MumTrinket' == $relationName) {
            return $this->initMumTrinkets();
        }
        if ('MumBear' == $relationName) {
            return $this->initMumBears();
        }
    }

    /**
     * Clears out the collMumTrinkets collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addMumTrinkets()
     */
    public function clearMumTrinkets()
    {
        $this->collMumTrinkets = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collMumTrinkets collection loaded partially.
     */
    public function resetPartialMumTrinkets($v = true)
    {
        $this->collMumTrinketsPartial = $v;
    }

    /**
     * Initializes the collMumTrinkets collection.
     *
     * By default this just sets the collMumTrinkets collection to an empty array (like clearcollMumTrinkets());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMumTrinkets($overrideExisting = true)
    {
        if (null !== $this->collMumTrinkets && !$overrideExisting) {
            return;
        }
        $this->collMumTrinkets = new ObjectCollection();
        $this->collMumTrinkets->setModel('\MumTrinket');
    }

    /**
     * Gets an array of ChildMumTrinket objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildMum is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return Collection|ChildMumTrinket[] List of ChildMumTrinket objects
     * @throws PropelException
     */
    public function getMumTrinkets($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collMumTrinketsPartial && !$this->isNew();
        if (null === $this->collMumTrinkets || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collMumTrinkets) {
                // return empty collection
                $this->initMumTrinkets();
            } else {
                $collMumTrinkets = ChildMumTrinketQuery::create(null, $criteria)
                    ->filterByMum($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collMumTrinketsPartial && count($collMumTrinkets)) {
                        $this->initMumTrinkets(false);

                        foreach ($collMumTrinkets as $obj) {
                            if (false == $this->collMumTrinkets->contains($obj)) {
                                $this->collMumTrinkets->append($obj);
                            }
                        }

                        $this->collMumTrinketsPartial = true;
                    }

                    $collMumTrinkets->getInternalIterator()->rewind();

                    return $collMumTrinkets;
                }

                if ($partial && $this->collMumTrinkets) {
                    foreach ($this->collMumTrinkets as $obj) {
                        if ($obj->isNew()) {
                            $collMumTrinkets[] = $obj;
                        }
                    }
                }

                $this->collMumTrinkets = $collMumTrinkets;
                $this->collMumTrinketsPartial = false;
            }
        }

        return $this->collMumTrinkets;
    }

    /**
     * Sets a collection of MumTrinket objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $mumTrinkets A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return   ChildMum The current object (for fluent API support)
     */
    public function setMumTrinkets(Collection $mumTrinkets, ConnectionInterface $con = null)
    {
        $mumTrinketsToDelete = $this->getMumTrinkets(new Criteria(), $con)->diff($mumTrinkets);


        $this->mumTrinketsScheduledForDeletion = $mumTrinketsToDelete;

        foreach ($mumTrinketsToDelete as $mumTrinketRemoved) {
            $mumTrinketRemoved->setMum(null);
        }

        $this->collMumTrinkets = null;
        foreach ($mumTrinkets as $mumTrinket) {
            $this->addMumTrinket($mumTrinket);
        }

        $this->collMumTrinkets = $mumTrinkets;
        $this->collMumTrinketsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related MumTrinket objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related MumTrinket objects.
     * @throws PropelException
     */
    public function countMumTrinkets(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collMumTrinketsPartial && !$this->isNew();
        if (null === $this->collMumTrinkets || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMumTrinkets) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getMumTrinkets());
            }

            $query = ChildMumTrinketQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByMum($this)
                ->count($con);
        }

        return count($this->collMumTrinkets);
    }

    /**
     * Method called to associate a ChildMumTrinket object to this object
     * through the ChildMumTrinket foreign key attribute.
     *
     * @param    ChildMumTrinket $l ChildMumTrinket
     * @return   \Mum The current object (for fluent API support)
     */
    public function addMumTrinket(ChildMumTrinket $l)
    {
        if ($this->collMumTrinkets === null) {
            $this->initMumTrinkets();
            $this->collMumTrinketsPartial = true;
        }

        if (!in_array($l, $this->collMumTrinkets->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddMumTrinket($l);
        }

        return $this;
    }

    /**
     * @param MumTrinket $mumTrinket The mumTrinket object to add.
     */
    protected function doAddMumTrinket($mumTrinket)
    {
        $this->collMumTrinkets[]= $mumTrinket;
        $mumTrinket->setMum($this);
    }

    /**
     * @param  MumTrinket $mumTrinket The mumTrinket object to remove.
     * @return ChildMum The current object (for fluent API support)
     */
    public function removeMumTrinket($mumTrinket)
    {
        if ($this->getMumTrinkets()->contains($mumTrinket)) {
            $this->collMumTrinkets->remove($this->collMumTrinkets->search($mumTrinket));
            if (null === $this->mumTrinketsScheduledForDeletion) {
                $this->mumTrinketsScheduledForDeletion = clone $this->collMumTrinkets;
                $this->mumTrinketsScheduledForDeletion->clear();
            }
            $this->mumTrinketsScheduledForDeletion[]= clone $mumTrinket;
            $mumTrinket->setMum(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Mum is new, it will return
     * an empty collection; or if this Mum has previously
     * been saved, it will retrieve related MumTrinkets from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Mum.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return Collection|ChildMumTrinket[] List of ChildMumTrinket objects
     */
    public function getMumTrinketsJoinTrinket($criteria = null, $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildMumTrinketQuery::create(null, $criteria);
        $query->joinWith('Trinket', $joinBehavior);

        return $this->getMumTrinkets($query, $con);
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
     * If this ChildMum is new, it will return
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
                    ->filterByMum($this)
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
     * @return   ChildMum The current object (for fluent API support)
     */
    public function setMumBears(Collection $mumBears, ConnectionInterface $con = null)
    {
        $mumBearsToDelete = $this->getMumBears(new Criteria(), $con)->diff($mumBears);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->mumBearsScheduledForDeletion = clone $mumBearsToDelete;

        foreach ($mumBearsToDelete as $mumBearRemoved) {
            $mumBearRemoved->setMum(null);
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
                ->filterByMum($this)
                ->count($con);
        }

        return count($this->collMumBears);
    }

    /**
     * Method called to associate a ChildMumBear object to this object
     * through the ChildMumBear foreign key attribute.
     *
     * @param    ChildMumBear $l ChildMumBear
     * @return   \Mum The current object (for fluent API support)
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
        $mumBear->setMum($this);
    }

    /**
     * @param  MumBear $mumBear The mumBear object to remove.
     * @return ChildMum The current object (for fluent API support)
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
            $mumBear->setMum(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Mum is new, it will return
     * an empty collection; or if this Mum has previously
     * been saved, it will retrieve related MumBears from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Mum.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return Collection|ChildMumBear[] List of ChildMumBear objects
     */
    public function getMumBearsJoinBear($criteria = null, $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildMumBearQuery::create(null, $criteria);
        $query->joinWith('Bear', $joinBehavior);

        return $this->getMumBears($query, $con);
    }

    /**
     * Clears out the collBears collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addBears()
     */
    public function clearBears()
    {
        $this->collBears = null; // important to set this to NULL since that means it is uninitialized
        $this->collBearsPartial = null;
    }

    /**
     * Initializes the collBears collection.
     *
     * By default this just sets the collBears collection to an empty collection (like clearBears());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initBears()
    {
        $this->collBears = new ObjectCollection();
        $this->collBears->setModel('\Bear');
    }

    /**
     * Gets a collection of ChildBear objects related by a many-to-many relationship
     * to the current object by way of the mum_bear cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildMum is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildBear[] List of ChildBear objects
     */
    public function getBears($criteria = null, ConnectionInterface $con = null)
    {
        if (null === $this->collBears || null !== $criteria) {
            if ($this->isNew() && null === $this->collBears) {
                // return empty collection
                $this->initBears();
            } else {
                $collBears = ChildBearQuery::create(null, $criteria)
                    ->filterByMum($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collBears;
                }
                $this->collBears = $collBears;
            }
        }

        return $this->collBears;
    }

    /**
     * Sets a collection of Bear objects related by a many-to-many relationship
     * to the current object by way of the mum_bear cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $bears A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return ChildMum The current object (for fluent API support)
     */
    public function setBears(Collection $bears, ConnectionInterface $con = null)
    {
        $this->clearBears();
        $currentBears = $this->getBears();

        $this->bearsScheduledForDeletion = $currentBears->diff($bears);

        foreach ($bears as $bear) {
            if (!$currentBears->contains($bear)) {
                $this->doAddBear($bear);
            }
        }

        $this->collBears = $bears;

        return $this;
    }

    /**
     * Gets the number of ChildBear objects related by a many-to-many relationship
     * to the current object by way of the mum_bear cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related ChildBear objects
     */
    public function countBears($criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        if (null === $this->collBears || null !== $criteria) {
            if ($this->isNew() && null === $this->collBears) {
                return 0;
            } else {
                $query = ChildBearQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByMum($this)
                    ->count($con);
            }
        } else {
            return count($this->collBears);
        }
    }

    /**
     * Associate a ChildBear object to this object
     * through the mum_bear cross reference table.
     *
     * @param  ChildBear $bear The ChildMumBear object to relate
     * @return ChildMum The current object (for fluent API support)
     */
    public function addBear(ChildBear $bear)
    {
        if ($this->collBears === null) {
            $this->initBears();
        }

        if (!$this->collBears->contains($bear)) { // only add it if the **same** object is not already associated
            $this->doAddBear($bear);
            $this->collBears[] = $bear;
        }

        return $this;
    }

    /**
     * @param    Bear $bear The bear object to add.
     */
    protected function doAddBear($bear)
    {
        $mumBear = new ChildMumBear();
        $mumBear->setBear($bear);
        $this->addMumBear($mumBear);
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$bear->getMums()->contains($this)) {
            $foreignCollection   = $bear->getMums();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a ChildBear object to this object
     * through the mum_bear cross reference table.
     *
     * @param ChildBear $bear The ChildMumBear object to relate
     * @return ChildMum The current object (for fluent API support)
     */
    public function removeBear(ChildBear $bear)
    {
        if ($this->getBears()->contains($bear)) {
            $this->collBears->remove($this->collBears->search($bear));

            if (null === $this->bearsScheduledForDeletion) {
                $this->bearsScheduledForDeletion = clone $this->collBears;
                $this->bearsScheduledForDeletion->clear();
            }

            $this->bearsScheduledForDeletion[] = $bear;
        }

        return $this;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->customer_id = null;
        $this->backing_id = null;
        $this->accent_bow_id = null;
        $this->letter1_id = null;
        $this->name_ribbon1 = null;
        $this->letter2_id = null;
        $this->name_ribbon2 = null;
        $this->status_id = null;
        $this->paid = null;
        $this->order_date = null;
        $this->paid_date = null;
        $this->deposit_sale_id = null;
        $this->paid_sale_id = null;
        $this->delivery_date = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
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
            if ($this->collMumTrinkets) {
                foreach ($this->collMumTrinkets as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collMumBears) {
                foreach ($this->collMumBears as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collBears) {
                foreach ($this->collBears as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        if ($this->collMumTrinkets instanceof Collection) {
            $this->collMumTrinkets->clearIterator();
        }
        $this->collMumTrinkets = null;
        if ($this->collMumBears instanceof Collection) {
            $this->collMumBears->clearIterator();
        }
        $this->collMumBears = null;
        if ($this->collBears instanceof Collection) {
            $this->collBears->clearIterator();
        }
        $this->collBears = null;
        $this->aCustomer = null;
        $this->aBacking = null;
        $this->aAccentBow = null;
        $this->aLetter = null;
        $this->aStatus = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(MumTableMap::DEFAULT_STRING_FORMAT);
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
