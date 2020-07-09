<?php
/**
 * sFire Framework (https://sfire.io)
 *
 * @link      https://github.com/sfire-framework/ for the canonical source repository
 * @copyright Copyright (c) 2014-2020 sFire Framework.
 * @license   http://sfire.io/license BSD 3-CLAUSE LICENSE
 */

declare(strict_types=1);

namespace sFire\Session;


/**
 * Interface SessionInterface
 * @package sFire\Session
 */
interface SessionInterface {


    /**
     * Stores a new piece of data and tries to merge the data if already exists
     * @param string|array $key
     * @param mixed $value
     * @return bool if value has been set
     */
    public function add($key, $value);


    /**
     * Stores a new piece of data and overwrites the data if already exists
     * @param mixed $key
     * @param mixed $value
     * @return void
     */
    public function set($key, $value): void;


    /**
     * Check if an item exists
     * @param mixed $key
     * @return bool
     */
    public function has($key): bool;


    /**
     * Deletes all data
     * @return void
     */
    public function flush(): void;


    /**
     * Remove data based on key
     * @param mixed $key
     * @return void
     */
    public function remove($key): void;


    /**
     * Retrieve data based on key. Returns $default if not exists
     * @param mixed $key
     * @param mixed $default A default value which will be returned if the key is not found
     * @return mixed
     */
    public function get($key, $default = null);


    /**
     * Retrieve and delete an item. Returns $default if not exists
     * @param string|array $key
     * @param mixed $default A default value which will be returned if the key is not found
     * @return mixed
     */
    public function pull($key, $default = null);


    /**
     * Retrieve all stored data
     * @return mixed
     */
    public function all();
}