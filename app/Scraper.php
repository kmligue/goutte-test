<?php

namespace App;

use Goutte\Client;

abstract class Scraper
{
    protected $client;
    protected $crawler;
    protected $method;
    protected $url;

    protected $posts;
    protected $post_selector;
    protected $post_title_selector;
    protected $post_link_selector;
    protected $post_released_date_selector;

    protected function crawl()
    {
        return $this->client->request($this->method, $this->url);
    }

    protected function get()
    {
        return $this->posts;
    }

    /**
     * We need this because there are some sites doesn't have released date.
     * As much as possible we need to determine the released date.
     * If it returns nothing then we will just use the datetime the data was scraped
     */
    protected function get_released_date($node)
    {
        if ($this->post_released_date_selector == '') {
            return date('Y-m-d H:i:s');
        }

        $date = $node->filter($this->post_released_date_selector)->first()->text();
        $date = new DateTime($date);

        return $date->format('Y-m-d H:i:s');
    }

    /**
     * This will only get to the first page of the site
     */
    protected function get_site_posts()
    {
        $this->crawler->filter($this->post_selector)->each(function($node) {
            $url = $node->filter($this->post_link_selector)->first()->attr('href');

            $this->posts[$url] = array(
                'post-source'        => parse_url($url)['host'],
                'post-title'         => $node->filter($this->post_title_selector)->first()->text(),
                'post-released-date' => $this->get_released_date($node)
            );
        });

        return $this;
    }

    public function __construct()
    {
        $this->client              = new Client;
        $this->method              = 'GET';
        $this->url                 = '';
    }
}