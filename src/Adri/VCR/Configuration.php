<?php

namespace Adri\VCR;

/**
 * Configuration.
 */
class Configuration
{
    private $cassettePath = 'tests/fixtures';

    private $enabledLibraryHooks;
    private $availableLibraryHooks = array(
        '\Adri\VCR\LibraryHooks\StreamWrapper',
        '\Adri\VCR\LibraryHooks\Curl',
        // '\Adri\VCR\LibraryHooks\Soap',
    );

    private $enabledRequestMatchers;
    private $availableRequestMatchers = array(
        'method'      => array('\Adri\VCR\RequestMatcher', 'matchMethod'),
        'url'         => array('\Adri\VCR\RequestMatcher', 'matchUrl'),
        'host'        => array('\Adri\VCR\RequestMatcher', 'matchHost'),
        'headers'     => array('\Adri\VCR\RequestMatcher', 'matchHeaders'),
        'body'        => array('\Adri\VCR\RequestMatcher', 'matchBody'),
        'post_fields' => array('\Adri\VCR\RequestMatcher', 'matchPostFields'),
    );

    private $turnOnAutomatically = true;

    public function getTurnOnAutomatically()
    {
        return $this->turnOnAutomatically;
    }

    public function getCassettePath()
    {
        return $this->cassettePath;
    }

    public function getLibraryHooks()
    {
        if (is_null($this->enabledLibraryHooks)) {
            return array_values($this->availableLibraryHooks);
        }

        return array_values(array_intersect_key(
            $this->availableLibraryHooks,
            array_flip($this->enabledLibraryHooks)
        ));
    }

    public function getRequestMatchers()
    {
        if (is_null($this->enabledRequestMatchers)) {
            return array_values($this->availableRequestMatchers);
        }

        return array_values(array_intersect_key(
            $this->availableRequestMatchers,
            array_flip($this->enabledRequestMatchers)
        ));
    }

    public function addRequestMatcher($name, $callback)
    {
        $this->availableRequestMatchers[$name] = $callback;
        return $this;
    }

    public function enableRequestMatchers(array $matchers)
    {
        $invalidMatchers = array_diff($matchers, array_keys($this->availableRequestMatchers));
        if ($invalidMatchers) {
            throw new \InvalidArgumentException('Request matches do not exist: ' . join(', ', $invalidMatchers));
        }
        $this->enabledRequestMatchers = $matchers;
    }

    public function setCassettePath($cassettePath)
    {
        $this->cassettePath = $cassettePath;
        return $this;
    }
}
