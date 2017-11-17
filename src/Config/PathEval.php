<?php

namespace Logikos\Util\Config;

use Logikos\Util\Config;

class PathEval {
  /**
   * @var Config
   */
  private $config;

  public function __construct(Config $config) {
    $this->config = $config;
  }

  public function find($path, $default=null, $delimiter='.') {
    if ($this->pathStartsWithConfig($path, $delimiter))
      return $this->evalSubPath($path, $delimiter, $default);

    return $this->get($this->getFirstToken($path, $delimiter), $default);
  }

  private function get($name, $default=null) {
    return $this->config->get($name, $default);
  }

  private function pathStartsWithConfig($path, $delimiter) {
    return $this->getFirstTokenValue($path, $delimiter) instanceof Config;
  }

  private function getFirstTokenValue($path, $delimiter) {
    return $this->get($this->getFirstToken($path, $delimiter));
  }

  private function getFirstToken($path, $delimiter) {
    return $this->gettok($path, $delimiter,0);
  }

  private function evalSubPath($path, $delimiter, $default) {
    return $this->getFirstTokenValue($path, $delimiter)->path(
        $this->subtok($path, $delimiter, 1),
        $default,
        $delimiter
    );
  }

  /**
   * subtok(string, delimiter, offset, length)
   *
   * Usage:
   *  subtok('a.b.c.d.e','.',0)     = 'a.b.c.d.e'
   *  subtok('a.b.c.d.e','.',0,2)   = 'a.b'
   *  subtok('a.b.c.d.e','.',2,1)   = 'c'
   *  subtok('a.b.c.d.e','.',2,-1)  = 'c.d'
   *  subtok('a.b.c.d.e','.',-4)    = 'b.c.d.e'
   *  subtok('a.b.c.d.e','.',-4,2)  = 'b.c'
   *  subtok('a.b.c.d.e','.',-4,-1) = 'b.c.d'
   *
   * @param  string   $string    The input string
   * @param  string   $delimiter The boundary string
   * @param  int      $offset    starting position, like in substr
   * @param  int|null $length    length, like in substr
   * @return string
   */
  private function subtok($string, $delimiter, $offset, $length = NULL) {
    return implode($delimiter, array_slice(explode($delimiter, $string), $offset, $length));
  }

  private function gettok($string, $delimiter, $offset) {
    return explode($delimiter, $string)[$offset];
  }
}