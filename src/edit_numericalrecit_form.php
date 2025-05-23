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
 *
 * @package    qtype_numericalrecit
 * @copyright  2019 RECIT
 * @copyright  Based on work by 2010 Hon Wai, Lau <lau65536@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/question/type/edit_question_form.php');

/**
 * coodinate question type editing form definition.
 */
class qtype_numericalrecit_edit_form extends question_edit_form {

    /**
     * Add question-type specific form fields.
     *
     * @param MoodleQuickForm $mform the form being built.
     */
    protected function definition_inner($mform) {
        global $PAGE;
        
        $PAGE->requires->js('/question/type/numericalrecit/script/editing.js');
        $PAGE->requires->js('/question/type/numericalrecit/script/tablevalue.js');

        $PAGE->requires->string_for_js('relerror', 'qtype_numericalrecit');
        $PAGE->requires->string_for_js('abserror', 'qtype_numericalrecit');
        $PAGE->requires->string_for_js('instantiate', 'qtype_numericalrecit');
        $PAGE->requires->string_for_js('renew', 'qtype_numericalrecit');
        $PAGE->requires->string_for_js('answermissing', 'qtype_numericalrecit');
        $PAGE->requires->string_for_js('notavailable', 'qtype_numericalrecit');
        $PAGE->requires->string_for_js('fillallfields', 'qtype_numericalrecit');
        $PAGE->requires->string_for_js('addcolumn', 'qtype_numericalrecit');
        $PAGE->requires->string_for_js('addline', 'qtype_numericalrecit');
        $PAGE->requires->string_for_js('save', 'qtype_numericalrecit');
        $PAGE->requires->string_for_js('showexample', 'qtype_numericalrecit');
        $PAGE->requires->string_for_js('error_algebraic_var', 'qtype_numericalrecit');
        $PAGE->requires->string_for_js('unit', 'qtype_numericalrecit');
        $PAGE->requires->string_for_js('valuetable', 'qtype_numericalrecit');
        $PAGE->requires->string_for_js('docs', 'qtype_numericalrecit');
        $PAGE->requires->string_for_js('calculate', 'qtype_numericalrecit');
        $PAGE->requires->string_for_js('descriptionhere', 'qtype_numericalrecit');
        $PAGE->requires->js('/question/type/numericalrecit/script/formatcheck.js');
        $PAGE->requires->css('/question/type/numericalrecit/styles.css');
        // Hide the unused form fields.
        $mform->removeElement('defaultmark');
        $mform->addElement('hidden', 'defaultmark');
        $mform->setType('defaultmark', PARAM_RAW);

        $mform->addHelpButton('questiontext', 'questiontext', 'qtype_numericalrecit');


        $mform->insertElementBefore($mform->createElement('header', 'mainq', get_string('mainq', 'qtype_numericalrecit'),
            ''), 'questiontext');
        $mform->insertElementBefore($mform->createElement('checkbox', 'automark', get_string('automark', 'qtype_numericalrecit'), ''), 'name');
        $mform->insertElementBefore($mform->createElement('editor', 'intro', get_string('intro', 'qtype_numericalrecit'), array('rows' => 10), $this->editoroptions), 'name');
        $mform->addHelpButton('intro', 'intro', 'qtype_numericalrecit');
        $numberingoptions = question_bank::get_qtype('multichoice')->get_numbering_styles();
        $mform->addElement('select', 'answernumbering',
                get_string('answernumbering', 'qtype_multichoice'), $numberingoptions);
        $mform->setDefault('answernumbering', get_config('qtype_multichoice', 'answernumbering'));
        // Part's answers.
        $this->add_per_answer_fields($mform, get_string('answerno', 'qtype_numericalrecit', '{no}'),
            question_bank::fraction_options(), 1, 1);
        // Random and global variables and main question.
        $mform->addElement($mform->createElement('header', 'globalvarshdr', get_string('globalvarshdr', 'qtype_numericalrecit'),
            ''));

        $mform->addElement($mform->createElement('textarea', 'varsrandom', get_string('varsrandom', 'qtype_numericalrecit'),
            array('cols' => 80, 'rows' => 1)));
        $mform->addHelpButton('varsrandom', 'varsrandom', 'qtype_numericalrecit');

        $mform->addElement($mform->createElement('textarea', 'varsglobal', get_string('varsglobal', 'qtype_numericalrecit'),
            array('cols' => 80, 'rows'  => 1)));
        $mform->addHelpButton('varsglobal', 'varsglobal', 'qtype_numericalrecit');

        $mform->addElement($mform->createElement('button', 'valuetable', get_string('valuetable', 'qtype_numericalrecit')));
        // Display options, flow options and global part's options.
        $mform->addElement('header', 'subqoptions', get_string('subqoptions', 'qtype_numericalrecit'));

        $mform->addElement('text', 'globalunitpenalty',
                get_string('globaloptions', 'qtype_numericalrecit') . get_string('unitpenalty', 'qtype_numericalrecit'),
            array('size' => 3));
        $mform->addHelpButton('globalunitpenalty', 'unitpenalty', 'qtype_numericalrecit');
        $mform->setDefault('globalunitpenalty', 1);
        $mform->setAdvanced('globalunitpenalty');
        $mform->SetType('globalunitpenalty', PARAM_FLOAT);

        $conversionrules = new qtype_numericalrecit_unit_conversion_rules;
        $allrules = $conversionrules->allrules();
        foreach ($allrules as $id => $entry) {
            $defaultrulechoice[$id] = $entry[0];
        }
        $mform->addElement('select', 'globalruleid',
                get_string('globaloptions', 'qtype_numericalrecit') . get_string('ruleid', 'qtype_numericalrecit'),
            $defaultrulechoice);
        $mform->setDefault('globalruleid', 1);
        $mform->addHelpButton('globalruleid', 'ruleid', 'qtype_numericalrecit');
        
        $mform->addElement('header', 'steppart', get_string('steppart', 'qtype_numericalrecit'));
        $mform->addElement('text', 'stepmark', get_string('stepmark', 'qtype_numericalrecit'));
        $mform->setDefault('stepmark', 1);
        $mform->SetType('stepmark', PARAM_FLOAT);
        $mform->addElement('editor', 'stepfeedback', get_string('stepfeedback', 'qtype_numericalrecit'), array('rows' => 5), $this->editoroptions);
        $mform->addHelpButton('stepfeedback', 'stepfeedback', 'qtype_numericalrecit');

        // Allow instantiate random variables and display the data for instantiated variables.
        $mform->addElement('header', 'checkvarshdr', get_string('checkvarshdr', 'qtype_numericalrecit'));
        $mform->addElement('static', 'numdataset', get_string('numdataset', 'qtype_numericalrecit'),
            '<div id="numdataset_option"></div>');
            $mform->addHelpButton('numdataset', 'numdataset', 'qtype_numericalrecit');
        $mform->addElement('static', 'qtextpreview', get_string('qtextpreview', 'qtype_numericalrecit'),
            '<div id="qtextpreview_controls"></div>'
            .'<div id="qtextpreview_display"></div>');
        $mform->addElement('static', 'varsstatistics', get_string('varsstatistics', 'qtype_numericalrecit'),
            '<div id="varsstatistics_controls"></div>'
            .'<div id="varsstatistics_display"></div>');
        $mform->addElement('static', 'varsdata', get_string('varsdata', 'qtype_numericalrecit'),
            '<div id="varsdata_controls"></div>'
            .'<div id="varsdata_display"></div>');
        $mform->closeHeaderBefore('instantiatevars');

        $this->add_combined_feedback_fields(true);
        $this->add_interactive_settings(true, true);
    }


