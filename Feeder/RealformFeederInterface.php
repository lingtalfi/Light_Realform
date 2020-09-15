<?php


namespace Ling\Light_Realform\Feeder;

/**
 * The RealformFeederInterface interface.
 */
interface RealformFeederInterface
{


    /**
     * Prepares the feeder instance.
     *
     * @param array $conf
     */
    public function prepare(array $conf): void;


    /**
     * Returns the default values of the form.
     * @return array
     */
    public function getDefaultValues(): array;
}