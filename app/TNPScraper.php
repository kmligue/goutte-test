<?php

namespace App;

use App\Scraper;

class TNPScraper extends Scraper
{
    private function scrape()
    {
        $this->crawler = $this->crawl();

        return $this;
    }

    public function __construct()
    {
        parent::__construct();

        $this->url                         = 'http://www.trendingnewsportal.net.ph/';
        $this->post_selector               = '.post-outer';
        $this->post_title_selector         = '.post.hentry .post-title a';
        $this->post_link_selector          = '.post.hentry .post-title a';
        $this->post_released_date_selector = '';
    }

    public function run()
    {
        return $this->scrape()->get_site_posts()->get();
    }
}