    /**
     * Add the answer field for a particular part labelled by placeholder.
     *
     * @param MoodleQuickForm $mform the form being built.
     */
    protected function get_per_answer_fields($mform, $label, $gradeoptions,
            &$repeatedoptions, &$answersoption) {
        $config = get_config('qtype_numericalrecit');
        $repeated = array();
        $repeated[] = $mform->createElement('header', 'answerhdr', $label);
        // Part's mark.
        $repeated[] = $mform->createElement('text', 'answermark', get_string('answermark', 'qtype_numericalrecit'),
            array('size' => 3));
        $repeatedoptions['answermark']['helpbutton'] = array('answermark', 'qtype_numericalrecit');
        $repeatedoptions['answermark']['default'] = $config->defaultanswermark;
        $repeatedoptions['answermark']['type'] = PARAM_FLOAT;
        // Part's number of coordinates.
        $repeated[] = $mform->createElement('hidden', 'numbox', '', '');   // Exact value will be computed during validation.
        $repeatedoptions['numbox']['type'] = PARAM_INT;
        // Part's local variables.
        $repeated[] = $mform->createElement('textarea', 'vars1', get_string('vars1', 'qtype_numericalrecit'),
            array('cols' => 80, 'rows' => 1));
        $repeatedoptions['vars1']['helpbutton'] = array('vars1', 'qtype_numericalrecit');
        $repeatedoptions['vars1']['advanced'] = true;
        // Part's answer type (0, 10, 100, 1000).
        $repeated[] = $mform->createElement('select', 'answertype', get_string('answertype', 'qtype_numericalrecit'),
                array(0 => get_string('number', 'qtype_numericalrecit'), 10 => get_string('numeric', 'qtype_numericalrecit'),
                        100 => get_string('numerical_formula', 'qtype_numericalrecit'),
                        1000 => get_string('algebraic_formula', 'qtype_numericalrecit')));;
        $repeatedoptions['answertype']['default'] = $config->defaultanswertype;
        $repeatedoptions['answertype']['type'] = PARAM_INT;
        $repeatedoptions['answertype']['helpbutton'] = array('answertype', 'qtype_numericalrecit');
        // Part's text.
        $repeated[] = $mform->createElement('editor', 'subqtext', get_string('subqtext', 'qtype_numericalrecit'),
            array('rows' => 3), $this->editoroptions);
        $repeatedoptions['subqtext']['helpbutton'] = array('subqtext', 'qtype_numericalrecit');
        // Part's answer.
        $repeated[] = $mform->createElement('text', 'answer', get_string('answer', 'qtype_numericalrecit'),
            array('size' => 80));
        $repeatedoptions['answer']['helpbutton'] = array('answer', 'qtype_numericalrecit');
        $repeatedoptions['answer']['type'] = PARAM_RAW;
        // Part's grading variables.
        $repeated[] = $mform->createElement('textarea', 'vars2', get_string('vars2', 'qtype_numericalrecit'),
            array('cols' => 80, 'rows' => 1));
        $repeatedoptions['vars2']['helpbutton'] = array('vars2', 'qtype_numericalrecit');
        $repeatedoptions['vars2']['advanced'] = true;
        // Part's grading criteria.
        $repeated[] = $mform->createElement('text', 'correctness', get_string('correctness', 'qtype_numericalrecit'), array('size' => 60));
        $repeatedoptions['correctness']['default'] = $config->defaultcorrectness;
        $repeatedoptions['correctness']['helpbutton'] = array('correctness', 'qtype_numericalrecit');
        $repeatedoptions['correctness']['type'] = PARAM_RAW;
        // Part's unit penalty.
        $repeated[] = $mform->createElement('text', 'unitpenalty', get_string('unitpenalty', 'qtype_numericalrecit'), array('size' => 3));
        $repeatedoptions['unitpenalty']['default'] = $config->defaultunitpenalty;
        $repeatedoptions['unitpenalty']['helpbutton'] = array('unitpenalty', 'qtype_numericalrecit');
        $repeatedoptions['unitpenalty']['type'] = PARAM_FLOAT;
        // Part's unit.
        $repeated[] = $mform->createElement('text', 'postunit', get_string('postunit', 'qtype_numericalrecit'), array('size' => 60, 'class' => 'numericalrecit_editing_unit'));
        $repeatedoptions['postunit']['helpbutton'] = array('postunit', 'qtype_numericalrecit');
        $repeatedoptions['postunit']['type'] = PARAM_RAW;
        // Part's basic conversion rules.
        $conversionrules = new qtype_numericalrecit_unit_conversion_rules;
        $allrules = $conversionrules->allrules();
        foreach ($allrules as $id => $entry) {
            $defaultrulechoice[$id] = $entry[0];
        }
        $repeated[] = $mform->createElement('select', 'ruleid', get_string('ruleid', 'qtype_numericalrecit'),
            $defaultrulechoice);
        $repeatedoptions['ruleid']['default'] = 1;
        // Part's other rules.
        $repeated[] = $mform->createElement('textarea', 'otherrule', get_string('otherrule', 'qtype_numericalrecit'),
            array('cols' => 80, 'rows' => 1));
        $repeatedoptions['otherrule']['helpbutton'] = array('otherrule', 'qtype_numericalrecit');
        $repeatedoptions['otherrule']['advanced'] = true;
        // Part's placeholder.
        $repeated[] = $mform->createElement('text', 'placeholder', get_string('placeholder', 'qtype_numericalrecit'),
            array('size' => 20));
        $repeatedoptions['placeholder']['helpbutton'] = array('placeholder', 'qtype_numericalrecit');
        $repeatedoptions['placeholder']['type'] = PARAM_RAW;
        // Part's feedback.
        $repeated[] = $mform->createElement('editor', 'feedback', get_string('feedback', 'qtype_numericalrecit'),
            array('rows' => 3), $this->editoroptions);
        $repeatedoptions['feedback']['helpbutton'] = array('feedback', 'qtype_numericalrecit');
        $repeatedoptions['feedback']['advanced'] = true;
        // Part's combined feedback.
        $repeated[] = $mform->createElement('editor', 'partcorrectfb', get_string('correctfeedback', 'qtype_numericalrecit'),
            array('rows' => 3), $this->editoroptions);
        $repeatedoptions['partcorrectfb']['helpbutton'] = array('correctfeedback', 'qtype_numericalrecit');
        $repeatedoptions['partcorrectfb']['advanced'] = true;
        $repeated[] = $mform->createElement('editor', 'partpartiallycorrectfb', get_string('partiallycorrectfeedback', 'qtype_numericalrecit'),
            array('rows' => 3), $this->editoroptions);
        $repeatedoptions['partpartiallycorrectfb']['helpbutton'] = array('partiallycorrectfeedback', 'qtype_numericalrecit');
        $repeatedoptions['partpartiallycorrectfb']['advanced'] = true;
        $repeated[] = $mform->createElement('editor', 'partincorrectfb', get_string('incorrectfeedback', 'qtype_numericalrecit'),
            array('rows' => 3), $this->editoroptions);
        $repeatedoptions['partincorrectfb']['helpbutton'] = array('incorrectfeedback', 'qtype_numericalrecit');
        $repeatedoptions['partincorrectfb']['advanced'] = true;
        $answersoption = 'answers';
        return $repeated;
    }

