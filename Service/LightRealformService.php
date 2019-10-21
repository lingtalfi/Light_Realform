<?php


namespace Ling\Light_Realform\Service;

use Ling\Light_Realform\Exception\LightRealformException;
use Ling\Light_Realform\Handler\RealformHandlerInterface;

/**
 * The LightRealformService class.
 */
class LightRealformService
{


    /**
     * This property holds the handlers for this instance.
     * It's an array of pluginName => RealformHandlerInterface
     * @var RealformHandlerInterface[]
     */
    protected $handlers;


    /**
     * Builds the LightRealformService instance.
     */
    public function __construct()
    {
        $this->handlers = [];
    }


    /**
     * Returns the realform handler instance corresponding to the given identifier.
     *
     * The identifier notation is:
     *
     * - identifier: {pluginName}.{id}
     *
     * More info in the @page(conception notes).
     *
     *
     * @param string $identifier
     * @return RealformHandlerInterface
     * @throws \Exception
     */
    public function getFormHandler(string $identifier): RealformHandlerInterface
    {
        $p = explode(".", $identifier, 2);
        if (2 === count($p)) {
            list($pluginName, $id) = $p;
            if (array_key_exists($pluginName, $this->handlers)) {
                $realformHandler = $this->handlers[$pluginName];
                $realformHandler->setId($id);
                return $realformHandler;
            }
            throw new LightRealformException("Form handler not found with identifier $identifier.");
        }
        throw new LightRealformException("Invalid realform identifier $identifier.");
    }


    /**
     * Registers a realform handler.
     *
     * @param string $pluginName
     * @param RealformHandlerInterface $formHandler
     */
    public function registerFormHandler(string $pluginName, RealformHandlerInterface $formHandler)
    {
        $this->handlers[$pluginName] = $formHandler;
    }




    //--------------------------------------------
    //
    //--------------------------------------------
    /**
     * Sets the handlers.
     *
     * @param RealformHandlerInterface[] $handlers
     */
    public function setHandlers(array $handlers)
    {
        $this->handlers = $handlers;
    }
}