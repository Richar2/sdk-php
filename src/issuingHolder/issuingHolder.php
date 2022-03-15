<?php

namespace StarkInfra;
use StarkInfra\Utils\Resource;
use StarkInfra\Utils\Checks;
use StarkInfra\Utils\Rest;
use StarkInfra\Utils\StarkDate;


class IssuingHolder extends Resource
{
    /**
    # IssuingHolder object

    The IssuingHolder object displays the informations of Cards created to your Workspace.

    ## Parameters (required):
        - name [string]: card holder name. ex: "Tony Stark"
        - taxId [string]: card holder tax ID. ex: "012.345.678-90"
        - externalId [string] card holder unique id, generated by the user to avoid duplicated holders. ex: "my-entity/123"

    ## Parameters (optional):
        - rules [list of IssuingRule, default null]: [EXPANDABLE] list of holder spending rules.
        - tags [list of strings]: list of strings for tagging. ex: ["travel", "food"]

    ## Attributes (return-only):
        - id [string, default null]: unique id returned when IssuingHolder is created. ex: "5656565656565656"
        - status [string, default null]: current IssuingHolder status. ex: "canceled" or "active"
        - created [string, default null]: creation datetime for the IssuingHolder. ex: "2020-03-10 10:30:00.000"
        - updated [string, default null]: latest update datetime for the IssuingHolder. ex: "2020-03-10 10:30:00.000"
     */
    function __construct(array $params)
    {
        parent::__construct($params);

        $this->name = Checks::checkParam($params, "name");
        $this->taxId = Checks::checkParam($params, "taxId");
        $this->externalId = Checks::checkParam($params, "externalId");
        $this->rules = Checks::checkParam($params, "rules");
        $this->tags = Checks::checkParam($params, "tags");
        $this->status = Checks::checkParam($params, "status");
        $this->created = Checks::checkDateTime(Checks::checkParam($params, "created"));
        $this->updated = Checks::checkDateTime(Checks::checkParam($params, "updated"));

        Checks::checkParams($params);
    }

    /**
    # Create IssuingHolder

    Send a list of IssuingHolder objects for creation in the Stark Infra API

    ## Parameters (required):
        - holders [list of IssuingHolder objects]: list of IssuingHolder objects to be created in the API

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkInfra\Settings::setUser() was used before function call

    ## Return:
        - list of IssuingHolder objects with updated attributes
     */
    public static function create($holders, $expand = null, $user = null)
    {
        $query = is_null($expand) ? [] : ["expand" => $expand];
        return Rest::post($user, IssuingHolder::resource(), $holders, $query);
    }

    /**
    # Retrieve a specific Holder

    Receive a single IssuingHolder object previously created in the Stark Infra API by its id

    ## Parameters (required):
        - id [string]: object unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkInfra\Settings::setUser() was used before function call

    ## Return:
        - IssuingHolder object with updated attributes
     */
    public static function get($id, $user = null)
    {
        return Rest::getId($user, IssuingHolder::resource(), $id);
    }

    /**
    # Retrieve IssuingHolders

    Receive a generator of IssuingHolder objects previously created in the Stark Infra API

    ## Parameters (optional):
        - limit [integer, default null]: maximum number of objects to be retrieved. Unlimited if None. ex: 35
        - after [DateTime or string, default null] date filter for objects created only after specified date. ex: "2020-04-03"
        - before [DateTime or string, default null] date filter for objects created only before specified date. ex: "2020-04-03"
        - status [string, default null]: filter for status of retrieved objects. ex: "paid" or "registered"
        - tags [list of strings, default null]: tags to filter retrieved objects. ex: ["tony", "stark"]
        - ids [list of strings, default null]: list of ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
        - expand [string, default null]: fields to to expand information. ex: "rules"
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkInfra\Settings::setUser() was used before function call

    ## Return:
        - generator of IssuingHolder objects with updated attributes
     */
    public static function query($options = [], $user = null)
    {
        $options["after"] = new StarkDate(Checks::checkParam($options, "after"));
        $options["before"] = new StarkDate(Checks::checkParam($options, "before"));
        return Rest::getList($user, IssuingHolder::resource(), $options);
    }

    /**
    # Retrieve paged Holders

    Receive a list of IssuingHolder objects previously created in the Stark Infra API and the cursor to the next page.

    ## Parameters (optional):
        - cursor [string, default null]: cursor returned on the previous page function call
        - limit [integer, default null]: maximum number of objects to be retrieved. Unlimited if None. ex: 35
        - after [DateTime or string, default null] date filter for objects created only after specified date. ex: "2020-04-03"
        - before [DateTime or string, default null] date filter for objects created only before specified date. ex: "2020-04-03"
        - status [string, default null]: filter for status of retrieved objects. ex: "paid" or "registered"
        - tags [list of strings, default null]: tags to filter retrieved objects. ex: ["tony", "stark"]
        - ids [list of strings, default null]: list of ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
        - expand [string, default null]: fields to to expand information. ex: "rules"
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkInfra\Settings::setUser() was used before function call
    
    ## Return:
        - list of IssuingHolder objects with updated attributes
        - cursor to retrieve the next page of IssuingHolder objects
     */
    public static function page($options = [], $user = null)
    {
        return Rest::getPage($user, IssuingHolder::resource(), $options);
    }

    /**
    # Update IssuingHolder entity

    Update IssuingHolder by passing id.

    ## Parameters (required):
        - id [array of strings]: IssuingHolder unique ids. ex: "5656565656565656"

    ## Parameters (optional):
        - status [string, default null]: You may block the IssuingHolder by passing 'blocked' in the status
        - name [string, default null]: card holder name. ex: "Tony Stark"
        - tags [list of strings, default null]: list of strings for tagging. ex: ["tony", "stark"]
        - rules [list of dictionaries, default null]: list of dictionaries with "amount": int, "currencyCode": string, "id": string, "interval": string, "name": string pairs
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkInfra\Settings::setUser() was used before function call

    ## Return:
        - target IssuingHolder with updated attributes
     */
    public static function update($id, $options = [], $user = null)
    {
        return Rest::patchId($user, IssuingHolder::resource(), $id, $options);
    }


    /**
    # Delete a IssuingHolder entity

    Delete a IssuingHolder entity previously created in the Stark Infra API

    ## Parameters (required):
        - id [string]: IssuingHolder unique id. ex: "5656565656565656"
    
    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkInfra\Settings::setUser() was used before function call
    
    ## Return:
        - deleted IssuingHolder object
     */
    public static function delete($id, $user = null)
    {
        return Rest::deleteId($user, IssuingHolder::resource(), $id);
    }

    private static function resource()
    {
        $holder = function ($array) {
            return new IssuingHolder($array);
        };
        return [
            "name" => "IssuingHolder",
            "maker" => $holder,
        ];
    }
}
