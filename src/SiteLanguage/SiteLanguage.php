<?php
declare(strict_types=1);

namespace Cognetif\SiteLanguage;

use Cognetif\SiteLanguage\Exceptions\InvalidConfigurationException;
use Cognetif\SiteLanguage\Exceptions\MissingServerAcceptLanguageException;

final class SiteLanguage
{
    /** @var array */
    private $accepted = [];

    /** @var  string */
    private $default = '';

    /**
     * SiteLanguage constructor.
     * @param array $accepted
     * @param string $default
     */
    public function __construct($accepted = [], $default = '')
    {
        $this->accepted = $accepted;
        $this->default = $default;
    }

    /**
     * @return string
     * @throws InvalidConfigurationException
     */
    public function get(): string
    {
        $this->guardIsConfigured();

        try {
            $serverAccepts = $this->getServerAccepts();
            return $this->getBestMatch($serverAccepts, $this->accepted);
        } catch (MissingServerAcceptLanguageException $e) {
            return $this->default;
        }
    }

    /**
     * @param $serverAccepts
     * @param $siteLanguages
     * @return string
     */
    private function getBestMatch($serverAccepts, $siteLanguages): string
    {
        $serverLang = explode(',', $serverAccepts);

        array_walk($serverLang, function (&$item) {

            $itemParts = explode(';', $item);
            $localParts = [];

            if (count($itemParts) > 0) {
                $localParts = explode('-', $itemParts[0]);
            }

            if (count($localParts) > 0) {
                $item = $localParts[0];
            }

        });

        $serverLangUnique = array_unique($serverLang);
        $sharedLangs = array_intersect($serverLangUnique, $siteLanguages);
        $found = array_shift($sharedLangs);

        if ($found) {
            return $found;
        }

        return $this->default;
    }

    /**
     * @return mixed
     * @throws MissingServerAcceptLanguageException
     */
    private function getServerAccepts()
    {
        if (array_key_exists('HTTP_ACCEPT_LANGUAGE', $_SERVER) && !is_null($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            return $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        }

        throw new MissingServerAcceptLanguageException();
    }


    /**
     * @return array
     */
    public function getAccepted(): array
    {
        return $this->accepted;
    }

    /**
     * @param array $accepted
     * @return SiteLanguage
     */
    public function setAccepted(array $accepted): SiteLanguage
    {
        $this->accepted = $accepted;
        return $this;
    }

    /**
     * @return string
     */
    public function getDefault(): string
    {
        return $this->default;
    }

    /**
     * @param string $default
     * @return SiteLanguage
     */
    public function setDefault(string $default): SiteLanguage
    {
        $this->default = $default;
        return $this;
    }

    /**
     * @throws InvalidConfigurationException
     */
    private function guardIsConfigured()
    {
        $this->guardAcceptedParam();
        $this->guardDefaultParam();
    }

    /**
     * @throws InvalidConfigurationException
     */
    private function guardAcceptedParam()
    {
        if (!is_array($this->accepted) || empty($this->accepted)) {
            throw new InvalidConfigurationException();
        }
    }

    /**
     * @throws InvalidConfigurationException
     */
    private function guardDefaultParam()
    {
        if (!is_string($this->default) || strlen($this->default) === 0) {
            throw new InvalidConfigurationException();
        }
    }

}