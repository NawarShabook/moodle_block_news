<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Block definition class for the block_news plugin.
 *
 * @package   block_news
 * @copyright 2023 Nawar Shabook <nawarshabook@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_news extends block_base {

    /**
     * Initialises the block.
     *
     * @return void
     */
    public function init() {
        $this->title = get_string('pluginname', 'block_news');
    }

    /**
     * Gets the block contents.
     *
     * @return string The block HTML.
     */
    public function get_content() {
        global $OUTPUT;
        global $DB;
       
        if ($this->content !== null) {
            return $this->content;
        }
        $this->content = new stdClass();
        // $news=$DB->get_records('local_news');
        $sql = "SELECT m.id, m.title,m.content, m.timecreated, m.categoryid,m.image, u.category_name
              FROM {local_news} m  LEFT JOIN {local_news_categories} u 
              ON u.id = m.categoryid ORDER BY timecreated DESC LIMIT 5";
              
        $news = $DB->get_records_sql($sql);

       
        $news_array = array();
        foreach ($news as $article) {
            $news_array[] = (array) $article;
        }
        $data = array('news' => $news_array);
    
        $this->content->text = $OUTPUT->render_from_template('block_news/content', $data);

        // print_r($news);
        
        return $this->content;
    }

    /**
     * Defines in which pages this block can be added.
     *
     * @return array of the pages where the block can be added.
     */
    public function applicable_formats() {
        return array('all' => true);
        // return [
        //     'admin' => false,
        //     'site-index' => true,
        //     'course-view' => true,
        //     'mod' => false,
        //     'my' => true,
        // ];
    }
}