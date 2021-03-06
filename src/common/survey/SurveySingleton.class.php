<?php
/**
 * Copyright (c) Xerox Corporation, Codendi Team, 2001-2009. All rights reserved
 *
 * This file is a part of Codendi.
 *
 * Codendi is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Codendi is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Codendi. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 *  SurveySingleton object for Codendi Surveys
 */
class SurveySingleton {
  
  // simply containing the 
  var $data_array = array();
  var $ranked_array = array();

  var $RADIO_BUTTON_1_5 = 1;
  var $TEXT_AREA = 2;
  var $RADIO_BUTTON_YES_NO = 3;
  var $COMMENT_ONLY = 4;
  var $TEXT_FIELD = 5;
  var $RADIO_BUTTON = 6;
  var $SELECT_BOX = 7;
  var $NONE = 100;
  
  function SurveySingleton() {
    $this->update();
  }
  
  function &instance() {
    static $survey_instance;
    if (isset($GLOBALS['Language'])) {
    }
    if (!$survey_instance) {
      $survey_instance = new SurveySingleton();
    }
    return $survey_instance;
  }
  
  function getLabel($question_type) {
    return $GLOBALS['Language']->getText('survey_common_survey',$this->data_array[$question_type]);
  }

  function update() {
    $db_res=db_query("SELECT id,type FROM survey_question_types");
    $this->data_array=array();
    $rows=db_numrows($db_res);
    for ($i=0; $i<$rows; $i++) {
      $this->data_array[db_result($db_res,$i,'id')] = db_result($db_res,$i,'type');
    }

    $db_res=db_query("SELECT * FROM survey_question_types ORDER BY rank");
    $this->ranked_array=array();
    $rows=db_numrows($db_res);
    for ($i=0; $i<$rows; $i++) {
      $this->ranked_array[db_result($db_res,$i,'rank')] = db_result($db_res,$i,'id');
    }
  }


  function showTypeBox($name='question_type',$checked_val='xzxz') {
    $ranked_ids = array();
    $localizedTypes = array();
    foreach ($this->ranked_array as $val) {
      $ranked_ids[] = $val;
      $localizedTypes[] = $this->getLabel($val);
    }
    
    return html_build_select_box_from_arrays ($ranked_ids,$localizedTypes,$name,$checked_val,false);
  }


  function getSurveyTitle($title) {
    global $Language;
    if (preg_match('/_title_key$/',$title)) {
      return $Language->getText('survey_common_survey',$title);
    } else {
      return $title;
    }
  }
}


?>