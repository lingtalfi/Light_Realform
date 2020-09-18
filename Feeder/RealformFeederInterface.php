<?php


namespace Ling\Light_Realform\Feeder;

/**
 * The RealformFeederInterface interface.
 */
interface RealformFeederInterface
{


    /**
     * Returns the default values of the form.
     * @param array $params
     * @return array
     */
    public function getDefaultValues(array $params = []): array;
}