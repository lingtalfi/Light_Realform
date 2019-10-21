[Back to the Ling/Light_Realform api](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform.md)



The BaseRealformHandler class
================
2019-10-21 --> 2019-10-21






Introduction
============

The BaseRealformHandler class.
A helper to implement a realform handler, using my organization techniques.



Class synopsis
==============


abstract class <span class="pl-k">BaseRealformHandler</span> implements [RealformHandlerInterface](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Handler/RealformHandlerInterface.md) {

- Properties
    - protected string [$confDir](#property-confDir) ;
    - protected array [$confCache](#property-confCache) ;
    - protected string [$id](#property-id) ;

- Methods
    - public [__construct](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Handler/BaseRealformHandler/__construct.md)() : void
    - public [setId](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Handler/BaseRealformHandler/setId.md)(string $id) : mixed
    - public [getFormHandler](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Handler/BaseRealformHandler/getFormHandler.md)() : Ling\Chloroform\Form\Chloroform
    - public [getConfiguration](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Handler/BaseRealformHandler/getConfiguration.md)() : array
    - public [getSuccessHandler](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Handler/BaseRealformHandler/getSuccessHandler.md)() : void
    - public [getFormRenderer](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Handler/BaseRealformHandler/getFormRenderer.md)() : void
    - public [setConfDir](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Handler/BaseRealformHandler/setConfDir.md)(string $confDir) : void
    - protected [getDefaultFormHandler](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Handler/BaseRealformHandler/getDefaultFormHandler.md)() : Ling\Chloroform\Form\Chloroform
    - protected [getChloroformField](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Handler/BaseRealformHandler/getChloroformField.md)(Ling\Chloroform\Form\Chloroform $form, string $type, string $fieldId, ?array $fieldConf = []) : Ling\Chloroform\Field\FieldInterface
    - protected [getChloroformValidator](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Handler/BaseRealformHandler/getChloroformValidator.md)(string $type, array $validatorConf) : Ling\Chloroform\Validator\ValidatorInterface

}




Properties
=============

- <span id="property-confDir"><b>confDir</b></span>

    This property holds the confDir for this instance.
    
    

- <span id="property-confCache"><b>confCache</b></span>

    This property holds the confCache for this instance.
    It's an array of id => configuration array
    
    

- <span id="property-id"><b>id</b></span>

    This property holds the realform id for this instance.
    
    



Methods
==============

- [BaseRealformHandler::__construct](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Handler/BaseRealformHandler/__construct.md) &ndash; Builds the BaseRealformHandler instance.
- [BaseRealformHandler::setId](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Handler/BaseRealformHandler/setId.md) &ndash; Sets the realform id.
- [BaseRealformHandler::getFormHandler](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Handler/BaseRealformHandler/getFormHandler.md) &ndash; Returns a chloroform instance configured based on the realform id.
- [BaseRealformHandler::getConfiguration](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Handler/BaseRealformHandler/getConfiguration.md) &ndash; Returns the realform configuration based on the realform id.
- [BaseRealformHandler::getSuccessHandler](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Handler/BaseRealformHandler/getSuccessHandler.md) &ndash; 
- [BaseRealformHandler::getFormRenderer](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Handler/BaseRealformHandler/getFormRenderer.md) &ndash; 
- [BaseRealformHandler::setConfDir](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Handler/BaseRealformHandler/setConfDir.md) &ndash; Sets the confDir.
- [BaseRealformHandler::getDefaultFormHandler](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Handler/BaseRealformHandler/getDefaultFormHandler.md) &ndash; Returns a default chloroform instance.
- [BaseRealformHandler::getChloroformField](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Handler/BaseRealformHandler/getChloroformField.md) &ndash; Returns a chloroform field.
- [BaseRealformHandler::getChloroformValidator](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Handler/BaseRealformHandler/getChloroformValidator.md) &ndash; Returns a validator instance.





Location
=============
Ling\Light_Realform\Handler\BaseRealformHandler<br>
See the source code of [Ling\Light_Realform\Handler\BaseRealformHandler](https://github.com/lingtalfi/Light_Realform/blob/master/Handler/BaseRealformHandler.php)



SeeAlso
==============
Previous class: [LightRealformException](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Exception/LightRealformException.md)<br>Next class: [RealformHandlerInterface](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Handler/RealformHandlerInterface.md)<br>
