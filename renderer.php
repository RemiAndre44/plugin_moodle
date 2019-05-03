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
 * MULTICHOICE_ADVANCE question renderer class.
 *
 * @package    qtype
 * @subpackage MULTICHOICE_ADVANCE
 * @copyright  2019 Rémi André (YOURCONTACTINFO)

 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();


/**
 * Generates the output for YOURQTYPENAME questions.
 *
 * @copyright  2019 Rémi André (YOURCONTACTINFO)

 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_multichoice_advance_renderer extends qtype_renderer {

    public function formulation_and_controls(question_attempt $qa,
            question_display_options $options) {

        var_dump("formulation_and_controls");

        $question = $qa->get_question();

        $result = '';

        $result .= html_writer::tag('div', $question->format_questiontext($qa),
                array('class' => 'qtext'));
        foreach ($question->answers as $key => $answer) {

            $result .= html_writer::tag('div', $answer->answer,
                array('class' => 'qtext'));

            $value = 'true';
            $result .= html_writer::start_tag('input', array('id' => 'input'.$answer->id, 'value' => 'dontknow', "type" => "hidden"));
            $result .= html_writer::end_tag('input');
            $result .= html_writer::start_tag('i', array('class'=>'fa fa-question iconSelect', 'id' => 'responseIcon'.$answer->id));
            $this->page->requires->js_call_amd('qtype_multichoice_advance/script','changeState', array($value,$answer->id));

            $result .= html_writer::end_tag('i');
        }

        return $result;
    }

    public function specific_feedback(question_attempt $qa) {
        $question = $qa->get_question();
        $response = $qa->get_last_qt_var('answer', '');

        if ($response) {
            return $question->format_text($question->truefeedback, $question->truefeedbackformat,
                $qa, 'question', 'answerfeedback', $question->trueanswerid);
        } else if ($response !== '') {
            return $question->format_text($question->falsefeedback, $question->falsefeedbackformat,
                $qa, 'question', 'answerfeedback', $question->falseanswerid);
        }
    }

    public function correct_response(question_attempt $qa) {
        $question = $qa->get_question();

        if ($question->rightanswer) {
            return get_string('correctanswertrue', 'qtype_truefalse');
        } else {
            return get_string('correctanswerfalse', 'qtype_truefalse');
        }
    }

    protected function get_input_name(question_attempt $qa, $value) {
        return $qa->get_qt_field_name('answer');
    }

    protected function get_input_id(question_attempt $qa, $value) {
        return $this->get_input_name($qa, $value);
    }

    protected function is_right(question_answer $ans) {
        if ($ans->fraction > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    /*public function correct_response(question_attempt $qa) {
        $question = $qa->get_question();

        $right = array();
        foreach ($question->answers as $ansid => $ans) {
            if ($ans->fraction > 0) {
                $right[] = $question->make_html_inline($question->format_text($ans->answer, $ans->answerformat,
                        $qa, 'question', 'answer', $ansid));
            }
        }
        return $this->correct_choices($right);
    }*/
}