    /**
     * Add a set of form fields, obtained from get_per_answer_fields, to the form,
     * one for each existing answer, with some blanks for some new ones.
     * @param object $mform the form being built.
     * @param $label the label to use for each option.
     * @param $gradeoptions the possible grades for each answer.
     * @param $minoptions the minimum number of answer blanks to display.
     *      Default QUESTION_NUMANS_START.
     * @param $addoptions the number of answer blanks to add. Default QUESTION_NUMANS_ADD.
     */
    protected function add_per_answer_fields(&$mform, $label, $gradeoptions,
            $minoptions = QUESTION_NUMANS_START, $addoptions = QUESTION_NUMANS_ADD) {
        $answersoption = '';
        $repeatedoptions = array();
        $repeated = $this->get_per_answer_fields($mform, $label, $gradeoptions,
                $repeatedoptions, $answersoption);

        if (isset($this->question->options)) {
            $repeatsatstart = count($this->question->options->$answersoption);
        } else {
            $repeatsatstart = $minoptions;
        }

        $this->repeat_elements($repeated, $repeatsatstart, $repeatedoptions,
                'noanswers', 'addanswers', $addoptions,
                $this->get_more_choices_string(), true);
    }

    protected function get_more_choices_string() {
        return get_string('addmorepartsblanks', 'qtype_numericalrecit');
    }

