<?php
/**
 * Created by PhpStorm.
 * User: FloatFlower Huang
 * Date: 2018/5/6
 * Time: 下午 05:45
 */

namespace App\Exception;


use Throwable;

class PageRequestOutOfRange extends \Exception
{
    private $maxPage;

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function getMaxPage() {
        return $this->maxPage;
    }

    public function setMaxPage($maxPage) {
        $this->maxPage = $maxPage;
    }
}
