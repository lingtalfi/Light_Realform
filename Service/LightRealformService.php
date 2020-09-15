<?php


namespace Ling\Light_Realform\Service;

use Ling\ArrayToString\ArrayToStringTool;
use Ling\Bat\ArrayTool;
use Ling\Bat\SmartCodeTool;
use Ling\Bat\StringTool;
use Ling\Bat\UriTool;
use Ling\Chloroform\Field\CSRFField;
use Ling\Chloroform\Field\DecorativeField;
use Ling\Chloroform\Field\FieldInterface;
use Ling\Chloroform\Field\PasswordField;
use Ling\Chloroform\Form\Chloroform;
use Ling\Chloroform\FormNotification\ErrorFormNotification;
use Ling\Chloroform\Validator\CSRFValidator;
use Ling\Chloroform\Validator\FileMimeTypeValidator;
use Ling\Chloroform\Validator\PasswordConfirmValidator;
use Ling\Chloroform\Validator\PasswordValidator;
use Ling\Chloroform\Validator\ValidatorInterface;
use Ling\Light\Events\LightEvent;
use Ling\Light\Http\HttpResponseInterface;
use Ling\Light\ServiceContainer\LightServiceContainerAwareInterface;
use Ling\Light\ServiceContainer\LightServiceContainerInterface;
use Ling\Light_ChloroformExtension\Field\TableListField;
use Ling\Light_Events\Service\LightEventsService;
use Ling\Light_Flasher\Service\LightFlasherService;
use Ling\Light_MicroPermission\Service\LightMicroPermissionService;
use Ling\Light_Nugget\Service\LightNuggetService;
use Ling\Light_Realform\DynamicInjection\RealformDynamicInjectionHandlerInterface;
use Ling\Light_Realform\Exception\LightRealformException;
use Ling\Light_Realform\Feeder\RealformDatabaseFeeder;
use Ling\Light_Realform\Feeder\RealformFeederInterface;
use Ling\Light_Realform\Handler\RealformHandlerInterface;
use Ling\Light_Realform\Result\RealformResult;
use Ling\Light_Realform\SuccessHandler\RealformSuccessHandlerInterface;
use Ling\Light_Realform\SuccessHandler\ToDatabaseSuccessHandler;
use Ling\Light_User\LightWebsiteUser;
use Ling\Light_UserManager\Service\LightUserManagerService;
use Ling\WiseTool\WiseTool;

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
     * This property holds the container for this instance.
     * @var LightServiceContainerInterface
     */
    protected $container;


    /**
     * This property holds the dynamicInjectionHandlers for this instance.
     * It's an array of identifier => RealformDynamicInjectionHandlerInterface
     *
     * Usually the identifier is a plugin name.
     *
     * @var RealformDynamicInjectionHandlerInterface[]
     */
    protected $dynamicInjectionHandlers;


    /**
     * Builds the LightRealformService instance.
     */
    public function __construct()
    {
        $this->handlers = [];
        $this->dynamicInjectionHandlers = [];
        $this->container = null;
    }


    /**
     * Creates the chloroform from the config nugget identified by the given nuggetId,
     * then execute our @page(form handling system a algorithm), and returns the chloroform
     * instance.
     *
     *
     * More info in the @page(Light_Realform conception notes).
     *
     *
     * Available options are:
     * - onSuccess: callable (array validPostedData).
     *      See more info in the @page(Light_Realform conception notes, the executeRealform method section).
     *
     *
     *
     * @param string $nuggetId
     * @param array $options
     * @return RealformResult
     * @throws \Exception
     */
    public function executeRealform(string $nuggetId, array $options = []): RealformResult
    {

        $realformResult = new RealformResult();

        /**
         * @var $nug LightNuggetService
         */
        $nug = $this->container->get("nugget");
        $nugget = $nug->getNugget($nuggetId, "Light_Realform/form");

        $formConf = $nugget['chloroform'] ?? [];
        $formId = $formConf['id'] ?? 'formid-' . $nuggetId;
        $instance = new Chloroform();
        $instance->setFormId($formId);


        //--------------------------------------------
        // GET THE CHLOROFORM INSTANCE
        //--------------------------------------------

        // dynamic injection phase
        //--------------------------------------------
        /**
         * @var $realformService LightRealformService
         */
        $realformService = $this->container->get("realform");
        SmartCodeTool::replaceSmartCodeFunction($formConf, "REALFORM", function ($identifier) use ($realformService) {
            $handler = $realformService->getDynamicInjectionHandler($identifier);
            $args = func_get_args();
            array_shift($args);
            return $handler->handle($args);
        });


        // adding fields
        //--------------------------------------------
        if (array_key_exists("fields", $formConf)) {
            $fields = $formConf['fields'];
            foreach ($fields as $identifier => $fieldConf) {

                // preparing the properties array of the field (see AbstractField constructor for more details)
                $type = $fieldConf['type'];
                unset($fieldConf['type']);

                $field = $this->getChloroformField($instance, $type, $identifier, $fieldConf);

                $validators = [];
                if (array_key_exists("validators", $fieldConf)) {
                    foreach ($fieldConf['validators'] as $validatorType => $validatorConf) {
                        $validators[] = $this->getChloroformValidator($validatorType, $validatorConf);
                    }
                }
                $instance->addField($field, $validators);


                // deprecated: use js to convert the data directly instead, see conception notes for more details
//                if (array_key_exists('dataTransformer', $fieldConf)) {
//                    $dataTransformerValue = $fieldConf['dataTransformer'];
//                    $field->setDataTransformer($this->getDataTransformer($dataTransformerValue));
//                }


            }
        }


        //--------------------------------------------
        // EXECUTING "Form handling system A"
        //--------------------------------------------
        $realformResult->setChloroform($instance);
        $this->handleFormSystemA($nugget, $realformResult, $instance, $options);


        return $realformResult;
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

    /**
     * Registers a @page(dynamic injection handler).
     * @param string $identifier
     * @param RealformDynamicInjectionHandlerInterface $handler
     */
    public function registerDynamicInjectionHandler(string $identifier, RealformDynamicInjectionHandlerInterface $handler)
    {
        $this->dynamicInjectionHandlers[$identifier] = $handler;
    }

    /**
     * Returns the realform dynamic injection handler associated with the given identifier,
     * or throws an exception if it's not there.
     *
     * @param string $identifier
     * @return RealformDynamicInjectionHandlerInterface
     * @throws \Exception
     */
    public function getDynamicInjectionHandler(string $identifier): RealformDynamicInjectionHandlerInterface
    {
        if (array_key_exists($identifier, $this->dynamicInjectionHandlers)) {
            $handler = $this->dynamicInjectionHandlers[$identifier];
            if ($handler instanceof LightServiceContainerAwareInterface) {
                $handler->setContainer($this->container);
            }
            return $handler;
        }
        throw new LightRealformException("Dynamic injection handler not found with identifier $identifier.");
    }


    /**
     * Sets the container.
     *
     * @param LightServiceContainerInterface $container
     */
    public function setContainer(LightServiceContainerInterface $container)
    {
        $this->container = $container;
    }


    /**
     * Returns the current valid website user, or throws an exception.
     *
     * Note: for now we use the website user exclusively, but that's an implementation
     * detail (i.e. not specified anywhere in the docs), and might changed in the future.
     *
     * @return LightWebsiteUser
     * @throws \Exception
     */
    public function getCurrentWebsiteUser(): LightWebsiteUser
    {
        /**
         * @var $um LightUserManagerService
         */
        $um = $this->container->get("user_manager");
        $user = $um->getUser();
        if (false === $user->isValid()) {
            $this->error("User is not valid.");
        }
        if (false === $user instanceof LightWebsiteUser) {
            $this->error("User is not a website user.");
        }
        return $user;
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


    //--------------------------------------------
    //
    //--------------------------------------------

    /**
     * Returns a chloroform field.
     * Note: fields from the @page(Light_ChloroformExtension plugin) also work.
     *
     * @param Chloroform $form
     * @param string $type
     * @param string $fieldId
     * @param array $fieldConf
     * @return FieldInterface
     * @throws \Exception
     */
    protected function getChloroformField(Chloroform $form, string $type, string $fieldId, array $fieldConf = []): FieldInterface
    {
        $fieldConf['id'] = $fieldId;


        switch ($type) {
            case "ajaxFileBox":
            case "color":
            case "date":
            case "datetime":
            case "file":
            case "hidden":
            case "number":
            case "string":
            case "text":
            case "time":
                $class = "Ling\Chloroform\Field\\" . ucfirst($type) . "Field";
                $field = new $class($fieldConf);
                break;
            case "checkbox":
            case "radio":
            case "select":
                $class = "Ling\Chloroform\Field\\" . ucfirst($type) . "Field";
                $field = new $class($fieldConf);
                if (array_key_exists("items", $fieldConf)) {
                    $field->setItems($fieldConf['items']);
                }
                break;
            case "csrf":
                $field = new CSRFField($fieldConf);
                if (array_key_exists("csrfIdentifier", $fieldConf)) {
                    $field->setCsrfIdentifier($fieldConf['csrfIdentifier']);
                }
                if (array_key_exists("csrfProtector", $fieldConf)) {
                    $field->setCsrfProtector(new $fieldConf['csrfProtector']);
                }
                break;
            case "password":
                $field = new PasswordField($fieldConf);
                $field->setForm($form);
                break;
            case "decorative":
                $field = new DecorativeField($fieldConf);
                break;
            case "table_list":
                $field = new TableListField($fieldConf);
                $field->setContainer($this->container);
                break;
            default:
                throw new LightRealformException("Unknown field type \"$type\" with id $fieldId.");
                break;
        }


        return $field;
    }


    /**
     * Returns a validator instance.
     *
     * @param string $type
     * @param array $validatorConf
     * @return ValidatorInterface
     * @throws \Exception
     */
    protected function getChloroformValidator(string $type, array $validatorConf): ValidatorInterface
    {
        switch ($type) {
            case "csrf":
                $validator = new CSRFValidator();
                if (array_key_exists("csrfProtector", $validatorConf)) {
                    $validator->setCsrfProtector(new $validatorConf['csrfProtector']);
                }
                break;
            case "fileMimeType":
                $validator = new FileMimeTypeValidator();
                if (array_key_exists("allowedMimeTypes", $validatorConf)) {
                    $validator->setMimeTypes($validatorConf['allowedMimeTypes']);
                }
                break;
            case "minMaxChar":
            case "minMaxDate":
            case "minMaxFileSize":
            case "minMaxItem":
            case "minMaxNumber":
                $class = "Ling\Chloroform\Validator\\" . ucfirst($type) . "Validator";
                $validator = new $class();
                if (array_key_exists("min", $validatorConf)) {
                    $validator->setMin($validatorConf['min']);
                }
                if (array_key_exists("max", $validatorConf)) {
                    $validator->setMax($validatorConf['max']);
                }
                break;
            case "passwordConfirm":
                $validator = new PasswordConfirmValidator();
                if (array_key_exists("otherFieldId", $validatorConf)) {
                    $validator->setOtherFieldId($validatorConf['otherFieldId']);
                }
                break;
            case "password":
                $validator = new PasswordValidator();
                if (array_key_exists("nbAlpha", $validatorConf)) {
                    $validator->setNbAlpha($validatorConf['nbAlpha']);
                }
                if (array_key_exists("nbAlphaLower", $validatorConf)) {
                    $validator->setNbAlphaLower($validatorConf['nbAlphaLower']);
                }
                if (array_key_exists("nbAlphaUpper", $validatorConf)) {
                    $validator->setNbAlphaUpper($validatorConf['nbAlphaUpper']);
                }
                if (array_key_exists("nbDigits", $validatorConf)) {
                    $validator->setNbDigits($validatorConf['nbDigits']);
                }
                if (array_key_exists("nbSpecial", $validatorConf)) {
                    $validator->setNbSpecial($validatorConf['nbSpecial']);
                }
                break;
            case "requiredDate":
            case "requiredDatetime":
            case "required":
                $class = "Ling\Chloroform\Validator\\" . ucfirst($type) . "Validator";
                $validator = new $class();
                break;
            default:


                /**
                 * @var $aliasHelper LightRealformHandlerAliasHelperService
                 */
                $aliasHelper = $this->container->get("realform_handler_alias_helper");
                $val = $aliasHelper->getChloroformValidator($type, $validatorConf);

                if (null !== $val) {
                    $validator = $val;
                } else {
                    throw new LightRealformException("Unknown validator class with type/id $type.");
                }
                break;
        }


        if (array_key_exists("errorMessage", $validatorConf)) {
            $errMsg = $validatorConf['errorMessage'];
            $msgIdentifier = null;
            if (is_array($errMsg)) {
                list($errMsg, $msgIdentifier) = $errMsg;
            }
            $validator->setErrorMessage($errMsg, $msgIdentifier);
        }

        return $validator;
    }




//    /**
//     * Returns a dataTransformer instance.
//     *
//     * @param $value
//     * @return DataTransformerInterface
//     * @throws \Exception
//     */
//    protected function getDataTransformer($value): DataTransformerInterface
//    {
//        $transformer = null;
//        if (is_string($value)) {
//            /**
//             * @var $aliasHelper LightRealformHandlerAliasHelperService
//             */
//            $aliasHelper = $this->container->get("realform_handler_alias_helper");
//            $params = [];
//            $trans = $aliasHelper->getDataTransformer($value, $params);
//            if (null !== $trans) {
//                $transformer = $trans;
//            }
//        } else {
//            $this->error("Not handled yet with a non string value.");
//        }
//
//
//        if (null !== $transformer) {
//            return $transformer;
//        } else {
//            $sVal = DebugTool::toString($value);
//            throw new LightRealformException("Cannot find the dataTransformer with the value $sVal.");
//        }
//    }


    /**
     * Applies a standard routine to the form identified by the given realformIdentifier,
     * and returns a chloroform instance.
     *
     *
     * The update mode is triggered if the ric strict columns are passed in the url (i.e. $_GET).
     *
     *
     * What does this method do?
     * ----------------
     *
     * It creates the form, using realform,
     * it handles both the form insert and update actions.
     *
     * If the form is posted correctly, either:
     *
     * - an @page(iframe signal) is triggered (if defined in the options)
     * - the posted data are handled using the on_success_handler (defined by the realform configuration),
     *              and a success callback can also be triggered (if defined in the options).
     *              That success callback (from the options) can return a http response to use directly.
     *
     *
     *
     *
     * Errors and success messages are handled using the @page(flash service).
     *
     *
     * Available options are:
     *
     * - iframeSignal; an @page(iframe signal) to use instead of the default success handler
     * - onSuccess: a success callback to trigger when the form was successfully posted (in addition to the
     *      success handler defined in the configuration). This applies only if the iframeSignal is not set
     *
     *
     *
     *
     * @param array $nugget
     * @param RealformResult $realformResult
     * @param Chloroform $form
     * @param array $options
     * @return Chloroform|HttpResponseInterface
     * @throws \Exception
     */
    protected function handleFormSystemA(array $nugget, RealformResult $realformResult, Chloroform $form, array $options = [])
    {

        /**
         * Warning: I've removed the reimplementation for the following concepts, which were implemented in the previous version
         * of this class: LightRealformRoutineOne:
         *
         * - iframe signal concept: https://github.com/lingtalfi/TheBar/blob/master/discussions/iframe-signal.md
         * - multiplier trick used in the table list field: https://github.com/lingtalfi/Light_ChloroformExtension/blob/master/doc/pages/conception-notes.md
         *
         * You might want to add them back when needed.
         */
        $ric = $nugget['ric'] ?? false;
        $storageId = $nugget['storage_id'];
        $formId = $form->getFormId();

        //--------------------------------------------
        // INSERT/UPDATE SWITCH
        //--------------------------------------------
        /**
         * For now, if ric exists in the url, then it's an update, otherwise it's an insert.
         */
        $isUpdate = false;
        $updateRic = null;
        $executeFormAlgo = true;

        if (false !== $ric) {
            if (true === ArrayTool::arrayKeyExistAll($ric, $_GET)) {
                $isUpdate = true;
                $updateRic = ArrayTool::intersect($_GET, $ric);
            }
        }



        //--------------------------------------------
        // FORM & SECURITY CHECK ONE: THE RECOMMENDED MICRO-PERMS
        //--------------------------------------------
        /**
         * @var $mp LightMicroPermissionService
         */
        $mp = $this->container->get("micro_permission");
        $mp->checkMicroPermission("store.$storageId.read");
        if (true === $isUpdate) {
            $form->setMode("update");
            $mp->checkMicroPermission("store.$storageId.update");
        } else {
            $form->setMode("insert");
            $mp->checkMicroPermission("store.$storageId.create");
        }



        //--------------------------------------------
        // SECURITY CHECK TWO: THE SECURITY NUGGET
        //--------------------------------------------
        $securityParams = [];
        if (true === $isUpdate) {

            $securityParams['updateRic'] = $updateRic;
            $securityParams['storageId'] = $storageId;
        }
        /**
         * @var $ng LightNuggetService
         */
        $ng = $this->container->get("nugget");
        try {
            $ng->checkSecurity($nugget, $securityParams);
        } catch (\Exception $e) {
            $executeFormAlgo = false;
            $form->addNotification(ErrorFormNotification::create($e->getMessage()));
        }






        /**
         * @var $flasher LightFlasherService
         */
        $flasher = $this->container->get('flasher');


        if (true === $executeFormAlgo) {


            //--------------------------------------------
            // Posting the form and validating data
            //--------------------------------------------
            if (true === $form->isPosted()) {
                if (true === $form->validates()) {
                    // do something with $postedData;
                    $data = $form->getVeryImportantData();


//                $form->executeDataTransformers($vid);


                    $formIsHandledSuccessfully = true;

                    //--------------------------------------------
                    // DO SOMETHING WITH THE DATA...
                    //--------------------------------------------
                    try {


                        //--------------------------------------------
                        // DO WE USE A SUCCESS HANDLER FROM THE CONF?
                        //--------------------------------------------
                        $successHandlerConf = $nugget['success_handler'] ?? [];
                        $params = $successHandlerConf['params'] ?? [];
                        $successHandler = null;
                        if (array_key_exists('class', $successHandlerConf)) {
                            $className = $successHandlerConf['class'];
                            if ('defaultDbHandler' === $className) {
                                $successHandler = new ToDatabaseSuccessHandler();
                            } else {
                                $successHandler = new $successHandlerConf['class'];
                            }


                            if (false === $successHandler instanceof RealformSuccessHandlerInterface) {
                                $type = gettype($successHandler);
                                $this->error("The success handler instance must be an instance of RealformSuccessHandlerInterface, $type given.");
                            }
                            if ($successHandler instanceof LightServiceContainerAwareInterface) {
                                $successHandler->setContainer($this->container);
                            }


                            $successHandler->prepare($params);
                        }


                        if (null !== $successHandler) {
                            $successOptions = [
                                "storageId" => $nugget['storage_id'] ?? null,
                            ];
                            if (true === $isUpdate) {
                                $successOptions["updateRic"] = $updateRic;
                            }
                            $successHandler->execute($data, $successOptions);
                        }


                    } catch (\Exception $e) {
                        $formIsHandledSuccessfully = false;

                        $form->addNotification(ErrorFormNotification::create($e->getMessage()));

                        // dispatch the exception (to allow deeper investigation)
                        /**
                         * @var $events LightEventsService
                         */
                        $events = $this->container->get("events");
                        $data = LightEvent::createByContainer($this->container);
                        $data->setVar('exception', $e);
                        /**
                         * Note from the Light_RealGenerator authors: we chose to use our plugin name as the handler
                         * rather than the host plugin, because it would be more practical for plugins
                         * like Light_ExceptionHandler (which dispatching below is mainly intended to) to deal with.
                         */
                        $events->dispatch("Light_RealGenerator.on_realform_exception_caught", $data);
                    }


                    //--------------------------------------------
                    // REDIRECT
                    //--------------------------------------------
                    if (true === $formIsHandledSuccessfully) {

                        $realformResult->setIsSuccessful(true);
                        $realformResult->setValidPostedData($data);


                        /**
                         * We prepare the redirect here.
                         * Redirect is good because the user data is used in the gui (for instance in the icon menu in the header).
                         * And so if the user changed her avatar for instance, we want her to notice the changes right away.
                         * Hence we redirect to the same page.
                         *
                         * Or even a simpler use case: an update form posted successfully: you want
                         * the new/updated values to show up (and depending on when your form handler
                         * injects the values into the template, you might need a redirect here too to
                         * refresh the page values).
                         *
                         */

                        /**
                         * Also, if it's an update, the ric params are in the $_GET (and in the url), and so if we were just
                         * refreshing the page (which is what the redirect basically will do) we would have the old ric
                         * parameters displayed in the form, which is not what we want: we want the refreshed form to
                         * reflect the newest changes, including changes in the ric.
                         * So, we just override the ric in $_GET, so that the new page refreshes with the new rics.
                         */
                        $urlParams = $_GET;
                        $urlParams['t'] = time(); // make sure the browser will think it's a new page (t is reserved, this is indicated in our docs)

                        if (true === $isUpdate) {
                            foreach ($data as $k => $v) {
                                if (in_array($k, $ric, true) && array_key_exists($k, $urlParams)) {
                                    $urlParams[$k] = $v; // note: if you implement the form multiplier trick, you might need to change this, see my warning at the beginning of this method.
                                }
                            }
                        }


                        $messages = $nugget['success_messages'] ?? null;
                        if (true === $isUpdate) {
                            $message = $messages['update'] ?? null;
                            if (null === $message) {
                                $message = "The form has been successfully updated.";
                            }
                            $sRic = StringTool::toCsv($updateRic);
                            $message = str_replace('{sRic}', $sRic, $message);
                        } else {
                            $message = $messages['create'] ?? null;
                            if (null === $message) {
                                $message = "The form has been successfully posted.";
                            }
                        }

                        $flasher->addFlash($formId, $message);
                        $url = UriTool::getCurrentUrl();
                        $redirectUrl = UriTool::uri($url, $urlParams);
                        $realformResult->setRedirectionUrl($redirectUrl);
                    }

                } else {
                    // the form doesn't validate
//                $form->addNotification(ErrorFormNotification::create("There was a problem."));
                }
            } else {
                //--------------------------------------------
                // DEFAULT VALUES
                //--------------------------------------------
                $feeder = $nugget['feeder'] ?? null;
                if (false !== $feeder) {
                    if (null === $feeder) {
                        $feeder = new RealformDatabaseFeeder();
                    } else {
                        $feeder = new $feeder();
                    }
                    if ($feeder instanceof LightServiceContainerAwareInterface) {
                        $feeder->setContainer($this->container);
                    }


                    if (false === $feeder instanceof RealformFeederInterface) {
                        $type = gettype($feeder);
                        $this->error("The feeder instance must be a RealformFeederInterface, $type passed.");
                    }

                    $feederParams = [];
                    if (array_key_exists("storage_id", $nugget)) {
                        $feederParams['storage_id'] = $nugget["storage_id"];
                    }
                    if (true === $isUpdate) {
                        $feederParams['updateRic'] = $updateRic;
                    }
                    $feeder->prepare($feederParams);
                    $defaultValues = $feeder->getDefaultValues();
                    $form->injectValues($defaultValues);
                }


                if ($flasher->hasFlash($formId)) {
                    list($message, $type) = $flasher->getFlash($formId);
                    $form->addNotification(WiseTool::wiseToChloroform($type, $message));
                }

            }
        }


        return $form;
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