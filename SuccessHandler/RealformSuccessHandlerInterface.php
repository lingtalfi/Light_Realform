<?php


namespace Ling\Light_Realform\SuccessHandler;


use Ling\Chloroform\Form\Chloroform;

/**
 * The RealformSuccessHandlerInterface interface.
 */
interface RealformSuccessHandlerInterface
{

    /**
     * Process the given data, and throws an exception if something unexpected happens.
     *
     *
     * Note: the form instance is available as an argument so that you can set properties if needed.
     *
     * @param array $data
     * @param Chloroform $form
     * @param array $options
     * @return mixed
     */
    public function processData(array $data, Chloroform $form, array $options = []);
}