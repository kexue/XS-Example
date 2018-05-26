<?php
/**
 * Created by PhpStorm.
 * User: kexue
 * Date: 2018/5/11
 * Time: 下午3:45
 */
namespace App\Libs\Xunsearch;

use App\Exceptions\XunsearchException;

class Search extends \XS
{
    protected $config = [
        'flush_index'     => false,     //立即刷新索引
        'set_fuzzy'       => false,     //开启模糊搜索
        'auto_synonyms'   => false,     //开启同义词搜索
    ];

    public function __construct($file, array $config = [])
    {
        parent::__construct($file);
        if (!empty($config)){
            $this->config = array_merge($this->config,$config);
        }
    }

    /**
     * 生成搜索字符串
     * @param array $keywords
     * @return string
     */
    public function genKeyword(array $keywords)
    {
        $strings = [];
        foreach ($keywords as $key => $val) {
            if (!$val) {
                continue;
            }
            $vals = explode(' ', $val);
            $words = [];
            foreach ($vals as $val) {
                if (empty($val)) {
                    continue;
                }

                $words[] = "{$key}:{$val}";
            }
            $strings[] = implode(' ', $words);
        }
        return implode(' ', $strings);
    }

    /**
     * 增加或更新索引
     * @param array $data
     * @return \XSIndex
     * @throws XunsearchException
     * @throws \XSException
     */
    public function replaceIndex(array $data)
    {
        if (!$data) {
            throw new XunsearchException('缺少索引数据');
        }
        if (count($data) == count($data, 1)) {
            // 一维数组
            $this->getIndex()->update(new \XSDocument($data));
        } else {
            // 多维数组
            foreach ($data as $v) {
                $this->getIndex()->update(new \XSDocument($v));
            }
        }
        //索引是否立即生效
        if ($this->config['flush_index']) {
            $this->getIndex()->flushIndex();
        }
        return $this->getIndex();
    }

    /**
     * 增加索引
     * @param array $data
     * @return bool|\XSIndex
     * @throws \XSException
     */
    public function addIndex(array $data)
    {
        if (!$data) {
            return false;
        }
        if (count($data) == count($data, 1)) {
            // 一维数组
            $this->getIndex()->add(new \XSDocument($data));
        } else {
            // 多维数组
            foreach ($data as $v) {
                $this->getIndex()->add(new \XSDocument($v));
            }
        }
        //索引是否立即生效
        if ($this->config['flush_index']) {
            $this->getIndex()->flushIndex();
        }
        return $this->getIndex();
    }

    /**
     * 删除索引
     * @param $ids
     * @return $this
     */
    public function delIndex($ids)
    {
        return $this->getIndex()->del($ids);
    }

    /**
     * 开始重建索引
     * @return $this
     */
    public function beginRebuild()
    {
        return $this->getIndex()->beginRebuild();
    }

    /**
     * 结束重建索引
     * @return $this
     */
    public function endRebuild()
    {
        return $this->getIndex()->endRebuild();
    }


    /**
     * 匹配搜索
     * @param $string 搜索字符串
     * @param $sorts 排序
     * @param $page 页码
     * @param $perPage 页条数
     * @return array 结果集
     * @throws XunsearchException
     * @throws \XSException
     */
    public function searchAll($string, $sorts, $page, $perPage)
    {
        if (!is_string($string)) {
            throw new XunsearchException('请输入搜索关键词');
        }

        $search = $this->getSearch();
        //设置模糊搜索
        $search->setFuzzy($this->config['set_fuzzy']);
        //设置同义词搜索
        $search->setAutoSynonyms($this->config['auto_synonyms']);
        //设置查询关键词
        $search->setQuery($string);
        //设置排序
        !empty($sorts) && $search->setMultiSort($sorts);
        //设置分页
        $search->setLimit($perPage, ($page - 1) * $perPage);

        $searchBegin = microtime(true);
        //获取数据
        $docs = $search->search();
        $searchCost = microtime(true) - $searchBegin;

        $count = $search->getLastCount();    //最近一次搜索结果数
        return [
            'list'          => $docs,        //搜索数据结果文档
            'count'         => $count,       //搜索结果统计
            'search_cost'   => $searchCost,  //搜索所用时间
            'per_page'      => (int)$perPage,    //页条数
            'current_page'  => (int)$page,    //页码
        ];
    }
}