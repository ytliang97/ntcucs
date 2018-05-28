<?php
/**
 * Created by PhpStorm.
 * User: FloatFlower Huang
 * Date: 2018/5/6
 * Time: 下午 05:42
 */

namespace App\Services;


use App\Exception\PageRequestOutOfRange;

class PaginationService
{
    private $entitiesPerPage = 20;

    public function setEntitiesPerpage($perPage) {
        $this->entitiesPerPage = $perPage;
    }

    public function getPagination($page, $entitiesAmount) {

        if ($entitiesAmount == 0) {
            $result = array();
            $result['hasNext'] = false;
            $result['hasPrev'] = false;
            $result['currentPageNumber'] = 1;
            $result['maxPageNumber'] = 1;
            $result['pageList'] = array();
            return $result;
        }

        $maxPage = (int)($entitiesAmount / $this->entitiesPerPage);
        $maxPage += $entitiesAmount % $this->entitiesPerPage == 0 ? 0 : 1;

        if ($page > $maxPage) {

            $e = new PageRequestOutOfRange("The page number: ".$page." out of range");
            $e->setMaxPage($maxPage);

            throw $e;
        }

        $result = array();
        $result['hasNext'] = $page < $maxPage;
        $result['hasPrev'] = $page > 1;
        $result['currentPageNumber'] = $page;
        $result['maxPageNumber'] = $maxPage;

        $pageList = array();
        $pageStart = max(1, $page - 2);
        $pageEnd = min($maxPage, $page + 2);

        if ($pageStart == 1) {
            for ($i = 1; $i <= 5 && $i <= $pageEnd; $i ++) {
                $pageList[] = $i;
            }
        }
        else if ($pageEnd == $maxPage) {
            for ($i = $pageStart; $i <= $pageEnd; $i ++) {
                $pageList[] = $i;
            }
        }
        else {
            for ($i = $pageStart; $i < $pageEnd; $i ++) {
                $pageList[] = $i;
            }
        }

        $result['pageList'] = $pageList;

        return $result;

    }

}