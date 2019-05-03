<?php


defined('MOODLE_INTERNAL') || die();


/**
 * Multiple choice editing form definition.
 *
 */
class qtype_multichoice_advance_edit_form extends question_edit_form {

    protected function definition_inner($mform) {
        //ajour par proposition
        $this->add_per_answer_fields($mform, get_string('choiceno', 'qtype_multichoice_advance', '{no}'),
            question_bank::fraction_options_full(), max(4, QUESTION_NUMANS_START));

        $this->add_combined_feedback_fields(true);

    }

    protected function get_per_answer_fields($mform, $label, $gradeoptions, &$repeatedoptions, &$answersoption) {
        $repeated = array();

        //éditeur réponse
        $repeated[] = $mform->createElement('editor', 'answer',
            $label, array('rows' => 1), $this->editoroptions);

        //dropdown pour indiquer si la réponse est bonne ou pas
        $repeated[] = $mform->createElement('select', 'correct',
            get_string('correctanswer', 'qtype_multichoice_advance'), array(
                0 => get_string('false', 'qtype_multichoice_advance'),
                1 => get_string('true', 'qtype_multichoice_advance')));

        //éditeur feedback
        $repeated[] = $mform->createElement('editor', 'feedback',
            get_string('feedback', 'question'), array('rows' => 1), $this->editoroptions);

        $repeatedoptions['answer']['type'] = PARAM_RAW;
        $answersoption = 'answers';

        return $repeated;
    }

    protected function data_preprocessing($question) {

        //ces fonctions permettent de remttre dans les champs les valeurs enregistrés
        $question = parent::data_preprocessing($question);

        $question = $this->data_preprocessing_answers($question, true);
        
        $question = $this->data_preprocessing_answers_advance($question, true);

        $question = $this->data_preprocessing_combined_feedback($question, true);

        if (!empty($question->options)) {
            //$question->single = $question->options->single;
            $question->shuffleanswers = $question->options->shuffleanswers;
            //$question->answernumbering = $question->options->answernumbering;
        }   
        return $question;
    }

    //on vérifie si le formulaire envoyé est valide (existance de champs, longueur)
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);
        $answers = $data['answer'];
        $answercount = 0;

        $totalfraction = 0;
        $maxfraction = -1;

        foreach ($answers as $key => $answer) {
            $trimmedanswer = trim($answer['text']);
            $fraction = (float) $data['fraction'][$key];
            if ($trimmedanswer === '' && empty($fraction)) {
                continue;
            }
            if ($trimmedanswer === '') {
                $errors['fraction['.$key.']'] = get_string('errgradesetanswerblank', 'qtype_multichoice');
            }

            $answercount++;
        }
        if ($answercount == 0) {
            $errors['answer[0]'] = get_string('notenoughanswers', 'qtype_multichoice_advance', 2);
            $errors['answer[1]'] = get_string('notenoughanswers', 'qtype_multichoice_advance', 2);
        } else if ($answercount == 1) {
            $errors['answer[1]'] = get_string('notenoughanswers', 'qtype_multichoice_advance', 2);

        }
        return $errors;
    }

    public function qtype() {
        return 'multichoice_advance';
    }

    /**
     * Perform the necessary preprocessing for the fields added by
     * {@link add_per_answer_fields()}.
     * @param object $question the data being passed to the form.
     * @return object $question the modified data.
     */
    protected function data_preprocessing_answers_advance($question, $withanswerfiles = false) {
        //cette fonction me permet d'avoir la valeur enregistrée du dropdown
        if (empty($question->options->answers)) {
            return $question;
        }

        $key = 0;
        foreach ($question->options->answers as $answer) {
            if($question->fraction[$key] > 0){
                $question->correct[$key]['correct'] = true;
            }
            $key++;
        }

        // Now process extra answer fields.
        $extraanswerfields = question_bank::get_qtype($question->qtype)->extra_answer_fields();
        if (is_array($extraanswerfields)) {
            // Omit table name.
            array_shift($extraanswerfields);
            $question = $this->data_preprocessing_extra_answer_fields($question, $extraanswerfields);
        }

        return $question;
    }
}