    protected function data_preprocessing($question) {
        $question = parent::data_preprocessing($question);
        $question = $this->data_preprocessing_combined_feedback($question, true);
        $question = $this->data_preprocessing_hints($question, true, true);
        if (isset($question->options)) {

            $defaultvalues = array();
            if (count($question->options->answers)) {
                $tags = question_bank::get_qtype($question->qtype)->part_tags();
                foreach ($question->options->answers as $key => $answer) {

                    foreach ($tags as $tag) {
                        $defaultvalues[$tag.'['.$key.']'] = $answer->$tag;
                    }

                    $fields = array('subqtext', 'feedback');
                    foreach ($fields as $field) {
                        $fieldformat = $field . 'format';
                        $itemid = file_get_submitted_draft_itemid($field . '[' . $key . ']');
                        $fieldtxt = file_prepare_draft_area($itemid, $this->context->id, 'qtype_numericalrecit',  'answer' . $field, empty($answer->id) ? null : (int)$answer->id, $this->fileoptions, $answer->$field);
                        $defaultvalues[$field . '[' . $key . ']'] = array('text' => $fieldtxt, 'format' => $answer->$fieldformat, 'itemid' => $itemid);
                    }
                    $fields = array('partcorrectfb', 'partpartiallycorrectfb', 'partincorrectfb');
                    foreach ($fields as $field) {
                        $fieldformat = $field . 'format';
                        $itemid = file_get_submitted_draft_itemid($field . '[' . $key . ']');
                        $fieldtxt = file_prepare_draft_area($itemid, $this->context->id, 'qtype_numericalrecit',
                                $field, empty($answer->id) ? null : (int)$answer->id,
                                $this->fileoptions, $answer->$field);
                        $defaultvalues[$field . '[' . $key . ']'] = array('text' => $fieldtxt,
                            'format' => $answer->$fieldformat, 'itemid' => $itemid);
                    }
                }
            }

            $question = (object)((array)$question + $defaultvalues);
        }
        return $question;
    }
    /**
     * Validating the data returning from the form.
     *
     * The check the basic error as well as the formula error by evaluating one instantiation.
     */
    public function validation($fromform, $files) {
        $errors = parent::validation($fromform, $files);
        // Use the validation defined in the question type, check by instantiating one variable set.
        $data = (object)$fromform;
        $instantiationresult = question_bank::get_qtype($this->qtype())->validate($data);
        if (isset($instantiationresult->errors)) {
            $errors = array_merge($errors, $instantiationresult->errors);
        }
        // Forward the (first) local error of the options to the global one.
        $globaltags = array('unitpenalty', 'ruleid');
        foreach ($globaltags as $gtag) {
            if (array_key_exists($gtag.'[0]', $errors)) {
                $errors['global'.$gtag] = $errors[$gtag.'[0]'];
            }
        }
        return $errors;
    }

    public function qtype() {
        return 'numericalrecit';
    }
}
