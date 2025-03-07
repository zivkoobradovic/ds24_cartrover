<?php

namespace App\Libraries;

use Exception;
if (!class_exists( 'DigistoreApi' )) {

define( 'DS_ERR_UNKNOWN',                  0 );
define( 'DS_ERR_NOT_CONNECTED',            1 );
define( 'DS_ERR_BAD_API_KEY',              2 );
define( 'DS_ERR_BAD_FUNCTION_PARAMS',      3 );
define( 'DS_ERR_NOT_FOUND',                4 );
define( 'DS_ERR_BAD_SERVER_RESPONSE',      5 );
define( 'DS_ERR_CURL',                     6 );
define( 'DS_ERR_BAD_HTTP_CODE',            7 );
define( 'DS_ERR_PERMISSION_DENIED',        8 );
define( 'DS_ERR_BAD_API_CALL',             9 );
define( 'DS_ERR_API_KEY_MISSING',         10 );
define( 'DS_ERR_INTERNAL',                11 );


define( 'DS_LOG_INFO',  'info' );
define( 'DS_LOG_ERROR', 'error' );

define( 'DS_API_WRITABLE', 'writable' );
define( 'DS_API_READONLY', 'readonly' );

class DigistoreApiException extends Exception {
}

class DigistoreApi {

    const digistore_api_connector_version = 1.2;

    /**
    * Initiates a connection to the Digistore24 api server. Note that no http call to the server is done. To actually test the connection,
    * use the api function ping()
    *
    * See api reference at https://docs.digistore24.com/api-en/
    *
    * @param string $api_key Your api key from you Digistore24 account, e.g. 123-iKWIrTsUTbCyrFuotOdV8yO20nfMI5bbrZhDCUAG
    * @throws DigistoreApiException
    */
    public static function connect( $api_key )
    {
        return new DigistoreApi( $api_key );
    }

    /**
    * Add a logger
    *
    * @param callable $callable a php callable accepting two arguments: $loglevel, $message - e.g. function my_logger( $loglevel, $message )
    * @return int handle to remove logger (using removeLogger())
    */
    public function addLogger( $callable )
    {
        $handle = $this->logger_index++;
        $this->loggers[ $handle ] = $callable;
        return $handle;
    }

    /**
    * Remove a logger
    *
    * @param int $handle as returned by addLogger()
    */
    public function removeLogger( $handle )
    {
        unset( $this->loggers[ $handle ] );
    }

    /**
    * Set an operator name - a person, who is responsible for the changes performed via the api call.
    * The name is only used for logging purposes.
    *
    * @param string $operator_name  your application's user name (NOT a Digistore24 name)
    */
    public function setOperator( $operator_name )
    {
        $this->operator_name = $operator_name;
    }

    /**
    * Sets the language for the api's messages.
    * By default all messages are in the language set in the Digistore24 account of the api key owner.
    *
    * @param string $language 'de' or 'en'
    */
    public function setLanguage( $language )
    {
        $tokens = explode( '_', $language );

        $language = strtolower(trim($tokens[0]));

        if ($language)
        {
            $this->language = $language;
        }
    }

    /**
    * Destroys the connection to the server.
    *
    */
    public function disconnect()
    {
        $this->api_key  = false;
    }

    /**
    * Execute api function on the Digistore24 server
    *
    * @param string $function_name
    * @param array $arguments
    * @throws DigistoreApiException
    */
    public function __call( $function_name, $arguments )
    {
        return $this->_exec( $function_name, $arguments );
    }

    /**
    * Used for debug purposes. Returns the most recently used api url called.
    */
    public function getLastUrl()
    {
        if ($this->last_url===false) {
            return false;
        }

        $querystring = http_build_query( $this->last_params, '', '&' );


        return $this->last_url . '?' . $querystring;
    }

    /**
    * For debugging purposes only
    *
    * @param string $url
    */
    public function setBaseUrl( $url='https://www.digistore24.com' ){
        $this->base_url = rtrim( $url, '/' );
    }

    //
    // private section /////////////////////////////////////////////////////////////////////////////////////////////
    //
    private $api_key  = '';
    private $language = '';
    private $operator_name = '';
    private $loggers  = array();
    private $logger_index = 1;
    private $base_url = 'https://www.digistore24.com';
    private $last_url    = false;
    private $last_params = false;


    private function __construct( $api_key )
    {
        $this->api_key  = $api_key;

        if (!$this->api_key) {
            $this->_error( DS_ERR_NOT_CONNECTED );
        }
    }

    private function _log( $level, $msg, $arg1='', $arg2='', $arg3='' )
    {
        if (empty($this->loggers)) {
            return;
        }

        $msg = sprintf( $msg, $arg1, $arg2, $arg3 );

        foreach ($this->loggers as $one)
        {
            call_user_func( $one, $level, $msg );
        }
    }

    private function _error( $code, $arg1='', $arg2='', $arg3='' )
    {
        $msg = $this->_errorMsg( $code, $arg1, $arg2, $arg3 );
        $this->_log( DS_LOG_ERROR, $msg );
        throw new DigistoreApiException( $msg, $code );
    }

    private function service_url()
    {
        $key  = $this->api_key;

        if (!$key) {
            $this->_error( DS_ERR_NOT_CONNECTED );
        }

        $base_url = $this->base_url;

        return "$base_url/api/call/";
    }

    private function _exec( $function_name, $arguments )
    {
        if (!$this->api_key) {
            $this->_error( DS_ERR_NOT_CONNECTED );
        }

        $this->_log( DS_LOG_INFO, "Call to '%s' - started", $function_name . '()' );

        if (!$function_name || !is_array($arguments)) {
            $this->_error( DS_ERR_BAD_FUNCTION_PARAMS, "$function_name()" );
        }

        $url = $this->service_url() . $function_name;

        $args = array();
        foreach ($arguments as $index => $one)
        {
            $key = 'arg' . ($index+1);

            $this->_set_post_param( $args, $key, $one );
        }

        $args['language'] = $this->language;
        $args['operator'] = $this->operator_name;
        $args['ds24ver' ] = self::digistore_api_connector_version;

        $data = $this->_http_request( $url, $args );

        $this->_log( DS_LOG_INFO, "Call to %s - completed", $function_name . '()' );

        return $data;
    }


    private function _http_request( $url, $params, $settings=array() )
    {
        $this->last_url    = $url;
        $this->last_params = $params;

        $querystring = http_build_query( $params, '', '&' );

        $headers = [
                        'Content-type: application/x-www-form-urlencoded; charset=utf-8',
                        'Accept-Charset: utf-8',
                        'Accept: application/json',
                        'X-DS-API-KEY: ' . $this->api_key,
        ];

        if (!function_exists('curl_init')) {
            $this->_error( DS_ERR_CURL,  $ch_error_no=0, $ch_error_msg='PHP module Curl is required. Please ask your web admin to enable it.' );
        }

        $ch = curl_init();

        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt( $ch, CURLOPT_PROTOCOLS, CURLPROTO_HTTP|CURLPROTO_HTTPS);
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, false );
        curl_setopt( $ch, CURLOPT_USERAGENT, 'DigiStore-API-Connector/1.0 (Linux; en-US; rv:1.0.0.0) php/20130430 curl/20130430' );
        curl_setopt( $ch, CURLOPT_TIMEOUT, 30 );
        curl_setopt( $ch, CURLOPT_URL, $url);
        curl_setopt( $ch, CURLOPT_POST, count($params));
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $querystring);
        curl_setopt( $ch, CURLOPT_ENCODING, "" );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );

        $contents  = curl_exec($ch);

        $http_code = ''.curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $ch_error_no  = curl_errno($ch);
        $ch_error_msg = curl_error($ch);

        @curl_close($ch);

        if ($ch_error_msg)
        {
            $this->_error( DS_ERR_CURL,  $ch_error_no, $ch_error_msg );
        }

        $is_http_call_success = $http_code == 200;

        $result = @json_decode( $contents );

        if (empty($result) && !$is_http_call_success)
        {
            $this->_error( DS_ERR_BAD_HTTP_CODE, $http_code );
        }

        $debug_info = '';
        if (!isset($result))
        {
            global $DM_SUPPORT_URL_TEST;

            $must_report = (defined('NCORE_DEBUG') && NCORE_DEBUG)
                        || !empty($DM_SUPPORT_URL_TEST);

            if ($must_report) {

                $api_key_disp = 'XXXX-XXXXXXXXXXXXXXXXX';
                if ($this->api_key)
                {
                    $parts = explode( '-', $this->api_key );
                    if (count($parts) >= 2)
                    {
                        $api_key_disp = $parts[0] . '-XXXXXXXXXXXXXXXXX';
                    }
                }

                ob_start();
                echo "<pre>URL: $url\nApi key: $api_key_disp\nQuery: $querystring\nParams: ";
                print_r( $params );
                echo "\nResponse:</pre>$contents";
                $debug_info = ob_get_clean();

                $debug_info = str_replace( $this->api_key, 'DS24_APIKEY_PROTECTED', $debug_info );

                trigger_error( "Invalid digistore24 api server response: $debug_info" );
            }
        }

        $success = $result && is_a($result,'stdClass') && isset($result->api_version) && isset($result->result) && $is_http_call_success;

        if ($success)
        {
            $api_version = $result->api_version;
            $result_type = $result->result;

            switch ($result_type)
            {
                case 'error':
                    $msg  = $result->message;
                    $code = $result->code;
                    throw new DigistoreApiException( $msg, $code );

                case 'success':
                    $data = $result->data;
                    return $data;
            }
        }
        elseif (!$is_http_call_success)
        {
            $this->_error( DS_ERR_BAD_HTTP_CODE, $http_code );
        }
        else {
            $this->_error(DS_ERR_BAD_SERVER_RESPONSE, $contents);
        }
    }

    /**
    * Translates an error code in a human readable message for your application's users.
    *
    * @param int $code  a DS_ERR_XXXXX constant
    */
    private function _errorMsg( $code, $arg1='', $arg2='', $arg3='' )
    {
        $error_messages  = $this->_errorMsgList();

        if ($code==='test') {
            $is_lang_valid = isset( $error_messages[ $this->language ] );
            return $is_lang_valid;
        }

        $msgs = isset( $error_messages[ $this->language ] )
              ? $error_messages[ $this->language ]
              : $error_messages[ 'en' ];

        $msg = isset( $msgs[ $code ] )
             ? $msgs[ $code ]
             : $msgs[ DS_ERR_UNKNOWN ];

        return sprintf( $msg, $arg1, $arg2, $arg3 );
    }

    private function _errorMsgList()
    {
        return array(
            'de' => array(
                DS_ERR_UNKNOWN                   => 'Unbekannter Fehler!',
                DS_ERR_NOT_CONNECTED             => 'Kein Api-Key angegeben - nicht zum Digistore24-Server verbunden.',
                DS_ERR_BAD_API_KEY               => 'Die Verbindungsparameter sind ungültig.',
                DS_ERR_BAD_API_CALL              => 'Ungültiger Funktionsaufruf.',
                DS_ERR_BAD_FUNCTION_PARAMS       => 'Ungültige Parameter bei Funktionsaufruf %s.',
                DS_ERR_BAD_SERVER_RESPONSE       => 'Der Digistore24-Server hat eine ungültige Antwort geliefert. (Technische Information: %s)',
                DS_ERR_CURL                      => 'Fehler beim HTTP-Aufruf durch CURL (#%s - %s)',
                DS_ERR_BAD_HTTP_CODE             => 'Der Digistore24-Server lieferte eine Antwort mit einem Ungültigen HTTP-Code (%s)',
                DS_ERR_NOT_FOUND                 => 'Das angefrage Objekt wurde nicht gefunden.',
                DS_ERR_PERMISSION_DENIED         => 'Zugriff verweigert.',
                DS_ERR_API_KEY_MISSING           => 'Apikey nicht angegeben.',
                DS_ERR_INTERNAL                  => 'Interner Fehler auf Digistore-Seite',
            ),
            'en' => array(
                DS_ERR_UNKNOWN                   => 'Unknown error!',
                DS_ERR_NOT_CONNECTED             => 'No api key given - not connected to the Digistore24 server.',
                DS_ERR_BAD_API_KEY               => 'Invalid connection parameters.',
                DS_ERR_BAD_API_CALL              => 'Invalid function call.',
                DS_ERR_BAD_FUNCTION_PARAMS       => 'Invalid parameters for function call %s.',
                DS_ERR_BAD_SERVER_RESPONSE       => 'The Digistore24 server delivered an invalid response. (Technical information: %s)',
                DS_ERR_CURL                      => 'Http call error reported by curl (#%s - %s)',
                DS_ERR_BAD_HTTP_CODE             => 'The Digistore24 server responded with an invalid http code (%s)',
                DS_ERR_NOT_FOUND                 => 'Requested object not found',
                DS_ERR_PERMISSION_DENIED         => 'Permission denied.',
                DS_ERR_API_KEY_MISSING           => 'Api key is missing.',
                DS_ERR_INTERNAL                  => 'Internal error on Digistore side',
            )
        );
    }

    function _set_post_param( &$args, $key, $value )
    {
        if (is_object($value)) {
            $value = (array) $value;
        }

        if (!is_array($value)) {
            $args[ $key ] = $value;
            return;
        }

        foreach ($value as $one_key => $one_value)
        {
            $one_name = $key . '[' . $one_key . ']';
            $this->_set_post_param( $args, $one_name, $one_value );
        }

    }

}

} // if class_exists
