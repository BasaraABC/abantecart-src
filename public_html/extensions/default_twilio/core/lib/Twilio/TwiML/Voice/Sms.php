<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\TwiML\Voice;

use Twilio\TwiML\TwiML;

class Sms extends TwiML
{
    /**
     * Sms constructor.
     *
     * @param string $message    Message body
     * @param array  $attributes Optional attributes
     */
    public function __construct($message, $attributes = array())
    {
        parent::__construct('Sms', $message, $attributes);
    }

    /**
     * Add To attribute.
     *
     * @param phoneNumber $to Number to send message to
     *
     * @return TwiML $this.
     */
    public function setTo($to)
    {
        return $this->setAttribute('to', $to);
    }

    /**
     * Add From attribute.
     *
     * @param phoneNumber $from Number to send message from
     *
     * @return TwiML $this.
     */
    public function setFrom($from)
    {
        return $this->setAttribute('from', $from);
    }

    /**
     * Add Action attribute.
     *
     * @param url $action Action URL
     *
     * @return TwiML $this.
     */
    public function setAction($action)
    {
        return $this->setAttribute('action', $action);
    }

    /**
     * Add Method attribute.
     *
     * @param httpMethod $method Action URL method
     *
     * @return TwiML $this.
     */
    public function setMethod($method)
    {
        return $this->setAttribute('method', $method);
    }

    /**
     * Add StatusCallback attribute.
     *
     * @param url $statusCallback Status callback URL
     *
     * @return TwiML $this.
     */
    public function setStatusCallback($statusCallback)
    {
        return $this->setAttribute('statusCallback', $statusCallback);
    }
}