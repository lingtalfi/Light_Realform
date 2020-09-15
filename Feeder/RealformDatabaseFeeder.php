<?php


namespace Ling\Light_Realform\Feeder;

use Ling\Light\ServiceContainer\LightServiceContainerAwareInterface;
use Ling\Light\ServiceContainer\LightServiceContainerInterface;
use Ling\Light_Realform\Exception\LightRealformException;
use Ling\SimplePdoWrapper\SimplePdoWrapper;
use Ling\SimplePdoWrapper\SimplePdoWrapperInterface;

/**
 * The RealformDatabaseFeeder class.
 */
class RealformDatabaseFeeder implements RealformFeederInterface, LightServiceContainerAwareInterface
{

    /**
     * This property holds the updateRic for this instance.
     * @var array
     */
    protected $updateRic;

    /**
     * This property holds the table for this instance.
     * @var string
     */
    protected $table;


    /**
     * This property holds the container for this instance.
     * @var LightServiceContainerInterface
     */
    protected $container;


    /**
     * Builds the RealformDatabaseFeeder instance.
     */
    public function __construct()
    {
        $this->updateRic = null;
        $this->table = null;
        $this->container = null;
    }

    //--------------------------------------------
    // LightServiceContainerAwareInterface
    //--------------------------------------------
    /**
     * @implementation
     */
    public function setContainer(LightServiceContainerInterface $container)
    {
        $this->container = $container;
    }


    //--------------------------------------------
    // RealformFeederInterface
    //--------------------------------------------
    /**
     * @implementation
     */
    public function prepare(array $conf): void
    {
        $this->updateRic = $conf['updateRic'] ?? null;
        if (false === array_key_exists("storage_id", $conf)) {
            $this->error("storage_id parameter is mandatory.");
        }
        $this->table = $conf['storage_id'];
    }

    /**
     * @implementation
     */
    public function getDefaultValues(): array
    {
        $ret = [];
        if (null !== $this->updateRic) {
            /**
             * @var $db SimplePdoWrapperInterface
             */
            $db = $this->container->get("database");
            $query = "select * from `$this->table`";
            $markers = [];
            SimplePdoWrapper::addWhereSubStmt($query, $markers, $this->updateRic);
            $row = $db->fetch($query, $markers);

            if (false !== $row) {
                $ret = $row;
            }
        }
        return $ret;
    }



    //--------------------------------------------
    //
    //--------------------------------------------
    /**
     * Throws an exception.
     * @param string $msg
     * @throws \Exception
     */
    private function error(string $msg)
    {
        throw new LightRealformException($msg);
    }
}