<?php

//This file cannot be called directly, only included.
if (str_replace(DIRECTORY_SEPARATOR, "/", __FILE__) == $_SERVER['SCRIPT_FILENAME']) {
    exit;
}

class Cache
{
 public static $cacheTimeout = 604800; //3600*24*7, 1 week

 public static function getCache($parameters)
 {
  $key = self :: encode($parameters);

  $result = sC_getTableData("cache", "value, timestamp, timeout", "cache_key='".$key."'");
  if (sizeof($result) > 0 && time() - $result[0]['timestamp'] <= self :: $cacheTimeout && ($result[0]['timeout'] && time() - $result[0]['timestamp'] <= $result[0]['timeout'])) {
   return $result[0]['value'];
  } else {
   return false;
  }
 }

 public static function setCache($parameters, $data, $timeout = false)
 {
  $key = self :: encode($parameters);
  $values = array("cache_key" => $key, "value" => $data, "timestamp" => time());
  if ($timeout && sC_checkParameter($timeout, 'int')) {
   $values['timeout'] = $timeout;
  }

  if (sizeof(sC_getTableData("cache", "value", "cache_key='".$key."'")) > 0) {
   $result = sC_updateTableData("cache", $values, "cache_key='$key'");
  } else {
   $result = sC_insertTableData("cache", $values);
  }

  return $result;
 }

 public static function resetCache($parameters)
 {
  $key = self :: encode($parameters);

  sC_deleteTableData("cache", "cache_key='".$key."'");
 }

 private static function encode($parameters)
 {
  $key = hash('sha256', $parameters);

  return $key;
 }

}

class MagesterCacheException extends Exception
{
    const KEY_NOT_FOUND = 1401;
    const KEY_EXPIRED = 1402;
    const ENTRY_INVALID = 1403;
}

abstract class MagesterCache
{
    public $cacheTimeout = 604800; //3600*24*7, 1 week

    public abstract function setCache($key, $entity, $timeout);
    public abstract function getCache($key);
    public abstract function deleteCache($key);
}

class CacheFactory
{
    public static function factory()
    {
        switch ($GLOBALS['configuration']['cache_method']) {
            case 'apc': $cache = new MagesterCacheAPC(); break;
            case 'memcache': $cache = new MagesterCacheMemcache(); break;
            case 'db':
            default: $cache = new MagesterCacheDB(); break;
        }
    }
}

class MagesterCacheDB extends MagesterCache
{
    //public $keys = array()

    public function setCache($key, $entity, $timeout)
    {
  $values = array("cache_key" => $key, "value" => serialize($entity), "timestamp" => time());

  if ($this -> get($parameters)) {
   $result = sC_updateTableData("cache", $values, "cache_key='$key'");
  } else {
   $result = sC_insertTableData("cache", $values);
  }

  return $result;

    }

    public function deleteCache($key)
    {
        sC_deleteTableData("cache", "cache_key='".$key."'");
    }

    public function getCache($key)
    {
  $result = sC_getTableData("cache", "value, timestamp", "cache_key='".$key."'");
  if (sizeof($result) > 0 || time() - $result['timestamp'] <= $this -> cacheTimeout) {
   if ($result[0]['value'] !== serialize(false)) {
       $result[0]['value'] = unserialize($result[0]['value']);
       if ($result[0]['value'] !== false) {
           return $result[0]['value'];
       } else {
           $this -> delete($key);
           throw new MagesterCacheException(_CACHEENTRYINVALID, MagesterCacheException::ENTRY_INVALID);
       }
   } else {
       return false; //This means that the serialized value was "false"
   }
  } elseif (time() - $result['timestamp'] <= $this -> cacheTimeout) {
      $this -> delete($key);
      throw new MagesterCacheException(_CACHEENTRYEXPIRED, MagesterCacheException::KEY_EXPIRED);
  } else {
      throw new MagesterCacheException(_CACHEENTRYNOTFOUND, MagesterCacheException::KEY_NOT_FOUND);
  }
    }

    public function deleteCacheBasedOnKeyFilter($filter)
    {
        //sC_deleteTableData("cache")
    }
}
/*

class MagesterCacheAPC implements iCache

{

    public function __construct($method)
    {
    }

    public function setCache($key, $entity, $timeout)
    {
    }

    public function deleteCache($key)
    {
    }

    public function getCache($key)
    {
    }

}

class MagesterCacheMemcache implements iCache

{

    public function __construct($method)
    {
    }

    public function setCache($key, $entity, $timeout)
    {
    }

    public function deleteCache($key)
    {
    }

    public function getCache($key)
    {
    }

}

*/
