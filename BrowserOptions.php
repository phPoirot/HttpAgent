<?php
namespace Poirot\HttpAgent;

use Poirot\Core\AbstractOptions;
use Poirot\Core\Interfaces\iDataSetConveyor;
use Poirot\Core\OpenOptions;
use Poirot\HttpAgent\Transporter\HttpTransporterOptions;
use Poirot\PathUri\HttpUri;
use Poirot\PathUri\Interfaces\iHttpUri;
use Poirot\PathUri\Psr\UriInterface;

/**
 * This is open options because may contains options for attached plugins
 */
class BrowserOptions extends OpenOptions
{
    /** @var string|iHttpUri|UriInterface Base Url to Server */
    protected $baseUrl;

    # default element options
    protected $connection;

    protected $userAgent;


    /**
     * @param iHttpUri|UriInterface|string $baseUrl
     * @return $this
     */
    public function setBaseUrl($baseUrl)
    {
        if (is_string($baseUrl) || $baseUrl instanceof UriInterface)
            $baseUrl = new HttpUri($baseUrl);

        if (!$baseUrl instanceof iHttpUri)
            throw new \InvalidArgumentException(sprintf(
                'BaseUrl must instance of iHttpUri, UriInterface or string. given: "%s"'
                , \Poirot\Core\flatten($baseUrl)
            ));

        $this->baseUrl = $baseUrl;
        return $this;
    }

    /**
     * @return iHttpUri
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * @param mixed $userAgent
     * @return $this
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = (string) $userAgent;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserAgent()
    {
        if (!$this->userAgent) {
            $userAgent = '';

            if (!$userAgent) {
                $userAgent = 'PoirotBrowser ';
                $userAgent .= ' PHP/' . PHP_VERSION;
            }

            $this->setUserAgent($userAgent);
        }

        return $this->userAgent;
    }


    // ...

    /**
     * Set Connection Options
     *
     * @param array|iDataSetConveyor|HttpTransporterOptions $connection
     * @return $this
     */
    public function setConnection($connection)
    {
        if (!$connection instanceof HttpTransporterOptions && $connection !== null)
            $connection = new HttpTransporterOptions($connection);

        $this->connection = $connection;
        return $this;
    }

    /**
     * @return null|HttpTransporterOptions
     */
    public function getConnection()
    {
        return $this->connection;
    }
}
