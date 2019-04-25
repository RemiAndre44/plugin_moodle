<?php

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->libdir . '/questionlib.php');


class qtype_multichoice_advance extends question_type {
    public function get_question_options($question) {
        global $DB, $OUTPUT;

        $question->options = $DB->get_record('qtype_multichoice_options', ['questionid' => $question->id]);

        if ($question->options === false) {
            // If this has happened, then we have a problem.
            // For the user to be able to edit or delete this question, we need options.
            debugging("Question ID {$question->id} was missing an options record. Using default.", DEBUG_DEVELOPER);

            $question->options = $this->create_default_options($question);
        }

        parent::get_question_options($question);
    }

    protected function create_default_options($question) {
        // Create a default question options record.
        $options = new stdClass();
        $options->questionid = $question->id;

        // Get the default strings and just set the format.
        $options->correctfeedback = get_string('correctfeedbackdefault', 'question');
        $options->correctfeedbackformat = FORMAT_HTML;
        $options->partiallycorrectfeedback = get_string('partiallycorrectfeedbackdefault', 'question');;
        $options->partiallycorrectfeedbackformat = FORMAT_HTML;
        $options->incorrectfeedback = get_string('incorrectfeedbackdefault', 'question');
        $options->incorrectfeedbackformat = FORMAT_HTML;

        $config = get_config('qtype_multichoice_advance');
        $options->single = $config->answerhowmany;
        if (isset($question->layout)) {
            $options->layout = $question->layout;
        }
        $options->answernumbering = $config->answernumbering;
        $options->shuffleanswers = $config->shuffleanswers;
        $options->shownumcorrect = 1;

        return $options;
    }

    public function save_question_options($question) {

        global $DB;
        $context = $question->context;
        $result = new stdClass();
        $totalCorrect = 0;
        $oldanswers = $DB->get_records('question_answers',
            array('question' => $question->id), 'id ASC');

        // Following hack to check at least two answers exist.
        $answercount = 0;
        foreach ($question->answer as $key => $answer) {
            if ($answer != '') {
                $answercount++;
            }
            if($question->correct[$key] == '1'){
                $totalCorrect++;
            }
        }
        if ($answercount < 2) { // Check there are at lest 2 answers for multiple choice.
            $result->error = get_string('notenoughanswers', 'qtype_multichoice_advance', '2');
            return $result;
        }

        // Insert all the new answers.
        $totalfraction = 0;
        $maxfraction = -1;
        $correctFraction = 1/$totalCorrect;
        $incorrectFraction = -(1/$answercount);
        foreach ($question->answer as $key => $answerdata) {
            if (trim($answerdata['text']) == '') {
                continue;
            }

            // Update an existing answer if possible.
            $answer = array_shift($oldanswers);
            if (!$answer) {
                $answer = new stdClass();
                $answer->question = $question->id;
                $answer->answer = '';
                $answer->feedback = '';
                $answer->id = $DB->insert_record('question_answers', $answer);
            }

            // Doing an import.
            $answer->answer = $this->import_or_save_files($answerdata,
                $context, 'question', 'answer', $answer->id);
            $answer->answerformat = $answerdata['format'];
            $answer->fraction = $question->fraction[$key];
            $answer->feedback = $this->import_or_save_files($question->feedback[$key],
                $context, 'question', 'answerfeedback', $answer->id);
            $answer->feedbackformat = $question->feedback[$key]['format'];

            if($question->correct[$key] == '1'){
                $answer->fraction = $correctFraction;
            }else{
                $answer->fraction = $incorrectFraction;
            }

            $DB->update_record('question_answers', $answer);

        }

        // Delete any left over old answer records.
        $fs = get_file_storage();
        foreach ($oldanswers as $oldanswer) {
            $fs->delete_area_files($context->id, 'question', 'answerfeedback', $oldanswer->id);
            $DB->delete_records('question_answers', array('id' => $oldanswer->id));
        }

        $options = $DB->get_record('qtype_multichoice_advance', array('questionid' => $question->id));

    }

    protected function make_question_instance($questiondata) {
        question_bank::load_question_definition_classes($this->name());
        if ($questiondata->options->single) {
            $class = 'qtype_multichoice_advance_single_question';
        } else {
            $class = 'qtype_multichoice_advance_multi_question';
        }
        return new $class();
    }

    protected function initialise_question_instance(question_definition $question, $questiondata) {
        parent::initialise_question_instance($question, $questiondata);
        $question->shuffleanswers = $questiondata->options->shuffleanswers;
        $question->answernumbering = $questiondata->options->answernumbering;
        if (!empty($questiondata->options->layout)) {
            $question->layout = $questiondata->options->layout;
        } else {
            $question->layout = qtype_multichoice_advance_single_question::LAYOUT_VERTICAL;
        }
        $this->initialise_combined_feedback($question, $questiondata, true);

        $this->initialise_question_answers($question, $questiondata, false);
    }

    public function make_answer($answer) {
        var_dump("yo");
    }

    public function delete_question($questionid, $contextid) {
        var_dump("yo");
    }

    public function get_random_guess_score($questiondata) {
        var_dump("yo");
    }

    public function get_possible_responses($questiondata) {
        var_dump("yo");
    }


    public static function get_numbering_styles() {
        var_dump("yo");
    }

    public function move_files($questionid, $oldcontextid, $newcontextid) {
        var_dump("yo");
    }

    protected function delete_files($questionid, $contextid) {
        var_dump("yo");
    }
}
