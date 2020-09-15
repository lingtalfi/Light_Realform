[Back to the Ling/Light_Realform api](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform.md)



The LightRealformService class
================
2019-10-21 --> 2020-09-15






Introduction
============

The LightRealformService class.



Class synopsis
==============


class <span class="pl-k">LightRealformService</span>  {

- Properties
    - protected [Ling\Light_Realform\Handler\RealformHandlerInterface[]](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Handler/RealformHandlerInterface.md) [$handlers](#property-handlers) ;
    - protected [Ling\Light\ServiceContainer\LightServiceContainerInterface](https://github.com/lingtalfi/Light/blob/master/doc/api/Ling/Light/ServiceContainer/LightServiceContainerInterface.md) [$container](#property-container) ;
    - protected [Ling\Light_Realform\DynamicInjection\RealformDynamicInjectionHandlerInterface[]](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/DynamicInjection/RealformDynamicInjectionHandlerInterface.md) [$dynamicInjectionHandlers](#property-dynamicInjectionHandlers) ;

- Methods
    - public [__construct](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Service/LightRealformService/__construct.md)() : void
    - public [executeRealform](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Service/LightRealformService/executeRealform.md)(string $nuggetId, ?array $options = []) : [RealformResult](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Result/RealformResult.md)
    - public [registerFormHandler](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Service/LightRealformService/registerFormHandler.md)(string $pluginName, [Ling\Light_Realform\Handler\RealformHandlerInterface](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Handler/RealformHandlerInterface.md) $formHandler) : void
    - public [registerDynamicInjectionHandler](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Service/LightRealformService/registerDynamicInjectionHandler.md)(string $identifier, [Ling\Light_Realform\DynamicInjection\RealformDynamicInjectionHandlerInterface](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/DynamicInjection/RealformDynamicInjectionHandlerInterface.md) $handler) : void
    - public [getDynamicInjectionHandler](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Service/LightRealformService/getDynamicInjectionHandler.md)(string $identifier) : [RealformDynamicInjectionHandlerInterface](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/DynamicInjection/RealformDynamicInjectionHandlerInterface.md)
    - public [setContainer](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Service/LightRealformService/setContainer.md)([Ling\Light\ServiceContainer\LightServiceContainerInterface](https://github.com/lingtalfi/Light/blob/master/doc/api/Ling/Light/ServiceContainer/LightServiceContainerInterface.md) $container) : void
    - public [getCurrentWebsiteUser](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Service/LightRealformService/getCurrentWebsiteUser.md)() : [LightWebsiteUser](https://github.com/lingtalfi/Light_User/blob/master/doc/api/Ling/Light_User/LightWebsiteUser.md)
    - public [setHandlers](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Service/LightRealformService/setHandlers.md)(array $handlers) : void
    - protected [getChloroformField](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Service/LightRealformService/getChloroformField.md)([Ling\Chloroform\Form\Chloroform](https://github.com/lingtalfi/Chloroform) $form, string $type, string $fieldId, ?array $fieldConf = []) : [FieldInterface](https://github.com/lingtalfi/Chloroform/blob/master/doc/api/Ling/Chloroform/Field/FieldInterface.md)
    - protected [getChloroformValidator](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Service/LightRealformService/getChloroformValidator.md)(string $type, array $validatorConf) : [ValidatorInterface](https://github.com/lingtalfi/Chloroform/blob/master/doc/api/Ling/Chloroform/Validator/ValidatorInterface.md)
    - protected [handleFormSystemA](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Service/LightRealformService/handleFormSystemA.md)(array $nugget, [Ling\Light_Realform\Result\RealformResult](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Result/RealformResult.md) $realformResult, [Ling\Chloroform\Form\Chloroform](https://github.com/lingtalfi/Chloroform) $form, ?array $options = []) : [Chloroform](https://github.com/lingtalfi/Chloroform) | [HttpResponseInterface](https://github.com/lingtalfi/Light/blob/master/doc/api/Ling/Light/Http/HttpResponseInterface.md)
    - private [error](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Service/LightRealformService/error.md)(string $msg) : void

}




Properties
=============

- <span id="property-handlers"><b>handlers</b></span>

    This property holds the handlers for this instance.
    It's an array of pluginName => RealformHandlerInterface
    
    

- <span id="property-container"><b>container</b></span>

    This property holds the container for this instance.
    
    

- <span id="property-dynamicInjectionHandlers"><b>dynamicInjectionHandlers</b></span>

    This property holds the dynamicInjectionHandlers for this instance.
    It's an array of identifier => RealformDynamicInjectionHandlerInterface
    
    Usually the identifier is a plugin name.
    
    



Methods
==============

- [LightRealformService::__construct](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Service/LightRealformService/__construct.md) &ndash; Builds the LightRealformService instance.
- [LightRealformService::executeRealform](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Service/LightRealformService/executeRealform.md) &ndash; instance.
- [LightRealformService::registerFormHandler](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Service/LightRealformService/registerFormHandler.md) &ndash; Registers a realform handler.
- [LightRealformService::registerDynamicInjectionHandler](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Service/LightRealformService/registerDynamicInjectionHandler.md) &ndash; Registers a [dynamic injection handler](https://github.com/lingtalfi/Light_Realform/blob/master/doc/pages/conception-notes-linear.md#dynamic-injection).
- [LightRealformService::getDynamicInjectionHandler](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Service/LightRealformService/getDynamicInjectionHandler.md) &ndash; or throws an exception if it's not there.
- [LightRealformService::setContainer](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Service/LightRealformService/setContainer.md) &ndash; Sets the container.
- [LightRealformService::getCurrentWebsiteUser](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Service/LightRealformService/getCurrentWebsiteUser.md) &ndash; Returns the current valid website user, or throws an exception.
- [LightRealformService::setHandlers](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Service/LightRealformService/setHandlers.md) &ndash; Sets the handlers.
- [LightRealformService::getChloroformField](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Service/LightRealformService/getChloroformField.md) &ndash; Returns a chloroform field.
- [LightRealformService::getChloroformValidator](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Service/LightRealformService/getChloroformValidator.md) &ndash; Returns a validator instance.
- [LightRealformService::handleFormSystemA](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Service/LightRealformService/handleFormSystemA.md) &ndash; and returns a chloroform instance.
- [LightRealformService::error](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Service/LightRealformService/error.md) &ndash; Throws an exception.





Location
=============
Ling\Light_Realform\Service\LightRealformService<br>
See the source code of [Ling\Light_Realform\Service\LightRealformService](https://github.com/lingtalfi/Light_Realform/blob/master/Service/LightRealformService.php)



SeeAlso
==============
Previous class: [LightRealformLateServiceRegistrationInterface](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Service/LightRealformLateServiceRegistrationInterface.md)<br>Next class: [RealformSuccessHandlerInterface](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/SuccessHandler/RealformSuccessHandlerInterface.md)<br>
