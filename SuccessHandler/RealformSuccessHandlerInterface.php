<?php


namespace Ling\Light_Realform\SuccessHandler;


/**
 * The RealformSuccessHandlerInterface interface.
 */
interface RealformSuccessHandlerInterface
{


    /**
     * Prepares the instance before calling the execute method.
     *
     * @param array $conf
     * @return void
     */
    public function prepare(array $conf);

    /**
     * Process the given data, and throws an exception if something unexpected happens.
     *
     * It is assumed that the given data is valid (it's generally posted by the user
     * and validated by some validation rules first before it arrives here).
     *
     * Available options are:
     * - updateRic: array|false=false, see @page(the updateRic definition in the Light_Realform conception notes) for more details.
     *      It's false if the form is not in update mode.
     * - storageId: string=null, the storage id that you defined in your configuration file.
     * - ...or you can add your own options
     *
     *
     *
     *
     * If an exception is thrown, it's message shall be displayed to the user.
     *
     *
     *
     *
     * @param array $data
     * @param array $options
     * @return void
     */
    public function execute(array $data, array $options = []);


}