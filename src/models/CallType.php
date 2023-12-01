<?php

namespace IP800\models;

class CallType
{
    /**
     * @var CallType
     */
    public static $INTERN;
    /**
     * @var CallType
     */
    public static $OUT_GOING;
    /**
     * @var CallType
     */
    public static $IN_COMING;
    /**
     * @var CallType
     */
    public static $UNKNOWN;
    /**
     * @var int|int
     */
    private $id;
    /**
     * @var string
     */
    public $name;

    /**
     * @param int $id
     */
    public function __construct($id)
    {
        $this->id = $id;
        switch ($id) {
            case 1: $this->name = "INTERN"; break;
            case 2: $this->name = "OUT_GOING"; break;
            case 3: $this->name = "IN_COMING"; break;
            case 4: $this->name = "UNKNOWN"; break;
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }



    static function init()
    {
        self::$INTERN = new CallType(1);
        self::$OUT_GOING = new CallType(2);
        self::$IN_COMING = new CallType(3);
        self::$UNKNOWN = new CallType(4);
    }
}

CallType::init();