<?php

/**
 *
 * A validator class to sanitize user input.
 */
class Validator
{
    public function __construct() { }

    public function __destruct() { }

    /**
     *
     * Function to sanitise a string.
     *
     * @param string $string_to_sanitise
     * @return bool|string
     */
    public function sanitiseString($string_to_sanitise): string
    {
        $sanitised_string = false;

        if (!empty($string_to_sanitise))
        {
            $sanitised_string = filter_var($string_to_sanitise, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        }
        return $sanitised_string;
    }

    /**
     *
     * Function to validate an Integer.
     *
     * @param int $int_to_sanitise
     * @return bool|int
     */
    public function validateInt($int_to_sanitise): int
    {
        $validated_int = false;

        if (!empty($int_to_sanitise))
        {
            $sanitised_string = filter_var($int_to_sanitise, FILTER_SANITIZE_STRING);
            $sanitised_string = ltrim($sanitised_string, '0');
            $validated_int = filter_var($sanitised_string, FILTER_VALIDATE_INT);
        }
        return $validated_int;
    }

    /**
     *
     * Function to sanitize a date time string.
     *
     * @param string $date_time_to_sanitise
     * @return bool|string
     */
    public function validateDate($date_to_sanitise): string
    {
        $validated_dt = false;


        if (!empty($date_to_sanitise))
        {
            $sanitised_string = filter_var($date_to_sanitise, FILTER_SANITIZE_STRING);
            $date = str_replace('/', '-', $sanitised_string);
            if(strtotime($date)) {
            $validated_dt = date('Y-m-d', strtotime($date));
            }
        }
        return $validated_dt;
    }

    /**
     *
     * Function to sanitize an email.
     *
     * @param string $email_to_sanitise
     * @return bool|string
     */
    public function sanitiseEmail($email_to_sanitise): string
    {
        $cleaned_string = false;

        if (!empty($email_to_sanitise))
        {
            $sanitised_email = filter_var($email_to_sanitise, FILTER_SANITIZE_EMAIL);
            $cleaned_string = filter_var($sanitised_email, FILTER_VALIDATE_EMAIL);
        }
        return $cleaned_string;
    }

    /**
     *
     * Function to validate an Api key.
     *
     * @param string $key_to_validate
     * @return bool|array 
     */
    public function validateApiKey(string $key_to_validate)
    {
        if (!empty($key_to_validate))
        {
            $keyarray = explode("$", $key_to_validate);
            if (count($keyarray) == 2) {
                $keyarray[0] = $this->validateInt($keyarray[0]);
                if (($keyarray[0] != false)) {
                    if(strlen($keyarray[1]) == 100) {
                    return $keyarray;                         
                    }
 
                }
            }
        }
        return false;
    }
}
