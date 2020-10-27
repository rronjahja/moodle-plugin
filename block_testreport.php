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
 * Form for editing HTML block instances.
 *
 * @package   block_testblock
 * @copyright 1999 onwards Martin Dougiamas (http://dougiamas.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_testreport extends block_base
{

    function init()
    {
        $this->title = get_string('pluginname', 'block_testreport');

    }


    function get_content()
    {
        global $DB;
        global $COURSE;
        global $USER;

        if ($this->content !== NULL) {
            return $this->content;
        }

        $content = '';
        $percentage = 0;

//        $quiz_attempts = $DB->get_records_sql('select count(state) as state from {quiz_attempts} join {quiz}
//            on {quiz_attempts}.quiz={quiz}.id join {course_modules} on {quiz}.course={course_modules}.course
//            where {quiz}.course=? and userid=? and state=? and deletioninprogress=?', [$COURSE->id, $USER->id, 'finished',0]);

        $quiz_attempts = $DB->get_records_sql('SELECT COUNT(*) as counter FROM ( 
                                                                                select count(*) as subsubcount	from  {quiz}  
                                                                                			join {quiz_attempts} 
                                                                                			    on {quiz}.id={quiz_attempts}.quiz 
                                                                                			join {course_modules} 
                                                                                				on {quiz}.course={course_modules}.course
                                                                                			where {quiz}.course=?
                                                                                			and userid=?
                                                                                			and {quiz_attempts}.state=?
                                                                                			and deletioninprogress=?
                                                                                			group by {quiz_attempts}.quiz) as subcount;', [$COURSE->id, $USER->id, 'finished', 0]);


        //17 is the value of quiz in course_modules
        $quiz_in_modules = $DB->get_records_sql('SELECT count(course) as allquizzes FROM {course_modules} 
                                                     where course=? and module = ? and deletioninprogress=?;', [$COURSE->id, 17, 0]);

        foreach ($quiz_attempts as $quiz_finished) {
            $content .= $quiz_finished->counter . '<br>';
        }
        foreach ($quiz_in_modules as $quiz_in_module) {
            $content2 .= $quiz_in_module->allquizzes . '<br>';
        }

        $this->content = new stdClass;

        $percentage = ($content / $content2) * 100;
        if ($content > $content2) {
            $percentage = 100;
        }
//        if (empty($percentage) || isset($percentage) == false || is_null($percentage)) {
        if (empty($percentage)) {
            $percentage = 0;
        }

        if ($percentage >= 80 && $percentage < 90) {
            $rank = get_string('rank', 'block_testreport', '3rd', true);
        } else if ($percentage >= 90 && $percentage < 100) {
            $rank = get_string('rank', 'block_testreport', '2nd', true);
        } else if ($percentage == 100) {
            $rank = get_string('rank', 'block_testreport', '1st', true);
        } else {
            $rank = get_string('rank', 'block_testreport', 'not defined', true);
        }
        echo $percentage . "adsadas";
        $comp = get_string('completed', 'block_testreport', $percentage);

        //echo get_string('testblock:completed', 'block_testblock');
        $this->content->text = $rank . '<br>' . $comp;
//        $this->content->text = $comp;
        $this->content->footer = " <progress id='file' value='$percentage' max='100'>  % </progress>" . $content2->allquizzes;
        return $this->content;
    }


}
