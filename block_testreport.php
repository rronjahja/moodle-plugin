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
 * @copyright 2020 Rron Jahja <rronjahja@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_testreport extends block_base
{
    /**
     * This is essential for all blocks, and its purpose is to give values to any class member variables that need instantiating.
     * $this->title is the title displayed in the header of our block. In this case it's set to read the actual title from the language file
     */

    function init()
    {
        $this->title = get_string('pluginname', 'block_testreport');

    }

    /**
     * This method adds the block to the front page. First we check if it is not NULL, then return the actual content.
     * Otherwise, if it is NULL, define it from the scratch.
     *
     *
     * $quiz_attempts: Calls get_records_sql('query') method to get all attempts of quizzes of the user in one module. The value of 0 is passed to deletioninprogress field to
     *                 filter the quizzes that are deleted.
     *
     * $quiz_in_modules: Calls get_records_sql('query') method to get all available quizzes in a module.
     *                   17 is the identification value for quizzes in course_modules table.
     *
     * $percentage: Percentage is calculated by dividing the number of $quiz_attempts and $quiz_in_modules.
     *
     * $rank: Is initialized dynamically by comparing it's value to the rank interval.
     */
    function get_content()
    {
        global $DB;
        global $COURSE;
        global $USER;

        if ($this->content !== NULL) {
            return $this->content;
        }

        $content = '';
     //Get all attempts of quizes of the user in one module (subject)
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

        //Get all available quizes in a module (subject)
        //17 is the value of quiz in course_modules
        $quiz_in_modules = $DB->get_records_sql('SELECT count(course) as allquizzes FROM {course_modules} 
                                                     where course=? and module = ? and deletioninprogress=?;', [$COURSE->id, 17, 0]);
        //Show all attempts
        foreach ($quiz_attempts as $quiz_finished) {
            $content .= $quiz_finished->counter . '<br>';
        }
        //Show all quizes
        foreach ($quiz_in_modules as $quiz_in_module) {
            $content2 .= $quiz_in_module->allquizzes . '<br>';
        }

        $this->content = new stdClass;

        //Calculate percentage
        $percentage = ($content / $content2) * 100;
        if ($content > $content2) {
            $percentage = 100;
        }
        if (empty($percentage)) {
            $percentage = 0;
        }

        //Create rank system
        if ($percentage >= 80 && $percentage < 90) {
            $rank = get_string('rank', 'block_testreport', '3rd', true);
        } else if ($percentage >= 90 && $percentage < 100) {
            $rank = get_string('rank', 'block_testreport', '2nd', true);
        } else if ($percentage == 100) {
            $rank = get_string('rank', 'block_testreport', '1st', true);
        } else {
            $rank = get_string('rank', 'block_testreport', 'not high enough!', true);
        }

        $comp = get_string('completed', 'block_testreport', $percentage);

        //echo get_string('testblock:completed', 'block_testblock');
        $this->content->text = $rank . '<br>' . $comp;
//        $this->content->text = $comp;
        $this->content->footer = " <progress id='file' value='$percentage' max='100'>  % </progress>" . $content2->allquizzes;
        return $this->content;
    }


}
