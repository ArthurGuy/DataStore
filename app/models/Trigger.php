<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trigger extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'triggers';

    protected $fillable = [
        'name',
        'streamId',
        'check_field',
        'check_operator',
        'check_value',
        'filter_value',
        'filter_field',
        'action',
        'action_details',
        'push_subject',
        'push_message',
        'push_when',
        'variable_name',
        'variable_value',
        'nest_api_key',
        'nest_property',
        'nest_value',
        'nest_structure',
        'location_id',
    ];


    public function getActionDetailsAttribute($value)
    {
        $actionDetails = json_decode($value);
        if (is_object($actionDetails)) {
            return $actionDetails;
        } else {
            return new \stdClass();
        }
    }

    public function setActionDetailsAttribute($value)
    {
        if (is_object($value)) {
            $this->attributes['action_details'] = json_encode($value);
        } else {
            $this->attributes['action_details'] = json_encode([]);
        }
    }


    # Push message getters and setters

    public function getPushSubjectAttribute($value)
    {
        if (is_object($this->action_details) && isset($this->action_details->push_subject)) {
            return $this->action_details->push_subject;
        } else {
            return null;
        }
    }

    public function getPushMessageAttribute($value)
    {
        if (is_object($this->action_details) && isset($this->action_details->push_message)) {
            return $this->action_details->push_message;
        } else {
            return null;
        }
    }

    public function getPushWhenAttribute($value)
    {
        if (is_object($this->action_details) && isset($this->action_details->push_when)) {
            return $this->action_details->push_when;
        } else {
            return null;
        }
    }

    public function setPushSubjectAttribute($value)
    {
        $actionDetails               = $this->action_details;
        $actionDetails->push_subject = $value;
        $this->action_details        = $actionDetails;
    }

    public function setPushMessageAttribute($value)
    {
        $actionDetails               = $this->action_details;
        $actionDetails->push_message = $value;
        $this->action_details        = $actionDetails;
    }

    public function setPushWhenAttribute($value)
    {
        $actionDetails            = $this->action_details;
        $actionDetails->push_when = $value;
        $this->action_details     = $actionDetails;
    }


    # Variable getters and setters

    public function getVariableNameAttribute($value)
    {
        if (is_object($this->action_details) && isset($this->action_details->variable_name)) {
            return $this->action_details->variable_name;
        } else {
            return null;
        }
    }

    public function getVariableValueAttribute($value)
    {
        if (is_object($this->action_details) && isset($this->action_details->variable_value)) {
            return $this->action_details->variable_value;
        } else {
            return null;
        }
    }

    public function setVariableNameAttribute($value)
    {
        $actionDetails                = $this->action_details;
        $actionDetails->variable_name = $value;
        $this->action_details         = $actionDetails;
    }

    public function setVariableValueAttribute($value)
    {
        $actionDetails                 = $this->action_details;
        $actionDetails->variable_value = $value;
        $this->action_details          = $actionDetails;
    }

//NEST
    public function setNestApiKeyAttribute($value)
    {
        $actionDetails               = $this->action_details;
        $actionDetails->nest_api_key = $value;
        $this->action_details        = $actionDetails;
    }

    public function setNestPropertyAttribute($value)
    {
        $actionDetails                = $this->action_details;
        $actionDetails->nest_property = $value;
        $this->action_details         = $actionDetails;
    }

    public function setNestValueAttribute($value)
    {
        $actionDetails             = $this->action_details;
        $actionDetails->nest_value = $value;
        $this->action_details      = $actionDetails;
    }

    public function setNestStructureAttribute($value)
    {
        $actionDetails                 = $this->action_details;
        $actionDetails->nest_structure = $value;
        $this->action_details          = $actionDetails;
    }

    public function getNestApiKeyAttribute($value)
    {
        if (is_object($this->action_details) && isset($this->action_details->nest_api_key)) {
            return $this->action_details->nest_api_key;
        } else {
            return null;
        }
    }

    public function getNestPropertyAttribute($value)
    {
        if (is_object($this->action_details) && isset($this->action_details->nest_property)) {
            return $this->action_details->nest_property;
        } else {
            return null;
        }
    }

    public function getNestValueAttribute($value)
    {
        if (is_object($this->action_details) && isset($this->action_details->nest_value)) {
            return $this->action_details->nest_value;
        } else {
            return null;
        }
    }

    public function getNestStructureAttribute($value)
    {
        if (is_object($this->action_details) && isset($this->action_details->nest_structure)) {
            return $this->action_details->nest_structure;
        } else {
            return null;
        }
    }



    public function getLocationIdAttribute($value)
    {
        if (is_object($this->action_details) && isset($this->action_details->location_id)) {
            return $this->action_details->location_id;
        } else {
            return null;
        }
    }

    public function setLocationIdAttribute($value)
    {
        $actionDetails                 = $this->action_details;
        $actionDetails->location_id    = $value;
        $this->action_details          = $actionDetails;
    }
}
