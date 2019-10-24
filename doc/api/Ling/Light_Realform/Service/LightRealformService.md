[Back to the Ling/Light_Realform api](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform.md)



The LightRealformService class
================
2019-10-21 --> 2019-10-24






Introduction
============

The LightRealformService class.



Class synopsis
==============


class <span class="pl-k">LightRealformService</span>  {

- Properties
    - protected [Ling\Light_Realform\Handler\RealformHandlerInterface[]](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Handler/RealformHandlerInterface.md) [$handlers](#property-handlers) ;

- Methods
    - public [__construct](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Service/LightRealformService/__construct.md)() : void
    - public [getFormHandler](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Service/LightRealformService/getFormHandler.md)(string $identifier) : [RealformHandlerInterface](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Handler/RealformHandlerInterface.md)
    - public [registerFormHandler](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Service/LightRealformService/registerFormHandler.md)(string $pluginName, [Ling\Light_Realform\Handler\RealformHandlerInterface](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Handler/RealformHandlerInterface.md) $formHandler) : void
    - public [setHandlers](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Service/LightRealformService/setHandlers.md)(array $handlers) : void

}




Properties
=============

- <span id="property-handlers"><b>handlers</b></span>

    This property holds the handlers for this instance.
    It's an array of pluginName => RealformHandlerInterface
    
    



Methods
==============

- [LightRealformService::__construct](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Service/LightRealformService/__construct.md) &ndash; Builds the LightRealformService instance.
- [LightRealformService::getFormHandler](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Service/LightRealformService/getFormHandler.md) &ndash; Returns the realform handler instance corresponding to the given identifier.
- [LightRealformService::registerFormHandler](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Service/LightRealformService/registerFormHandler.md) &ndash; Registers a realform handler.
- [LightRealformService::setHandlers](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Service/LightRealformService/setHandlers.md) &ndash; Sets the handlers.





Location
=============
Ling\Light_Realform\Service\LightRealformService<br>
See the source code of [Ling\Light_Realform\Service\LightRealformService](https://github.com/lingtalfi/Light_Realform/blob/master/Service/LightRealformService.php)



SeeAlso
==============
Previous class: [RealformRendererInterface](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Renderer/RealformRendererInterface.md)<br>Next class: [RealformSuccessHandlerInterface](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/SuccessHandler/RealformSuccessHandlerInterface.md)<br>
