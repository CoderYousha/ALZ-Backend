<?php


namespace App\Http\DTO\Base;
use App\Exceptions\ErrorMsgException;
use Carbon\CarbonTimeZone;
use \Spatie\DataTransferObject\DataTransferObject;

use Carbon\Carbon;

class ObjectData extends DataTransferObject
{

    public static $deletableFields=[];
    public static $unUpdatableFields=[];

    public static function generateCarbonObject(?string $date,$ignoreTimeZone=false): ?Carbon

    {

        if (!$date) {

            return null;

        }

        if(is_null(request()->time_zone) || $ignoreTimeZone){
            return new Carbon($date);
        }

        return toTimezone($date,request()->time_zone,config('panel.timezone'));

    }

    public static function generateCarbonTimeObject(?string $date): ?Carbon

    {

        if (!$date) {

            return null;

        }


      return  (new Carbon($date,request()->time_zone))
            ->setTimezone(new CarbonTimeZone(config('panel.timezone')));

    }

    /**
     * @param array-key of strings
     * @return static
     * @throws  ErrorMsgException
     */
    public function merge(array $fields){
        foreach ($fields as $fieldName =>$field){
            if($this->checkPropertyExists($fieldName)){
                $this->{$fieldName} = $field;
            }
        }
        return $this;
    }

    /**
     * check if the property @param string $name is exists in the class variables
     * else @throws ErrorMsgException
     */
    private function checkPropertyExists($name){
        if(!property_exists($this, $name)){
            throw new ErrorMsgException(
                'you trying to sign to field doesnt exist in the '.get_class($this)
            );
        }
        return true;
    }

    public function initializeForUpdate(?ObjectData $data=null){

        $arrayUpdate = [];
        foreach ($this->all() as $key=>$element){

            //Ignore update  field
            if(in_array($key,static::$unUpdatableFields)){
                continue;
            }

            if(isset($element) || in_array($key,static::$deletableFields)){
                $arrayUpdate[$key]=$element;
            }
        }
        return $arrayUpdate;

    }

}
