<?php

namespace StarkInfra\CreditNote;
use StarkInfra\Utils\Resource;
use StarkInfra\Utils\Checks;
use StarkInfra\Utils\Rest;
use StarkInfra\Utils\API;
use StarkInfra\Utils\StarkDate;
use StarkInfra\CreditNote;


class Log extends Resource
{
    /**
    # CreditNote\Log object

    Every time a CreditNote entity is updated, a corresponding CreditNote\Log
    is generated for the entity. This log is never generated by the
    user, but it can be retrieved to check additional information
    on the CreditNote.

    ## Attributes (return-only):
        - id [string]: unique id returned when the log is created. ex: "5656565656565656"
        - note [CreditNote]: Credit Note entity to which the log refers to.
        - errors [array of strings]: array of errors linked to this Credit Note event
        - type [string]: type of the Credit Note event which triggered the log creation. ex: "canceled", "created", "expired", "failed", "refunded", "registered", "sending", "sent", "signed", "success"
        - created [DateTime]: creation datetime for the log.
     */

    function __construct(array $params)
    {
        parent::__construct($params);

        $this-> created = Checks::checkDateTime(Checks::checkParam($params, "created"));
        $this-> type = Checks::checkParam($params, "type");
        $this-> errors = Checks::checkParam($params, "errors");
        $this-> note = Checks::checkParam($params, "note");

        Checks::checkParams($params);
    }

    /**
    # Retrieve a specific CreditNote\Log

    Receive a single Log object previously created by the Stark Infra API by passing its id

    ## Parameters (required):
        - id [string]: object unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkInfra\Settings::setUser() was used before function call

    ## Return:
        - Log object with updated attributes
     */
    public static function get($id, $user = null)
    {
        return Rest::getId($user, Log::resource(), $id);
    }

    /**
    # Retrieve CreditNote\Logs

    Receive an enumerator of CreditNote\Log objects previously created in the Stark Infra API.
    Use this function instead of page if you want to stream the objects without worrying about cursors and pagination.

    ## Parameters (optional):
        - limit [integer, default 100]: maximum number of objects to be retrieved. 
        - after [Date or string, default null] date filter for objects created only after specified date. ex: "2020-04-03"
        - before [Date or string, default null] date filter for objects created only before specified date. ex: "2020-04-03"
        - types [array of strings, default null]: filter for log event types. ex: "created", "paid", "canceled" or "overdue"
        - noteIds [array of strings, default null]: array of CreditNote ids to filter logs. ex: ["5656565656565656", "4545454545454545"]
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkInfra\Settings::setUser() was used before function call

    ## Return:
        - enumerator of Log objects with updated attributes
     */
    public static function query($options = [], $user = null)
    {
        $options["after"] = new StarkDate(Checks::checkParam($options, "after"));
        $options["before"] = new StarkDate(Checks::checkParam($options, "before"));

        return Rest::getList($user, Log::resource(), $options);
    }

    /**
    # Retrieve paged CreditNote\Logs

    Receive a list of up to 100 CreditNote\Log objects previously created in the Stark Infra API and the cursor to the next page.
    Use this function instead of query if you want to manually page your requests.

    ## Parameters (optional):
        - cursor [string, default null]: cursor returned on the previous page function call
        - limit [integer, default 100]: maximum number of objects to be retrieved. 
        - after [Date or string, default null] date filter for objects created only after specified date. ex: "2020-04-03"
        - before [Date or string, default null] date filter for objects created only before specified date. ex: "2020-04-03"
        - types [array of strings, default null]: filter for log event types. ex: "canceled", "created", "expired", "failed", "refunded", "registered", "sending", "sent", "signed", "success"
        - noteIds [array of strings, default null]: array of Credit Note ids to filter logs. ex: ["5656565656565656", "4545454545454545"]
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkInfra\Settings::setUser() was used before function call
    
    ## Return:
        - list of CreditNote\Log objects with updated attributes
        - cursor to retrieve the next page of CreditNote\Log objects
     */
    public static function page($options = [], $user = null)
    {
        $options["after"] = new StarkDate(Checks::checkParam($options, "after"));
        $options["before"] = new StarkDate(Checks::checkParam($options, "before"));

        return Rest::getPage($user, Log::resource(), $options);
    }

    private static function resource()
    {
        $noteLog = function ($array) {
            $creditNote = function ($array) {
                return new CreditNote($array);
            };
            $array["note"] = API::fromApiJson($creditNote, $array["note"]);
            return new Log($array);
        };
        return [
            "name" => "CreditNoteLog",
            "maker" => $noteLog,
        ];
    }
}
