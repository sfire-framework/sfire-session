<?php
/**
 * sFire Framework (https://sfire.io)
 *
 * @link      https://github.com/sfire-framework/ for the canonical source repository
 * @copyright Copyright (c) 2014-2020 sFire Framework.
 * @license   http://sfire.io/license BSD 3-CLAUSE LICENSE
 */

declare(strict_types=1);

namespace sFire\Session\Adapter;

use sFire\DataControl\Translators\TranslatorInterface;
use sFire\DataControl\Translators\StringTranslator;
use sFire\DataControl\Token;
use sFire\Session\Exception\RuntimeException;
use sFire\Session\SessionInterface;


/**
 * Class Session
 * @package sFire\Session
 */
class Session implements SessionInterface {


    /**
     * Contains an instance of a class that implements the TranslatorInterface
     * @var TranslatorInterface
     */
    private ?TranslatorInterface $translator = null;


    /**
     * Constructor
     */
    public function __construct() {

        if(false === isset($_SESSION)) {

            $sessionId = '';

            if('1' === ini_get('session.use_cookies') && true === isset($_COOKIE[session_name()])) {
                $sessionId = $_COOKIE[session_name()];
            }
            elseif('1' !== ini_get('session.use_only_cookies') && true === isset($_GET[session_name()])) {
                $sessionId = $_GET[session_name()];
            }

            if(0 === preg_match('/^[a-zA-Z0-9\-]{32}$/', (string) $sessionId)) {
                session_id(Token :: create(32, true, true, true, false));
            }

            session_start();
        }
    }


    /**
     * Returns a class that implements the TranslatorInterface. Default is StringTranslator.
     * @param array $data
     * @return TranslatorInterface
     */
    public function getTranslator(array &$data): TranslatorInterface {

        if(null === $this -> translator) {
            $this -> translator = new StringTranslator($data);
        }

        return $this -> translator;
    }


    /**
     * Set a new translator for getting and retrieving values
     * @param TranslatorInterface $translator
     * @return void
     */
    public function setTranslator(TranslatorInterface $translator): void {
        $this -> translator = new $translator();
    }


    /**
     * Set the path where all the session files are saved
     * @param string $directory The directory where all the session files will be saved
     * @throws RuntimeException
     * @return void
     */
    public function setSessionSavePath(string $directory): void {

        if(false === is_writable($directory)) {
            throw new RuntimeException(sprintf('Argument 1 with value "%s" given to "%s" should be an existing and writable directory', $directory, __METHOD__));
        }

        session_save_path($directory);
    }


    /**
     * Stores a new piece of data and tries to merge the data if already exists
     * @param string|array $key
     * @param mixed $value
     */
    public function add($key, $value) {
        $this -> getTranslator($_SESSION) -> add($key, $value);
    }


    /**
     * Stores a new piece of data and overwrites the data if already exists
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value): void {
        $this -> getTranslator($_SESSION) -> set($key, $value);
    }


    /**
     * Check if an item exists
     * @param string $key
     * @return boolean
     */
    public function has($key): bool {
        return $this -> getTranslator($_SESSION) -> has($key);
    }


    /**
     * Deletes all data
     */
    public function flush(): void {
        $_SESSION = [];
    }


    /**
     * Remove data based on key
     * @param string $key
     */
    public function remove($key): void {
        $this -> getTranslator($_SESSION) -> remove($key);
    }


    /**
     * Retrieve data based on key. Returns $default if not exists
     * @param string|array $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null) {
        return $this -> getTranslator($_SESSION) -> get($key, $default);
    }


    /**
     * Retrieve and delete an item. Returns $default if not exists
     * @param string|array $key
     * @param mixed $default
     * @return mixed
     */
    public function pull($key, $default = null) {
        return $this -> getTranslator($_SESSION) -> pull($key, $default);
    }


    /**
     * Retrieve all data
     * @return mixed
     */
    public function all() {
        return $_SESSION;
    }


    /**
     * Regenerates session id
     * @return void
     */
    public static function regenerate(): void {
        session_regenerate_id();
    }


    /**
     * Returns the session id
     * @return string
     */
    public static function getSessionId(): string {
        return session_id();
    }
}