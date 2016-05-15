<?php
namespace Comfort\Validator\StringValidator;

use Comfort\Validator\AbstractMethod;

class UriMethod extends AbstractMethod
{
    /**
     * @var string
     */
    protected $scheme;

    /**
     * @var string
     */
    protected $domain;


    public function __invoke($value)
    {
        $pslManager = new \Pdp\PublicSuffixListManager();
        $parser = new \Pdp\Parser($pslManager->getList());

        try {
            $url = $parser->parseUrl($value);
        } catch (\InvalidArgumentException $e) {
            return false;
        }

        $scheme = $this->getScheme();
        if (!empty($scheme)) {
            return $scheme == $url->scheme;
        }

        $domain = $this->getDomain();
        if (!empty($domain)) {
            return $domain == (string)$url->host;
        }

        return true;
    }

    /**
     * @return string
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * @param string $scheme
     */
    public function setScheme($scheme)
    {
        $this->scheme = $scheme;
    }

    /**
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param string $domain
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }
}