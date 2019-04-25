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
 * Multiple choice question definition classes.
 *
 * @package    qtype
 * @subpackage multichoice
 * @copyright  2009 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/question/type/questionbase.php');

/**
 * Base class for multiple choice questions. The parts that are common to
 * single select and multiple select.
 *
 * @copyright  2009 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class qtype_multichoice_advance_base extends question_graded_automatically {
    const LAYOUT_DROPDOWN = 0;
    const LAYOUT_VERTICAL = 1;
    const LAYOUT_HORIZONTAL = 2;

    public $answers;

    public $shuffleanswers;
    public $answernumbering;
    public $layout = self::LAYOUT_VERTICAL;

    public $correctfeedback;
    public $correctfeedbackformat;
    public $partiallycorrectfeedback;
    public $partiallycorrectfeedbackformat;
    public $incorrectfeedback;
    public $incorrectfeedbackformat;

    protected $order = null;

    public function start_attempt(question_attempt_step $step, $variant) {

    }

    public function apply_attempt_state(question_attempt_step $step) {

    }

    public function get_question_summary() {

    }

    public function get_order(question_attempt $qa) {

    }

    protected function init_order(question_attempt $qa) {

    }

    public abstract function get_response(question_attempt $qa);

    public abstract function is_choice_selected($response, $value);

    public function check_file_access($qa, $options, $component, $filearea, $args, $forcedownload) {

    }
}


class qtype_multichoice_advance_single_question extends qtype_multichoice_base {
    public function get_renderer(moodle_page $page) {

    }

    public function get_min_fraction() {

    }


    public function get_expected_data() {

    }

    public function summarise_response(array $response) {

    }

    public function classify_response(array $response) {

    }

    public function get_correct_response() {

    }

    public function prepare_simulated_post_data($simulatedresponse) {

    }

    public function get_student_response_values_for_simulation($postdata) {

    }

    public function is_same_response(array $prevresponse, array $newresponse) {

    }

    public function is_complete_response(array $response) {

    }

    public function is_gradable_response(array $response) {

    }

    public function grade_response(array $response) {

    }

    public function get_validation_error(array $response) {

    }

    public function get_response(question_attempt $qa) {

    }

    public function is_choice_selected($response, $value) {

    }
}


/**
 * Represents a multiple choice question where multiple choices can be selected.
 *
 * @copyright  2009 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_multichoice_multi_question extends qtype_multichoice_base {
    public function get_renderer(moodle_page $page) {

    }

    public function get_min_fraction() {

    }

    public function clear_wrong_from_response(array $response) {

    }

    public function get_num_parts_right(array $response) {

    }

    protected function field($key) {

    }

    public function get_expected_data() {

    }

    public function summarise_response(array $response) {

    }

    public function classify_response(array $response) {

    }

    public function get_correct_response() {

    }

    public function prepare_simulated_post_data($simulatedresponse) {

    }

    public function get_student_response_values_for_simulation($postdata) {

    }

    public function is_same_response(array $prevresponse, array $newresponse) {

    }

    public function is_complete_response(array $response) {

    }

    public function is_gradable_response(array $response) {
    }


    public function get_num_selected_choices(array $response) {

    }

    public function get_num_correct_choices() {

    }

    public function grade_response(array $response) {

    }

    public function get_validation_error(array $response) {
    }


    protected function disable_hint_settings_when_too_many_selected(question_hint_with_parts $hint) {
    }

    public function get_hint($hintnumber, question_attempt $qa) {

    }

    public function get_response(question_attempt $qa) {
    }

    public function is_choice_selected($response, $value) {
    }
}
