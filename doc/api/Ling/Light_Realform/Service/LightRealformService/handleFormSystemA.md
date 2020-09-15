[Back to the Ling/Light_Realform api](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform.md)<br>
[Back to the Ling\Light_Realform\Service\LightRealformService class](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Service/LightRealformService.md)


LightRealformService::handleFormSystemA
================



LightRealformService::handleFormSystemA — and returns a chloroform instance.




Description
================


protected [LightRealformService::handleFormSystemA](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Service/LightRealformService/handleFormSystemA.md)(array $nugget, [Ling\Light_Realform\Result\RealformResult](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Result/RealformResult.md) $realformResult, [Ling\Chloroform\Form\Chloroform](https://github.com/lingtalfi/Chloroform) $form, ?array $options = []) : [Chloroform](https://github.com/lingtalfi/Chloroform) | [HttpResponseInterface](https://github.com/lingtalfi/Light/blob/master/doc/api/Ling/Light/Http/HttpResponseInterface.md)




Applies a standard routine to the form identified by the given realformIdentifier,
and returns a chloroform instance.


The update mode is triggered if the ric strict columns are passed in the url (i.e. $_GET).


What does this method do?
----------------

It creates the form, using realform,
it handles both the form insert and update actions.

If the form is posted correctly, either:

- an [iframe signal](https://github.com/lingtalfi/TheBar/blob/master/discussions/iframe-signal.md) is triggered (if defined in the options)
- the posted data are handled using the on_success_handler (defined by the realform configuration),
             and a success callback can also be triggered (if defined in the options).
             That success callback (from the options) can return a http response to use directly.




Errors and success messages are handled using the [flash service](https://github.com/lingtalfi/Light_Flasher/).


Available options are:

- iframeSignal; an [iframe signal](https://github.com/lingtalfi/TheBar/blob/master/discussions/iframe-signal.md) to use instead of the default success handler
- onSuccess: a success callback to trigger when the form was successfully posted (in addition to the
     success handler defined in the configuration). This applies only if the iframeSignal is not set




Parameters
================


- nugget

    

- realformResult

    

- form

    

- options

    


Return values
================

Returns [Chloroform](https://github.com/lingtalfi/Chloroform) | [HttpResponseInterface](https://github.com/lingtalfi/Light/blob/master/doc/api/Ling/Light/Http/HttpResponseInterface.md).


Exceptions thrown
================

- [Exception](http://php.net/manual/en/class.exception.php).&nbsp;







Source Code
===========
See the source code for method [LightRealformService::handleFormSystemA](https://github.com/lingtalfi/Light_Realform/blob/master/Service/LightRealformService.php#L528-L805)


See Also
================

The [LightRealformService](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Service/LightRealformService.md) class.

Previous method: [getChloroformValidator](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Service/LightRealformService/getChloroformValidator.md)<br>Next method: [error](https://github.com/lingtalfi/Light_Realform/blob/master/doc/api/Ling/Light_Realform/Service/LightRealformService/error.md)<br>

