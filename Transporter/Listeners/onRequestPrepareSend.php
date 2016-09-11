<?php
namespace Poirot\HttpAgent\Transporter\Listeners;

use Poirot\Events\Listener\aListener;
use Poirot\Http\Header\FactoryHttpHeader;
use Poirot\Http\Interfaces\iHttpRequest;
use Poirot\HttpAgent\Transporter\HttpSocketTransporter;
use Poirot\Stream\Interfaces\iStreamable;


class onRequestPrepareSend 
    extends aListener
{
    /**
     * @param HttpSocketTransporter $transporter
     * @param iHttpRequest          $request
     *
     * @return mixed
     */
    function __invoke($transporter = null, $request = null)
    {
        # Header Content-Length:

        /**
         * Http Messages With Body Should be with Content-Length
         * !! without this post requests always not working
         * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec4.html#sec4.4
         * @see https://issues.apache.org/jira/browse/TS-2902
         */
        $body = $request->getBody();

        $length = false;
        if ($body) {
            if ($body instanceof iStreamable)
                $length = $body->getSize();
            else
                $length = strlen($body);
        }

        if ($length !== false) {
            if (!$request->headers()->has('Content-Length'))
                $request->headers()->insert(FactoryHttpHeader::of(array('Content-Length' => (string) $length)));
        }
    }
}
